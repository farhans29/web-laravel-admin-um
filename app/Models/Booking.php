<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 't_booking';
    protected $primaryKey = 'idrec';

    protected $appends = ['status'];

    protected $fillable = [
        'property_id',
        'order_id',
        'room_id',
        'check_in_at',
        'check_out_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'idrec');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }

    public function getStatusAttribute()
    {
        if (is_null($this->check_in_at) && is_null($this->check_out_at)) {
            return 'Waiting for Check-In';
        } elseif (!is_null($this->check_in_at) && is_null($this->check_out_at)) {
            return 'Checked-In';
        } elseif (!is_null($this->check_in_at) && !is_null($this->check_out_at)) {
            return 'Checked-Out';
        }
    
        return 'Unknown';
    }
}
