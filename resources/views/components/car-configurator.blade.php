{{-- Configuratore Auto --}}
<div class="card shadow-sm mt-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">🚗 Usa il nostro configuratore AI 🤖 </h5>
    </div>
    <div class="card-body">
        <form id="carConfiguratorForm">
            @csrf
            <div class="mb-3">
                <label for="carDescription" class="form-label">Descrivi la tua auto ideale:</label>
                <textarea 
                    class="form-control" 
                    id="carDescription" 
                    name="description" 
                    rows="4" 
                    placeholder="Esempio: Voglio una macchina sportiva tedesca, veloce, di colore rosso, budget 50.000 euro..."
                    required></textarea>
                <div class="form-text">Descrivi caratteristiche, marca preferita, budget, utilizzo, etc.</div>
            </div>
            
            <button type="submit" class="btn " id="analyzeBtn">
                <span id="btnText">Analizza e Trova Auto</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
            </button>
        </form>

        <!-- Risultati -->
        <div id="results" class="mt-4 d-none">
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Auto Consigliata</h6>
                            <p class="mb-1"><strong>Marca:</strong> <span id="resultBrand"></span></p>
                            <p class="mb-1"><strong>Modello:</strong> <span id="resultModel"></span></p>
                            <p class="mb-1"><strong>Categoria:</strong> <span id="resultCategory"></span></p>
                            <p class="mb-1"><strong>Fascia Prezzo:</strong> <span id="resultPrice"></span></p>
                            <p class="mb-3"><span id="resultDescription"></span></p>
                            
                            <a href="#" id="officialSiteLink" class="btn btn-success" target="_blank">
                                Visita Sito Ufficiale
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="card-title">Anteprima</h6>
                            <img 
                                id="carImage" 
                                src="" 
                                alt="Auto consigliata" 
                                class="img-fluid rounded"
                                style="max-height: 200px; object-fit: cover;"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messaggio di errore -->
        <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>
    </div>
</div>

<script>
document.getElementById('carConfiguratorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const description = document.getElementById('carDescription').value;
    const analyzeBtn = document.getElementById('analyzeBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const results = document.getElementById('results');
    const errorMessage = document.getElementById('errorMessage');
    
    // Mostra loading
    btnText.textContent = 'Analizzando...';
    btnSpinner.classList.remove('d-none');
    analyzeBtn.disabled = true;
    results.classList.add('d-none');
    errorMessage.classList.add('d-none');
    
    // Chiamata AJAX
    fetch('{{ route("car.analyze") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Popola i risultati
        document.getElementById('resultBrand').textContent = data.brand;
        document.getElementById('resultModel').textContent = data.model;
        document.getElementById('resultCategory').textContent = data.category;
        document.getElementById('resultPrice').textContent = data.price_range;
        document.getElementById('resultDescription').textContent = data.description;
        document.getElementById('officialSiteLink').href = data.official_site;
        document.getElementById('carImage').src = data.image_url;
        
        // Mostra risultati
        results.classList.remove('d-none');
        
        // Scroll ai risultati
        results.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = 'Errore durante l\'analisi: ' + error.message;
        errorMessage.classList.remove('d-none');
    })
    .finally(() => {
        // Ripristina il pulsante
        btnText.textContent = 'Analizza e Trova Auto';
        btnSpinner.classList.add('d-none');
        analyzeBtn.disabled = false;
    });
});
</script>