<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAjuDetail extends Model
{
    use HasFactory;

    protected $table = 't_aju_detail';
    protected $primaryKey = 'idrec';
    public $timestamps = false;

    protected $fillable = [
        'id_aju',
        'id_archieve',
    ];

    public function aju()
    {
        return $this->belongsTo(TAju::class, 'id_aju');
    }

    public function archive()
    {
        return $this->belongsTo(TArchive::class, 'id_archieve');
    }
}
