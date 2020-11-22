@extends('backend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/dist/css/util.css') !!}
    {!! Html::style('assets/backend/dist/css/jquery-ui-smooth.css') !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-sm">
                <h5><i class="fa fa-plus"></i> Add Product</h5>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'product.add-cart', 'method'=>'post','id'=>'purchaseProductForm']) !!}
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="input-group">
                        {!! Form::text('add_product','',['class'=>'form-control required','id'=>'addProduct','placeholder'=>'Enter a Product / Double click','required'=>true,'autofocus'=>true]) !!}
                        <span class="input-group-btn">
                        <button type="button" id="showProductList" class="btn btn-info rounded-0"><i class="fa fa-search-plus"aria-hidden="true"></i> </button>
                    </span>
                        <input type="hidden" id="productId" name="productId" value=""/>
                    </div>
                    <ul class="append hidden" id="productList"></ul>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="font-weight-bold">Select Products:</label>
                    <div class="table-responsive" id="addProductTable">
                        <table class="table table-bordered table-striped order-product-table">
                            <thead class="alert alert-info">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Item Total</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $i = 0;
                            if (Session::get('add.activeProduct')) {
                                $reverse_products = array_reverse(Session::get('add.activeProduct'));
                            }
                            ?>
                            @if(Session::get('add.activeProduct'))

                                @foreach($reverse_products as $activeProduct)
                                    {!! Form::open(['route'=>'product.add-cart.delete', 'method'=>'post']) !!}

                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td class="text-left"><input type="hidden" name="product_id" value="{{ $activeProduct['id'] }}"> {{ $activeProduct['product_code'] }} </td>
                                        <td class="text-left">{{ $activeProduct['name'] }}</td>
                                        <td class="text-left">{{ $activeProduct['unit'] }}</td>
                                        <td class="text-left" style="width: 10em;">{!! Form::number('quantity',$activeProduct['quantity'],['class'=>'form-control quantity']) !!}</td>
                                        <td class="text-left">
                                            <input class="price" type="hidden" name="price" value="{{ $activeProduct['price'] }}">
                                            {{ $activeProduct['price'] }} BDT
                                        </td>
                                        <td class="text-left">
                                            {{ $activeProduct['item_total'] }} BDT
                                            <input type="hidden" name="item_total" value="{{ $activeProduct['item_total'] }}">
                                        </td>
                                        <td class="span2 text-center">
                                            <button title="Add" type="submit" class="hidden edit btn btn-primary btn-sm" name="edit_delete" value="edit"><i class="fa fa-save"></i></button>
                                            <button title="Remove" type="submit" class="btn btn-danger btn-sm" name="delete" onclick="return confirm('Are you sure to remove question?');"><i class="fa fa-times-circle"></i></button>
                                        </td>
                                    </tr>

                                    {!! Form::close() !!}
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-right">Sub Total</td>
                                    <td>
                                        <input class="subtotal" type="hidden" name="subtotal" value="">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Tax (15%)</td>
                                    <td>
                                        <input class="tax" type="hidden" name="tax" value="">
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Discount</td>
                                    <td>
                                        <input type="hidden" name="discount" value="100">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Grand Total</td>
                                    <td>
                                        <input type="hidden" name="grand_total" value="">
                                    </td>
                                    <td></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="8" style="text-align:center; color:#f10505;"><strong>This type of questions are not available.</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {!! Form::open(['route'=>'exam.live-exam.questions.store', 'method'=>'post','id'=>'dataForm']) !!}
            <div class="row">
                {{--                <input type="hidden" name="exam_id" value="{{ $liveExamId }}">--}}
            </div>
        </div><!--card-body-->
        <div class="card-footer">
            <a href="{{ route('purchases.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn float-right btn-primary pull-right"><i class="fa fa-save"></i> Purchase</button>
        </div>
        {!! Form::close() !!}
    </div><!--card-->
@endsection

@section('footer-script')
    {!! Html::script('assets/backend/dist/js/jquery-migrate-3.0.0.min.js') !!}
    {!! Html::script('assets/backend/dist/js/jquery-ui-1.10.2.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $("#addProduct").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        dataType: "json",
                        type: 'POST',
                        url: '{{ url('products/auto-suggest') }}',
                        data: {
                            term : request.term
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            console.log(data);

                            response($.map(data, function (value) {
                                return {
                                    label: value.name + " (" + value.product_code + ")",
                                    hitem: value.id
                                };
                            }));
                        },
                        error: function (data) {
                            console.log("error");
                        }
                    });
                },
                select: function (event, ui) {
                    $("#questionId").val(ui.item.hitem);
                    $("#questionForm").submit();
                },
                autoFocus:true,
                matchContains: true,
                focus: function (event, ui) {
                    $("#productId").val(ui.item.hitem);
                },
                select: function (event, ui) {
                    $("#productId").val(ui.item.hitem);
                    $("#purchaseProductForm").submit();
                },
            }).bind('dblclick', function () { $(this).autocomplete("search", "all"); });
            $('#showBookList').focus(function(event) {
                $("#addProduct").autocomplete('search' , 'all');
                $("#addProduct").focus();
            });

            /**********************
             VALIDATION START HERE
             **********************/
            $('#dataForm').validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection
