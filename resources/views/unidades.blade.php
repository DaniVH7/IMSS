<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Unidades de Salud</title>

    <!-- Estilos de Select2 y Leaflet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        fieldset {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border: 2px solid #ddd;
        }

        #selectedInfo {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

    <h1>Buscar Unidades de Salud</h1>

    <!-- Selección de CLUES -->
    <fieldset>
        <legend>Búsqueda por CLUES o Nombre</legend>
        <label for="searchClues">CLUES / Nombre:</label>
        <select id="searchClues" style="width: 100%;"></select>
    </fieldset>

    <!-- Selección automática de Jurisdicción -->
    <fieldset>
        <legend>Jurisdicción Sanitaria</legend>
        <label for="searchJurisdiccion">Jurisdicción:</label>
        <select id="searchJurisdiccion" disabled style="width: 100%;"></select>
    </fieldset>

    <!-- Selección automática de Municipio -->
    <fieldset>
        <legend>Municipio</legend>
        <label for="searchMunicipio">Municipio:</label>
        <select id="searchMunicipio" disabled style="width: 100%;"></select>
    </fieldset>

    <div id="selectedInfo"></div>
    <div id="map"></div>

    <script>
        let map, markers = [];

        $(document).ready(function () {
            // Inicializar Select2 para CLUES o Nombre
            $('#searchClues').select2({
                placeholder: "Ingrese CLUES o Nombre",
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '/api/unidades/buscarClues', // Asegúrate de que esta API esté funcionando
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ query: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.clues,
                            text: `${item.nombre} (CLUES: ${item.clues})`,
                            clues: item.clues,
                            nombre: item.nombre
                        }))
                    }),
                    cache: true
                }
            });

            // Evento al seleccionar un CLUES o Nombre
            $('#searchClues').on('select2:select', function (e) {
                let selectedData = e.params.data;

                if (selectedData) {
                    $.ajax({
                        url: '/api/unidades/getInfoByClues',
                        type: 'GET',
                        data: { clues: selectedData.clues },
                        dataType: 'json',
                        success: function (data) {
                            $('#searchJurisdiccion').html(`<option value="${data.idjurisdiccion}" selected>${data.jurisdiccion}</option>`);
                            $('#searchMunicipio').html(`<option value="${data.idmunicipio}" selected>${data.municipio}</option>`);

                            // Mostrar información en el contenedor
                            $('#selectedInfo').html(`
                                <h3>Información de la Unidad</h3>
                                <p><strong>CLUES:</strong> ${data.clues}</p>
                                <p><strong>Nombre:</strong> ${data.nombre}</p>
                                <p><strong>Jurisdicción:</strong> ${data.jurisdiccion}</p>
                                <p><strong>Municipio:</strong> ${data.municipio}</p>
                            `);

                            // Limpiar y agregar marcador en el mapa
                            clearMarkers();
                            if (data.latitud && data.longitud) {
                                addMarker(data.latitud, data.longitud, data.nombre, data.clues);
                                ajustarMapa();
                            }
                        },
                        error: function (xhr) {
                            console.error("Error al obtener datos:", xhr.responseText);
                        }
                    });
                }
            });

            // Inicializar el mapa en una ubicación por defecto
            map = L.map('map').setView([20.05, -98.21], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function addMarker(lat, lng, name, clues) {
                let marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>${name}</b><br>CLUES: ${clues}`);
                markers.push(marker);
            }

            function clearMarkers() {
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
            }

            function ajustarMapa() {
                if (markers.length === 0) return;
                let group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds(), { padding: [50, 50] });
            }
        });
    </script>

</body>
</html>
