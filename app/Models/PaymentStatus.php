<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_statuses';

    protected $fillable = [
        'payment_status',
        'status',
    ];

    public function scopeWithName($query, $payment_status)
    {
        return $query->where('payment_status',$payment_status)->where('status','ACTIVE')->first();
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
