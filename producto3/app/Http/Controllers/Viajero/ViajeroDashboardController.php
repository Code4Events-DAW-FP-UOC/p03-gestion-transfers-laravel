<?php

namespace App\Http\Controllers\Viajero;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ViajeroDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->isViajero()) {
            abort(403);
        }

        return view('viajero.dashboard', ['user' => $user,]);
    }
}
