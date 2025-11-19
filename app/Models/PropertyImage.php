<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\Property;

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

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    public function getImageUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            return [
                'id' => $image->id,
                'image_url' => $image->image ? asset('storage/' . $image->image) : null,
                'created_at' => $image->created_at,
                'updated_at' => $image->updated_at,
            ];
        });
    }

    public function getThumbnailUrlAttribute()
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }
        return null;
    }
}
