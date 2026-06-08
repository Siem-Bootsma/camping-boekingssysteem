<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class DashboardController extends Controller
{
    public function create()
    {
//        $reserveringen = Booking::where('gegevens_id', auth()->id())->get();
//
//        return view('dashboard', [
//            'reserveringen' => $reserveringen
//
//        ]);
        return view('dashboard');
    }
}
