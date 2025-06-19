<x-layout>
    <div class="container-fluid-p-5-bg-secondary-subtle text-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="display-1">Lavora con noi </h1>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-6">
                <form action="{{ route('careers.submit') }}" method="POST" class="card p-5 shadow">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Per quale ruolo ti stai candidando?</label>
                        <select name="role" id="role" class="form-control">
                            <option value="" selected disabled>Seleziona un ruolo</option>
                            @if (!Auth::user()->is_admin)
                                <option value="admin">Amministratore</option>
                            @endif
                            @if (!Auth::user()->is_revisor)
                                <option value="revisor">Revisore</option>
                            @endif
                            @if (!Auth::user()->is_writer)
                                <option value="writer">Redattore</option>
                            @endif
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="mail" name="email"
                            value="{{ Auth::user()->email }}"readonly>
                        {{-- @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Perchè vuoi candidarti a questo ruolo?
                            Raccontacelo</label>
                        <textarea name="message" id="message" cols="30" rows="7" class="form-control">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-denger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-secondary">Invia candidatura</button>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-6 p-5">
                <h2>
                    Lavora come amministratore
                </h2>
                <p>Scegliendo di lavorare come amministratore, ti occuperai di gestire le richieste di lavoro e
                    aggiungere e modificare le categorie.</p>
                <h2>Lavora come revisore</h2>
                <p>Scegliendo di lavorare come revisore, deciderai se un articolo può essere pubblicato o meno sul
                    giornale</p>
                <h2>Lavora come redattore</h2>
                <p>Scegliendo di lavorare come redattore, potrai scrivere gli articoli che saranno pubblicati</p>

            </div>
        </div>
    </div>
</x-layout>
