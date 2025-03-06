$(document).ready(function () {
    let cluesSeleccionado = null;
    let municipioSeleccionado = null;
    let localidadSeleccionada = null;
    let jurisdiccionSeleccionada = null;

    // Capturar selección sin ejecutar búsqueda inmediata
    $('#searchClues').on('select2:select', function (e) {
        let data = e.params.data;
        cluesSeleccionado = data.id;
    });

    $('#searchMunicipio').on('select2:select', function (e) {
        let data = e.params.data;
        municipioSeleccionado = data.text;
    });

    $('#searchLocalidad').on('select2:select', function (e) {
        let data = e.params.data;
        localidadSeleccionada = data.text;
    });

    $('#searchJurisdiccion').on('select2:select', function (e) {
        let data = e.params.data;
        jurisdiccionSeleccionada = data.id;
    });

    // **Evento del botón "Buscar"**
    $('#btnGlobalSearch').click(function () {
        if (!cluesSeleccionado && !municipioSeleccionado && !localidadSeleccionada && !jurisdiccionSeleccionada) {
            Swal.fire("", "Por favor seleccione al menos un criterio de búsqueda.", "warning");
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
        console.log("🔄 Restableciendo filtros...");

        // Limpiar selects y variables
        $('#searchClues, #searchMunicipio, #searchLocalidad, #searchJurisdiccion').val(null).trigger('change');
        cluesSeleccionado = municipioSeleccionado = localidadSeleccionada = jurisdiccionSeleccionada = null;
        $('#selectedInfo').html('');
        clearMarkers();
        console.log("✅ Filtros restablecidos correctamente.");
    });

    // **Funciones de búsqueda AJAX**
    function buscarUnidadesPorClues(clues) {
        $.ajax({
            url: '/api/unidades/buscarClues',
            type: 'GET',
            data: { query: clues },
            dataType: 'json',
            success: function (data) {
                if (!data || data.length === 0) {
                    Swal.fire("Atención", "No se encontraron unidades con este CLUES.", "info");
                    return;
                }
                mostrarInformacion(data);
            },
            error: function (xhr) {
                Swal.fire("Error", "No se pudo realizar la búsqueda por CLUES.", "error");
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
                    Swal.fire("Atención", "No hay unidades médicas registradas en este municipio.", "info");
                    return;
                }
                mostrarInformacion(data);
            },
            error: function (xhr) {
                Swal.fire("Error", "No se pudo realizar la búsqueda por municipio.", "error");
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
                if (!data || data.length === 0) {
                    Swal.fire("Atención", "No hay unidades médicas registradas en esta localidad.", "info");
                    return;
                }
                mostrarInformacion(data);
            },
            error: function (xhr) {
                Swal.fire("Error", "No se pudo realizar la búsqueda por localidad.", "error");
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
                if (!data || data.length === 0) {
                    Swal.fire("⚠️ Atención", "No hay unidades médicas registradas en esta jurisdicción.", "info");
                    return;
                }
                mostrarInformacion(data);
            },
            error: function (xhr) {
                Swal.fire("❌ Error", "No se pudo realizar la búsqueda por jurisdicción.", "error");
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
    

    console.log("✅ Filtros de búsqueda configurados correctamente.");
});
