<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controllers
{
        public function create(Request $request): View
        {
            return view('home');
        }
}
