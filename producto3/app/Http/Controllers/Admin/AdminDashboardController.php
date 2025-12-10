<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        return view('admin.dashboard', ['user' => $user,]);
    }
}
