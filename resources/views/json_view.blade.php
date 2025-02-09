<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Municipio y Mostrar Clínicas</title>

    <!-- Carga los estilos de Leaflet para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map; // Variable para el mapa
        let markersLayer; // Capa de marcadores

        /**
         * Inicializa el mapa en una vista general de México.
         */
        async function initMap() {
            map = L.map('map').setView([20.0, -98.0], 6); // Coordenadas centrales de México

            // Carga de mapa desde OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            markersLayer = L.layerGroup().addTo(map); // Capa donde se agregarán los marcadores
        }

        /**
         * Busca la información del municipio, localidades y unidades médicas.
         */
        async function buscarMunicipio() {
            const municipioId = document.getElementById('municipio').value.trim(); // Obtiene el ID ingresado
            if (!municipioId) {
                alert("Por favor ingresa un ID de municipio.");
                return;
            }

            // Cargar información desde los archivos JSON en la carpeta public/json
            const municipios = await fetch('/json/municipios.json').then(res => res.json());
            const localidades = await fetch('/json/localidades.json').then(res => res.json());
            const unidades = await fetch('/json/unidades.json').then(res => res.json());

            // Buscar el municipio en la lista
            const municipioInfo = municipios.find(m => m.idmunicipio === municipioId);
            const localidadesInfo = localidades.filter(l => l.idmunicipio === municipioId);
            const unidadesInfo = unidades.filter(u => u.idmunicipio === municipioId);

            // Si el municipio no existe, mostrar un mensaje de error
            if (!municipioInfo) {
                document.getElementById('resultado').innerHTML = "<p style='color: red;'>Municipio no encontrado</p>";
                return;
            }

            // Mostrar la información del municipio
            document.getElementById('resultado').innerHTML = `
                <h2>Información del Municipio</h2>
                <p><strong>ID Estado:</strong> ${municipioInfo.idestado}</p>
                <p><strong>ID Jurisdicción:</strong> ${municipioInfo.idjurisdiccion}</p>
                <p><strong>ID Municipio:</strong> ${municipioInfo.idmunicipio}</p>
                <p><strong>Nombre:</strong> ${municipioInfo.municipio}</p>

                <h3>Localidades</h3>
                <ul>
                    ${localidadesInfo.length > 0 
                        ? localidadesInfo.map(l => `<li>${l.localidad} (ID: ${l.idlocalidad})</li>`).join('')
                        : '<li>No hay localidades registradas</li>'
                    }
                </ul>

                <h3>Unidades Médicas</h3>
                <ul>
                    ${unidadesInfo.length > 0 
                        ? unidadesInfo.map(u => `<li>${u.nombre} - ${u.vialidad}, ${u.asentamiento} (Lat: ${u.latitud}, Lon: ${u.longitud})</li>`).join('')
                        : '<li>No hay unidades médicas registradas</li>'
                    }
                </ul>
            `;

            // Limpiar los marcadores previos del mapa
            markersLayer.clearLayers();

            // Agregar marcadores en el mapa con la información de las unidades médicas
            unidadesInfo.forEach(u => {
                if (u.latitud && u.longitud) {
                    const marker = L.marker([parseFloat(u.latitud), parseFloat(u.longitud)])
                        .bindPopup(`<strong>${u.nombre}</strong><br>${u.vialidad}, ${u.asentamiento}`)
                        .addTo(markersLayer);
                }
            });

            // Si hay unidades médicas, centrar el mapa en la primera unidad
            if (unidadesInfo.length > 0) {
                const primeraUnidad = unidadesInfo[0];
                map.setView([parseFloat(primeraUnidad.latitud), parseFloat(primeraUnidad.longitud)], 12);
            }
        }

        // Cargar el mapa cuando la página termine de cargar
        window.onload = initMap;
    </script>
</head>
<body>
    <h1>Buscar Municipio y Mostrar Clínicas</h1>
    
    <label for="municipio">ID del Municipio:</label>
    <input type="text" id="municipio" placeholder="Ejemplo: 001">
    <button onclick="buscarMunicipio()">Buscar</button>

    <!-- Contenedor donde se mostrará la información del municipio -->
    <div id="resultado" style="margin-top: 20px; background: #f4f4f4; padding: 15px;"></div>

    <h2>Mapa de Unidades Médicas</h2>
    <!-- Contenedor del mapa -->
    <div id="map" style="height: 500px;"></div>
</body>
</html>
