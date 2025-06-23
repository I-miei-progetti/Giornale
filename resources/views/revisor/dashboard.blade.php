<x-layaout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Bentornato Revisore{{ Auth::user()->name }}</h1>
            </div>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli da Revisore</h2>
                <x-article-table :articles="$unrevisionedArticles" />
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-cemter">
            <div class="col-12">
                <h2>Articoli pubblicati</h2>
                <x-articles-table :articles="$acceptedArticles" />
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli respinti</h2>
                <x-articles-table :articles="$rejectedArticles" />
            </div>
        </div>
    </div>
</x-layaout>
