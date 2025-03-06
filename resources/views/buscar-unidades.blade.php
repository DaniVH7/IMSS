<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Unidades de Salud</title>

    <!-- Estilos de Select2 y Leaflet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/mapa.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
            // Configuración de Select2 para CLUES
            function setupCluesSelect2(id, url, placeholder) {
                $('#' + id).select2({
                    placeholder: placeholder,
                    allowClear: true,
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

            $('#searchClues').on('select2:select', function(e) {
                let data = e.params.data;

                if (data.idmunicipio) {
                    $('#searchMunicipio')
                        .empty()
                        .append(new Option(data.municipio, data.idmunicipio, false, true))
                        .trigger('change.select2'); // Forzar actualización en Select2
                } else {}

                if (data.idlocalidad) {
                    $('#searchLocalidad')
                        .empty()
                        .append(new Option(data.localidad, data.idlocalidad, false, true))
                        .trigger('change.select2');
                } else {}
            });




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
            $(document).ready(function() {
                // ✅ Select2 para Localidades con AJAX
                $('#searchLocalidad').select2({
                    placeholder: "Seleccione una Localidad",
                    allowClear: true,
                    minimumInputLength: 1, // Inicia búsqueda después de 1 caracter
                    ajax: {
                        url: "/api/unidades/buscarLocalidadesConUnidades",
                        dataType: "json",
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.idlocalidad,
                                    text: item.localidad
                                }))
                            };
                        },
                        cache: true
                    }
                });

                // ✅ Evento al seleccionar una Localidad
                $('#searchLocalidad').on('select2:select', function(e) {
                    let data = e.params.data;
                    if (!data || !data.text) {
                        console.warn("⚠️ No se seleccionó una localidad válida.");
                        return;
                    }
                    buscarUnidadesPorLocalidad(data.text);
                });

                // ✅ Función para buscar unidades médicas en la localidad seleccionada
                function buscarUnidadesPorLocalidad(localidadNombre) {
                    if (!localidadNombre) {
                        alert("⚠️ Por favor seleccione una localidad.");
                        return;
                    }

                    $.ajax({
                        url: "/api/unidades/buscarUnidadesPorLocalidad",
                        type: "GET",
                        data: {
                            localidad: localidadNombre
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.length > 0) {
                                mostrarInformacion(response);
                            } else {
                                alert("⚠️ No hay unidades médicas registradas en esta localidad.");
                                $('#selectedInfo').html('<h3>No se encontraron unidades médicas en esta localidad.</h3>');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire("❌ Error al buscar unidades por localidad: " + xhr.responseText);
                        }
                    });
                }
                $('#searchMunicipio').select2({
                    placeholder: "Seleccione un Municipio",
                    allowClear: true,
                    minimumInputLength: 1,
                    ajax: {
                        url: "/api/unidades/buscarMunicipiosConUnidades",
                        dataType: "json",
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
                                    text: item.municipio
                                }))
                            };
                        },
                        cache: true
                    }
                });

                // Evento para capturar selección de municipio
                $('#searchMunicipio').on('select2:select', function (e) {
    let data = e.params.data;
    let nombreMunicipio = data.text; // Ahora usamos el nombre del municipio

    if (!nombreMunicipio) {
        console.warn("⚠️ No se seleccionó un municipio válido.");
        return;
    }

    // ✅ Limpiar las localidades previas
    $('#searchLocalidad').val(null).trigger('change');

    // 🚀 Cargar localidades filtradas por municipio (nombre)
    $('#searchLocalidad').select2({
        placeholder: "Seleccione una Localidad",
        allowClear: true,
        ajax: {
            url: "/api/unidades/buscarLocalidadesPorNombreMunicipio", // Nueva API en Laravel
            dataType: "json",
            delay: 250,
            data: function () {
                return { municipio: nombreMunicipio }; // Se envía el nombre del municipio
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.idlocalidad,
                        text: item.localidad
                    }))
                };
            },
            cache: true
        }
    });
});


                function buscarUnidadesPorLocalidad(localidadNombre) {
                    if (!localidadNombre) {
                        alert("⚠️ Por favor seleccione una localidad.");
                        return;
                    }

                    $.ajax({
                        url: "/api/unidades/buscarUnidadesPorLocalidad",
                        type: "GET",
                        data: {
                            localidad: localidadNombre
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.length > 0) {
                                mostrarInformacion(response);
                            } else {
                                alert("⚠️ No hay unidades médicas registradas en esta localidad.");
                                $('#selectedInfo').html('<h3>No se encontraron unidades médicas en esta localidad.</h3>');
                            }
                        },
                        error: function(xhr) {
                            alert("Error al buscar unidades por localidad: " + xhr.responseText);
                        }
                    });
                }

            });




            // Inicializar Select2
            setupCluesSelect2('searchClues', '/api/unidades/buscarClues', 'Ingrese CLUES o Nombre');
            setupMunicipioSelect2('searchMunicipio', '/api/unidades/buscarUnidadesPorMunicipio', 'Seleccione Municipio');

            // Manejo de búsquedas
            function buscarYMostrar(id) {
                let selectedData = $('#' + id).select2('data');
                if (selectedData.length > 0) {
                    mostrarInformacion(selectedData);
                } else {
                    alert("Por favor seleccione una opción antes de buscar.");
                }
            }
            $('#searchClues, #searchMunicipio, #searchJurisdiccion').select2({
                placeholder: "Seleccione una opción",
                allowClear: true,
                ajax: {
                    url: function() {
                        return $(this).attr('data-url');
                    },
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.nombre || item.municipio || item.jurisdiccion
                            }))
                        };
                    },
                    cache: true
                }
            });



            $('#searchClues').select2({
                placeholder: "Ingrese CLUES o Nombre",
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: "/api/unidades/buscarClues",
                    dataType: "json",
                    delay: 250,
                    minimumInputLength: 1,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                        console.log("Datos de la respuesta:", data);

                    },

                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.clues,
                                text: `${item.nombre} (CLUES: ${item.clues})`,
                                clues: item.clues,
                                nombre: item.nombre,
                                latitud: item.latitud,
                                longitud: item.longitud,
                                idmunicipio: item.idmunicipio,
                                municipio: item.municipio,
                                idlocalidad: item.idlocalidad,
                                localidad: item.localidad,
                                idjurisdiccion: item.idjurisdiccion,
                                jurisdiccion: item.jurisdiccion
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#searchClues').on('select2:select', function(e) {
                let data = e.params.data;

                if (data.latitud && data.longitud) {
                    addMarker(data.latitud, data.longitud, data.nombre, data.clues);
                    ajustarMapa();
                }

                if (data.idmunicipio) {
                    $('#searchMunicipio')
                        .empty()
                        .append(new Option(data.municipio, data.idmunicipio, false, true))
                        .trigger('change.select2');
                } else {}

                if (data.idlocalidad) {
                    $('#searchLocalidad')
                        .empty()
                        .append(new Option(data.localidad, data.idlocalidad, false, true))
                        .trigger('change.select2');
                } else {
                    console.log("No se encontró idlocalidad en la respuesta.");
                }

                if (data.idjurisdiccion) {
                    $('#searchJurisdiccion')
                        .empty()
                        .append(new Option(data.jurisdiccion, data.idjurisdiccion, false, true))
                        .trigger('change.select2');
                } else {
                    console.log("No se encontró idjurisdiccion en la respuesta.");
                }
            });



            $('#searchJurisdiccion').select2({
                placeholder: "Seleccione una Jurisdicción",
                allowClear: true,
                ajax: {
                    url: "/api/unidades/buscarJurisdicciones",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.idjurisdiccion,
                                text: item.jurisdiccion
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#searchJurisdiccion').on('select2:select', function (e) {
    let data = e.params.data;
    let idJurisdiccion = data.id;

    if (!idJurisdiccion) {
        Swal.fire("⚠️ Atención", "Seleccione una jurisdicción válida.", "warning");
        return;
    }

    // Limpiar selects dependientes
    $('#searchMunicipio').val(null).trigger('change');
    $('#searchLocalidad').val(null).trigger('change');

    // Cargar municipios filtrados por jurisdicción
    $('#searchMunicipio').select2({
        placeholder: "Seleccione un Municipio",
        allowClear: true,
        ajax: {
            url: "/api/unidades/buscarMunicipiosPorJurisdiccion",
            dataType: "json",
            delay: 250,
            data: function () {
                return { idjurisdiccion: idJurisdiccion };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.idmunicipio,
                        text: item.municipio
                    }))
                };
            },
            cache: true
        }
    });
});





            $('#searchMunicipio').select2({
                placeholder: "Seleccione un Municipio",
                allowClear: true,
                ajax: {
                    url: "/api/unidades/buscarMunicipiosPorJurisdiccion", 
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            idjurisdiccion: $('#searchJurisdiccion').val()
                        };
                    },
                    processResults: function(data) {
                        console.log("🔄 Datos recibidos:", data);
                        return {
                            results: data.map(item => ({
                                id: item.idmunicipio,
                                text: item.municipio
                            }))
                        };
                    },
                    cache: true
                }
            });

            $('#searchMunicipio').on('select2:select', function(e) {
                let data = e.params.data;
                let nombreMunicipio = data.text; 

                if (!nombreMunicipio) {
                    Swal.alert("⚠️ No se seleccionó un municipio válido.");
                    return;
                }

                //Limpiar las localidades previas
                $('#searchLocalidad').val(null).trigger('change');

                //Cargar localidades filtradas por municipio (nombre)
                $('#searchLocalidad').select2({
                    placeholder: "Seleccione una Localidad",
                    allowClear: true,
                    ajax: {
                        url: "/api/unidades/buscarLocalidadesPorNombreMunicipio",
                        dataType: "json",
                        delay: 250,
                        data: function() {
                            return {
                                municipio: nombreMunicipio
                            }; // Enviar el NOMBRE del municipio
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.idlocalidad,
                                    text: item.localidad
                                }))
                            };
                        },
                        cache: true
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
            map = L.map('map').setView([20.05, -98.21], 10); 

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
    <header>
        <div class="header-top">
            <div class="logo">Buscar Unidades de Salud</div>
        </div>
    </header>
    
    <div class="container">
        <div class="filters">
            <h2>Filtrar Unidades de Salud</h2>

            <label for="searchJurisdiccion">Jurisdicción o Zona:</label>
            <select id="searchJurisdiccion" class="select2" style="width: 100%;"></select>

            <label for="searchMunicipio">Municipio:</label>
            <select id="searchMunicipio" class="select2" style="width: 100%;"></select>
            
            <label for="searchLocalidad">Localidad:</label>
            <select id="searchLocalidad" class="select2" style="width: 100%;"></select>
            
            <label for="searchClues">CLUES o Nombre de la Unidad:</label>
            <select id="searchClues" class="select2" style="width: 100%;"></select>
            
            <button id="btnGlobalSearch">Buscar</button>
            <button id="btnResetFilters">Restablecer Filtros</button>
        </div>

        <div class="map">
            <div id="map"></div>
        </div>
    </div>

    <div id="selectedInfo" class="resultado-busqueda"></div>

    <footer>
        <p>&copy; 2025 Prototipo de mapa interactivo de unidades médicas. | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
    </footer>
    <script src="{{ asset('js/buttons.js') }}"></script>
    <script>
        let map = L.map('map').setView([20.05, -98.21], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            allowClear: true
        });
    </script>

</body>

</html>