<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 't_booking';
    protected $primaryKey = 'idrec';

    protected $fillable = [
        'property_id',
        'order_id',
        'room_id',
        'check_in_at',
        'check_out_at',
        'created_by',
        'updated_by',
        'status'
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
        return $this->belongsTo(Transaction::class, 'order_id', 'idrec');
    }
}
