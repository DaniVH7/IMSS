<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Unidades de Salud</title>

    <!-- Estilos de Select2 y Leaflet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Scripts -->
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

        button {
            padding: 5px 10px;
            cursor: pointer;
        }

        #selectedInfo {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border: 2px solid #ddd;
        }
    </style>

    <script>
        let map, marker;

        $(document).ready(function () {
            // Inicializar el mapa en una ubicación genérica
            map = L.map('map').setView([19.43, -99.13], 6); // Coordenadas iniciales (México)

            // Agregar capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Configurar Select2 para búsqueda por CLUES o Nombre
            $('#searchClues').select2({
                placeholder: "Ingrese CLUES o Nombre",
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: "/api/unidades/buscarClues",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return { query: params.term };
                    },
                    processResults: function (data) {
                        // Filtrar duplicados con un conjunto (Set)
                        let uniqueResults = [];
                        let seenClues = new Set();

                        data.forEach(item => {
                            if (!seenClues.has(item.clues)) {
                                seenClues.add(item.clues);
                                uniqueResults.push({
                                    id: item.clues,
                                    text: `${item.nombre} (CLUES: ${item.clues})`,
                                    clues: item.clues,
                                    nombre: item.nombre,
                                    municipio: item.municipio,
                                    localidad: item.localidad,
                                    latitude: item.latitud,
                                    longitude: item.longitud
                                });
                            }
                        });

                        return {
                            results: uniqueResults.slice(0, 3) // <-- Solo mantiene el primer resultado único
                        };
                    },
                    cache: true
                }
            });

            // Evento al seleccionar un resultado
            $('#searchClues').on('select2:select', function (e) {
                let data = e.params.data;

                // Mostrar información seleccionada
                $('#selectedInfo').html(`
                    <h3>Información de la Unidad</h3>
                    <p><strong>CLUES:</strong> ${data.clues}</p>
                    <p><strong>Unidad Médica:</strong> ${data.nombre}</p>
                    <p><strong>Municipio:</strong> ${data.municipio}</p>
                    <p><strong>Localidad:</strong> ${data.localidad}</p>
                    <p><strong>Latitud:</strong> ${data.latitude}</p>
                    <p><strong>Longitud:</strong> ${data.longitude}</p>
                `);

                // Mover el mapa a la ubicación seleccionada y agregar marcador
                if (marker) {
                    map.removeLayer(marker); // Elimina el marcador anterior
                }

                if (data.latitude && data.longitude) {
                    marker = L.marker([data.latitude, data.longitude]).addTo(map)
                        .bindPopup(`<b>${data.nombre}</b><br>CLUES: ${data.clues}`)
                        .openPopup();

                    map.setView([data.latitude, data.longitude], 14); // Centra y acerca el mapa
                }
            });

            // Botón para limpiar la búsqueda y reiniciar el mapa
            $('#btnResetFilters').click(function () {
                $('#searchClues').val(null).trigger('change');
                $('#selectedInfo').html('');

                if (marker) {
                    map.removeLayer(marker); // Elimina el marcador actual
                    marker = null;
                }

                map.setView([19.43, -99.13], 6); // Restablece la vista inicial del mapa
            });
        });
    </script>

</head>

<body>

    <h1>Buscar Unidades de Salud</h1>

    <fieldset>
        <legend>Búsqueda por CLUES o Nombre</legend>
        <select id="searchClues" style="width: 50%;"></select>
    </fieldset>

    <button id="btnResetFilters">Restablecer Búsqueda</button>

    <div id="selectedInfo"></div>

    <div id="map"></div>

</body>

</html>
