@extends('layout.master')
@section('content')
<!--style-->
@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/iconly/bold.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendors/simple-datatables/style.css')}}">
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
                                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
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
                                    Roles List
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-outline">
                                        <span class="bi bi-plus"></span>Create
                                    </a>
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
                                    @forelse ($allRecords as $Role)
                                    <tr>
                                        <td>
                                         {{$loop->iteration}}</td>
                                        <td>{{$Role->name}}</td>
                                        <td>
                                            @statusBadge($Role->status)
                                        </td>
                                        <td>
                                             <x-action-buttons
                                            :canEdit="auth()->user()->hasPermission('admin.roles.edit')" :canDelete="auth()->user()->hasPermission('admin.roles.destroy')" :canShow="auth()->user()->hasPermission('admin.roles.show')" :editRoute="route('admin.roles.edit',$Role)" :deleteRoute="route('admin.roles.destroy',$Role)" :showRoute="route('admin.roles.show',$Role)"
                                            />
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
<script src="{{asset('assets/vendors/simple-datatables/simple-datatables.js')}}"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
@stop
@endsection
