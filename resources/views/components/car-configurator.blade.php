<div class="card shadow-sm mt-4">
    <div class="card-header bg-black text-white">
        <h5 class="mb-0">🚗 Trova la tua auto perfetta grazie all'inteligenza artificiale</h5>
    </div>
    <div class="card-body">
        <form id="carConfiguratorForm">
            @csrf
            <div class="mb-3">
                <label for="carDescription" class="form-label fw-bold">Descrivi la tua auto ideale:</label>
                <textarea class="form-control" id="carDescription" name="description" rows="3"
                    placeholder="Es: BMW Serie 3 sportiva, budget 45k" required></textarea>
                <div class="form-text">Includi: marca, tipo, budget</div>
            </div>

            <button type="submit" class="btn btn-lg" id="analyzeBtn">
                <span id="btnText">🔍 Trova la Mia Auto</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
            </button>
        </form>

        <!-- Risultati con conversazione -->
        <div id="results" class="mt-4 d-none">
            <hr>

            <!-- Conversazione AI -->
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">🤖 Analisi AI Personalizzata</h6>
                </div>
                <div class="card-body">
                    <div id="aiConversation" class="ai-conversation"></div>
                </div>
            </div>

            <!-- Risultato principale -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">🎯 La Tua Auto Consigliata</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>🏷️ Marca:</strong> <span id="resultBrand"
                                            class="badge bg-primary fs-6"></span></p>
                                    <p class="mb-2"><strong>🚗 Modello:</strong> <span id="resultModel"
                                            class="text-primary fw-bold"></span></p>
                                    <p class="mb-2"><strong>📋 Categoria:</strong> <span id="resultCategory"
                                            class="badge bg-secondary"></span></p>
                                    <p class="mb-2"><strong>💰 Prezzo:</strong> <span id="resultPrice"
                                            class="text-success fw-bold"></span></p>
                                    <p class="mb-3"><strong>🤖 Confidenza AI:</strong> <span id="resultConfidence"
                                            class="badge bg-info"></span></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="#" id="officialSiteLink" class="btn btn-success btn-lg mb-2"
                                        target="_blank">
                                        🌐 Sito Ufficiale
                                    </a>
                                    <br>
                                    <small class="text-muted">Scopri tutte le versioni disponibili</small>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <p class="mb-0" id="resultDescription"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Modelli Alternativi -->
                    <div class="card mt-3 border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">🔄 Altri Modelli Disponibili</h6>
                        </div>
                        <div class="card-body">
                            <div id="alternativeModels" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Immagine dell'auto -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">📸 Anteprima</h6>
                        </div>
                        <div class="card-body text-center p-2">
                            <img id="carImage" src="" alt="Auto consigliata" class="img-fluid rounded shadow"
                                style="max-height: 250px; object-fit: cover; width: 100%; opacity: 0; transition: opacity 0.5s;">
                            <div id="imageLoader" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Caricamento immagine...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">📊 Statistiche Ricerca</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Modelli analizzati:</strong> <span id="totalModels" class="badge bg-primary"></span></p>
                            <p class="mb-0"><strong>Database aggiornato:</strong> <span class="text-success">✅ Online</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Richiesta dettagli -->
        <div id="askDetails" class="alert alert-warning mt-3 d-none">
            <h6>💭 Ho bisogno di più informazioni:</h6>
            <div id="detailsMessage"></div>
            <div class="mt-3">
                <strong>Prova questi esempi:</strong>
                <div id="suggestions" class="mt-2"></div>
            </div>
        </div>

        <!-- Messaggio di errore -->
        <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>
    </div>
</div>



<script>
    document.getElementById('carConfiguratorForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const description = document.getElementById('carDescription').value;
        const analyzeBtn = document.getElementById('analyzeBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const results = document.getElementById('results');
        const askDetails = document.getElementById('askDetails');
        const errorMessage = document.getElementById('errorMessage');

        // Reset UI
        btnText.textContent = '🔄 Analizzando...';
        btnSpinner.classList.remove('d-none');
        analyzeBtn.disabled = true;
        results.classList.add('d-none');
        askDetails.classList.add('d-none');
        errorMessage.classList.add('d-none');

        // Chiamata AJAX
        fetch('{{ route('car.analyze') }}', {
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
                if (data.ask_details) {
                    // Mostra richiesta dettagli
                    document.getElementById('detailsMessage').innerHTML = data.message.replace(/\n/g, '<br>');

                    // Aggiungi suggestions cliccabili
                    const suggestionsDiv = document.getElementById('suggestions');
                    suggestionsDiv.innerHTML = '';
                    data.suggestions.forEach(suggestion => {
                        const btn = document.createElement('button');
                        btn.className = 'btn btn-outline-primary btn-sm suggestion-btn';
                        btn.textContent = suggestion;
                        btn.onclick = () => {
                            document.getElementById('carDescription').value = suggestion;
                            document.getElementById('carConfiguratorForm').dispatchEvent(new Event('submit'));
                        };
                        suggestionsDiv.appendChild(btn);
                    });

                    askDetails.classList.remove('d-none');
                    return;
                }

                if (data.error) {
                    throw new Error(data.error);
                }

                // Mostra conversazione AI
                const conversation = data.conversation;
                const conversationDiv = document.getElementById('aiConversation');
                conversationDiv.innerHTML = conversation.map(line => {
                    if (line.trim() === '') return '<br>';
                    if (line.startsWith('**') && line.endsWith('**')) {
                        return `<p class="fw-bold text-primary mb-2">${line.replace(/\*\*/g, '')}</p>`;
                    }
                    if (line.startsWith('•')) {
                        return `<p class="mb-1 ms-3">${line}</p>`;
                    }
                    return `<p class="mb-2">${line}</p>`;
                }).join('');

                // Popola risultati
                document.getElementById('resultBrand').textContent = data.brand;
                document.getElementById('resultModel').textContent = data.model;
                document.getElementById('resultCategory').textContent = data.category;
                document.getElementById('resultPrice').textContent = data.price_range;
                document.getElementById('resultDescription').textContent = data.description;
                document.getElementById('officialSiteLink').href = data.official_site;
                document.getElementById('totalModels').textContent = data.total_models_found || '0';
                
                // Confidenza AI
                const confidence = Math.round((data.ai_confidence || 0.7) * 100);
                document.getElementById('resultConfidence').textContent = `${confidence}%`;

                // Modelli alternativi
                const alternativeModels = document.getElementById('alternativeModels');
                alternativeModels.innerHTML = '';
                if (data.available_models && data.available_models.length > 1) {
                    data.available_models.forEach(model => {
                        if (model !== data.model) {
                            const btn = document.createElement('button');
                            btn.className = 'btn btn-outline-secondary btn-sm alternative-model-btn';
                            btn.textContent = `${data.brand} ${model}`;
                            btn.onclick = () => {
                                document.getElementById('carDescription').value = `${data.brand} ${model}`;
                                document.getElementById('carConfiguratorForm').dispatchEvent(new Event('submit'));
                            };
                            alternativeModels.appendChild(btn);
                        }
                    });
                }

                // Carica immagine con fallback
                loadCarImageWithFallback(data.image_url, data.brand, data.model);

                // Mostra risultati con animazione
                results.classList.remove('d-none');
                results.classList.add('fade-in');
                results.scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.innerHTML = `<strong>❌ Errore:</strong> ${error.message}`;
                errorMessage.classList.remove('d-none');
            })
            .finally(() => {
                // Ripristina pulsante
                btnText.textContent = '🔍 Trova la Mia Auto';
                btnSpinner.classList.add('d-none');
                analyzeBtn.disabled = false;
            });
    });

    // Funzione per caricare immagine con fallback
    function loadCarImageWithFallback(imageUrl, brand, model) {
        const carImage = document.getElementById('carImage');
        const imageLoader = document.getElementById('imageLoader');
        
        imageLoader.style.display = 'block';
        carImage.style.opacity = '0';
        
        const img = new Image();
        img.onload = function() {
            carImage.src = imageUrl;
            carImage.style.opacity = '1';
            imageLoader.style.display = 'none';
        };
        
        img.onerror = function() {
            // Fallback 1: Unsplash generico
            const fallback1 = `https://source.unsplash.com/1200x800/?car,automotive&${Date.now()}`;
            
            const img2 = new Image();
            img2.onload = function() {
                carImage.src = fallback1;
                carImage.style.opacity = '1';
                imageLoader.style.display = 'none';
            };
            
            img2.onerror = function() {
                // Fallback 2: Placeholder con testo
                const fallback2 = `https://via.placeholder.com/1200x800/3498db/ffffff?text=${encodeURIComponent(brand + ' ' + model)}`;
                carImage.src = fallback2;
                carImage.style.opacity = '1';
                imageLoader.style.display = 'none';
            };
            
            img2.src = fallback1;
        };
        
        img.src = imageUrl;
    }
</script>