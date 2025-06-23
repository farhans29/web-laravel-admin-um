<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class RoomStatusToggle extends Component
{
    public $roomId;
    public $status;
    public $roomNo;

    public function mount($roomId, $status, $roomNo)
    {
        $this->roomId = $roomId;
        $this->status = $status; // Ensures it's true or false
        $this->roomNo = $roomNo;
    }

    public function updatedStatus($newStatus)
    {
        logger("Updated status for room {$this->roomId} to: " . $newStatus); // <-- Debug line
    
        $this->status = $newStatus;
        Room::where('id', $this->roomId)->update(['status' => $this->status]);
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;

        Room::where('idrec', $this->roomId)
            ->update(['status' => $this->status]);
    }

    public function render()
    {
        return view('livewire.room-status-toggle');
    }
}
