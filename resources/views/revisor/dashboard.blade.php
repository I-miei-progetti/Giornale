<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Bentornato Revisore {{ Auth::user()->name }}</h1>
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
                <h2>Articoli da Revisiore</h2>
                <x-article-table :articles="$unrevisionedArticles"/>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-cemter">
            <div class="col-12">
                <h2>Articoli pubblicati</h2>
                <x-article-table :articles="$acceptedArticles"/>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2>Articoli respinti</h2>
                <x-article-table :articles="$rejectedArticles" />
            </div>
        </div>
    </div>
</x-layout>
