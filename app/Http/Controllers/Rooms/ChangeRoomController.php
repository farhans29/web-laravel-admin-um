<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\RoomTransferNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ChangeRoomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $historySearch = $request->input('history_search');
        $currentDate = now()->format('Y-m-d');
        $propertyId = $request->input('property_id');

        // Get all bookings that are eligible for room transfer
        // Includes: 1) Paid bookings that haven't checked in yet, 2) Checked-in guests who haven't checked out, 3) Transferred bookings
        $bookings = Booking::with(['user', 'room', 'property', 'transaction', 'payment'])
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'paid'); // Only paid bookings
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Status 1 bookings (active)
                    $q->where('status', 1)
                      ->where(function ($subQ) {
                          $subQ->whereNull('check_in_at') // Paid but not checked in yet
                               ->orWhere(function ($checkQ) {
                                   $checkQ->whereNotNull('check_in_at') // Already checked in
                                          ->whereNull('check_out_at'); // But not checked out yet
                               });
                      });
                })
                ->orWhere('status', 2); // Also include transferred bookings
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // Search by order_id
                    $q->where('order_id', 'like', '%' . $search . '%')
                        // Search by username
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('username', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByRaw('status ASC, check_in_at IS NULL DESC, check_in_at DESC') // Show active first, then transferred, pending bookings first, then checked-in by date
            ->paginate(3);

        // Check if this is an AJAX request for live search
        if ($request->ajax() || $request->get('ajax')) {
            $html = view('pages.rooms.changerooms.partials.changeRoom_table', [
                'bookings' => $bookings,
                'per_page' => request('per_page', 8),
            ])->render();

            $pagination = $bookings->withQueryString()->links()->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        // Get available rooms - must be active and available for rental
        $availableRooms = Room::where('status', 1) // Room must be active
            ->where('rental_status', 1) // Room must be available for rental
            ->whereDoesntHave('bookings', function ($query) use ($currentDate) {
                $query->where('status', 1)
                    ->whereDate('check_in_at', '<=', $currentDate)
                    ->whereDate('check_out_at', '>=', $currentDate);
            })
            ->when($propertyId, function ($query, $propertyId) {
                return $query->where('property_id', $propertyId);
            })
            ->get();

        // Modified transfer history query with search
        // Show bookings with status 3 (old/cancelled) as they represent the rooms that were transferred FROM
        $transferHistory = Booking::with(['user', 'room', 'property'])
            ->where('status', 3) // Status 3 means old/cancelled room
            ->whereNotNull('reason') // Must have a transfer reason
            ->when($historySearch, function ($query, $historySearch) {
                return $query->where(function ($q) use ($historySearch) {
                    $q->where('order_id', 'like', '%' . $historySearch . '%')
                        ->orWhereHas('user', function ($uq) use ($historySearch) {
                            $uq->where('username', 'like', '%' . $historySearch . '%');
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->through(function ($history) {
                // The current record (status 3) is the old/previous room
                // Find the new room (status 2) with the same order_id and more recent updated_at
                $newRoomBooking = Booking::with('room')
                    ->where('order_id', $history->order_id)
                    ->where('status', 2)
                    ->where('idrec', '!=', $history->idrec)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                return [
                    'order_id' => $history->order_id,
                    'guest_name' => $history->user->username ?? 'N/A',
                    'previous_room' => $history, // This is the old room (status 3)
                    'current_room' => $newRoomBooking, // This is the new room (status 2)
                    'reason' => $history->reason,
                    'description' => $history->description,
                    'transfer_date' => $history->updated_at, // Use updated_at as transfer timestamp
                ];
            });

        return view('pages.rooms.changerooms.index', compact(
            'bookings',
            'search',
            'availableRooms',
            'transferHistory',
            'historySearch'
        ));
    }


    public function getAvailableRooms(Request $request)
    {
        $propertyId = $request->input('property_id');
        $roomId     = $request->input('room_id');
        $checkIn    = $request->input('check_in');
        $checkOut   = $request->input('check_out');

        if (!$checkIn || !$checkOut || !$propertyId) {
            return response()->json(['error' => 'Missing required fields.'], 400);
        }
        $checkInDate  = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);

        $rooms = Room::where('property_id', $propertyId)
            ->where('status', 1) // Room must be active
            ->where('rental_status', 0) // Room must be available for rental
            ->when($roomId, function ($query) use ($roomId) {
                return $query->where('idrec', '!=', $roomId);
            })
            ->get();

        $availableRooms = $rooms->filter(function ($room) use ($checkInDate, $checkOutDate) {
            // Check if room has any booking conflicts for the given date range
            // Only check for active bookings (status 1) and transferred bookings (status 2)
            $hasConflict = Booking::with('transaction')
                ->where('room_id', $room->idrec)
                ->whereIn('status', [1, 2]) // Active and transferred bookings
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    // For bookings with check_in_at/check_out_at (checked-in guests)
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereNotNull('check_in_at')
                          ->where(function ($subQ) use ($checkInDate, $checkOutDate) {
                              // Case 1: Booking check-in falls within the transfer period
                              $subQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                  $q1->where('check_in_at', '>=', $checkInDate)
                                     ->where('check_in_at', '<', $checkOutDate);
                              })
                              // Case 2: Booking check-out falls within the transfer period
                              ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                  $q2->where('check_out_at', '>', $checkInDate)
                                     ->where('check_out_at', '<=', $checkOutDate);
                              })
                              // Case 3: Booking completely encompasses the transfer period
                              ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                  $q3->where('check_in_at', '<=', $checkInDate)
                                     ->where('check_out_at', '>=', $checkOutDate);
                              });
                          });
                    })
                    // For pending bookings without check_in_at (use transaction dates)
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereNull('check_in_at')
                          ->whereHas('transaction', function ($tq) use ($checkInDate, $checkOutDate) {
                              $tq->where(function ($subQ) use ($checkInDate, $checkOutDate) {
                                  // Case 1: Transaction check-in falls within the transfer period
                                  $subQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                      $q1->where('check_in', '>=', $checkInDate)
                                         ->where('check_in', '<', $checkOutDate);
                                  })
                                  // Case 2: Transaction check-out falls within the transfer period
                                  ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                      $q2->where('check_out', '>', $checkInDate)
                                         ->where('check_out', '<=', $checkOutDate);
                                  })
                                  // Case 3: Transaction completely encompasses the transfer period
                                  ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                      $q3->where('check_in', '<=', $checkInDate)
                                         ->where('check_out', '>=', $checkOutDate);
                                  });
                              });
                          });
                    });
                })
                ->exists();

            return !$hasConflict;
        });


        return response()->json($availableRooms->values());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_property_id' => 'required|string|max:100',
            'order_id' => 'required|string|max:100',
            'new_room' => 'nullable|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'nullable|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fill all required fields.');
        }

        try {
            // Get the previous booking to find the user and previous room
            // Allow transfer for both checked-in and pending bookings (status 1 or 2)
            $previousBooking = Booking::where('order_id', $request->order_id)
                ->whereIn('status', [1, 2]) // Active or already transferred
                ->whereNull('check_out_at') // Must not have checked out
                ->orderBy('updated_at', 'desc') // Get the most recent booking
                ->firstOrFail();

            // Validate that new room is different from current room
            if ($previousBooking->room_id == $request->new_room) {
                return redirect()->back()
                    ->with('error', 'Kamar baru tidak boleh sama dengan kamar saat ini.');
            }

            // Get room details
            $previousRoom = Room::findOrFail($previousBooking->room_id);
            $newRoom = Room::findOrFail($request->new_room);

            // Validate new room belongs to same property
            if ($newRoom->property_id != $request->current_property_id) {
                return redirect()->back()
                    ->with('error', 'Kamar baru harus dalam properti yang sama.');
            }

            // Check if new room has any booking conflicts
            $checkInDate = Carbon::parse($request->check_in);
            $checkOutDate = $request->check_out ? Carbon::parse($request->check_out) : $previousBooking->check_out_at;

            $hasConflict = Booking::with('transaction')
                ->where('room_id', $request->new_room)
                ->whereIn('status', [1, 2]) // Active or transferred bookings
                ->where('order_id', '!=', $request->order_id) // Exclude current booking
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    // For bookings with check_in_at/check_out_at (checked-in guests)
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereNotNull('check_in_at')
                          ->where(function ($subQ) use ($checkInDate, $checkOutDate) {
                              $subQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                  $q1->where('check_in_at', '>=', $checkInDate)
                                     ->where('check_in_at', '<', $checkOutDate);
                              })
                              ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                  $q2->where('check_out_at', '>', $checkInDate)
                                     ->where('check_out_at', '<=', $checkOutDate);
                              })
                              ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                  $q3->where('check_in_at', '<=', $checkInDate)
                                     ->where('check_out_at', '>=', $checkOutDate);
                              });
                          });
                    })
                    // For pending bookings without check_in_at (use transaction dates)
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereNull('check_in_at')
                          ->whereHas('transaction', function ($tq) use ($checkInDate, $checkOutDate) {
                              $tq->where(function ($subQ) use ($checkInDate, $checkOutDate) {
                                  $subQ->where(function ($q1) use ($checkInDate, $checkOutDate) {
                                      $q1->where('check_in', '>=', $checkInDate)
                                         ->where('check_in', '<', $checkOutDate);
                                  })
                                  ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                      $q2->where('check_out', '>', $checkInDate)
                                         ->where('check_out', '<=', $checkOutDate);
                                  })
                                  ->orWhere(function ($q3) use ($checkInDate, $checkOutDate) {
                                      $q3->where('check_in', '<=', $checkInDate)
                                         ->where('check_out', '>=', $checkOutDate);
                                  });
                              });
                          });
                    });
                })
                ->exists();

            if ($hasConflict) {
                return redirect()->back()
                    ->with('error', 'Kamar yang dipilih sudah dipesan untuk tanggal tersebut.');
            }

            // Get user who made the booking
            $user = $previousBooking->user;

            // Create the new booking (transfer) - copy all relevant data from previous booking
            $booking = Booking::create([
                'property_id' => $request->current_property_id,
                'order_id' => $request->order_id,
                'room_id' => $request->new_room,
                'user_id' => $previousBooking->user_id,
                'transaction_id' => $previousBooking->transaction_id,
                'payment_id' => $previousBooking->payment_id,
                'check_in_at' => $previousBooking->check_in_at, // Keep original check-in time (null for pending)
                'check_out_at' => $previousBooking->check_out_at, // Keep original check-out time
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status' => 2, // Set to transferred status for the new room
                'reason' => null,
                'description' => null,
            ]);

            // Mark all previous bookings with the same order_id as cancelled/old (status 3)
            // Save reason and description to the old room (status 3)
            Booking::where('order_id', $request->order_id)
                ->where('idrec', '!=', $booking->idrec) // Exclude the newly created booking
                ->update([
                    'status' => 3,
                    'reason' => $request->reason,
                    'description' => $request->notes,
                    'updated_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

            // Prepare transfer details for notification
            $transferDetails = [
                'guest_name' => $user->username,
                'order_id' => $request->order_id,
                'previous_room' => $previousRoom->name . ' (' . $previousRoom->type . ')',
                'new_room' => $newRoom->name . ' (' . $newRoom->type . ')',
                'reason' => $request->reason,
                'transfer_date' => now()->format('Y-m-d H:i'),
                'check_in' => $previousBooking->check_in_at ? $previousBooking->check_in_at->format('Y-m-d H:i') : 'Not checked in yet',
                'check_out' => $previousBooking->check_out_at ? $previousBooking->check_out_at->format('Y-m-d H:i') : 'Not specified',
            ];

            // Send notification to user
            try {
                if ($user && $user->email) {
                    Notification::send($user, new RoomTransferNotification($transferDetails));
                }
                return redirect()->route('changerooom.index')->with('success', 'Kamar berhasil dipindahkan dan notifikasi telah dikirim!');
            } catch (\Exception) {
                // Room transfer succeeded but notification failed
                return redirect()->route('changerooom.index')->with('success', 'Kamar berhasil dipindahkan! (Notifikasi email gagal dikirim)');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Booking tidak ditemukan atau tidak dapat dipindahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memindahkan kamar: ' . $e->getMessage());
        }
    }
}
