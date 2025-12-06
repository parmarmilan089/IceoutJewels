@extends('admin.layout.master')

@section('title', isset($addon) ? 'Edit Addon' : 'Add New Addon')

@section('body')
    <!-- Page header -->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.addons.index') }}" class="text-decoration-none">Addons</a></li>
            <li class="breadcrumb-item1 active">{{ isset($addon) ? 'Edit' : 'Add' }} Addon</li>
        </ol>
    </div>
    <!-- End Page header -->

    <div class="row">
        <div class="col-6">
            <div class="card card-max-width">
                <div class="card-header border-bottom-0">
                    <h4 class="card-title">{{ isset($addon) ? 'Edit' : 'Add' }} Addon Information</h4>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script>


    </script>
@endsection
