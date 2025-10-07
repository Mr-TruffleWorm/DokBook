<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = Auth::user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            default => abort(403, 'Unauthorized access.')
        };
    }
}