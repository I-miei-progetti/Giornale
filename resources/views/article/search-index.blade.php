<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Tutti gli articoli per {{ $query }}</h1>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-evenly">
            @foreach ($articles as $article)
                <div class="col-12 col-md-3">
                    <div class="card" style="width:18rem">
                        <img src="{{ Storage::url($article->image) }}" class="card-img-top"
                            alt="Immagine dell'articolo:{{ $article->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-subtitle">{{ $article->subtitle }}</p>
                            <p class="small tetxt-muted">Categoria:
                                <a href="{{ route('article.byCategory', $article->category) }}"
                                    class="text-capitalize text-muted">{{ $article->category->name }}</a>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align items-center">
                            @if ($article->user)
                                <p>
                                    Redatto il {{ $article->created_at->format('d/m/Y') }}
                                    <br>da <a href="{{ route('article.byUser', ['user' => $article->user->id]) }}"
                                        class="text-muted">{{ $article->user->name }}</a>
                                </p>
                            @endif
                            <a href="{{ route('article.show', $article) }}" class="btn">Leggi</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>




</x-layout>
