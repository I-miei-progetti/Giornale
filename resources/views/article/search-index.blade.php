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
               <div class="col-12 col-md-3 mb-4 d-flex justify-content-center">
                    <x-article-card :article="$article"/>
                </div>
            @endforeach
        </div>
    </div>




</x-layout>
