<?php
namespace App\Http\Controllers;

use App\Models\Article;

class RevisorController extends Controller
{
    public function dashboard()
    {
        $unrevisionedArticles = Article::where('is_accepted', NULL)->get();
        $acceptedArticles = Article::where('is_accepted', true)->get();
        $rejectedArticles = Article::where('is_accepted', false)->get();

        return view('revisor.dashboard', compact('unrevisionedArticles', 'acceptedArticles', 'rejectedArticles'));
    }

    public function acceptArticle(Article $article)
    {
        $article->is_accepted = true;
        $article->save();
        return redirect(route('revisor.dashboard'))->with('message', 'Articolo publicato');
    }

    public function rejectArticle(article $article)
    {
        $article->is_accepted = false;
        $article->save();
        return redirect(route('revisor.dashboard'))->with('message', 'Articolo rifiutato');

    }

    public function undoArticle(Article $article)
    {
        $article->is_accepted = null; //false;
        $article->save();
        return redirect(route('revisor.dashboard'))->with('message', 'Articolo rimandato in revisione');

    }

}
