<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta name=" robots" content=" noindex, nofollow">

    <!-- Title -->
    <title>{{ config('app.name') }} Admin · Login</title>

    <!--Favicon -->
    {{-- <link rel="icon" href="{{ asset('assets') }}/images/brand/CORPORATE_TV_04.png" type="image/x-icon" /> --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/small-logo.png') }}">
    <!-- Bootstrap css -->
    <link href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />

    <!-- Style css -->
    <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/custom.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/dark.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/skin-modes.css" rel="stylesheet" />

    <!-- Animate css -->
    <link href="{{ asset('assets') }}/plugins/animated/animated.css" rel="stylesheet" />

    <!---Icons css-->
    <link href="{{ asset('assets') }}/plugins/icons/icons.css" rel="stylesheet" />

    <!-- Select2 css -->
    <link href="{{ asset('assets') }}/plugins/select2/select2.min.css" rel="stylesheet" />

    <!-- P-scroll bar css-->
    <link href="{{ asset('assets') }}/plugins/p-scrollbar/p-scrollbar.css" rel="stylesheet" />

</head>

<body>
    <div class="page login-bg">
        <div class="page-single">
            <div class="container">
                <div class="card mb-0 login-card-width">
                    <div class="text-center login-logo">
                        {{-- <img src="{{ asset('assets') }}/images/Transmedia_logo_updated.jpg"
                            class="header-brand-img custom-logo" alt="Dayonelogo"> --}}
                    </div>
                    <div class="p-0 text-center logo-name-text">
                        <img src="{{ asset('assets') }}/images/logo.png" alt="logo" class="login-logo-ct">
                        <p class="text">Sign In to your account</p>
                    </div>
                    <form class="card-body p-0" id="login" method="POST"
                        action="{{ route('admin.login.check') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-control" placeholder="Enter Email" type="email" name="email"
                                value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Enter Password" type="password" name="password"
                                    id="password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="custom-control custom-checkbox mb-0 ">
                                <input type="checkbox" class="custom-control-input" name="remember_me"
                                    @if (old('remember_me')) checked @endif>
                                <span class="custom-control-label mb-0 form-label">Remeber me</span>
                            </label>
                        </div>
                        <div class="submit">
                            <button type="submit" class="btn btn-primary btn-block ct-btn-w">Login</button>
                        </div>
                        {{-- <div class="text-center mt-3">
                                                <p class="mb-2"><a href="#">Forgot Password</a></p>
                                            </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery js-->
    <script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap4 js-->
    <script src="{{ asset('assets') }}/plugins/bootstrap/popper.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Select2 js -->
    <script src="{{ asset('assets') }}/plugins/select2/select2.full.min.js"></script>

    <!-- P-scroll js-->
    <script src="{{ asset('assets') }}/plugins/p-scrollbar/p-scrollbar.js"></script>

    <!-- Custom js-->
    <script src="{{ asset('assets') }}/js/custom.js"></script>

    @include('notification.iziToast')

    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                iziToast.error({
                    title: 'Error!',
                    message: '{{ $error }}',
                    position: 'topRight',
                    balloon: false,
                    timeout: 5000,
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight',
                });
            @endforeach
        @endif

        // Show password visibility toggle button on click event
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>

</body>

</html>
