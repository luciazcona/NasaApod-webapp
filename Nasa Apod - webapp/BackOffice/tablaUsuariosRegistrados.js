var response;

// Function to fetch all users and display them in a list
function fetchAllUsers() {
  // Make the request to the server
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "tablaUsuariosRegistrados.php", true);
  xhr.responseType = "json";
  console.log("Hola:"); // Debugging line
  xhr.onload = function () {
    console.log("Response status:", xhr.status);
    //console.log("Response text:", xhr.responseText); // Debugging line
    if (xhr.status == 200) {
      try {
        response = xhr.response;
        console.log("Parsed response:", response); // Debugging line

        if (response && response.error) {
          console.error("Error in response:", response.error); // Debugging line
          alert(response.error);
        } else if (response) {
          var tableBody = document.getElementById("table_data");
          console.log("Table body:", tableBody); // Debugging line

          tableBody.innerHTML = "";

          response.forEach(function (item) {
            var row = document.createElement("tr");

            var cellName = document.createElement("td");
            cellName.textContent = item.nombre;
            row.appendChild(cellName);

            var cellEmail = document.createElement("td");
            cellEmail.textContent = item.email;
            row.appendChild(cellEmail);

            tableBody.appendChild(row);
          });
        } else {
          console.error("Response is null or undefined"); // Debugging line
          alert("Error retrieving user information.");
        }
      } catch (e) {
        console.error("Error parsing response:", e); // Debugging line
        alert("Error parsing response.");
      }
    } else {
      console.error("Error status:", xhr.status); // Debugging line
      console.error("Response:", xhr.responseText); // Debugging line
      alert("Error retrieving user information.");
    }
  };
  xhr.onerror = function () {
    console.error("Request failed"); // Debugging line
    alert("Request failed.");
  };
  xhr.send();
}

// Call the function to fetch all users when the page loads
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed"); // Debugging line
  fetchAllUsers();
});
