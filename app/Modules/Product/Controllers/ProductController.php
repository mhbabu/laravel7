<?php

namespace App\Modules\Product\Controllers;

use App\DataTables\ProductListDataTable;
use App\Libraries\Encryption;
use App\Modules\Product\Models\Product;
use App\Modules\ProductCategory\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(ProductListDataTable $dataTable)
    {
        return $dataTable->render("Product::index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $data['productCategories'] = ProductCategory::where('is_archive',0)->where('status',1)->pluck('name','id');
       return view('Product::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'    => ['required',Rule::unique('products')->where(function($query) use($request){
                $query->where(['product_category_id'=> $request->input('product_category_id'),'is_archive'=>false]);})
            ],
            'product_category_id'   => 'required',
            'unit'   => 'required',
            'price'   => 'required',
            'status'  => 'required'
        ]);

        $product = new Product();
        $product->product_category_id = $request->input('product_category_id');
        $product->name = $request->input('name');
        $product->unit = $request->input('unit');
        $product->price = $request->input('price');
        $product->status = $request->input('status');
        $product->save();

        /* Generating Product code */

        $productCodePrefix = 'P-';
        DB::statement("update products, products as table2  SET products.product_code=(
            select concat('$productCodePrefix', LPAD( IFNULL(MAX(SUBSTR(table2.product_code,-4,4) )+1,0),4,'0')) as product_code
            from (select * from products ) as table2
            where table2.id!='$product->id' and table2.product_code like '$productCodePrefix%')
            where products.id='$product->id' and table2.id='$product->id'");

        return redirect(route('products.index'))->with('flash_success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $productId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($productId)
    {
        $decodedProductId = Encryption::decodeId($productId);
        $data['productCategories'] = ProductCategory::where('is_archive',0)->where('status',1)->pluck('name','id');
        $data['product'] = Product::find($decodedProductId);

        return view('Product::edit',$data);
    }

    public function update(Request $request,$productId)
    {

        $decodedProductId = Encryption::decodeId($productId);
        $this->validate($request, [
            'name'  => ['required',Rule::unique('products')->ignore($decodedProductId)->where(function($query)use($request){
                $query->where('product_category_id',$request->input('product_category_id'))->where('is_archive',false);})
            ],
            'product_category_id' => 'required',
            'unit'   => 'required',
            'price'   => 'required',
            'status' => 'required'
        ]);

        $product = Product::find($decodedProductId);
        $product->product_category_id = $request->input('product_category_id');
        $product->name = $request->input('name');
        $product->unit = $request->input('unit');
        $product->price = $request->input('price');
        $product->status = $request->input('status');
        $product->save();

        return redirect(route('products.index'))->with('flash_success','Product updated successfully.');
    }

    public function delete($productId)
    {
        $decodedProductId = Encryption::decodeId($productId);
        $product = Product::find($decodedProductId);
        $product->is_archive = 1;
        $product->deleted_by = auth()->user()->id;
        $product->deleted_at = Carbon::now();
        $product->save();
        session()->put('flash_success', 'Product deleted successfully!');
    }
}
