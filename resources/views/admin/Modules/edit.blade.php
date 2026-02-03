@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
<!-- Include Choices CSS -->
<link rel="stylesheet" href="{{asset('assets/vendors/choices.js/choices.min.css')}}" />
@stop
<!--/style-->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Modules</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Modules</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Module</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.modules.update',$module->id) }}" method="POST" class="form">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="title-column"> Parent Module</label>
                                            <select class="choices form-select" id="parent_id"
                                            value="{{ old('parent_id') }}" name="parent_id">
                                            <option value="" disabled selected>Select Parent Module</option>
                                             @foreach ($modules as $submodule)
                                                <option value="{{ $submodule->id }}"
                                                    @if ($module->parent_id == $submodule->id) selected @endif>
                                                    {{ $submodule->name }}</option>
                                            @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name-column">User Name</label>
                                            <input type="text" id="user-name-column" value="{{ old('name',$module->name) }}" class="form-control @error('name')
                                            is-invalid
                                            @enderror"
                                            placeholder="Name" name="name">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                        class="btn btn-primary me-1 mb-1">Submit</button>
                                        <button type="reset"
                                        class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
@section('scripts')
<!-- Include Choices JavaScript -->
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
@stop
@endsection
