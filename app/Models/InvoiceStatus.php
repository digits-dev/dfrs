<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    use HasFactory;

    protected $table = 'invoice_statuses';

    protected $fillable = [
        'invoice_status',
        'status',
    ];

    public function scopeWithName($query, $invoice_status)
    {
        return $query->where('invoice_status',$invoice_status)->where('status','ACTIVE')->first();
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
