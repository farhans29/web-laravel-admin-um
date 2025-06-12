<?php

namespace App\Livewire;

use App\Models\RoomPrices;
use Carbon\CarbonPeriod;
use Livewire\Component;

class SetRoomPrice extends Component
{
    public $roomId;
    public $startDate;
    public $endDate;
    public $setPrice;

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatePrice()
    {
        $dates = CarbonPeriod::create($this->startDate, $this->endDate);

        foreach ($dates as $date) {
            RoomPrices::updateOrCreate(
                ['room_id' => $this->roomId, 'date' => $date->format('Y-m-d')],
                ['price' => $this->setPrice]
            );
        }

        $this->dispatchBrowserEvent('notify', ['message' => 'Harga berhasil diperbarui!']);
    }

    public function render()
    {
        return view('livewire.set-room-price');
    }
}
