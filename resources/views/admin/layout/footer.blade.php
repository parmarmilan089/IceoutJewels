</div>
</div>
    <!-- end app-content-->
    </div>

    <!--Footer-->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                    Copyright © {{ date('Y') }}. Designed by <a href="#">PizzaElectric</a> All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer-->

</div>

<!-- Back to top -->
<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

<!--Moment js-->
<script src="{{ asset('assets') }}/plugins/moment/moment.js"></script>

<!-- Bootstrap4 js-->
<script src="{{ asset('assets') }}/plugins/bootstrap/popper.min.js"></script>
<script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.min.js"></script>


<!--Sidemenu js-->
<script src="{{ asset('assets') }}/plugins/sidemenu/sidemenu.js"></script>

<!-- P-scroll js-->
<script src="{{ asset('assets') }}/plugins/p-scrollbar/p-scrollbar.js"></script>
<script src="{{ asset('assets') }}/plugins/p-scrollbar/p-scroll1.js"></script>

<!--Sidebar js-->
<script src="{{ asset('assets') }}/plugins/sidebar/sidebar.js"></script>

<!-- Select2 js -->
<script src="{{ asset('assets') }}/plugins/select2/select2.full.min.js"></script>

<!-- INTERNAL  Datepicker js -->
<script src="{{ asset('assets') }}/plugins/date-picker/jquery-ui.js"></script>

<!-- JQuery Validation -->
<script src="{{ asset('assets') }}/plugins/js-validation/jquery.validate.min.js"></script>

<!-- Custom js-->
<script src="{{ asset('assets') }}/js/custom.js"></script>
{{-- @livewireScripts --}}

@include('notification.iziToast')
@yield('script')
</body>
</html>
