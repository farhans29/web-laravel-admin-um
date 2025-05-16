<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TBooking extends Model
{
    protected $table = 't_booking';
    protected $primaryKey = 'idrec';
    public $timestamps = false; // Karena kita pakai manual untuk created_at dan updated_at

    protected $appends = ['status'];

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

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }

    public function getStatusAttribute()
    {
        if (is_null($this->check_in_at)) {
            return 'Waiting for Check-In';
        } elseif (is_null($this->check_out_at)) {
            return 'Checked-In';
        } else {
            return 'Checked-Out';
        }
    }
}
