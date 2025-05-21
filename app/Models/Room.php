<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'm_rooms';
    protected $primaryKey = 'idrec';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'property_name',
        'slug',
        'name',
        'descriptions',
        'periode',
        'type',
        'level',
        'facility',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'attachment',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'room_id', 'idrec');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id', 'idrec');
    }
}
