<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Unidades Médicas en Tulancingo</title>

    <!-- Estilos de Leaflet (para el mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map;
        let markersLayer;

        /**
         * Inicializa el mapa en una vista centrada en Tulancingo.
         */
        async function initMap() {
            map = L.map('map').setView([20.0923182, -98.3670238], 13); // Coordenadas de Tulancingo

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            markersLayer = L.layerGroup().addTo(map);
        }

        /**
         * Carga las tipologías y tipos de unidad médica en los selectores.
         */
        async function cargarFiltros() {
            const unidades = await fetch('/json/unidades.json').then(res => res.json());
            const tipologias = await fetch('/json/tipologias_unidades.json').then(res => res.json());
            const tiposUnidades = await fetch('/json/tipos_unidades.json').then(res => res.json());

            // Filtrar unidades que pertenecen a Tulancingo (idmunicipio: "077")
            const unidadesTulancingo = unidades.filter(u => u.idmunicipio === "077");

            // Crear lista única de tipologías disponibles
            const tipologiasDisponibles = [...new Set(unidadesTulancingo.map(u => u.idtipologia_unidad))];
            const tiposUnidadesDisponibles = [...new Set(unidadesTulancingo.map(u => u.idtipo_unidad))];

            const selectTipologia = document.getElementById('tipologia');
            const selectTipoUnidad = document.getElementById('tipo_unidad');

            // Llenar select de Tipologías
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

            // Llenar select de Tipos de Unidades Médicas
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

        /**
         * Filtra y muestra las unidades médicas en función de los filtros seleccionados.
         */
        async function buscarUnidades() {
            const tipologiaId = document.getElementById('tipologia').value;
            const tipoUnidadId = document.getElementById('tipo_unidad').value;

            const unidades = await fetch('/json/unidades.json').then(res => res.json());

            // Filtrar unidades en Tulancingo (municipio 077)
            let unidadesFiltradas = unidades.filter(u => u.idmunicipio === "077");

            // Aplicar filtros si se han seleccionado
            if (tipologiaId) {
                unidadesFiltradas = unidadesFiltradas.filter(u => u.idtipologia_unidad === tipologiaId);
            }
            if (tipoUnidadId) {
                unidadesFiltradas = unidadesFiltradas.filter(u => u.idtipo_unidad === tipoUnidadId);
            }

            // Mostrar resultados
            document.getElementById('resultado').innerHTML = `
                <h2>Unidades Médicas Encontradas</h2>
                <ul>
                    ${unidadesFiltradas.length > 0
                        ? unidadesFiltradas.map(u => `<li><strong>${u.nombre}</strong> - ${u.vialidad}, ${u.asentamiento} (CP: ${u.cp})</li>`).join('')
                        : '<li>No se encontraron unidades con estos filtros</li>'
                    }
                </ul>
            `;

            // Limpiar marcadores anteriores en el mapa
            markersLayer.clearLayers();

            // Agregar marcadores en el mapa
            unidadesFiltradas.forEach(u => {
                if (u.latitud && u.longitud) {
                    L.marker([parseFloat(u.latitud), parseFloat(u.longitud)])
                        .bindPopup(`<strong>${u.nombre}</strong><br>${u.vialidad}, ${u.asentamiento}`)
                        .addTo(markersLayer);
                }
            });

            // Si hay unidades, centrar el mapa en la primera
            if (unidadesFiltradas.length > 0) {
                const primeraUnidad = unidadesFiltradas[0];
                map.setView([parseFloat(primeraUnidad.latitud), parseFloat(primeraUnidad.longitud)], 14);
            }
        }

        // Cargar mapa y filtros al cargar la página
        window.onload = function() {
            initMap();
            cargarFiltros();
        };
    </script>
</head>
<body>
    <h1>Buscar Unidades Médicas en Tulancingo</h1>

    <label for="tipologia">Seleccione una Tipología:</label>
    <select id="tipologia"></select>

    <label for="tipo_unidad">Seleccione un Tipo de Unidad Médica:</label>
    <select id="tipo_unidad"></select>

    <button onclick="buscarUnidades()">Buscar</button>

    <!-- Contenedor donde se mostrarán los resultados -->
    <div id="resultado" style="margin-top: 20px; background: #f4f4f4; padding: 15px;"></div>

    <h2>Ubicación en el Mapa</h2>
    <div id="map" style="height: 500px;"></div>
</body>
</html>
