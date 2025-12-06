@extends('admin.layout.master')

@section('title', 'Product Variants')

@section('style')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endsection

@section('body')
    <div class="page-header d-flex flex-wrap">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1 active">Product Variants</li>
        </ol>
        <div class="page-rightheader ml-md-auto">
            <div class="btn-list">
                <a href="{{ route('admin.product-variants.create') }}" class="btn btn-primary d-flex align-items-center mb-0">
                    <i class="feather feather-plus"></i> &nbsp;Add Variant Type
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Variant Types (Color, Size, Metal, etc.)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-data">
                        <table class="table table-bordered border-bottom" id="variantsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Options Count</th>
                                    <th>Display Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#variantsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product-variants.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'slug', name: 'slug'},
                    {data: 'options_count', name: 'options_count', orderable: false},
                    {data: 'display_order', name: 'display_order'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will also delete all options under this variant type!",
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
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
