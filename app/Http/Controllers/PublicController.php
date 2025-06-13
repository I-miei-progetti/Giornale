<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PublicController extends Controller implements HasMiddleware
{
    public function homepage()
    {
        $articles = Article::orderBY('created_at', 'desc')->take(4)->get();
        return view('welcome', compact('articles'));
    }

    public function careers()
    {
        return view('careers');
    }

    public static function middleware()
    {
        return [
            new Middleware('auth', except: ['homapege']),
        ];
    }

    public function carerSubmit(Request $request)
    {
        $request->validate([
            'role'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);
    }

}
