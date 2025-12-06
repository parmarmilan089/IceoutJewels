<link rel="stylesheet" href="{{ asset('assets/plugins/izitoast/iziToast.css') }}" />
<script src="{{ asset('assets/plugins/izitoast/iziToast.min.js') }}"></script>

@if (session()->get('success'))
    <script>
        $(document).ready(function () {
            iziToast.success({
                title: 'Success !',
                message: '{{ session()->get('success') }}',
                timeout: 5000,
                iconColor: 'green',
                position: 'topRight',
                balloon: false,
                animateInside: true,
                transitionIn: 'fadeInLeft',
                transitionOut: 'fadeOutRight',
                close: true,
            });
        });
    </script>
@endif

@if (session()->get('info'))
    <script>
        $(document).ready(function () {
            iziToast.info({
                title: 'Info !',
                message: '{{ session()->get('info') }}',
                timeout: 7000,
                iconColor: 'blue',
                position: 'topRight',
                balloon: false,
                animateInside: true,
                transitionIn: 'fadeInLeft',
                transitionOut: 'fadeOutRight',
                close: true,
            });
        });
    </script>
@endif

@if (session()->get('error'))
    <script>
        $(document).ready(function () {
            iziToast.error({
                title: 'Error !',
                message: '{{ session()->get('error') }}',
                timeout: 5000,
                iconColor: 'red',
                position: 'topRight',
                balloon: false,
                animateInside: true,
                transitionIn: 'fadeInLeft',
                transitionOut: 'fadeOutRight',
                close: true,
            });
        });
    </script>
@endif
