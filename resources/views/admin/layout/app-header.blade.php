<div class="app-content main-content">
    <div class="side-app">

        <!--app header-->
        <div class="app-header header">
                <div class="d-flex align-items-center w-100 justify-content-between inner-header-section">
                    <a class="header-brand" href="{{ route('admin.dashboard') }}"></a>
                    <div class="app-sidebar__toggle" data-toggle="sidebar">
                        <a class="open-toggle " href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                fill="none">
                                <path
                                    d="M0 14.6667C0 15.4042 0.510714 16 1.14286 16H14.8571C15.4893 16 16 15.4042 16 14.6667C16 13.9292 15.4893 13.3333 14.8571 13.3333H1.14286C0.510714 13.3333 0 13.9292 0 14.6667ZM0 8C0 8.7375 0.510714 9.33333 1.14286 9.33333H14.8571C15.4893 9.33333 16 8.7375 16 8C16 7.2625 15.4893 6.66667 14.8571 6.66667H1.14286C0.510714 6.66667 0 7.2625 0 8ZM16 1.33333C16 0.595833 15.4893 -4.76837e-07 14.8571 -4.76837e-07H1.14286C0.510714 -4.76837e-07 0 0.595833 0 1.33333C0 2.07083 0.510714 2.66667 1.14286 2.66667H14.8571C15.4893 2.66667 16 2.07083 16 1.33333Z"
                                    fill="currentColor" />
                            </svg>
                        </a>
                        <a class="close-toggle" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m18 18l-6-6m0 0L6 6m6 6l6-6m-6 6l-6 6" />
                            </svg>
                        </a>
                    </div>


                    <div class="d-flex align-items-center gap-5 order-lg-2">
                        <div class="dropdown header-fullscreen">
                            <a class="nav-link icon full-screen-link m-0">
                                <i class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
                                <i class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
                            </a>
                        </div>
                        <div class="dropdown profile-dropdown p-0">
                            <a href="#"
                                class="p-0 nav-link leading-none user-link-box d-flex align-items-center justify-content-center "
                                data-toggle="dropdown">
                                <img src="{{ Auth::guard('Admin')->user()->profile != null ? Auth::guard('Admin')->user()->profile : asset('assets/images/profile/default.jpg') }}"
                                    alt="img" class="avatar ">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated user-data-dropdown">
                                <div class="p-3 text-center border-bottom">
                                    <a href="#"
                                        class="text-center user pb-0 font-weight-bold">{{ Auth::guard('Admin')->user()->name }}</a>
                                </div>
                                <a class="dropdown-item d-flex" href="{{ route('admin.profile') }}">
                                    <i class="feather feather-user mr-3 fs-16 my-auto"></i>
                                    <div class="mt-1">Profile</div>
                                </a>
                                <a class="dropdown-item d-flex" href="{{ route('admin.change_password') }}">
                                    <i class="feather feather-edit-2 mr-3 fs-16 my-auto"></i>
                                    <div class="mt-1">Change Password</div>
                                </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                                <a class="dropdown-item d-flex" href="#" onclick="confirmLogout(event)">
                                    <i class="feather feather-power mr-3 fs-16 my-auto"></i>
                                    <div class="mt-1">Sign Out</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!--/app header-->

        <!-- SweetAlert JS and CSS -->
        <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            function confirmLogout(event) {
                event.preventDefault(); // Prevent the default link behavior

                swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to sign out?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "Yes, sign out!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the logout route
                       document.getElementById('logout-form').submit();

                    } else {
                        // If the user clicked "Cancel", no action is taken
                        return false;
                    }
                });
            }
        </script>
