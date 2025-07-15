<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $table = 'm_properties';
    protected $primaryKey = 'idrec';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'slug',
        'tags',
        'name',
        'initial',
        'description',
        'level_count',
        'province',
        'city',
        'subdistrict',
        'village',
        'postal_code',
        'address',
        'location',        
        'latitude',
        'longitude',        
        'price',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'features',
        'attributes',
        'amenities',
        'room_facilities',
        'rules',        
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    /**
     * Type casting to native types.
     */
    protected $casts = [
        'price' => 'array',
        'features' => 'array',
        'attributes' => 'array',
        'amenities' => 'array',
        'room_facilities' => 'array',
        'rules' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class, 'property_id', 'idrec');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'idrec');
    }
    
    public function rooms()
    {
        return $this->hasMany(Room::class, 'property_id', 'idrec');
    }
}
