<?php

namespace App\Http\Controllers\Bookings\CheckOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        return view('pages.bookings.checkout.index');
    }
}
