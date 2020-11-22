<?php

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model {

    protected $table = 'purchases';
    protected $fillable = [
        'id',
        'product_id',
        'date',
        'subtotal',
        'tax',
        'discount',
        'grand_total',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getPurchaseProductList()
    {
        $query = Purchase::leftJoin('products','products.id','=','purchases.product_id')
            ->leftJoin('product_categories','product_categories.id','=','products.product_category_id')
            ->where('purchases.is_archive', 0);
        return $query->orderBy('purchases.id', 'desc');
    }

    public static function boot() {
        parent::boot();
        static::creating(function($purchase) {
            $purchase->created_by = Auth::user()->id;
            $purchase->updated_by = Auth::user()->id;
        });

        static::updating(function($purchase) {
            $purchase->updated_by = Auth::user()->id;
        });
    }

}
