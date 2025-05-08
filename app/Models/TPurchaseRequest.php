<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPurchaseRequest extends Model
{
    protected $table = 't_purchase_request';
    protected $primaryKey = 'idrec';
    public $timestamps = false;

    protected $fillable = [
        'idreqform',
        'pr_title',
        'pr_type',
        'id_rab',
        'pr_date',
        'rab_date',
        'applicant',
        'company_id',
        'currency',
        'payment_by',
        'department',
        'division',
        'purch_type',
        'approval_to',
        'approval2_to',
        'approval3_to',
        'reqlevel',
        'delivery_date',
        'note',
        'idsupplier',
        'id_warehouse',
        'pic',
        'phone',
        'delivery_address',
        'city',
        'province',
        'country',
        'zip_code',
        'subtotal',
        'discount',
        'total',
        'ppn',
        'approvaldate',
        'approvalstat',
        'approved1by',
        'approved2by',
        'approved3by',
        'approval1_status',
        'approval2_status',
        'approval3_status',
        'gtotal',
        'balance',
        'delivery_charge',
        'remarks1',
        'remarks2',
        'remarks3',
        'prepared_by',
        'reviewed_by',
        'reviewed2_by',
        'approved_by',
        'prepared_date',
        'reviewed_date',
        'approved_date',
        'print_status',
        'price_updated',
        'pr_file',
        'quotation1',
        'quotation2',
        'quotation3',
        'quotation_approval1',
        'quotation_approval2',
        'quotation_approval3',
        'vendor_quo1',
        'vendor_quo2',
        'vendor_quo3',
        'total_quo1',
        'total_quo2',
        'total_quo3',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function details()
    {
        return $this->hasMany(TPurchaseRequestDetail::class, 'idrecrab', 'idrec');
    }
}
