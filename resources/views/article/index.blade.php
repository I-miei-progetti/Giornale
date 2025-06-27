<x-layout>

    <div class="container-fluid p-5 text-center title">
        <div class="row justify-content-cente ">
            <div class="col-12">
                <h1 class="display-1">Tutti gli articoli</h1>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-envely">
            @foreach ($articles as $article)
                <div class="col-12 col-md 3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center mt-1"
                            style="height: 200px;">
                            <img src="{{ Storage::url($article->image) }}" class="h-100" style="object-fit: contain;"
                                alt="Immagine dell'articolo: {{ $article->title }}">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ $article->subtitle }}</p>
                            @if ($article->category)
                            <p class="small text-muted"> Categoria:
                                <a href="{{ route('article.byCategory', $article->category) }}"
                                    class="text-capitalize text-muted">{{ $article->category->name }}
                                </a>
                            </p>
                            @else
                            <p class="small text-muted">Nessuna categoria</p>
                            @endif
                            <p class="small text-muted my-0">
                                @foreach ($article->tags as $tag)
                                    #{{ $tag->name }}
                                @endforeach
                            </p>

                        </div>
                        <div class="card-footer d-flex justify-content-between align-iteam-center">
                            <p>Redatto il {{ $article->created_at->format('d/m/Y') }} <br>
                                @if ($article->user)
                                    da <a href="{{ route('article.byUser', ['user' => $article->user->id]) }}"
                                        class="text-capitalize text-muted">{{ $article->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Autore sconosciuto</span>
                                @endif
                            </p>
                            <a href="{{ route('article.show', $article) }}" class="btn btn-outline-secondary">Leggi</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


</x-layout>
