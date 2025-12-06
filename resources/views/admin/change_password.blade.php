@extends('admin.layout.master')

@section('title', 'Change Password')

@section('style')

@endsection

@section('body')
    <!--Page header-->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1 active">Change Password</li>
        </ol>
    </div>
    <!--End Page header-->

    <div class="row">
        <div class="col-12 ">
            <div class="card card-max-width">
                <div class="card-header border-bottom-0">
                    <h4 class="card-title">Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.change_password.update') }}" id="frmChangePassword" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="old_password">Old Password <span class="text-red">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="old_password" class="form-control" name="old_password" placeholder="Enter Old Password">
                                        <div class="input-group-append">
                                            <span class="input-group-text togglePassword" data-target="#old_password">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <label for="old_password" generated="true" class="error" style="display: none;">Please Provide a Old Password</label> <!-- Error message -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="password">New Password <span class="text-red">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password">
                                        <div class="input-group-append">
                                            <span class="input-group-text togglePassword" data-target="#password">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <label for="password" generated="true" class="error" style="display: none;">Please Provide a New Password</label> <!-- Error message -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">Confirm Password <span class="text-red">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password">
                                        <div class="input-group-append">
                                            <span class="input-group-text togglePassword" data-target="#password_confirmation">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <label for="password_confirmation" generated="true" class="error" style="display: none;">Please Provide a Confirm Password</label> <!-- Error message -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-footer mt-4 mt-lg-5 d-flex align-items-center justify-content-end ">
                                    <button type="submit" class="btn btn-primary ct-btn-w">Change Password </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Custom method to validate the password pattern
            $.validator.addMethod("strongPassword", function(value, element) {
                return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
            }, "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");

            $("#frmChangePassword").validate({
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                    $(element).siblings('.error').show(); // Show error message
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                    $(element).siblings('.error').hide(); // Hide error message
                },
                rules: {
                    old_password: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        strongPassword: true // Use the custom validator
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    old_password: {
                        required: "Please Provide a Old Password",
                    },
                    password: {
                        required: "Please Provide a New Password",
                        pattern: "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                    },
                    password_confirmation: {
                        required: "Please Provide a Confirm Password",
                        equalTo: "Please Enter Same as Password"
                    },
                },
            });

             // Toggle password visibility
            $('.togglePassword').on('click', function() {
                const input = $($(this).data('target'));
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                const icon = $(this).find('i');
                icon.toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
@endsection
