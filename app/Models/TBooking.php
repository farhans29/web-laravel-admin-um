<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TBooking extends Model
{
    protected $table = 't_booking';
    protected $primaryKey = 'idrec';
    public $timestamps = false; // Karena kita pakai manual untuk created_at dan updated_at

    protected $fillable = [
        'idrec',
        'property_id',
        'order_id',
        'room_id',
        'check_in_at',
        'check_out_at',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'activeyn',
    ];
}
