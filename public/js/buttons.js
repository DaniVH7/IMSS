$('#btnGlobalSearch').click(function() {
    let cluesData = $('#searchClues').select2('data')?.[0] || null;
    let municipioData = $('#searchMunicipio').select2('data')?.[0] || null;
    let localidadNombre = $('#searchLocalidad option:selected').text()?.trim();

    // Prioridad: CLUES > Municipio > Localidad
    if (cluesData) {
        mostrarInformacion([cluesData]);
    } else if (municipioData) {
        buscarUnidadesPorMunicipio(municipioData.id);
    } else if (localidadNombre && localidadNombre !== "Seleccione una Localidad") {
        buscarUnidadesPorLocalidad(localidadNombre);
    } else {
        alert("Por favor seleccione un criterio de búsqueda.");
    }
});

// Funciones específicas para buscar por Municipio y Localidad
function buscarUnidadesPorMunicipio(idMunicipio) {
    $.ajax({
        url: '/api/unidades/buscarUnidadesPorMunicipio',
        type: 'GET',
        data: { municipio: idMunicipio },
        dataType: 'json',
        success: function(data) {
            if (data.length === 0) {
                alert("No hay unidades médicas registradas en este municipio.");
                return;
            }
            mostrarInformacion(data);
        },
        error: function(xhr) {
            alert("Error al buscar unidades: " + xhr.responseText);
        }
    });
}

function buscarUnidadesPorLocalidad(localidadNombre) {
    $.ajax({
        url: '/api/unidades/buscarUnidadesPorLocalidad',
        type: 'GET',
        data: { localidad: localidadNombre },
        dataType: 'json',
        success: function(data) {
            if (data.length === 0) {
                alert("No hay unidades médicas registradas en esta localidad.");
                return;
            }
            mostrarInformacion(data);
        },
        error: function(xhr) {
            alert("Error al buscar unidades: " + xhr.responseText);
        }
    });
}

// Función para mostrar la información de las unidades
function mostrarInformacion(dataArray) {
    let unidadesInfo = dataArray.map(data => {
        let nombre = data.unidad_medica || data.nombre || "Sin Nombre"; // ✅ Maneja ambos casos

        return `
            <p><strong>CLUES:</strong> ${data.clues || 'No disponible'}</p>
            <p><strong>Unidad Médica:</strong> ${nombre}</p>
            <p><strong>Latitud:</strong> ${data.latitud || 'No disponible'}</p>
            <p><strong>Longitud:</strong> ${data.longitud || 'No disponible'}</p>
            <hr>
        `;
    }).join('');

    $('#selectedInfo').html(`<h3>Información de la Búsqueda</h3>${unidadesInfo}`);

    clearMarkers();
    dataArray.forEach(item => {
        let nombre = item.unidad_medica || item.nombre || "Sin Nombre"; // ✅ Verifica ambas claves
        if (item.latitud && item.longitud) {
            addMarker(item.latitud, item.longitud, nombre, item.clues);
        }
    });

    ajustarMapa();
}

console.log("buttons.js cargado correctamente");
