<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TArchive extends Model
{
    use HasFactory;

    protected $table = 't_archive';
    protected $primaryKey = 'idrec';
    public $timestamps = true;

    protected $fillable = [
        'id_archieve',
        'date',
        'doc_type',
        'no_document',
        'sub_dep',
        'description',
        'file_name',
        'pdfblob',
        'active_y_n',
        'created_by',
        'updated_by',
    ];

    public function ajuDetails()
    {
        return $this->hasMany(TAjuDetail::class, 'id_archieve');
    }

    public function aju()
    {
        return $this->belongsTo(TAju::class, 'id_archieve', 'id_aju');
    }

    public function subDepartment()
    {
        return $this->belongsTo(MDepartment::class, 'sub_dep', 'id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
