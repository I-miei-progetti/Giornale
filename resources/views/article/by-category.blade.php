<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1 text-capitalize">{{ $category->name }}</h1>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-evenly g-4">
            @foreach ($articles as $article)
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <img src="{{ Storage::url($article->image) }}" class="h-100" style="object-fit: contain;" alt="Immagine di {{ $article->title }}">
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-subtitle">{{ $article->subtitle }}</p>
                        </div>

                        <div class="card-footer small text-muted">
                            @if($article->category)
                                <p class="mb-1">Categoria:
                                    <a href="{{ route('article.byCategory', $article->category) }}" class="text-capitalize text-muted">
                                        {{ $article->category->name }}
                                    </a>
                                </p>
                            @else
                                <p class="mb-1">Nessuna categoria</p>
                            @endif

                            <p class="mb-1">
                                @foreach ($article->tags as $tag)
                                    #{{ $tag->name }}
                                @endforeach
                            </p>

                            <p class="mb-1">
                                Redatto il {{ $article->created_at->format('d/m/Y') }}<br>
                                @if ($article->user)
                                    da <a href="{{ route('article.byUser', ['user' => $article->user->id]) }}" class="text-capitalize text-muted">
                                        {{ $article->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Autore sconosciuto</span>
                                @endif
                            </p>

                            <div class="text-end mt-2">
                                <a href="{{ route('article.show', $article) }}" class="btn btn-sm btn-outline-secondary">Leggi</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
