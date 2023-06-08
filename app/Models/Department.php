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
        'status',
    ];

    public function scopeWithName($query, $department)
    {
        return $query->where('department_name',$department)->where('status','ACTIVE')->first();
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
