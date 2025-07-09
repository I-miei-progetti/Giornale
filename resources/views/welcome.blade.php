<x-layout>
    {{-- Avvisi --}}
    @if (session('message'))
        <div id="success-message" class="alert alert-success"
            style="position: relative;background-color:rgba(255, 247, 0, 0.908);">
            {{ session('message') }}
            <button onclick="closeMessage()"
                style=" top:5px; right:10px; font-size:16px; cursor:pointer; background-color:red;"> &times; </button>
        </div>

        <script>
            function closeMessage() {
                var msg = document.getElementById('success-message');
                if (msg) {
                    msg.style.display = "none";
                }
            }
        </script>
    @endif

    @if (session('alert'))
        <div id="alert-message" class="alert alert-danger"
            style="position: relative;background-color:rgba(255, 247, 0, 0.908;">
            {{ session('alert') }}
            <button onclick="closeMessage()"
                style=" top:5px; right:10px; font-size:16px; cursor:pointer; background-color:red;"> &times; </button>
        </div>
        <script>
            function closeMessage() {
                var msg = document.getElementById('alert-message');
                if (msg) {
                    msg.style.display = "none";
                }
            }
        </script>
    @endif
{{-- fine avvisi --}}

{{-- carousel --}}
    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Colonna sinistra: logo + cards -->
            <div class="col-12 col-md-4 d-flex flex-column align-items-center">
                <!-- Logo -->
                <img src="/image/logo_scritto.png" alt="Logo" class="img-fluid mb-3" style="max-width: 350px;">

                <!-- Cards -->
                <div class="card mb-3 w-100" style="max-width: 18rem;">
                    <a href="https://www.alvolante.it/" target="_blank">
                        <img src="image/copertina.jpg" class="card-img-top" alt="copertina al volante">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">DA QUI LA VERA RIVISTA DI AlVolante</h5>
                        <p class="card-text">la mia è solo un interpretazione del sito volta all'esercitazione</p>
                        <a href="https://www.alvolante.it/" class="btn " target="_blank">Vai al vero sito</a>
                    </div>
                </div>

                <div class="card w-100" style="max-width: 18rem;">
                    <a href="https://www.insella.it/rivista/insella/2025/luglio" target="_blank">
                        <img src="/image/inSella.jpeg" class="card-img-top" alt="copertina in sella">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">IN SELLA</h5>
                        <p class="card-text">Se leggi alVolante ti piacerà anche inSella</p>
                        <a href="https://www.insella.it/rivista/insella/2025/luglio" class="btn " target="_blank">Vai
                            al sito</a>
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

{{-- fine carousel --}}


    <div class="container my-5">
        <div class="row justify-content-enenly">
            @foreach ($articles as $article)
                <div class="col-12 col-md-3 mb-4 d-flex justify-content-center">
                    <x-article-card :article="$article" />
                </div>
            @endforeach
        </div>
    </div>



</x-layout>
