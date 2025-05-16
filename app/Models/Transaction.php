<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 't_transactions';
    protected $primaryKey = 'idrec';
    public $timestamps = false; // Karena kita pakai manual untuk created_at dan updated_at

    public function tbookings()
    {
        return $this->hasMany(TBooking::class, 'order_id', 'order_id');
    }
}
