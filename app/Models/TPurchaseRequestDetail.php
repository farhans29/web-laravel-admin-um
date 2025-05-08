<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPurchaseRequestDetail extends Model
{
    protected $table = 't_purchase_request_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'idrecrab',
        'idreqform',
        'idpr',
        'id_rab',
        'idassets',
        'name_detail',
        'qty',
        'qtyBalance',
        'unit',
        'currency',
        'price',
        'total',
        'balance',
        'balance_rab',
        'remarks',
        'idsupplier',
        'created_at',
        'updated_at',
        'status'
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(TPurchaseRequest::class, 'idrecrab', 'idrec');
    }
}
