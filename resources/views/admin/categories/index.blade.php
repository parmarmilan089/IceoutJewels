
@extends('admin.layout.master')

@section('title', 'List of Categories')

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
            <li class="breadcrumb-item1 active">Categories</li>
        </ol>
        <div class="page-rightheader ml-md-auto">
            <div class="btn-list">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-flex align-items-center mb-0">
                    <i class="feather feather-plus"></i> &nbsp;Add New Category
                </a>
            </div>
        </div>
    </div>

    <!--End Page header-->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">List Of Categories</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive-data">
                        <table class="table table-bordered border-bottom" id="categoryDataTable">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="5%">No</th>
                                    <th class="border-bottom-0">Image</th>
                                    <th class="border-bottom-0">Name</th>
                                    <th class="border-bottom-0">Parent Category</th>
                                    <th class="border-bottom-0">Sort Order</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0" width="10%">Actions</th>
                                </tr>
                            </thead>
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
            // Initialize DataTable
            var table = $('#categoryDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.categories.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'parent_name', name: 'parent.name'},
                    {data: 'sort_order', name: 'sort_order'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive: true,
                order: [[4, 'asc']], // Sort by sort_order by default
            });

            // Handle status toggle
            $(document).on('change', '.status-toggle', function() {
                var categoryId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                
                $.ajax({
                    url: "{{ route('admin.categories.index') }}/" + categoryId + "/toggle-status",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: categoryId
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update status',
                        });
                        // Revert checkbox state
                        $('.status-toggle[data-id="' + categoryId + '"]').prop('checked', !isChecked);
                    }
                });
            });

            // Handle delete
            $(document).on('click', '.delete-btn', function() {
                var deleteUrl = $(this).data('url');
                var categoryId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if(response.success) {
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
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to delete category',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection