<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    protected $table = 'm_rooms';
    protected $primaryKey = 'idrec';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'int';

    protected $fillable = [
        'property_id',
        'property_name',
        'slug',
        'no',
        'name',
        'descriptions',
        'size',
        'bed_type',
        'capacity',
        'periode',
        'type',
        'level',
        'facility',
        'price',
        'discount_percent',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'facility' => 'array',
        'periode' => 'array'
    ];
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function dailyPrices()
    {
        return $this->hasMany(RoomPrices::class, 'idrec', 'room_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function roomImages()
    {
        return $this->hasMany(MRoomImage::class, 'room_id', 'idrec');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}
