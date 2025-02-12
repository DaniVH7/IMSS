<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo - Unidades Médicas en Tulancingo</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/tulancingo.css') }}">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
            <label for="busqueda">Ingrese el Nombre o CLUES:</label>
            <input type="text" id="busqueda" placeholder="Ejemplo: TULANCINGO o HGIMB003844" oninput="mostrarSugerencias()">
            
            <!-- Nuevo div para mostrar sugerencias en vivo -->
            <div id="sugerencias" class="autocomplete-suggestions"></div>

            <label for="tipo_unidad">Filtrar por Tipo de Unidad Médica:</label>
            <select id="tipo_unidad"></select>

            <button onclick="buscarUnidades()">Buscar</button>
        </div>
        <div class="map">
            <div id="map"></div>
        </div>
    </div>

    <!-- Nuevo div para mostrar los resultados después de hacer clic en buscar -->
    <div id="resultado" class="resultado-busqueda"></div>

    <footer>
        <div>
            <p>&copy; 2025 Prototipo de mapa interactivo de unidades médicas. | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
        </div>
    </footer>

    <script>
        let map;
        let markersLayer;
        let unidades = [];
        let tiposUnidades = [];

        async function initMap() {
            map = L.map('map').setView([20.0923182, -98.3670238], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            markersLayer = L.layerGroup().addTo(map);
        }

        async function cargarUnidades() {
            unidades = await fetch('/json/unidades.json').then(res => res.json());
            tiposUnidades = await fetch('/json/tipos_unidades.json').then(res => res.json());

            unidades = unidades.filter(u => u.idmunicipio === "077");

            const selectTipoUnidad = document.getElementById('tipo_unidad');
            selectTipoUnidad.innerHTML = '<option value="">Todos los tipos de unidades</option>';

            tiposUnidades.forEach(t => {
                let option = document.createElement('option');
                option.value = t.idtipo_unidad;
                option.textContent = t.tipo_unidad;
                selectTipoUnidad.appendChild(option);
            });
        }

        function mostrarSugerencias() {
            const query = document.getElementById('busqueda').value.trim().toLowerCase();
            const sugerenciasDiv = document.getElementById('sugerencias');

            sugerenciasDiv.innerHTML = '';

            if (!query) return;

            let unidadesFiltradas = unidades.filter(u =>
                u.nombre.toLowerCase().includes(query) || u.clues.toLowerCase().includes(query)
            ).slice(0, 5); 

            if (unidadesFiltradas.length === 0) {
                sugerenciasDiv.style.display = "none";
                return;
            }

            sugerenciasDiv.style.display = "block";
            unidadesFiltradas.forEach(u => {
                let div = document.createElement('div');
                div.textContent = `${u.nombre} (CLUES: ${u.clues})`;
                div.onclick = function() {
                    document.getElementById('busqueda').value = u.nombre;
                    sugerenciasDiv.innerHTML = ''; 
                    sugerenciasDiv.style.display = "none";
                    buscarUnidades();
                };
                sugerenciasDiv.appendChild(div);
            });
        }

        function buscarUnidades() {
            const query = document.getElementById('busqueda').value.trim().toLowerCase();
            const resultadoDiv = document.getElementById('resultado');
            const sugerenciasDiv = document.getElementById('sugerencias');

            resultadoDiv.innerHTML = '';
            sugerenciasDiv.innerHTML = ''; 
            sugerenciasDiv.style.display = "none";

            let unidadesFiltradas = unidades.filter(u =>
                u.nombre.toLowerCase().includes(query) || u.clues.toLowerCase().includes(query)
            );

            resultadoDiv.innerHTML = `
                <h2>Resultados de Búsqueda</h2>
                <ul>
                    ${unidadesFiltradas.length > 0
                        ? unidadesFiltradas.map(u => `
                            <li><strong>${u.nombre}</strong> - ${u.vialidad}, ${u.asentamiento} (CP: ${u.cp}) <br>
                            <strong>CLUES:</strong> ${u.clues}</li>
                        `).join('')
                        : '<li>No se encontraron unidades con estos criterios</li>'
                    }
                </ul>
            `;

            markersLayer.clearLayers();

            unidadesFiltradas.forEach(u => {
                if (u.latitud && u.longitud) {
                    L.marker([parseFloat(u.latitud), parseFloat(u.longitud)])
                        .bindPopup(`<strong>${u.nombre}</strong><br>${u.vialidad}, ${u.asentamiento} <br> <strong>CLUES:</strong> ${u.clues}`)
                        .addTo(markersLayer);
                }
            });

            if (unidadesFiltradas.length > 0) {
                const primeraUnidad = unidadesFiltradas[0];
                map.setView([parseFloat(primeraUnidad.latitud), parseFloat(primeraUnidad.longitud)], 14);
            }
        }

        window.onload = function() {
            initMap();
            cargarUnidades();
        };
    </script>
</body>
</html>
