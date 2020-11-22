@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <span class="card-title"><i class="fa fa-plus"></i> Product Category Create</span>
                </div><!--col-->
            </div>
        </div>
        {!! Form::open(['route'=>'product-categories.store', 'method'=>'post','id'=>'dataForm']) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name','Name : ',['class'=>'required-star']) !!}
                        {!! Form::text('name','',['class'=>$errors->has('name')?'form-control is-invalid':'form-control required','placeholder'=>'Name']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('status','Status : ',['class'=>'required-star']) !!}
                        {!! Form::select('status',['1'=>'Active','0'=>'Inactive'],'',['class'=>$errors->has('status')?'form-control is-invalid':'form-control required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('product-categories.index') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn float-right btn-primary" id="productCategorySubmit"><i class="fa fa-save"></i> Save</button>
        </div>
        {!! Form::close() !!}
    </div><!--card-->
@endsection
@section('footer-script')
    <script type="text/javascript">
        /********************
         VALIDATION START HERE
         ********************/
        $(document).ready(function () {
            $('#dataForm').validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection
