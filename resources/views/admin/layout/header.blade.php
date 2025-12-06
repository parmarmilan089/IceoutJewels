<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <!-- Meta data -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta name="robots" content="noindex, nofollow">
        <!-- Title -->
        <title>{{ config('app.name') }} Admin · @yield('title')</title>

        <!--Favicon -->
        {{-- <link rel="icon" href="{{ asset('assets') }}/images/brand/favicon.ico" type="image/x-icon" /> --}}
        {{-- <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo/small-logo.png') }}"> --}}

        {{-- Laravel CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap css -->
        <link href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />

        <!-- Style css -->
        <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/custom.css" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/skin-modes.css" rel="stylesheet" />

        <!-- Animate css -->
        <link href="{{ asset('assets') }}/plugins/animated/animated.css" rel="stylesheet" />

        <!--Sidemenu css -->
        <link href="{{ asset('assets') }}/css/sidemenu.css" rel="stylesheet">

         <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>

        <!-- P-scroll bar css-->
        <link href="{{ asset('assets') }}/plugins/p-scrollbar/p-scrollbar.css" rel="stylesheet" />

        <!---Icons css-->
        <link href="{{ asset('assets') }}/plugins/icons/icons.css" rel="stylesheet" />

        <!---Sidebar css-->
        <link href="{{ asset('assets') }}/plugins/sidebar/sidebar.css" rel="stylesheet" />

        <!-- Select2 css -->
        <link href="{{ asset('assets') }}/plugins/select2/select2.min.css" rel="stylesheet" />

        <!-- INTERNAL Data table css -->
        <link href="{{ asset('assets') }}/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" />

        <!-- INTERNAL Time picker css -->
        <link href="{{ asset('assets') }}/plugins/time-picker/jquery.timepicker.css" rel="stylesheet" />

        @yield('style')

        <!-- Jquery js-->
        <script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>

    </head>

    <body class="app sidebar-mini" id="index1">

        <!---Global-loader-->
		<div id="global-loader" >
			<img src="{{ asset('assets') }}/images/svgs/loader.gif" alt="loader">
		</div>

        <div class="page">
			<div class="page-main">
