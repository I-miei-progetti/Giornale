<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public static function fetchNews()
    {
        $apiKey = env('NEWS_API_KEY');
        $response = Http::get('https://newsapi.org/v2/everything', [
            'q' => 'automobile',
            'sortBy' => 'publishedAt',
            'language' => 'it',
            'apiKey' => $apiKey
        ]);

        return $response->json()['articles'] ?? [];
    }
}
