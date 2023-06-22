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
        'is_viewable',
        'status',
    ];

    public function scopeWithName($query, $inter_company)
    {
        return $query->where('inter_company_name',$inter_company)->where('status','ACTIVE')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status','ACTIVE')->where('is_viewable',1)
            ->orderBy('inter_company_name','ASC')->get();
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
