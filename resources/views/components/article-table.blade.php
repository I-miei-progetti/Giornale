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
                    {{-- @if (is_null($article->is_accepted))
                        <a href="{{route ('article.show', $article)}}" class="btn">Leggi l'articolo</a>
                    @else
                        <form action="{{route ('revisor.undoArticle', $article)}}" method="POST">
                            @csrf 
                            <button type="submit" class="btn"> Riporta in revisione</button>
                        </form>
                    @endif --}}
                    @if (is_null($article->is_accepted))
                        <a href="{{ route('article.show', $article) }}" class="btn btn-primary">Leggi l'articolo</a>
                    @elseif ($article->is_accepted === true)
                        <span class="badge bg-success">Accettato</span>
                        <form action="{{ route('revisor.undoArticle', $article) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">Riporta in revisione</button>
                        </form>
                    @else
                        <span class="badge bg-danger">Respinto</span>
                        <form action="{{ route('revisor.undoArticle', $article) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">Riporta in revisione</button>
                        </form>
                    @endif

                </td>
            </tr>
        @endforeach
    </tbody>
</table>
