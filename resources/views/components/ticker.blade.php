<div class="news-ticker">
    <div class="ticker-content">
        @foreach ($news as $n)
            🚗 {{ $n['title'] }} •
        @endforeach
    </div>
</div>
