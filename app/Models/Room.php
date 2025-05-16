<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'm_rooms';
    protected $primaryKey = 'idrec';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idrec',
        'property_id',
        'property_name',
        'slug',
        'name',
        'descriptions',
        'periode',
        'type',
        'level',
        'facility',
        'price',
        'attachment',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
    ];
}
