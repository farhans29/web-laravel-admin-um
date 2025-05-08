<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MDocumentType extends Model
{
    protected $table = 'm_document_types';

    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
