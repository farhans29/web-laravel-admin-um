<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDepartment extends Model
{
    use HasFactory;

    protected $table = 'm_department';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'pid',
        'status'
    ];

    // Relationship to parent department
    public function parent()
    {
        return $this->belongsTo(MDepartment::class, 'pid');
    }

    // Relationship to child departments
    public function children()
    {
        return $this->hasMany(MDepartment::class, 'pid');
    }

    public static function getDepartments()
    {
        return self::with('children')->where('pid', 0)->get();
    }

    public static function getSubDepartments()
    {
        return self::with('parent')->where('pid', '!=', 0)->get();
    }
}
