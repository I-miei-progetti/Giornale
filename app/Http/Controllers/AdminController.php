<?php
namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $adminRequests   = User::where('is_admin', null)->get();
        $revisorRequests = User::where('is_revisor', null)->get();
        $writeRequests   = User::where('is_writer', null)->get();
        return view('admin.dashboard', compact('adminRequests', 'revisorRequests', 'writeRequests'));

    }

    public function setAdmin(User $user)
    {
        $user->is_admin = true;
        $user->save();
        return redirect(route('admin.dashboard'))->with('message', "Hai reso $user->name amministratore ");
    }

    public function setRevisor(User $user)
    {
        $user->is_revisor = true;
        $user->save();
        return redirect(route('admin.dashboard'))->with('message', "Hai reso $user->name revisore");
    }

    public function setWriter(User $user)
    {
        $user->is_writer = true;
        $user->save();
        return redirect(ruote('admin.dashboard'))->with('message', "Hai reso $user->name redattore");
    }
}
