<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBrand extends Model
{
    protected $table = 'm_brand';
    protected $primaryKey = 'id_brand';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'p_id_brand',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(MBrand::class, 'p_id_brand');
    }

    public function children()
    {
        return $this->hasMany(MBrand::class, 'p_id_brand');
    }
}
