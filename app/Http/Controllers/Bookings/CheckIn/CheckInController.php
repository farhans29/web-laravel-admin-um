<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        return view('pages.bookings.checkin.index');
    }

   
}
