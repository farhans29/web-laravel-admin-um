<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'm_properties';
    protected $primaryKey = 'idrec';
    public $incrementing = true; 
    protected $keyType = 'int';  
    public $timestamps = false;

    protected $fillable = [
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
