@extends('admin.layout.master')

@section('title', 'Update Profile')

@section('style')

@endsection

@section('body')
    <!--Page header-->
    <div class="page-header d-lg-flex d-block">
        <ol class="breadcrumb1" style="background: transparent">
            <li class="breadcrumb-item1"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item1 active">Profile</li>
        </ol>
    </div>
    <!--End Page header-->

    <div class="row">
        <div class="col-lg-6 mb-lg-0 mb-5 ">
            <div class="card h-100 mb-0">
                <div class="card-header border-bottom-0">
                    <h4 class="card-title">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" id="frmProfile" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name <span
                                            class="text-red">*</span></label>
                                    <input type="text" id="first_name" class="form-control" name="first_name"
                                        placeholder="Enter First Name" value="{{ $data['first_name'] }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name <span
                                            class="text-red">*</span></label>
                                    <input type="text" id="last_name" class="form-control" name="last_name"
                                        placeholder="Enter Last Name" value="{{ $data['last_name'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input type="text" id="email" class="form-control" readonly name="email"
                                        placeholder="Enter Email Address" value="{{ $data['email'] }}">
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
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="mt-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary ct-btn-w">Update Profile</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6" id="imgPreview" @if ($data['profile'] == null) style="display: none" @endif>
            <div class="card h-100 mb-0">
                <div class="card-header border-bottom-0">
                    <h3 class="card-title">Admin Profile Preview</h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    {{-- <img src="{{ $data['profile'] }}" alt="" id="blah" width="297" height="297"> --}}
                    <img src="{{ isset($data['profile']) ? $data['profile'] : '' }}"
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
            // jQuery to automatically add the token to all request headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#profile").change(function() {
                $("#imgPreview").show();
                readURL(this);
            });

            $("#frmProfile").validate({
                highlight: function(element) {
                    $(element).closest('.form').removeClass('has-success').addClass('has-error');
                },
                success: function(element) {
                    $(element).closest('.form').removeClass('has-error').addClass('has-success');
                    $(element).closest('.error').remove();
                },
                rules: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    profile: {
                        // required: true,
                        accept: "png,jpeg,jpg,webp",
                        filesize: 3 * 1024 * 1024 // 3MB in bytes
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter first name",
                    },
                    last_name: {
                        required: "Please enter last name",
                    },
                    profile: {
                        accept: "Only jpeg, png, jpg ,webp file types are allowed",
                        filesize: "The image size must not exceed 3MB"
                    }
                }
            });

            // Custom validation for file size
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
