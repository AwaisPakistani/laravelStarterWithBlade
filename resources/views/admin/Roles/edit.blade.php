@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
@stop
<!--/style-->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Roles</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url()->previous() }}">Roles</a></li>
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
                        <h4 class="card-title">Update Role</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.roles.update',$Role->id) }}" method="POST" class="form">
                            @csrf
                            @method('PUT')
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="name-column"> Name</label>
                                            <input type="text" id="user-name-column" value="{{ old('name',$Role->name) }}" class="form-control @error('name')
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

                                     <!-- table responsive -->
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        Module<div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </th>
                                                    <th scope="col">Create</th>
                                                    <th scope="col">Store</th>
                                                    <th scope="col">Read</th>
                                                    <th scope="col">Show</th>
                                                    <th scope="col">Edit</th>
                                                    <th scope="col">Update</th>
                                                    <th scope="col">Delete</th>
                                                    <th scope="col">Approve</th>
                                                    <th scope="col">Download</th>
                                                    <th scope="col">All</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($modules as $index=>$module)
                                                <tr>
                                                    <td colspan="10">
                                                    <b class="text-capitalize" id="{{ $index }}">
                                                        {{
                                                        $module->name}}
                                                     </b>
                                                    </td>
                                                </tr>
                                                @foreach ($module->subModules as $index2=>$submodule)
                                                <tr>
                                                    <td>
                                                     {{ $submodule->name }}
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                            <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                            <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                                    </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                        class="btn btn-primary me-1 mb-1">Submit</button>
                                        <a href="{{ url()->previous() }}""
                                        class="btn btn-light-secondary me-1 mb-1">Back</a>
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

@stop
@endsection
