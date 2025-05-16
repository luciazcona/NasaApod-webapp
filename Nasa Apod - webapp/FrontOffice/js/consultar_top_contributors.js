// Hacer la petición al servidor
var xhr = new XMLHttpRequest();
xhr.open('GET', 'php/consultar_top_contributors.php', true);
xhr.responseType = 'json'; // Establecer el tipo de respuesta JSON
xhr.onload = function() {
    if (xhr.status == 200) {
        var topContributors = xhr.response;
        // Poblar el selector con los datos obtenidos
        var select = document.getElementById('top_contributors');
        topContributors.forEach(function(contributor) {
            var option = document.createElement('option');
            option.text = contributor;
            select.add(option);
        });
    } else {
        console.log('Error on the top contributors request.');
    }
};
xhr.send();

var respuesta;
document.getElementById('enviarContributors').addEventListener('click', function(event) {
    event.preventDefault(); // Esto previene el comportamiento predeterminado del botón

    // Obtener la opción seleccionada del menú desplegable
    var selectElement = document.getElementById('top_contributors');
    var selectedIndex = selectElement.selectedIndex;

    // Verificar si se ha seleccionado una opción
    if (selectedIndex >= 1) {
        var selectedValue = selectElement.options[selectedIndex].value;
        //alert(selectedValue);

            // Realizar la solicitud al servidor
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/consultar_contributors_images.php?copyright=' + encodeURIComponent(selectedValue), true);
        xhr.responseType = 'json';
        xhr.onload = function() {
            if (xhr.status == 200) {
                respuesta = xhr.response;
                if (respuesta.error) {
                    alert(respuesta.error);
                } else {
                    //console.log(respuesta);
                    abrirVentanaConTitulos(respuesta);
                }
            } else {
                alert('Error al consultar la imagen.');
            }
        };
        xhr.send();
    } else {
        alert('Please select an option.');
    }
});

function abrirVentanaConTitulos(respuesta) {
    // Crear una nueva ventana
    var nuevaVentana = window.open('', '_blank');

    var web = 'detalle_imagen';
    // Verifiar si es anonimo o registrado
    var esRegistrado = document.getElementById('esRegistrado').dataset.value;
    if (esRegistrado === 'true')
        web = 'detalle_imagen2';

    // Construir el contenido HTML de la nueva ventana
    var contenidoHTML = '<!DOCTYPE html>';
    contenidoHTML += '<html lang="en">';
    contenidoHTML += '<head>';
    contenidoHTML += '<meta charset="UTF-8">';
    contenidoHTML += '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    contenidoHTML += '<title>Images from contributor</title>';
    contenidoHTML += '</head>';
    contenidoHTML += '<body>';

    // Agregar la lista de títulos al contenido HTML
    contenidoHTML += '<h1>Contributor: ' + respuesta[0].copyright + '</h1>';
    contenidoHTML += '<ul>';
    respuesta.forEach(function(objeto, indice) {
        if (objeto.hasOwnProperty('titulo')) {
            // Obtener la respuesta correspondiente al índice seleccionado
            var selectedResponse = respuesta[indice];

            // Abrir una nueva ventana con los detalles de la imagen seleccionada
            var urlDetalleImagen =  web + '.html?titulo=' + encodeURIComponent(selectedResponse.titulo) + 
                                    '&copyright=' + encodeURIComponent(selectedResponse.copyright) +
                                    '&fecha=' + encodeURIComponent(selectedResponse.fecha) + 
                                    '&explicacion=' + encodeURIComponent(selectedResponse.explicacion) + 
                                    '&url_imagen=' + encodeURIComponent(selectedResponse.url_imagen);

            contenidoHTML += '<li>' + '<span style="font-size: 1.1em;">' + objeto.fecha + ':&nbsp;&nbsp;&nbsp;' + '<a href="' + urlDetalleImagen + '" target="_blank">' + objeto.titulo + '</a></li>';
        }
    });
    contenidoHTML += '</ul>';


    // Cerrar el cuerpo y el HTML
    contenidoHTML += '</body>';
    contenidoHTML += '</html>';

    // Escribir el contenido HTML en la nueva ventana
    nuevaVentana.document.write(contenidoHTML);
}
