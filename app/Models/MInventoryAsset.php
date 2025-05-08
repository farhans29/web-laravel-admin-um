<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MInventoryAsset extends Model
{
    use HasFactory;

    protected $table = 'm_inventory_assets';
    protected $primaryKey = 'idassets';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idassets',
        'id_coa',
        'id_rab_item',
        'id_dept',
        'category',
        'id_sub_dept',
        'sub_category',
        'type',
        'inv_type',
        'name',
        'id_brand',
        'brand',
        'sku',
        'id_model',
        'model_number',
        'color',
        'vendor_preference',
        'qty',
        'unit',
        'hpp',
        'automargin',
        'minsales',
        'pricelist',
        'currency',
        'lastpurch',
        'aktifyn',
        'wsprice',
        'category2',
        'plu',
        'wunit',
        'net_weight',
        'idsupplier',
        'file',
        'img',
        'img_name',
        'description',
        'created_at',
        'updated_at',
    ];

    public function mainBrand()
    {
        return $this->belongsTo(MBrand::class, 'id_brand', 'id_brand');
    }

    public function modelBrand()
    {
        return $this->belongsTo(MBrand::class, 'id_model', 'id_brand');
    }

    public function Department()
    {
        return $this->belongsTo(MDepartment::class, 'id_dept', 'id');
    }

    public function subDepartment()
    {
        return $this->belongsTo(MDepartment::class, 'id_sub_dept', 'id');
    }

}
