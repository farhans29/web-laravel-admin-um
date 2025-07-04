<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'caption',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
