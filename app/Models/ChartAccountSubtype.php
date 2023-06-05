<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartAccountSubtype extends Model
{
    use HasFactory;

    protected $table = 'chart_account_subtypes';

    protected $fillable = [
        'chart_account_subtype',
        'status',
    ];
}
