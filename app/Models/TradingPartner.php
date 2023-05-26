<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class TradingPartner extends Model
{
    use HasFactory;

    protected $table = 'trading_partners';

    protected $fillable = [
        'trading_partner',
        'status',
    ];

    public function scopeWithName($query, $trading_partner)
    {
        return $query->where('trading_partner',$trading_partner)->where('status','ACTIVE')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status','ACTIVE')->get();
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
