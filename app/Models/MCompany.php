<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCompany extends Model
{
    protected $table = 'm_companies';
    protected $primaryKey = 'id_company';
    public $timestamps = false; // karena kolom created_at dan updated_at tidak otomatis

    protected $fillable = [
        'id_company',
        'initials',
        'company_type',
        'name',
        'address',
        'city',
        'country',
        'zip_code',
        'npwp_id',
        'npwp_address',
        'npwp_city',
        'npwp_country',
        'npwp_zipcode',
        'npwp_pdf',
        'logo_blob',
        'logo_filename',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
    ];
}
