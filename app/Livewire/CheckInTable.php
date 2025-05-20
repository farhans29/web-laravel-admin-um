<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;

class CheckInTable extends Component
{
    use WithPagination;

    public $propertyType = '';
    public $status = '';
    public $checkInDate = '';
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['propertyType', 'status', 'checkInDate', 'search', 'perPage'];

    public function updating($field)
    {
        if ($field !== 'perPage') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Booking::with(['transaction', 'property', 'room']);

        if ($this->propertyType) {
            $query->whereHas('property', fn($q) => $q->where('type', $this->propertyType));
        }

        if ($this->status) {
            match ($this->status) {
                'checkin' => $query->whereNotNull('check_in_at')->whereNull('check_out_at'),
                'waiting' => $query->whereNull('check_in_at')->whereNull('check_out_at'),
                'checkout' => $query->whereNotNull('check_in_at')->whereNotNull('check_out_at'),
            };
        }

        if ($this->checkInDate) {
            $query->whereDate('check_in_at', $this->checkInDate);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_id', 'like', "%{$this->search}%")
                  ->orWhereHas('transaction', fn($q) => $q
                      ->where('user_name', 'like', "%{$this->search}%")
                      ->orWhere('user_phone_number', 'like', "%{$this->search}%"));
            });
        }

        $bookings = $query->orderBy('check_in_at', 'desc')->paginate($this->perPage);

        return view('livewire.check-in-table', compact('bookings'));
    }
}
