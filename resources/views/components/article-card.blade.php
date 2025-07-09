<div class="card" style="width: 120rem;">
    <div class="card-img-top bg-light d-flex align-items-center justify-content-center mt-1" style="height: 200px;">

        {{-- carousel --}}
        @php
            $allImages = collect();
            if ($article->image) {
                $allImages->push((object) ['path' => $article->image]);
            }
            foreach ($article->images as $img) {
                $allImages->push($img);
            }
        @endphp

        @if ($allImages->count() > 1)
            <div id="articleCarousel-{{ $article->id }}" class="carousel slide h-100 w-100" data-bs-ride="carousel"
                data-bs-wrap="true" data-bs-interval="3000">
                <div class="carousel-inner h-100">
                    @foreach ($allImages as $index => $image)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($image->path) }}" class="d-block h-100 w-100"
                                style="object-fit: contain;"
                                alt="Immagine {{ $index + 1 }} dell'articolo: {{ $article->title }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button"
                    data-bs-target="#articleCarousel-{{ $article->id }}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Precedente</span>
                </button>

                <button class="carousel-control-next" type="button"
                    data-bs-target="#articleCarousel-{{ $article->id }}" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Successivo</span>
                </button>
                <div class="carousel-indicators">
                    @foreach ($article->images as $index => $image)
                        <button type="button" data-bs-target="#articleCarousel-{{ $article->id }}"
                            data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                            aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        @elseif ($allImages->count() === 1)
            <img src="{{ Storage::url($allImages->first()->path) }}" class="h-100 w-100" style="object-fit: contain;"
                alt="Immagine singola articolo: {{ $article->title }}">
        @else
            <div class="text-muted">Nessuna immagine disponibile</div>
        @endif

    </div>
    {{-- fine carousel --}}
    <div class="card-body">
        <h5 class="card-title">{{ $article->title }}</h5>
        <p class="card-text">{{ $article->subtitle }}</p>
        <p class="small text-muted">Categoria:
            <a href="{{ route('article.byCategory', $article->category) }}"
                class="text-capitalize text-muted">{{ $article->category->name }}</a>
        </p>
        <p class="small text-muted my-0">
            @foreach ($article->tags as $tag)
                #{{ $tag->name }}
            @endforeach
        </p>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <p>Redatto il {{ $article->created_at->format('d/m/Y') }} <br>
            @if ($article->user)
                da <a href="{{ route('article.byUser', ['user' => $article->user->id]) }}"
                    class="text-capitalize text-muted">{{ $article->user->name }}
                </a>
            @else
                <span class="text-muted">Autore sconosciuto</span>
            @endif
            <br>
            <a href="{{ route('article.show', $article) }}" class="btn">Leggi</a>
        </p>
    </div>
</div>



