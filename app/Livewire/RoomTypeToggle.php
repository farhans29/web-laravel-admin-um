<?php

namespace App\Livewire;

use Livewire\Component;

class RoomTypeToggle extends Component
{
    public $roomId;
    public $roomType;

    public function mount($roomId, $roomType)
    {
        $this->roomId = $roomId;
        $this->roomType = $roomType; // Ensures it's true or false
    }

    public function updatedRoomType()
    {
        // Optional: Emit event, save to DB, or trigger logic
        // For example:
        // $this->emit('roomTypeUpdated', $this->roomType);
    }

    public function render()
    {
        return view('livewire.room-type-toggle');
    }
}
