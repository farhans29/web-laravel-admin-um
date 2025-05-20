<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class RoomStatusToggle extends Component
{
    public $roomId;
    public $status;

    public function mount($roomId, $status)
    {
        $this->roomId = $roomId;
        $this->status = (bool) $status; // Ensures it's true or false
    }

    public function updatedStatus($value)
    {
        logger("Updated status for room {$this->roomId} to: " . ($value ? 1 : 0)); // <-- Debug line
    
        Room::where('idrec', $this->roomId)->update(['status' => $value ? 1 : 0]);
    }

    public function toggleStatus($isChecked)
    {
        $this->status = $isChecked ? 1 : 0;

        Room::where('idrec', $this->roomId)
            ->update(['status' => $this->status]);
    }

    public function render()
    {
        return view('livewire.room-status-toggle');
    }
}
