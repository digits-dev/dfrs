<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class InterCompany extends Model
{
    use HasFactory;

    protected $table = 'inter_companies';

    protected $fillable = [
        'inter_company_code',
        'inter_company_name',
        'status',
    ];

    public function scopeWithName($query, $inter_company)
    {
        return $query->where('inter_company_name',$inter_company)->where('status','ACTIVE')->first();
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
