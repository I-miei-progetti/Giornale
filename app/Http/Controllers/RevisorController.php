<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class RevisorController extends Controller
{
    public function dashboard(){
        $unrevisionedArticles = Article ::where('is_accepted',NULL)->get();
        $acceptedArticles = Article::where('is_accepted',true)->get();
        $rejectedArticles =Articles::where('is_accepted',false)->get();

        return view('revisor.dashboard', compact('unrevisionadArticles','acceptedArticles','rejectedArticles'));
    }
}
