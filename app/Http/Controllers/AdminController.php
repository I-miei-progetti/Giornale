<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        $adminRequests = User::where ('is_admin', NULL)->get();
        $revisorRequests = User ::where ('is_revisor', NULL)->get();
        $writeRequests =User ::where ('is_write', NULL)->get();
        return view('admin.dashboard', compact('adminRequests', 'revisorRequests','writeRequests'));

    }
}
