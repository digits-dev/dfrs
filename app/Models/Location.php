<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'location_code',
        'location_name',
        'is_viewable',
        'status',
    ];

    public function scopeWithName($query, $location)
    {
        return $query->where('location_name',$location)->where('status','ACTIVE')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status','ACTIVE')->where('is_viewable',1)
            ->orderBy('location_name','ASC')->get();
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
