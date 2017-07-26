<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AldoController extends Controller
{
    public function index()
    {
        $artisan = \Artisan::call('crossdocking:run');
        dd($artisan);
    }
}
