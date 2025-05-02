<x-layout>
    <div class="container-fluid p-5 bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Registrati</h1>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <form action="{{ route('register') }}" method="POST" class="card p-5 shadow">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome utente</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <span class="text-dangex">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <span class="text-dangex">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            value="{{ old('password') }}">
                        @error('password')
                            <span class="text-dangex">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Conferma Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            value="{{ old('password') }}">       
                    </div>
                    <div class="mb-3 d-flex justify-content-center flex-column align-iteam-center">
                        <button type="submit" class="btn btn-outline-secondary">Registrati</button>
                        <p class="mt-2">Sei registrato? <a href="{{route('login')}}" class="tect-secondary">Clicca qui</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layout>
