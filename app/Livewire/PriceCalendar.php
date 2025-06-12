<?php

namespace App\Livewire;

use App\Models\RoomPrices;
use Livewire\Component;

class PriceCalendar extends Component
{
    public $roomId;
    public $selectedDate;
    public $price;

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->selectedDate = now()->format('Y-m-d');
        $this->fetchPrice();
    }

    public function updatedSelectedDate()
    {
        $this->fetchPrice();
    }

    public function fetchPrice()
    {
        $this->price = RoomPrices::where('room_id', $this->roomId)
            ->where('date', $this->selectedDate)
            ->value('price') ?? 'Tidak tersedia';
    }

    public function render()
    {
        return view('livewire.price-calendar');
    }
}
