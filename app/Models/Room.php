<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($room) {
            $property = Property::find($room->property_id);
            $propertyInitials = $property
                ? Str::upper($property->initial)
                : 'UNK';
            $roomNameInitials = Str::upper(collect(explode(' ', $room->name))->map(fn($word) => Str::substr($word, 0, 1))->implode(''));
            $roomTypeInitials = Str::upper(collect(explode(' ', $room->type))->map(fn($word) => Str::substr($word, 0, 1))->implode(''));
            $level = $room->level ?? '0';
            $nextId = static::max('idrec') + 1;
            $randomDigits = rand(100, 999);
            $room->slug = "{$propertyInitials}_{$roomNameInitials}_{$level}_{$roomTypeInitials}_{$nextId}_{$randomDigits}";
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'room_id', 'idrec');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id', 'idrec');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }
}
