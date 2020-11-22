<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model {

    protected $table = 'products';
    protected $fillable = [
        'id',
        'product_category_id',
        'name',
        'unit',
        'code',
        'price',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductList()
    {
        $query = Product::leftJoin('product_categories','product_categories.id','=','products.product_category_id')
            ->where('products.is_archive', 0);
        return $query->orderBy('products.id', 'desc');
    }

    public static function boot() {
        parent::boot();
        static::creating(function($product) {
            $product->created_by = Auth::user()->id;
            $product->updated_by = Auth::user()->id;
        });

        static::updating(function($product) {
            $product->updated_by = Auth::user()->id;
        });
    }

}
