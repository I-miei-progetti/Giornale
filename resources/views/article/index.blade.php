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
                <div class="col-12 col-md-3 mb-4 d-flex justify-content-center">
                    <x-article-card :article="$article"/>
                </div>
            @endforeach
        </div>
    </div>


</x-layout>
