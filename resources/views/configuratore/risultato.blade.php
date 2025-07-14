@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    @if(isset($output['testo']))
        <h4 class="mb-3">{!! $output['testo'] !!}</h4>
        <img src="{{ $output['immagine'] ?? '' }}" class="img-fluid mb-3" alt="Auto consigliata" />
        <a href="{{ $output['link'] ?? '#' }}" target="_blank" class="btn btn-info">Vai al sito</a>
    @else
        <p>Nessuna risposta trovata. Riprova con dati diversi.</p>
    @endif
</div>
@endsection

