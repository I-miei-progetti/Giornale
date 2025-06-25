<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Titolo</th>
            <th scope="col">Sottotitolo</th>
            <th scope="col">Redattore</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($articles as $article)
            <tr>
                <th scope="row">{{ $article->id }}</th>
                <td>{{ $article->title }}</td>
                <td>{{ $article->subtitle }}</td>
                <td>{{ $article->user?->name ?? 'Nessun autore' }}</td>
                <td>
    @if (is_null($article->is_accepted))
        <a href="{{ route('article.show', $article) }}" class="btn btn-danger text-white">Leggi l'articolo</a>
        <span class="badge bg-warning">Da revisionare</span>
    @elseif ($article->is_accepted === 1)
        <form action="{{ route('revisor.undoArticle', $article) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">Riporta in revisione</button>
        </form>
        <span class="badge bg-success ms-2">Pubblicato</span>
    @else
        <form action="{{ route('revisor.undoArticle', $article) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">Riporta in revisione</button>
        </form>
        <span class="badge bg-danger ms-2">Respinto</span>
    @endif
</td>

            </tr>
        @endforeach
    </tbody>
</table>
