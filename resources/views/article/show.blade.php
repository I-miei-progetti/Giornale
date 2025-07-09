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

                {{-- Sezione immagini con carousel --}}
                @php
    $allImages = collect();
    if ($article->image) {
        $allImages->push((object)['path' => $article->image]);
    }
    foreach ($article->images as $img) {
        $allImages->push($img);
    }
@endphp

@if ($allImages->count() > 1)
    <div id="articleCarousel-{{ $article->id }}" class="carousel slide h-100 w-100" data-bs-ride="carousel" data-bs-wrap="true" data-bs-interval="5000">
        <div class="carousel-inner h-100">
            @foreach ($allImages as $index => $image)
                <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ Storage::url($image->path) }}" class="d-block h-100 w-100"
                        style="object-fit: contain;"
                        alt="Immagine {{ $index + 1 }} dell'articolo: {{ $article->title }}">
                </div>
            @endforeach
        </div>
        {{-- prev/next + indicators... --}}
    </div>
@elseif ($allImages->count() === 1)
    <img src="{{ Storage::url($allImages->first()->path) }}" class="h-100 w-100"
         style="object-fit: contain;" alt="Immagine singola articolo: {{ $article->title }}">
@else
    <div class="text-muted">Nessuna immagine disponibile</div>
@endif


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
