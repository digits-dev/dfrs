<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class FinancialReport extends Model
{
    use HasFactory;

    protected $table = 'financial_reports';

    protected $fillable = [
        'invoice_date',
        'invoice_number',
        'invoice_types_id',
        'voucher_number',
        'trading_partners_id',
        'invoice_statuses_id',
        'payment_statuses_id',
        'is_accounted',
        'amount',
        'currencies_id',
        'exchange_rate',
        'exchange_date',
        'invoice_amount',
        'po_number',
        'gl_date',
        'description',
        'chart_account'
    ];

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
