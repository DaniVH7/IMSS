window.actualizarMunicipioYLocalidad = function(data) {
    console.log("Datos recibidos para actualizar Municipio y Localidad:", data);

    let municipio = data.municipio ? data.municipio.trim() : "No disponible";
    let localidad = data.localidad ? data.localidad.trim() : "No disponible";
    

    if (municipio !== "No disponible") {
        $('#searchMunicipio').append(new Option(municipio, municipio, true, true)).prop('disabled', false);
    } else {
        console.warn("⚠️ No se encontró información de municipio en la respuesta.");
    }

    if (localidad !== "No disponible") {
        $('#searchLocalidad').append(new Option(localidad, localidad, true, true)).prop('disabled', false);
    } else {
        console.warn("⚠️ No se encontró información de localidad en la respuesta.");
    }

    // Recarga Select2
    $('#searchMunicipio').select2({ placeholder: "Seleccione Municipio" }).trigger('change.select2');
    $('#searchLocalidad').select2({ placeholder: "Seleccione una Localidad" }).trigger('change.select2');

    console.log("Municipio y Localidad actualizados:", municipio, localidad);
};

// Evento de búsqueda global
$('#btnGlobalSearch').click(function() {
    let cluesData = $('#searchClues').select2('data')?.[0] || null;
    let municipioData = $('#searchMunicipio').select2('data')?.[0] || null;
    let localidadNombre = $('#searchLocalidad option:selected').text()?.trim();

    // Prioridad: CLUES > Municipio > Localidad
    if (cluesData) {
        window.actualizarMunicipioYLocalidad(cluesData); // ✅ Llamar a la función desde autoFill.js
        mostrarInformacion([cluesData]);
    } else if (municipioData) {
        buscarUnidadesPorMunicipio(municipioData.id);
    } else if (localidadNombre && localidadNombre !== "Seleccione una Localidad") {
        buscarUnidadesPorLocalidad(localidadNombre);
    } else {
        alert("Por favor seleccione un criterio de búsqueda.");
    }
});

console.log("autoFill.js cargado correctamente");
