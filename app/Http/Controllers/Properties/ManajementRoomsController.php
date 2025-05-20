<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class ManajementRoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('created_at', 'desc')->paginate(5);
        return view('pages.Properties.m-Rooms.index', compact('rooms'));
    }
}
