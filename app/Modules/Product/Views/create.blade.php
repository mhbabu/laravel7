@extends('backend.layouts.app')
@section('title','Product Create')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <span class="card-title"><i class="fa fa-plus"></i> Product Create</span>
                </div><!--col-->
            </div>
        </div>
        {!! Form::open(['route'=>'products.store', 'method'=>'post','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name','Name : ',['class'=>'required-star']) !!}
                        {!! Form::text('name','',['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('product_category_id','Category : ',['class'=>'required-star']) !!}
                        {!! Form::select('product_category_id',$productCategories,'',['class'=>$errors->has('product_category_id')?'form-control is-invalid':'form-control required','placeholder'=>'Select category']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('unit','Unit : ',['class'=>'required-star']) !!}
                        {!! Form::text('unit','',['class'=>$errors->has('unit')?'form-control is-invalid':'form-control required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('price','Price : ',['class'=>'required-star']) !!}
                        {!! Form::number('price','',['class'=>$errors->has('price')?'form-control is-invalid':'form-control required']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('status','Status : ',['class'=>'required-star']) !!}
                        {!! Form::select('status',['1'=>'Active','0'=>'Inactive'],'',['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('products.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn float-right btn-primary"><i class="fa fa-save"></i> Save</button>
        </div>
        {!! Form::close() !!}
    </div><!--card-->
    <br/>
@endsection
@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataForm').validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

    </script>
@endsection
