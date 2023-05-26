<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class InvoiceType extends Model
{
    use HasFactory;

    protected $table = 'invoice_types';

    protected $fillable = [
        'invoice_type',
        'status',
    ];

    public function scopeWithName($query, $invoice_type)
    {
        return $query->where('invoice_type',$invoice_type)->where('status','ACTIVE')->first();
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
