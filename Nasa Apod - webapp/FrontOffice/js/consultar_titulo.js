var respuesta;

document.getElementById('titulo').addEventListener('input', function() {
    var tituloSeleccionado = document.getElementById('titulo').value;
    if (tituloSeleccionado.length < 2)
        return;

    // Realizar la solicitud al servidor
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/consultar_titulo.php?titulo=' + encodeURIComponent(tituloSeleccionado), true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        if (xhr.status == 200) {
            respuesta = xhr.response;
            if (respuesta.error) {
                //alert(respuesta.error);
            } else {
                // Referencia al elemento select en el HTML
                var selectElement = document.getElementById('titulos');

                // Limpiar las opciones existentes del menú desplegable
                selectElement.innerHTML = '';

                // Iterar sobre los resultados y crear una opción para cada título
                respuesta.forEach(function(item) {
                    var option = document.createElement('option');
                    option.value = item.titulo;
                    option.textContent = item.titulo;
                    selectElement.appendChild(option);
                });
            }
        } else {
            alert('Error al consultar la imagen.');
        }
    };
    xhr.send();
});

document.getElementById('enviarTitulo').addEventListener('click', function(event) {
    event.preventDefault(); // Esto previene el comportamiento predeterminado del botón

    var web = 'detalle_imagen';
    // Verifiar si es anonimo o registrado
    var esRegistrado = document.getElementById('esRegistrado').dataset.value;
    if (esRegistrado === 'true')
        web = 'detalle_imagen2';

    // Obtener la opción seleccionada del menú desplegable
    var selectElement = document.getElementById('titulos');
    var selectedIndex = selectElement.selectedIndex;

    // Verificar si se ha seleccionado una opción
    if (selectedIndex >= 0) {
        // Obtener la respuesta correspondiente al índice seleccionado
        var selectedResponse = respuesta[selectedIndex];

        // Abrir una nueva ventana con los detalles de la imagen seleccionada
        var urlDetalleImagen =  web + '.html?titulo=' + encodeURIComponent(selectedResponse.titulo) + 
                                '&copyright=' + encodeURIComponent(selectedResponse.copyright) +
                                '&fecha=' + encodeURIComponent(selectedResponse.fecha) + 
                                '&explicacion=' + encodeURIComponent(selectedResponse.explicacion) + 
                                '&url_imagen=' + encodeURIComponent(selectedResponse.url_imagen);
        window.open(urlDetalleImagen, '_blank');
    } else {
        alert('Por favor selecciona una opción.');
    }
});
