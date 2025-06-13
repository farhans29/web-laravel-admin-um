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

    public function toggleStatus()
    {
        try {
            // Toggle the status
            $newStatus = $this->status ? 0 : 1;
            
            // Update the room status
            Room::where('idrec', $this->roomId)->update(['status' => $newStatus]);
            
            // Update the local status
            $this->status = (bool)$newStatus;
            
            // Emit success event
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Status kamar berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating room status: ' . $e->getMessage());
            
            // Emit error event
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Gagal memperbarui status kamar: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.room-status-toggle');
    }
}
