<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        $languageButtons = [
            'de' => 'Deutsch',
            'nl' => 'Nederlands',
            'en' => 'English',
        ];

        return view('bookings.login', [
            'languageButtons' => $languageButtons,
        ]);
    }
}
