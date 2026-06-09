<?php

namespace App\Http\Controllers;

use App\Models\Booking;



class DashboardController extends Controller
{
    public function create()
    {
//        $reserveringen = Booking::where('gegevens_id', auth()->id())
//            ->select('property_id', 'start_date', 'end_date')
//            ->get();
//
//        return view('dashboard', [
//            'reserveringen' => $reserveringen
//        ]);
//        $gegevens_klant = Booking::where('id', auth()->user()->id)->first()
//            ->select('voornaam', 'achternaam', 'telefoonnummer');
//        ->$this->get();
//

           return view('dashboard');
    }

//    private function get()
//    {
//
//
//    }
}
