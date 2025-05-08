<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MVendor extends Model
{
    use HasFactory;

    protected $table = 'm_vendors';
    protected $primaryKey = 'idsupplier';

    protected $fillable = [
        'vendor_type',
        'category',
        'initials',
        'company_type',
        'name',
        'address',
        'city',
        'country',
        'phone',
        'termin',
        'bank_acc_num',
        'bank_name',
        'bank_acc_name',
        'zip_code',
        'npwp_id',
        'npwp_address',
        'npwp_city',
        'npwp_country',
        'npwp_zipcode',
        'npwp_pdf',
        'status',
        'created_by',
        'updated_by'
    ];
}
