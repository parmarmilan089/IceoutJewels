@extends('admin.layout.master')

@section('title', isset($user) ? 'Edit User' : 'Add New User')

@section('body')
    <!-- Page header -->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Users</a></li>
            <li class="breadcrumb-item1 active">{{ isset($user) ? 'Edit' : 'Add' }} User</li>
        </ol>
    </div>
    <!-- End Page header -->

    <div class="row">
        <div class="col-6">
            <div class="card card-max-width">
                <div class="card-header border-bottom-0">
                    <h4 class="card-title">{{ isset($user) ? 'Edit' : 'Add' }} User Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
                        method="POST" id="userAddForm" enctype="multipart/form-data">
                        @csrf
                        @if (isset($user))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">Full Name <span
                                            class="text-red">*</span></label>
                                    <input  type="text" class="form-control" name="first_name" id="first_name"
                                        placeholder="Enter First Name" value="{{ isset($user) ? $user->first_name : '' }}">
                                        @error('first_name')
                                                <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Enter Email"
                                    value="{{ $user->email ?? '' }}"
                                    {{ isset($user) ? 'disabled' : '' }}>
                                    @error('email')
                                           <span class="error text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status <span class="text-red">*</span></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Please Select User Status</option>
                                        <option value=1
                                            {{ isset($user) && $user->status == true ? 'selected' : '' }}>Active
                                        </option>
                                        <option value=0
                                            {{ isset($user) && $user->status == false ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="profile">Profile Pic</label>
                                    <input type="file" id="profile" class="form-control" name="profile">
                                </div>
                            </div>
                        </div>




                        <div class="form-footer mt-4 d-flex align-items-center justify-content-end gap-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-danger ct-btn-w">Cancel</a>
                            <button type="submit" class="btn btn-primary ct-btn-w">{{ isset($user) ? 'Update' : 'Save' }}
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6" id="imgPreview" @if (isset($user) && $user->profile == null) style="display: none" @endif>
           <div class="card h-100 mb-0">
               <div class="card-header border-bottom-0">
                   <h3 class="card-title">User Profile Preview</h3>
               </div>
               <div class="card-body d-flex align-items-center justify-content-center">
                   <img src="{{ isset($user) && $user->profile != null ? $user->profile : asset('assets/images/profile/default.jpg') }}"
                       alt="" id="blah" class="user-profile-img">
               </div>
           </div>
       </div>
    </div>
@endsection


@section('script')
    <script>

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);

                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {

            $("#profile").change(function() {
                $("#imgPreview").show();
                readURL(this);
            });
            // jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // jQuery Validation for the form
            $("#userAddForm").validate({
                highlight: function(element) {
                    $(element).closest('.form-control').addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-control').removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit();
                },
                rules: {
                    email: {
                        required: true,
                        maxlength: 255,
                        remote: {
                            url: "{{ route('user.checkUniqueEmail') }}", // Route to check if the font name is unique
                            type: "GET",
                            data: {
                                email: function() {
                                    return $("#email").val(); // Get the value of font_name field
                                },
                                id: function() {
                                    return "{{ isset($user) ? $user->id : '' }}"; // Pass the font ID (if editing)
                                }
                            }
                        }
                    },
                    first_name: {
                        required: true,
                        maxlength: 255,
                    },

                    status: {
                        required: true,
                    },
                     profile: {
                        // required: true,
                        accept: "png,jpeg,jpg",
                        filesize: 3 * 1024 * 1024 // 3MB in bytes
                    },
                },
                messages: {
                    email: {
                        required: "Please enter email",
                        remote: "This email is already taken. Please choose another."
                    },
                    first_name: {
                        required: "Please enter first name",
                    },

                    status: {
                        required: "Please select a status",
                    },
                    profile: {
                        accept: "Only jpeg, png, jpg file types are allowed",
                        filesize: "The image size must not exceed 3MB"
                    }
                },
            });

            $.validator.addMethod("filesize", function(value, element, param) {
                if (element.files.length > 0) {
                    var fileSize = element.files[0].size; // Get file size
                    return this.optional(element) || (fileSize <= param);
                }
                return true; // If no file is selected, valid
            });
        });
    </script>
@endsection
