<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('admin')) {
    function admin() {
        return Auth::guard('Admin')->user();
    }
}

