<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->take(10)->get();
        return view('clientarea.index', compact('notifications'));
    }
}