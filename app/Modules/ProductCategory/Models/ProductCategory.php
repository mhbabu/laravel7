<?php

namespace App\Modules\ProductCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductCategory extends Model {

    protected $table = 'product_categories';
    protected $fillable = [
        'id',
        'name',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductCategoryList()
    {
        $query = ProductCategory::where('is_archive', 0);
        return $query->orderBy('id','desc');
    }

    public static function boot() {
        parent::boot();
        static::creating(function($productCategory) {
            $productCategory->created_by = Auth::user()->id;
            $productCategory->updated_by = Auth::user()->id;
        });

        static::updating(function($productCategory) {
            $productCategory->updated_by = Auth::user()->id;
        });
    }
}
