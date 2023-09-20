<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Icon Ruko</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="map" style="height: 500px;"></div>

    <form id="coordinateForm">
        <label for="searchAddress">Cari Alamat:</label>
        <input type="text" id="searchAddress" name="searchAddress">
        <button type="button" id="searchButton">Cari</button>
        <br>
        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" name="latitude" readonly>
        <br>
        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" name="longitude" readonly>
    </form>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map = L.map('map').setView([-0.90141682332305, 119.87739681899808], 13.50);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let markersLayer = L.layerGroup().addTo(map);

        map.on('click', function(e) {
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });


        $('#searchButton').on('click', function() {
            let searchAddress = $('#searchAddress').val();

            $.ajax({
                url: 'https://nominatim.openstreetmap.org/search?format=json&q=' + searchAddress,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        let result = data[0];
                        let latitude = result.lat;
                        let longitude = result.lon;
                        map.setView([latitude, longitude], 15);
                        $('#latitude').val(latitude);
                        $('#longitude').val(longitude);
                    } else {
                        alert('Alamat tidak ditemukan.');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        $.ajax({
            url: '/api/ruko',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $.each(response.data, function(index, item) {
                    let customIcon = L.icon({
                        iconUrl: '/uploads/ruko/' + item.gambar_ruko,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    let marker = L.marker([item.latitude, item.longtitude], {
                        icon: customIcon
                    }).addTo(markersLayer);

                    marker.bindPopup(item.nama_ruko);
                })
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>

</html>
