<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('dashboard_route_for')) {
    function dashboard_route_for($user): string
    {
        return match ($user->usertype) {
            'admin'  => route('admin.dashboard', absolute: false),
            'doctor' => route('doctor.dashboard', absolute: false),
            default  => route('login', absolute: false), // or abort(403)
        };
    }
}
