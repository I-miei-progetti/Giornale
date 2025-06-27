<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">{{ $article->title }}</h1>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 d-flex flex-column">
                <img src="{{ Storage::url($article->image) }}" class="img-fluid mb-3"
                    alt="Immagine dell'articolo {{ $article->title }}">

                <div class="text-center mb-4">
                    <h2>{{ $article->subtitle }}</h2>
                    @if ($article->category)
                        <p class="fs-5">
                            Categoria:
                            <a href="{{ route('article.byCategory', $article->category) }}"
                                class="text-capitalize text-muted">
                                {{ $article->category->name }}
                            </a>
                        </p>
                    @else
                        <p class="fs-5">Nessuna categoria</p>
                    @endif
                    <p class="text-muted">
                        Redatto il {{ $article->created_at->format('d/m/Y') }}
                        {{-- da:{{ $article->user->name }} --}}
                    </p>
                </div>

                <hr>
                <p>{{ $article->body }}</p>

                <div class="mt-5">
                    <div class="text-center">
                        <a href="{{ route('article.index') }}" class="text-secondary">← Torna alla lista degli
                            articoli</a>
                    </div>
                </div>

                @if (Auth::check() && Auth::user()->is_revisor)
                    <div class="mt-5 pt-4 border-top">
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                            <form action="{{ route('revisor.acceptArticle', $article) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">✅ Accetta l'articolo</button>
                            </form>

                            <form action="{{ route('revisor.rejectArticle', $article) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">❌ Rifiuta l'articolo</button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>
