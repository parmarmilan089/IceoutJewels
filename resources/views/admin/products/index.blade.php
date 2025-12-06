
@extends('admin.layout.master')

@section('title', 'List of Products')

@section('style')
    <!-- Data Table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Sweet Alert css -->
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endsection

@section('body')
    <!--Page header-->
    <div class="page-header d-flex flex-wrap">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1 active">Products</li>
        </ol>
        <div class="page-rightheader ml-md-auto">
            <div class="btn-list">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-flex align-items-center mb-0"><i
                        class="feather feather-plus"></i> &nbsp;Add New Product</a>
            </div>
        </div>
    </div>

    <!--End Page header-->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">List Of Products</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-data">
                        <table class="table table-bordered border-bottom" id="productDataTable">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">No</th>
                                    <th class="border-bottom-0">Image</th>
                                    <th class="border-bottom-0">Name</th>
                                    <th class="border-bottom-0">SKU</th>
                                    <th class="border-bottom-0">Category</th>
                                    <th class="border-bottom-0">Price Range</th>
                                    <th class="border-bottom-0">Stock</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Data tables js-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <!-- Sweet Alert Js -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#productDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.products.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'sku', name: 'sku'},
                    {data: 'category_name', name: 'category.name'},
                    {data: 'price_range', name: 'price_range', orderable: false},
                    {data: 'stock', name: 'stock', orderable: false},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Status toggle
            $(document).on('change', '.status-toggle', function() {
                var id = $(this).data('id');
                
                $.ajax({
                    url: '/admin/products/' + id + '/toggle-status',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status'
                        });
                    }
                });
            });

            // Delete product
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0b2c58',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    table.ajax.reload();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to delete product'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection