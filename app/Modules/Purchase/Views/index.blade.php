@extends('backend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/dist/css/dataTables.bootstrap4.min.css') !!}
    {!! Html::style('assets/backend/dist/css/buttons.dataTables.min.css') !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <span class="card-title"><i class="fa fa-list-alt"></i> Purchase Products </span>
                </div><!--col-->

                <div class="col-sm-7 pull-right">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('purchases.create') }}"
                           class="btn btn-sm btn-success ml-1"
                           data-toggle="tooltip"
                           title="Add Purchase Product"
                           data-original-title="Create New">
                            <i class="fa fa-plus-circle"></i> Purchase Product
                        </a>
                    </div>
                </div><!--col-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection

@section('footer-script')
    {!! Html::script('assets/backend/dist/js/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.bootstrap4.min.js') !!}
    {!! Html::script('assets/backend/dist/js/dataTables.buttons.min.js') !!}
    {!! Html::script('assets/backend/dist/js/buttons.server-side.js') !!}
    @if(isset($dataTable))
        {!! $dataTable->scripts() !!}
    @endif

    <script type="text/javascript">
        $(document.body).on('click','.action-delete',function(ev){
            ev.preventDefault();
            let URL = $(this).attr('href');
            let redirectURL = "{{ route('purchases.index') }}";
            warnBeforeAction(URL, redirectURL);
        });
    </script>
@endsection
