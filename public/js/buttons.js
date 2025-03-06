    $(document).ready(function () {
        let cluesSeleccionado = null;
        let municipioSeleccionado = null;
        let localidadSeleccionada = null;
        let jurisdiccionSeleccionada = null;

        // Capturar selección de CLUES sin ejecutar búsqueda inmediata
        $('#searchClues').on('select2:select', function (e) {
            let data = e.params.data;
            cluesSeleccionado = data.id;
        });

        // Capturar selección de Municipio sin ejecutar búsqueda inmediata
        $('#searchMunicipio').on('select2:select', function (e) {
            let data = e.params.data;
            let nombreMunicipio = data.text; // Ahora usamos el nombre del municipio
        
            if (!nombreMunicipio) {
                console.warn("⚠️ No se seleccionó un municipio válido.");
                return;
            }
        
            // Limpiar las localidades previas
            $('#searchLocalidad').val(null).trigger('change');
        
            // Cargar localidades filtradas por municipio (nombre)
            $('#searchLocalidad').select2({
                placeholder: "Seleccione una Localidad",
                allowClear: true,
                ajax: {
                    url: "/api/unidades/buscarLocalidadesPorMunicipio", 
                    dataType: "json",
                    delay: 250,
                    data: function () {
                        return { municipio: nombreMunicipio }; 
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
        

        // Capturar selección de Localidad sin ejecutar búsqueda inmediata
        $('#searchLocalidad').on('select2:select', function (e) {
            let data = e.params.data;
            localidadSeleccionada = data.text;
        });

        // Capturar selección de Jurisdicción sin ejecutar búsqueda inmediata
        $('#searchJurisdiccion').on('select2:select', function (e) {
            let data = e.params.data;
            jurisdiccionSeleccionada = data.id;
        });

        // **Evento del botón "Buscar"**
        $('#btnGlobalSearch').click(function () {
            if (!cluesSeleccionado && !municipioSeleccionado && !localidadSeleccionada && !jurisdiccionSeleccionada) {
                alert("Por favor seleccione un criterio de búsqueda antes de presionar 'Buscar'.");
                return;
            }
            if (cluesSeleccionado) {
                buscarUnidadesPorClues(cluesSeleccionado);
            } else if (municipioSeleccionado) {
                buscarUnidadesPorMunicipio(municipioSeleccionado);
            } else if (localidadSeleccionada) {
                buscarUnidadesPorLocalidad(localidadSeleccionada);
            } else if (jurisdiccionSeleccionada) {
                buscarUnidadesPorJurisdiccion(jurisdiccionSeleccionada);
            }
        });

        // **Evento del botón "Restablecer Filtros"**
        $('#btnResetFilters').click(function () {
            console.log("Restableciendo filtros...");

            $('#searchClues, #searchMunicipio, #searchLocalidad, #searchJurisdiccion').val(null).trigger('change');
            cluesSeleccionado = municipioSeleccionado = localidadSeleccionada = jurisdiccionSeleccionada = null;
            $('#selectedInfo').html('');
            clearMarkers();
            console.log("Filtros restablecidos correctamente.");
        });

        function buscarUnidadesPorClues(clues) {
            $.ajax({
                url: '/api/unidades/buscarClues',
                type: 'GET',
                data: { query: clues },
                dataType: 'json',
                success: function (data) {
                    if (!data || data.length === 0) {
                        Swal.fire("⚠️ No se encontraron unidades con este CLUES.");
                        return;
                    }
                    mostrarInformacion(data);
                },
                error: function (xhr) {
                    alert("❌ Error al buscar unidades por CLUES: " + xhr.responseText);
                }
            });
        }

        function buscarUnidadesPorMunicipio(nombreMunicipio) {
            $.ajax({
                url: '/api/unidades/buscarUnidadesPorMunicipio',
                type: 'GET',
                data: { municipio: nombreMunicipio },
                dataType: 'json',
                success: function (data) {
                    if (!data || data.length === 0) {
                        alert("⚠️ No hay unidades médicas registradas en este municipio.");
                        $('#selectedInfo').html('<p>No se encontraron unidades médicas en este municipio.</p>');
                        return;
                    }
                    mostrarInformacion(data);
                },
                error: function (xhr) {
                    alert("❌ Error al buscar unidades por municipio: " + xhr.responseText);
                }
            });
        }
        

        function buscarUnidadesPorLocalidad(localidadNombre) {
            $.ajax({
                url: '/api/unidades/buscarUnidadesPorLocalidad',
                type: 'GET',
                data: { localidad: localidadNombre },
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        alert("⚠️ No hay unidades médicas registradas en esta localidad.");
                        return;
                    }
                    mostrarInformacion(data);
                },
                error: function (xhr) {
                    alert("❌ Error al buscar unidades por localidad: " + xhr.responseText);
                }
            });
        }

        function buscarUnidadesPorJurisdiccion(idJurisdiccion) {
            $.ajax({
                url: '/api/unidades/buscarUnidadesPorJurisdiccion',
                type: 'GET',
                data: { idjurisdiccion: idJurisdiccion },
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        Swal.fire("⚠️ No hay unidades médicas registradas en esta jurisdicción.");
                        return;
                    }
                    mostrarInformacion(data);
                },
                error: function (xhr) {
                    Swal.fire("❌ Error al buscar unidades por jurisdicción: " + xhr.responseText);
                }
            });
        }

        function mostrarInformacion(dataArray) {
            let unidadesInfo = dataArray.map(data => `
                <p><strong>CLUES:</strong> ${data.clues || 'No disponible'}</p>
                <p><strong>Unidad Médica:</strong> ${data.unidad_medica || data.nombre || 'No disponible'}</p>
                <p><strong>Municipio:</strong> ${data.municipio || 'No disponible'}</p>
                <p><strong>Latitud:</strong> ${data.latitud || 'No disponible'}</p>
                <p><strong>Longitud:</strong> ${data.longitud || 'No disponible'}</p>
                <hr>
            `).join('');
        
            $('#selectedInfo').html(`<h3>Información de la Búsqueda</h3>${unidadesInfo}`);
        
            //Limpiar los marcadores previos
            clearMarkers();
        
            //Agregar nuevos marcadores al mapa
            dataArray.forEach(item => {
                if (item.latitud && item.longitud) {
                    addMarker(item.latitud, item.longitud, item.unidad_medica, item.clues);
                }
            });
        
            // Ajustar mapa para mostrar los nuevos marcadores
            ajustarMapa();
        }
        

        console.log("Script cargado correctamente.");
    });
