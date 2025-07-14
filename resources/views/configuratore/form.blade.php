@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Configuratore Auto</h2>
    <form action="/configura-auto" method="POST">
        @csrf

        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" required class="form-control">

        <label for="tipologia">Tipologia:</label>
        <input type="text" name="tipologia" id="tipologia" required class="form-control">

        <label for="carburante">Carburante:</label>
        <input type="text" name="carburante" id="carburante" required class="form-control">

        <label for="budget">Budget (€):</label>
        <input type="number" name="budget" id="budget" required class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Trova la tua auto</button>
    </form>
</div>
@endsection
