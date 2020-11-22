<?php

namespace App\DataTables;

use App\Libraries\Encryption;
use App\Modules\Product\Models\Product;
use Yajra\DataTables\Services\DataTable;


class ProductListDataTable extends DataTable
{

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($data) {

                $actionBtn = '<a href="/products/'.Encryption::encodeId($data->id).'/edit/" class="btn btn-xs btn-info" title="Edit Product"><i class="fa fa-edit"></i> Edit</a> ';
                $actionBtn .= '<a href="/products/'.Encryption::encodeId($data->id).'/delete/" class="btn btn-xs btn-danger action-delete" title="Delete Product"><i class="fa fa-trash"></i> Delete</a>';

                return $actionBtn;
            })
            ->editColumn('price',function($data){
                return ($data->price)? $data->price.' BDT' : 'N/A' ;
            })
            ->editColumn('status',function($data){
                return ($data->status == 1)? "<label class='badge badge-success'> Active </label>" : "<label class='badge badge-danger'> Inactive </label>" ;
            })

            ->rawColumns(['price','status','action'])
//            ->removeColumn('id')
            ->make(true);

    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        $account = Product::getProductList();
        $account->select([
            'products.*',
            'product_categories.name as product_category',
        ]);
        return $this->applyScopes($account);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
//            ->addAction(['width' => '160px'])
//            ->minifiedAjax('', null, request()->only(['from', 'to', 'team', 'user', 'category', 'status']))
            ->parameters([
                'dom'         => 'Blfrtip',
                'responsive'  => true,
                'autoWidth'   => false,
                'paging'      => true,
                "pagingType"  => "full_numbers",
                'searching'   => true,
                'info'        => true,
                'searchDelay' => 350,
                "serverSide"  => true,
                'order'       => [[1, 'asc']],
                'buttons'     => ['excel','csv', 'print', 'reset', 'reload'],
                'pageLength'  => 10,
                'lengthMenu'  => [[10, 20, 25, 50, 100, 500, -1], [10, 20, 25, 50, 100, 500, 'All']],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'code'      => ['data' => 'product_code', 'name' => 'product_code', 'orderable' => true, 'searchable' => true],
            'name'      => ['data' => 'name', 'name' => 'name', 'orderable' => true, 'searchable' => true],
            'category'  => ['data' => 'product_category', 'name' => 'product_categories.name', 'orderable' => true, 'searchable' => true],
            'unit'      => ['data' => 'unit', 'name' => 'unit', 'orderable' => true, 'searchable' => true],
            'price'     => ['data' => 'price', 'name' => 'price', 'orderable' => true, 'searchable' => true],
            'status'    => ['data' => 'status', 'name' => 'status', 'orderable' => true, 'searchable' => true],
            'action'    => ['searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products_list_' . date('Y_m_d_H_i_s');
    }
}
