<x-layout>

    <div class="container-fluid py-5 bg-secondary-subtitle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Bentornato, Redattore {{ Auth::user()->nam }}</h1>
            </div>
        </div>
    </div>

      @if (session('message'))
        
        <div id="success-message" class="alert alert-success" style="position: relative;background-color:rgba(255, 247, 0, 0.908);">
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

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli in attesa di revisione</h2>
                <x-writer-articles-table :articles="$unrevisionedArticles" />
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli pubblicati</h2>
                <x-writer-articles-table :articles="$acceptedArticles" />
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli respinti</h2>
                <x-writer-articles-table :articles="$rejectedArticles" />
            </div>
        </div>
    </div>
</x-layout>
