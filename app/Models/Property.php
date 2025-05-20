<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'm_properties';
    protected $primaryKey = 'idrec';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idrec',
        'slug',
        'tags',
        'name',
        'description',
        'province',
        'city',
        'subdistrict',
        'village',
        'postal_code',
        'address',
        'location',
        'distance',
        'price',
        'features',
        'attributes',
        'image',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function room_type()
    {
        return $this->hasMany(RoomType::class, 'idrec', 'property_id');
    }
}
