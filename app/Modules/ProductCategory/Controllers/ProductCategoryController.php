<?php

namespace App\Modules\ProductCategory\Controllers;

use App\DataTables\ProductCategoryDataTable;
use App\Libraries\Encryption;
use App\Modules\ProductCategory\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(ProductCategoryDataTable $dataTable)
    {
        return $dataTable->render("ProductCategory::index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ProductCategory::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => ['required',Rule::unique('product_categories')->where(function($query){
                $query->where('is_archive',false);})
            ],
            'status'        => 'required'
        ]);

        $productCategory = new ProductCategory();
        $productCategory->name = $request->get('name');
        $productCategory->status = $request->get('status');
        $productCategory->save();

        return redirect(route('product-categories.index'))->with('flash_success', 'Product category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($productCategoryId)
    {
        $decodedProductCategoryId = Encryption::decodeId($productCategoryId);
        $data['productCategory'] = ProductCategory::find($decodedProductCategoryId);
        return view('ProductCategory::edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $productCategoryId)
    {
        $decodedId  = Encryption::decodeId($productCategoryId);
        $this->validate($request, [
            'name'    => ['required',Rule::unique('product_categories')->ignore($decodedId)->where(function($query){
                $query->where('is_archive',false);})
            ],
            'status'        => 'required'
        ]);

        $decodedProductCategoryId = Encryption::decodeId($productCategoryId);
        $productCategory = ProductCategory::find($decodedProductCategoryId);
        $productCategory->name = $request->get('name');
        $productCategory->status = $request->get('status');
        $productCategory->save();
        return redirect(route('product-categories.index'))->with('flash_success', 'Product category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function delete($productCategoryId)
    {
        $decodedProductCategoryId = Encryption::decodeId($productCategoryId);
        $productCategory = ProductCategory::find($decodedProductCategoryId);
        $productCategory->is_archive = 1;
        $productCategory->deleted_by = auth()->user()->id;
        $productCategory->deleted_at = Carbon::now();
        $productCategory->save();
        session()->put('flash_success', 'Product category deleted successfully!');
    }
}
