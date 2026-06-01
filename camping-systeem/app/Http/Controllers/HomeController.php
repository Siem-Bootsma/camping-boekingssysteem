<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request): View
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
