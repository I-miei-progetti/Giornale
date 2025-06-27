<x-layout>
    <div class="container-fluid p-5 bg-secondary-suble text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1 text-capitalize">
                    {{ $category->name }}
                </h1>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-evenly">
            @foreach ($articles as $article)
                <div class="col-12 col-md-3">
                    <div class="card" style="width:18rem;">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center mt-2"
                            style="height: 200px;">
                            <img src="{{ Storage::url($article->image) }}" class="h-100" style="object-fit: contain;"
                                alt="Immagine dell'articolo: {{ $article->title }}">
                        </div>

                        <div class="card-body">
                            <h5 class="ùcard-title">
                                {{ $article->title }}
                            </h5>
                            <p class="card-subtitle">{{ $article->subtitle }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-iteams-center">
                             @if($article->category)
                            <p class="small tetxt-muted">Categoria:
                                <a href="{{ route('article.byCategory', $article->category) }}"
                                    class="text-capitalize text-muted">{{ $article->category->name }}</a>
                            </p>
                            @else
                            <p class="small text-muted">Nessuna categoria</p>
                            @endif
                            <p class="small text-muted my-0">
                                @foreach ($article->tags as $tag)
                                    #{{ $tag->name }}
                                @endforeach
                            </p>
                            <p>
                                Redatto il {{ $article->created_at->format('d/m/Y') }} <br>
                                @if ($article->user)
                                    da <a href="{{ route('article.byUser', ['user' => $article->user->id]) }}"
                                        class="text-capitalize text-muted">{{ $article->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Autore sconosciuto</span>
                                @endif
                                {{-- da{{ $article->user->name }} --}}
                            </p>
                            <a href="{{ route('article.show', $article) }}" class="btn btn-outline-secondary">Leggi</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>





</x-layout>
