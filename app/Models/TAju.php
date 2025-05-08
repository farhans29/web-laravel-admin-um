<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAju extends Model
{
    use HasFactory;

    protected $table = 't_aju';
    protected $primaryKey = 'id_aju';
    public $timestamps = true;

    protected $fillable = [
        'date',
        'id_department',
        'no_docs',
        'active_y_n',
        'description',
        'created_by',
        'updated_by',
    ];

    public function details()
    {
        return $this->hasMany(TAjuDetail::class, 'id_aju');
    }

    public function department()
    {
        return $this->belongsTo(MDepartment::class, 'id_department', 'id');
    }

    public function archives()
    {
        return $this->hasMany(TArchive::class, 'id_archieve', 'id_aju');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
