<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-cente ">
            <div class="col-12">
                <h1 class="display-1">Al Volante</h1>
            </div>
        </div>
    </div>
    @if (session('message'))
        <div class="aler alert-success">
            {{ session('message') }}
        </div>
    @endif

</x-layout>
