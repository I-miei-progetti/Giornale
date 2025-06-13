<x-layout>
    <div class="d-flex align-items-start gap-4 p-4 flex-wrap" style="width: 100%;">
        <!-- Colonna sinistra: logo + immagini -->
        <div class="d-flex flex-column align-items-center" style="min-width: 200px;">
            <!-- Logo -->
            <img src="/image/logo_scritto.png" alt="Logo" class="img-fluid mb-3" style="max-width: 180px;">

            <!-- Immagini sotto il logo -->
            <div class="d-flex flex-column gap-2 align-items-center">
                <img src="/image/img1.jpg" alt="img1" class="mini-img">
                <img src="/image/img2.jpg" alt="img2" class="mini-img">
                <img src="/image/img3.jpg" alt="img3" class="mini-img">
            </div>

        </div>
        <div class="col-md-6">
            <x-carousel />
        </div>
    </div>


    @if (session('message'))
        <div class="aler alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="container my-5">
        <div class="row justify-content-envely">
            @foreach ($articles as $article)
                <div class="col-12 col-md 3">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ Storage::url($article->image) }}" class="card-img-top"
                            alt="Immagine dell'articolo:{{ $article->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ $article->subtitle }}</p>
                            <p class="small text-muted"> Categoria:
                                <a href="{{ route('article.byCategory', $article->category) }}"
                                    class="text-capitalize text-muted">{{ $article->category->name }}</a>
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
                                <a href="{{ route('article.show', $article) }}"
                                    class="btn btn-outline-secondary">Leggi</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


</x-layout>
