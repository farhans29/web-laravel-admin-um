<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 't_payment';
    protected $primaryKey = 'idrec';
    public $timestamps = false; 
    
    protected $fillable = [      
        'order_id',
        'user_id',
        'grandtotal_price',
        'verified_by',
        'verified_at',
        'notes',
        'payment_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }
}
