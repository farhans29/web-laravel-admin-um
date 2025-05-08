<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MDepartment;
use App\Models\MCompany;

class TInventoryAssetsRequest extends Model
{
    protected $table = 't_inventory_assets_requests';
    protected $primaryKey = 'idrec';
    public $timestamps = false;

    protected $guarded = [];

    public function subDepartment()
    {
        return $this->belongsTo(MDepartment::class, 'company_id', 'id');
    }

    public function approvelSiteTo()
    {
        return $this->belongsTo(User::class, 'approval2_to', 'id');
    }

    public function reviewHqTo()
    {
        return $this->belongsTo(User::class, 'approval3_to', 'id');
        
    }

    public function company()
    {
        return $this->belongsTo(MCompany::class, 'company_id', 'id_company');
    }
}
