@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
@stop

<div class="page-heading">
   <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profile</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Profile</a></li>
                    </ol>
                </nav>
            </div>
            
        </div>
    </div>
</div>

@section('scripts')
<script src={{asset('assets/vendors/jquery/jquery.min.js')}}></script>

@stop
@endsection
