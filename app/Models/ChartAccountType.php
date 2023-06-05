<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartAccountType extends Model
{
    use HasFactory;

    protected $table = 'chart_account_types';

    protected $fillable = [
        'chart_account_type',
        'status',
    ];
}
