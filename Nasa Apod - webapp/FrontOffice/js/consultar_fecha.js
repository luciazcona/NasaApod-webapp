document.getElementById('enviar').addEventListener('click', function(event) {
    event.preventDefault(); // Esto previene el comportamiento predeterminado del boton

    var web = 'detalle_imagen';
    // Verifiar si es anonimo o registrado
    var esRegistrado = document.getElementById('esRegistrado').dataset.value;
    if (esRegistrado === 'true')
        web = 'detalle_imagen2';

    var fechaSeleccionada = document.getElementById('fecha').value;
    // Hacer la peticion al servidor
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/consultar_fecha.php?fecha=' + fechaSeleccionada, true);
    xhr.responseType = 'json'; // Establecer el tipo de respuesta JSON
    xhr.onload = function() {
        if (xhr.status == 200) {
            var respuesta = xhr.response; // La respuesta ya está en formato JSON
            if (respuesta.error) {
                alert(respuesta.error);
            } else {
                // Abrir una nueva ventana con los detalles de la imagen
                var urlDetalleImagen =  web + '.html?titulo=' + encodeURIComponent(respuesta.titulo) + 
                                        '&copyright=' + encodeURIComponent(respuesta.copyright) +
                                        '&fecha=' + encodeURIComponent(respuesta.fecha) + 
                                        '&explicacion=' + encodeURIComponent(respuesta.explicacion) + 
                                        '&url_imagen=' + encodeURIComponent(respuesta.url_imagen);
                window.open(urlDetalleImagen, '_blank');
                
            }
        } else {
            alert('Error al consultar la imagen.');
        }
    };
    xhr.send();
});