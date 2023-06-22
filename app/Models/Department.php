<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_code',
        'department_name',
        'is_viewable',
        'status',
    ];

    public function scopeWithName($query, $department)
    {
        return $query->where('department_name',$department)->where('status','ACTIVE')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status','ACTIVE')->orderBy('department_name','ASC')->get();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = CRUDBooster::myId();
        });
        static::updating(function($model)
        {
            $model->updated_by = CRUDBooster::myId();
        });
    }
}
