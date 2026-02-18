<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\ParkingFee;
use App\Models\ParkingFeeTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParkingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Parking::with(['property', 'createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc');

        $user = Auth::user();
        $accessiblePropertyId = $user->getAccessiblePropertyId();

        if ($accessiblePropertyId !== null) {
            $query->where('property_id', $accessiblePropertyId);
        }

        // Include soft-deleted if requested
        if ($request->input('show_deleted') === '1') {
            $query->withTrashed();
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('vehicle_plate', 'like', "%{$request->search}%")
                  ->orWhere('owner_name', 'like', "%{$request->search}%")
                  ->orWhereHas('property', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== '' && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('parking_type') && !empty($request->parking_type)) {
            $query->where('parking_type', $request->parking_type);
        }

        $parkings = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            return view('pages.Properties.Parking.partials.parking_table', compact('parkings'));
        }

        return view('pages.Properties.Parking.index', compact('parkings'));
    }

    public function filter(Request $request)
    {
        $perPage = $request->input('per_page', 8);
        $search = $request->input('search');
        $status = $request->input('status', '');
        $parkingType = $request->input('parking_type', '');
        $showDeleted = $request->input('show_deleted', '0');

        $query = Parking::with(['property', 'createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc');

        $user = Auth::user();
        $accessiblePropertyId = $user->getAccessiblePropertyId();

        if ($accessiblePropertyId !== null) {
            $query->where('property_id', $accessiblePropertyId);
        }

        if ($showDeleted === '1') {
            $query->withTrashed();
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_plate', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhereHas('property', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($parkingType)) {
            $query->where('parking_type', $parkingType);
        }

        $parkings = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->appends($request->all());

        return response()->json([
            'html' => view('pages.Properties.Parking.partials.parking_table', compact('parkings'))->render(),
            'pagination' => $perPage !== 'all' && $parkings instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $parkings->links()->toHtml()
                : ''
        ]);
    }

    public function update(Request $request, $idrec)
    {
        $validated = $request->validate([
            'parking_type' => 'required|in:car,motorcycle',
            'vehicle_plate' => 'required|string|max:20',
            'owner_name' => 'nullable|string|max:150',
            'owner_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $parking = Parking::findOrFail($idrec);
            $oldParkingType = $parking->parking_type;
            $typeChanged = $validated['parking_type'] !== $oldParkingType;

            // Check unique if plate changed
            $newPlate = strtoupper($validated['vehicle_plate']);
            if ($newPlate !== $parking->vehicle_plate || $typeChanged) {
                $existing = Parking::where('property_id', $parking->property_id)
                    ->where('vehicle_plate', $newPlate)
                    ->where('idrec', '!=', $idrec)
                    ->first();

                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => __('ui.parking_plate_exists')
                    ], 422);
                }

                // Check fee exists for new type
                $newParkingFee = ParkingFee::where('property_id', $parking->property_id)
                    ->where('parking_type', $validated['parking_type'])
                    ->where('status', 1)
                    ->first();

                if (!$newParkingFee) {
                    return response()->json([
                        'success' => false,
                        'message' => __('ui.parking_fee_not_configured')
                    ], 422);
                }

                // Adjust quota if parking type changed and has active paid transaction
                if ($typeChanged) {
                    $hasActivePaidTransaction = ParkingFeeTransaction::where('parking_id', $parking->idrec)
                        ->where('transaction_status', 'paid')
                        ->exists();

                    if ($hasActivePaidTransaction) {
                        // Decrement old type quota
                        $oldParkingFee = ParkingFee::where('property_id', $parking->property_id)
                            ->where('parking_type', $oldParkingType)
                            ->where('status', 1)
                            ->first();

                        if ($oldParkingFee && $oldParkingFee->capacity > 0) {
                            $oldParkingFee->decrementQuota();
                        }

                        // Check new type quota availability
                        if ($newParkingFee->capacity > 0 && !$newParkingFee->hasAvailableQuota()) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => 'Kuota parkir ' . ucfirst($validated['parking_type']) . ' sudah penuh. Tersedia: 0, Kapasitas: ' . $newParkingFee->capacity
                            ], 422);
                        }

                        // Increment new type quota
                        if ($newParkingFee->capacity > 0) {
                            $newParkingFee->incrementQuota();
                        }

                        // Update parking_type on active paid transactions
                        ParkingFeeTransaction::where('parking_id', $parking->idrec)
                            ->where('transaction_status', 'paid')
                            ->update([
                                'parking_type' => $validated['parking_type'],
                                'fee_amount' => $newParkingFee->fee,
                                'updated_by' => Auth::id(),
                            ]);
                    }
                }
            }

            $parking->update([
                'parking_type' => $validated['parking_type'],
                'vehicle_plate' => $newPlate,
                'owner_name' => $validated['owner_name'] ?? null,
                'owner_phone' => $validated['owner_phone'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('ui.parking_updated_success'),
                'data' => $parking,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data parkir: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($idrec)
    {
        try {
            $parking = Parking::findOrFail($idrec);
            $parking->update(['updated_by' => Auth::id()]);
            $parking->delete();

            return response()->json([
                'success' => true,
                'message' => __('ui.parking_deleted_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data parkir: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($idrec)
    {
        try {
            $parking = Parking::withTrashed()->findOrFail($idrec);
            $parking->restore();
            $parking->update(['updated_by' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => __('ui.parking_restored_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan data parkir: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $parking = Parking::find($request->id);

            if (!$parking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data parkir tidak ditemukan'
                ], 404);
            }

            $parking->update([
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status'
            ], 500);
        }
    }
}
