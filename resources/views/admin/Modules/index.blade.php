@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href={{asset('assets/vendors/iconly/bold.css')}}>
<link rel="stylesheet" href={{asset('assets/vendors/simple-datatables/style.css')}}>
<link rel="stylesheet" href={{asset('assets/vendors/sweetalert2/sweetalert2.min.css')}}>
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
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    Modules List
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.modules.create') }}" class="btn btn-primary btn-outline">
                                        <span class="bi bi-plus"></span>Create
                                    </a>
<button id="warning"
                                                class="btn btn-outline-warning btn-lg btn-block">Warning</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allRecords as $Module)
                                    <tr>
                                        <td>
                                         {{$loop->iteration}}</td>
                                        <td>{{$Module->name}}</td>
                                        <td>
                                            @statusBadge($Module->status)
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.modules.edit',$Module->id) }}" class="btn btn-primary btn-sm"><span class="bi bi-pencil"></span></a>
                                            <a href="#" class="btn btn-success btn-sm"><span class="bi bi-eye"></span></a>

                                            <a href="#" id="warning"
                                                class="btn btn-danger p-0">
                                                <form action="{{ route('admin.modules.destroy', $Module->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <span class="bi bi-trash"></span>
                                                    </button>
                                                </form>
                                            </a>
                                            <a href="#" id="warning" class="btn btn-warning btn-sm">
                                                <span class="bi bi-eye"></span>
                                            </a>

                                        </td>
                                    </tr>
                                    @empty
                                    Data Not Found
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
</div>
@section('scripts')
<script src={{asset('assets/vendors/simple-datatables/simple-datatables.js')}}></script>
<script src={{asset('assets/js/extensions/sweetalert2.js')}}></script>
<script src={{asset('assets/vendors/sweetalert2/sweetalert2.all.min.js')}}></script>
<script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
</script>
@stop
@endsection
