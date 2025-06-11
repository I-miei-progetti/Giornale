<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
public function homepage(){
   $articles = Article::orderBY('created_at','desc')->take (4)->get();
   return view('welcome', compact('articles'));
}
}
