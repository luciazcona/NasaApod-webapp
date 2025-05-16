var respuesta;

document.getElementById("titulo").addEventListener("input", function () {
  var tituloSeleccionado = document.getElementById("titulo").value;
  if (tituloSeleccionado.length < 2) return;

  // Realizar la solicitud al servidor
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "consultar_titulo.php?nombre=" + encodeURIComponent(tituloSeleccionado),
    true
  );
  xhr.responseType = "json";
  xhr.onload = function () {
    if (xhr.status == 200) {
      respuesta = xhr.response;
      if (respuesta.error) {
        alert(respuesta.error);
      } else {
        // Referencia al elemento select en el HTML
        var selectElement = document.getElementById("titulos");

        // Limpiar las opciones existentes del menú desplegable
        selectElement.innerHTML = "";

        // Iterar sobre los resultados y crear una opción para cada título
        respuesta.forEach(function (item) {
          var option = document.createElement("option");
          option.value = item.nombre;
          option.textContent = item.nombre;
          selectElement.appendChild(option);
        });
      }
    } else {
      alert("Error al consultar los nombres.");
    }
  };
  xhr.send();
});

document
  .getElementById("enviarTitulo")
  .addEventListener("click", function (event) {
    event.preventDefault(); // Esto previene el comportamiento predeterminado del botón

    // Obtener la opción seleccionada del menú desplegable
    var selectElement = document.getElementById("titulos");
    var selectedIndex = selectElement.selectedIndex;

    // Verificar si se ha seleccionado una opción
    if (selectedIndex >= 0) {
      // Obtener la respuesta correspondiente al índice seleccionado
      var selectedResponse = respuesta[selectedIndex];

      // Abrir una nueva ventana con los detalles de la imagen seleccionada
      var urlDetalleImagen =
        "detalle_imagen.html?nombre=" +
        encodeURIComponent(selectedResponse.nombre);
      window.open(urlDetalleImagen, "_blank");
    } else {
      alert("Por favor selecciona una opción.");
    }
  });
