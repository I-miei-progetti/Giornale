<x-layout>
    @if (session('message'))
        <div id="success-message" class="alert alert-success" style="position: relative;background-color:rgb(221, 255, 0);">
            {{ session('message') }}
            <button onclick="closeMessage()" style=" top:5px; right:10px; font-size:16px; cursor:pointer; background-color:red;"> &times; </button>
        </div>

         <script>
        function closeMessage(){
            var msg=document.getElementById('success-message');
             if(msg){
                msg.style.display= "none";
             }
        }
    </script> 
    @endif
    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Colonna sinistra: logo + cards -->
            <div class="col-12 col-md-4 d-flex flex-column align-items-center">
                <!-- Logo -->
                <img src="/image/logo_scritto.png" alt="Logo" class="img-fluid mb-3" style="max-width: 180px;">

                <!-- Cards -->
                <div class="card mb-3 w-100" style="max-width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text...</p>
                        <a href="#" class="btn ">Go somewhere</a>
                    </div>
                </div>

                <div class="card w-100" style="max-width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text...</p>
                        <a href="#" class="btn ">Go somewhere</a>
                    </div>
                </div>
            </div>

            <!-- Colonna destra: Carousel -->
            <div class="col-12 col-md-8">
                <div class="w-100" style="aspect-ratio: 16 / 9;">
                    <x-carousel />
                </div>
            </div>
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
                <div class="col-12 col-md-3 mb-4 d-flex justify-content-center">
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
                                <a href="{{ route('article.show', $article) }}" class="btn ">Leggi</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

   

</x-layout>
