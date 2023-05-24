<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class ChartAccount extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'fs',
        'header',
        'parent',
        'child',
        'chart_code',
        'chart_account',
        'status',
    ];

    public function scopeWithAccount($query, $chart_account)
    {
        return $query->where('chart_account',$chart_account)->where('status','ACTIVE')->first();
    }

    public function scopeWithAccountCode($query, $chart_code)
    {
        return $query->where('chart_code',$chart_code)->where('status','ACTIVE')->first();
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
