<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $table = 'm_promo_banners';
    protected $primaryKey = 'idrec';
    public $incrementing = false; 
    public $timestamps = false; 

    protected $fillable = [
        'idrec',
        'title',
        'attachment',
        'descriptions',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
    ];
}
