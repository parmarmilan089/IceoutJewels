@extends('admin.layout.master')

@section('title', 'List of Users')

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
            <li class="breadcrumb-item1 active">Users</li>
        </ol>
    </div>
    <!--End Page header-->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">List Of Users</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-data">
                        <table class="table table-bordered border-bottom" id="userDataTable">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">No</th>
                                    <th class="border-bottom-0">Full Name</th>
                                    <th class="border-bottom-0">Email</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Action</th>
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
            // jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(function() {
                var table = $('#userDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [
                        [0, "desc"]
                    ],
                    ajax: {
                        url: "{{ route('admin.users.index') }}",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id',
                            'searchable': false
                        },
                        {
                            data: 'first_name',
                            name: 'first_name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    initComplete: function() {
                        // Add placeholder to the default search input
                        $('#userDataTable_filter input')
                            .attr('placeholder', 'Search Users')
                            .attr('autocomplete', 'off')
                            .val('');
                    },
                });
            });

        });

        // Delete user confirmation
        function deleteUserConfirmation(id) {
            swal.fire({
                title: "Delete?",
                icon: 'question',
                text: "Please ensure and then confirm!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then(function(e) {
                if (e.value === true) {
                    var url = "{{ route('admin.users.destroy', ['_id_']) }}";
                    var delete_url = url.replace('_id_', id);

                    $.ajax({
                        type: 'DELETE',
                        url: delete_url,
                        dataType: 'JSON',
                        success: function(results) {
                            if (results.status === true) {
                                iziToast.success({
                                    title: 'Success!',
                                    message: results.message,
                                    position: 'topRight', // Set the position to top right
                                    timeout: 5000,
                                    iconColor: 'green'
                                });
                                $("#userDataTable").DataTable().ajax.reload(); // Reload the DataTable
                            } else {
                                swal.fire("Error!", results.message, "error");
                            }
                        },
                        error: function(xhr) {
                            // Handle server error response
                            let message = xhr.responseJSON?.message || "An error occurred while deleting the user.";
                            swal.fire("Error!", message, "error");
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function(dismiss) {
                return false;
            });
        }

    </script>
@endsection
