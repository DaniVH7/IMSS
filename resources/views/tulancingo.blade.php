<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo - Unidades Médicas en Tulancingo</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tulancingo.css') }}">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">Buscar Unidades Médicas en Tulancingo</div>
        </div>
    </header>
    <div class="container">
        <div class="filters">
            <h2>Filtrar Unidades Médicas</h2>
            <label for="tipologia">Seleccione una Tipología:</label>
            <select id="tipologia"></select>
            <label for="tipo_unidad">Seleccione un Tipo de Unidad Médica:</label>
            <select id="tipo_unidad"></select>
            <button onclick="buscarUnidades()">Buscar</button>
        </div>
        <div class="map">
            <div id="map"></div>
        </div>
    </div>
    <footer>
        <div>
            <p>&copy; 2025 Prototipo de mapa interactivo de unidades medicas. | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
        </div>
    </footer>


    
    

    <script>
        let map;
        let markersLayer;

        async function initMap() {
            map = L.map('map').setView([20.0923182, -98.3670238], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            markersLayer = L.layerGroup().addTo(map);
        }

        async function cargarFiltros() {
            const unidades = await fetch('/json/unidades.json').then(res => res.json());
            const tipologias = await fetch('/json/tipologias_unidades.json').then(res => res.json());
            const tiposUnidades = await fetch('/json/tipos_unidades.json').then(res => res.json());

            const unidadesTulancingo = unidades.filter(u => u.idmunicipio === "077");
            const tipologiasDisponibles = [...new Set(unidadesTulancingo.map(u => u.idtipologia_unidad))];
            const tiposUnidadesDisponibles = [...new Set(unidadesTulancingo.map(u => u.idtipo_unidad))];

            const selectTipologia = document.getElementById('tipologia');
            const selectTipoUnidad = document.getElementById('tipo_unidad');
            
            selectTipologia.innerHTML = '<option value="">Seleccione una tipología</option>';
            tipologiasDisponibles.forEach(id => {
                let tipologia = tipologias.find(t => t.idtipologia_unidad === id);
                if (tipologia) {
                    let option = document.createElement('option');
                    option.value = tipologia.idtipologia_unidad;
                    option.textContent = tipologia.tipologia_unidad;
                    selectTipologia.appendChild(option);
                }
            });

            selectTipoUnidad.innerHTML = '<option value="">Seleccione un tipo de unidad</option>';
            tiposUnidadesDisponibles.forEach(id => {
                let tipoUnidad = tiposUnidades.find(t => t.idtipo_unidad === id);
                if (tipoUnidad) {
                    let option = document.createElement('option');
                    option.value = tipoUnidad.idtipo_unidad;
                    option.textContent = tipoUnidad.tipo_unidad;
                    selectTipoUnidad.appendChild(option);
                }
            });
        }

        async function buscarUnidades() {
            const tipologiaId = document.getElementById('tipologia').value;
            const tipoUnidadId = document.getElementById('tipo_unidad').value;
            const unidades = await fetch('/json/unidades.json').then(res => res.json());
            let unidadesFiltradas = unidades.filter(u => u.idmunicipio === "077");

            if (tipologiaId) unidadesFiltradas = unidadesFiltradas.filter(u => u.idtipologia_unidad === tipologiaId);
            if (tipoUnidadId) unidadesFiltradas = unidadesFiltradas.filter(u => u.idtipo_unidad === tipoUnidadId);

            markersLayer.clearLayers();
            unidadesFiltradas.forEach(u => {
                if (u.latitud && u.longitud) {
                    L.marker([parseFloat(u.latitud), parseFloat(u.longitud)])
                        .bindPopup(`<strong>${u.nombre}</strong><br>${u.vialidad}, ${u.asentamiento}`)
                        .addTo(markersLayer);
                }
            });
        }

        window.onload = function() {
            initMap();
            cargarFiltros();
        };
    </script>
</body>
</html>
