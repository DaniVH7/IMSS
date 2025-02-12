<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa Interactivo - Unidades Médicas en Tulancingo</title>
    
    <!-- Estilos de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Estilos de Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    
    <link rel="stylesheet" href="{{ asset('css/tulancingo.css') }}">
    
    <!-- Scripts de Leaflet y jQuery -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
            <select id="busqueda" class="select2">
                <option value=""></option>
            </select>

            <label for="tipo_unidad">Filtrar por Tipo de Unidad Médica:</label>
            <select id="tipo_unidad" class="select2">
                <option value="">Todos los tipos de unidades</option>
            </select>

            <button onclick="buscarUnidades()">Buscar</button>
        </div>
        <div class="map">
            <div id="map"></div>
        </div>
    </div>

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
            try {
                unidades = await fetch('/json/unidades.json').then(res => res.json());
                tiposUnidades = await fetch('/json/tipos_unidades.json').then(res => res.json());

                // Filtrar solo unidades de Tulancingo (077)
                unidades = unidades.filter(u => u.idmunicipio === "077");

                // Cargar unidades médicas en el select con Select2
                const selectBusqueda = $('#busqueda');
                selectBusqueda.empty().append('<option value="">Seleccione una unidad médica</option>');

                unidades.forEach(u => {
                    selectBusqueda.append(new Option(`${u.nombre} (CLUES: ${u.clues})`, u.clues));
                });

                selectBusqueda.select2({
                    placeholder: "Seleccione una unidad médica",
                    allowClear: true
                });

                selectBusqueda.on('change', function() {
                    buscarUnidades();
                });

                // Cargar tipos de unidad en el select con Select2
                const selectTipoUnidad = $('#tipo_unidad');
                selectTipoUnidad.empty().append('<option value="">Todos los tipos de unidades</option>');

                tiposUnidades.forEach(t => {
                    selectTipoUnidad.append(new Option(t.tipo_unidad, t.idtipo_unidad));
                });

                selectTipoUnidad.select2({
                    placeholder: "Seleccione un tipo de unidad",
                    allowClear: true
                });

            } catch (error) {
                console.error("Error al cargar las unidades médicas:", error);
            }
        }

        function buscarUnidades() {
            const query = $('#busqueda').val();
            const resultadoDiv = document.getElementById('resultado');

            resultadoDiv.innerHTML = '';

            // Filtra unidades basadas en la selección
            let unidadesFiltradas = unidades.filter(u => u.clues === query || u.nombre.includes(query));

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

        $(document).ready(function() {
            initMap();
            cargarUnidades();
        });
    </script>
</body>
</html>
