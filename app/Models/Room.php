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
        'name',
        'descriptions',
        'periode',
        'type',
        'level',
        'facility',
        'discount_percentage',
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

    public function getFacilityAttribute($value)
    {
        // Handle null or empty
        if (empty($value)) {
            $decoded = [];
        } else {
            // First decode
            $decoded = json_decode($value, true);

            // If still a string after decode, decode again
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }

            // Fallback if somehow still not array
            if (!is_array($decoded)) {
                $decoded = [];
            }
        }

        $allFacilities = ['wifi', 'tv', 'ac', 'bathroom'];

        return collect($allFacilities)->mapWithKeys(function ($key) use ($decoded) {
            return [$key => in_array($key, $decoded)];
        })->toArray();
    }

    protected static function booted()
    {
        static::creating(function ($room) {
            // Get property initials
            $property = Property::find($room->property_id);
            $propertyInitials = $property
                ? Str::upper($property->initial)
                : 'UNK'; // fallback if property not found

            // Room name initials (e.g. "Ini Ruangan Loh" → IRL)
            $roomNameInitials = Str::upper(collect(explode(' ', $room->name))->map(fn($word) => Str::substr($word, 0, 1))->implode(''));

            // Room type initials (e.g. "Super Deluxe Room" → SDR)
            $roomTypeInitials = Str::upper(collect(explode(' ', $room->type))->map(fn($word) => Str::substr($word, 0, 1))->implode(''));

            // Level
            $level = $room->level ?? '0';

            // Next ID
            $nextId = static::max('idrec') + 1;

            // Random 3-digit number
            $randomDigits = rand(100, 999);

            // Final code
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

    public function dailyPrices()
    {
        return $this->hasMany(RoomPrices::class, 'idrec', 'room_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
