@extends('admin.layout.master')

@section('title', 'Variant Options')

@section('style')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endsection

@section('body')
    <div class="page-header d-flex flex-wrap">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1 active">Variant Options</li>
        </ol>
        <div class="page-rightheader ml-md-auto">
            <div class="btn-list">
                <a href="{{ route('admin.variant-options.create') }}" class="btn btn-primary d-flex align-items-center mb-0">
                    <i class="feather feather-plus"></i> &nbsp;Add Variant Option
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Variant Options (Color: Silver, Gold, etc.)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-data">
                        <table class="table table-bordered border-bottom" id="variantOptionsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Variant Type</th>
                                    <th>Option Name</th>
                                    <th>SKU Code</th>
                                    <th>Additional Price</th>
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
            var table = $('#variantOptionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.variant-options.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'variant_name', name: 'variant.name'},
                    {data: 'option_name', name: 'option_name'},
                    {data: 'sku_code', name: 'sku_code'},
                    {data: 'price_display', name: 'additional_price'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

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
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
