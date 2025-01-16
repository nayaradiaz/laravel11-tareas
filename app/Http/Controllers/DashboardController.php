<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user=Auth::user()->name;
        $tasks =Auth::user()->tasks;
        return view('dashboard',compact('user','tasks'));
    }
    
}
