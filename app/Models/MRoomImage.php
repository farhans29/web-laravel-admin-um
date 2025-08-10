<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MRoomImage extends Model
{
    protected $table = 'm_room_images';
    protected $primaryKey = 'idrec';
    public $timestamps = false; // karena tidak pakai timestamps Laravel standar

    protected $fillable = [
        'room_id',
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

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'idrec');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $baseUrl = rtrim(config('app.url', 'http://localhost:8000'), '/');
            $imageContent = Storage::disk('public')->get($this->image);
            return $baseUrl . '/storage/' . 'data:image/jpeg;base64,' . base64_encode($imageContent);
        }
    }


    protected $appends = ['image_url'];

   
}
