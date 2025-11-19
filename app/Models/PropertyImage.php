<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    use HasFactory;

    protected $table = 'm_property_images';
    protected $primaryKey = 'idrec';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'image',
        'thumbnail',
        'caption',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'thumbnail' => 'boolean',
    ];

    // TAMBAHKAN appends untuk accessor
    protected $appends = ['image_url', 'thumbnail_url'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }
        return null;
    }

}
