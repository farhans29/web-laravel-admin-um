<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'm_properties';
    protected $primaryKey = 'idrec';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

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
        'distance',
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
        'image',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

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
}
