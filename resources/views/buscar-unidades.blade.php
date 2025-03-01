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
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border: 2px solid #ddd;
        }

        fieldset {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 10px;
            padding: 5px 10px;
            cursor: pointer;
        }

        #selectedInfo {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>

    <script>
        let map, markers = [];
        window.clearMarkers = function() {
    if (markers.length > 0) {
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
        console.log("Marcadores eliminados correctamente");
    } else {
        console.log("No hay marcadores para eliminar");
    }
};


        $(document).ready(function() {
            $('#searchJurisdiccion').html('<option selected>Jurisdicción Sanitaria II Tulancingo</option>').prop('disabled', true);
            // Llamar a la función cuando cargue la página
            cargarLocalidades();
            // Configuración de Select2 para CLUES
            function setupCluesSelect2(id, url, placeholder) {
                $('#' + id).select2({
                    placeholder: placeholder,
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term
                            };
                        },
                        success: function(data) {
                            let localidadSelect = $('#searchLocalidad');
                            localidadSelect.empty().append('<option value="">Seleccione una Localidad</option>');
                            data.forEach(item => {
                                localidadSelect.append(`<option value="${item.idlocalidad}">${item.localidad}</option>`);
                            });
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.clues,
                                    text: `${item.nombre} (CLUES: ${item.clues})`,
                                    clues: item.clues,
                                    nombre: item.nombre,
                                    latitud: item.latitud,
                                    longitud: item.longitud
                                }))
                            };
                        },
                        cache: true
                    }
                });
            }


            // Configuración de Select2 para Municipios
            function setupMunicipioSelect2(id, url, placeholder) {
                $('#' + id).select2({
                    placeholder: placeholder,
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.idmunicipio,
                                    text: item.municipio,
                                    clues: item.clues,
                                    unidad_medica: item.unidad_medica,
                                    latitud: item.latitud,
                                    longitud: item.longitud
                                }))
                            };
                        },
                        cache: true
                    }
                });
            }

            // Cargar localidades al seleccionar la jurisdicción
            function cargarLocalidades() {
                $.ajax({
                    url: '/api/unidades/buscarUnidadesTulancingo',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let localidadSelect = $('#searchLocalidad');
                        localidadSelect.empty().append('<option value="">Seleccione una Localidad</option>');

                        if (!data || data.length === 0) {
                            console.log("No se encontraron localidades.");
                            return;
                        }

                        data.forEach(item => {
                            if (item.unidad_medica) { // Usamos unidad_medica en lugar de nombre
                                localidadSelect.append(`<option value="${item.unidad_medica}">${item.unidad_medica}</option>`);
                            } else {
                                console.log("Dato inválido en la respuesta de la API:", item);
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error("Error al cargar localidades:", xhr.responseText);
                    }
                });
            }



            // Evento para buscar unidades médicas cuando se seleccione una localidad
            $('#btnSearchLocalidad').click(function() {
                let localidadNombre = $('#searchLocalidad option:selected').text().trim();

                if (!localidadNombre || localidadNombre === "Seleccione una Localidad") {
                    alert("Por favor seleccione una localidad antes de buscar.");
                    return;
                }

                $.ajax({
                    url: '/api/unidades/buscarUnidadesPorLocalidad',
                    type: 'GET',
                    data: {
                        localidad: localidadNombre
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length === 0) {
                            alert("No hay unidades médicas registradas en esta localidad.");
                            return;
                        }
                        mostrarUnidadesPorLocalidad(data);
                    },
                    error: function(xhr) {
                        alert("Error al buscar unidades: " + xhr.responseText);
                    }
                });

            });

            // Función para mostrar la información de unidades médicas en la localidad
            function mostrarUnidadesPorLocalidad(dataArray) {
                let unidadesInfo = dataArray.map(data => `
        <p><strong>CLUES:</strong> ${data.clues || 'No disponible'}</p>
        <p><strong>Unidad Médica:</strong> ${data.unidad_medica || 'No disponible'}</p>
        <p><strong>Latitud:</strong> ${data.latitud || 'No disponible'}</p>
        <p><strong>Longitud:</strong> ${data.longitud || 'No disponible'}</p>
        <hr>
    `).join('');

                $('#selectedInfo').html(`<h3>Unidades Médicas en la Localidad</h3>${unidadesInfo}`);

                clearMarkers();
                dataArray.forEach(item => {
                    if (item.latitud && item.longitud) {
                        addMarker(item.latitud, item.longitud, item.unidad_medica, item.clues);
                    }
                });

                ajustarMapa();
            }



            // Configuración de Select2 para la Jurisdicción Sanitaria II Tulancingo
            function setupJurisdiccionSelect2(id, url) {
                $('#' + id).select2({
                    placeholder: "Jurisdicción Sanitaria II Tulancingo",
                    allowClear: true,
                    minimumInputLength: 0,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.idjurisdiccion,
                                    text: "Jurisdicción Sanitaria II Tulancingo",
                                    localidad: item.localidad,
                                    clues: item.clues,
                                    unidad_medica: item.unidad_medica,
                                    latitud: item.latitud,
                                    longitud: item.longitud
                                }))
                            };
                        },
                        cache: true
                    }
                });
            }

            // Inicializar Select2
            setupCluesSelect2('searchClues', '/api/unidades/buscarClues', 'Ingrese CLUES o Nombre');
            setupMunicipioSelect2('searchMunicipio', '/api/unidades/buscarUnidadesPorMunicipio', 'Seleccione Municipio');
            setupJurisdiccionSelect2('searchJurisdiccion', '/api/unidades/buscarUnidadesTulancingo');

            // Manejo de búsquedas
            function buscarYMostrar(id) {
                let selectedData = $('#' + id).select2('data');
                if (selectedData.length > 0) {
                    mostrarInformacion(selectedData);
                } else {
                    alert("Por favor seleccione una opción antes de buscar.");
                }
            }

            $('#btnSearchClues').click(function() {
                let selectedData = $('#searchClues').select2('data')[0];

                if (selectedData) {
                    console.log(selectedData);

                    $('#selectedInfo').html(`
            <h3>Información Seleccionada</h3>
            <p><strong>CLUES:</strong> ${selectedData.clues}</p>
            <p><strong>Nombre:</strong> ${selectedData.nombre ? selectedData.nombre : 'No disponible'}</p>
            <p><strong>Latitud:</strong> ${selectedData.latitud}</p>
            <p><strong>Longitud:</strong> ${selectedData.longitud}</p>
        `);

                    if (selectedData.latitud && selectedData.longitud) {
                        clearMarkers();
                        addMarker(selectedData.latitud, selectedData.longitud, selectedData.nombre, selectedData.clues);
                        ajustarMapa();
                    } else {
                        alert("No hay coordenadas disponibles para esta unidad.");
                    }
                } else {
                    alert("Por favor seleccione un CLUES antes de buscar.");
                }
            });


            $('#btnSearchMunicipio').click(() => buscarYMostrar('searchMunicipio'));
            $('#btnSearchJurisdiccion').click(function() {
                let jurisdiccionNombre = $('#searchJurisdiccion option:selected').text();

                if (!jurisdiccionNombre || jurisdiccionNombre === "Seleccione una Jurisdicción") {
                    alert("Por favor seleccione una jurisdicción antes de buscar.");
                    return;
                }

                $.ajax({
                    url: '/api/unidades/buscarUnidadesTulancingo',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.length === 0) {
                            alert("No hay unidades médicas registradas en esta jurisdicción.");
                            return;
                        }
                        mostrarUnidadesPorJurisdiccion(data);
                    },
                    error: function(xhr) {
                        alert("Error al buscar unidades: " + xhr.responseText);
                    }
                });

            });

            function mostrarUnidadesPorJurisdiccion(dataArray) {
                let unidadesInfo = dataArray.map(data => `
        <p><strong>CLUES:</strong> ${data.clues || 'No disponible'}</p>
        <p><strong>Unidad Médica:</strong> ${data.unidad_medica || 'No disponible'}</p>
        <p><strong>Latitud:</strong> ${data.latitud || 'No disponible'}</p>
        <p><strong>Longitud:</strong> ${data.longitud || 'No disponible'}</p>
        <hr>
    `).join('');

                $('#selectedInfo').html(`<h3>Unidades Médicas en la Jurisdicción</h3>${unidadesInfo}`);

                clearMarkers();
                dataArray.forEach(item => {
                    if (item.latitud && item.longitud) {
                        addMarker(item.latitud, item.longitud, item.unidad_medica, item.clues);
                    }
                });

                ajustarMapa();
            }


            $('#btnResetFilters').click(function() {
                console.log("Restableciendo filtros...");

                // Restablecer los Select2 si estan seleccionados
                if ($('#searchClues').hasClass("select2-hidden-accessible")) {
                    $('#searchClues').val(null).trigger('change');
                }
                if ($('#searchMunicipio').hasClass("select2-hidden-accessible")) {
                    $('#searchMunicipio').val(null).trigger('change');
                }
                if ($('#searchJurisdiccion').hasClass("select2-hidden-accessible")) {
                    $('#searchJurisdiccion').val(null).trigger('change');
                }
                if ($('#searchLocalidad').hasClass("select2-hidden-accessible")) {
                    $('#searchLocalidad').val(null).trigger('change');
                }

                $('#selectedInfo').html('');

                // Restablecer el mapa
                map.setView([20.05, -98.21], 12);

                clearMarkers();
                console.log("Filtros restablecidos");
            });

            // Eliminar los marcadores del mapa
            function clearMarkers() {
                if (markers.length > 0) {
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];
                    console.log("Marcadores eliminados");
                }
            }


            function mostrarInformacion(dataArray) {
                let unidadesInfo = dataArray.map(data => `
                    <p><strong>CLUES:</strong> ${data.clues || 'No disponible'}</p>
                    <p><strong>Unidad Médica:</strong> ${data.unidad_medica || 'No disponible'}</p>
                    <p><strong>Latitud:</strong> ${data.latitud || 'No disponible'}</p>
                    <p><strong>Longitud:</strong> ${data.longitud || 'No disponible'}</p>
                    <hr>
                `).join('');

                $('#selectedInfo').html(`<h3>Información Seleccionada</h3>${unidadesInfo}`);

                clearMarkers();
                dataArray.forEach(item => {
                    if (item.latitud && item.longitud) {
                        addMarker(item.latitud, item.longitud, item.unidad_medica, item.clues);
                    }
                });

                ajustarMapa();
            }


            function addMarker(lat, lng, name, clues) {
                let marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>${name}</b><br>CLUES: ${clues}`)
                    .openPopup();
                markers.push(marker);
            }

            function clearMarkers() {
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
            }

            function ajustarMapa() {
                if (markers.length === 0) return;
                let group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds(), {
                    padding: [50, 50]
                });
            }

            // Inicializar el mapa
            // Inicializar el mapa en Tulancingo, Hidalgo
            map = L.map('map').setView([20.05, -98.21], 10); // Ajuste de nivel de zoom para visualizar mejor el área

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

        });
        window.addMarker = function(lat, lng, name, clues) {
    let marker = L.marker([lat, lng]).addTo(map)
        .bindPopup(`<b>${name}</b><br>CLUES: ${clues}`)
        .openPopup();
    markers.push(marker);
};

window.ajustarMapa = function() {
    if (markers.length === 0) return;
    let group = new L.featureGroup(markers);
    map.fitBounds(group.getBounds(), {
        padding: [50, 50]
    });
};


    </script>

</head>

<body>

    <h1>Buscar Unidades de Salud</h1>

    <fieldset>
        <legend>Búsqueda por CLUES</legend>
        <select id="searchClues" style="width: 50%;"></select>
    </fieldset>

    <fieldset>
        <legend>Búsqueda por Municipio</legend>
        <select id="searchMunicipio" style="width: 50%;"></select>
    </fieldset>

    <fieldset>
        <legend>Seleccione una Localidad</legend>
        <label for="searchLocalidad">Localidad:</label>
        <select id="searchLocalidad" style="width: 50%;">
            <option value="">Seleccione una Localidad</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Jurisdicción Sanitaria II Tulancingo</legend>
        <select id="searchJurisdiccion" style="width: 50%;"></select>
        <button id="btnSearchJurisdiccion" style="margin-top: 10px; padding: 5px 10px; cursor: pointer;">Buscar</button>
    </fieldset>



    <button id="btnResetFilters" style="margin-top: 10px; padding: 5px 10px; cursor: pointer;">Restablecer Filtros</button>
    <button id="btnGlobalSearch" style="margin-top: 10px; padding: 5px 10px; cursor: pointer;">Buscar</button>

    <div id="selectedInfo"></div>
    <div id="map"></div>


    <div id="selectedInfo"></div>
    <div id="map"></div>
    <script src="{{ asset('js/buttons.js') }}"></script>
    <script src="{{ asset('js/autofill.js') }}"></script>

</body>

</html>