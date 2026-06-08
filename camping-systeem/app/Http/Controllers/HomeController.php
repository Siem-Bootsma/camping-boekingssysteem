<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $languageButtons = [
            'de' => 'Deutsch',
            'nl' => 'Nederlands',
            'en' => 'English',
        ];

        return view('bookings.home_content', [
            'languageButtons' => $languageButtons,
        ]);
    }
}
