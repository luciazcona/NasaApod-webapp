document.addEventListener('DOMContentLoaded', function() {
    fetch('php/last_db_update.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        //console.log(data);
        document.getElementById('lastUpdate').innerText = 'Last DB update: ' + data.fecha;
      })
      .catch(error => console.error('Fetch error:', error));
});
