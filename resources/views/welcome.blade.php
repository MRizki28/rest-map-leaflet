<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Icon Ruko</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div id="map" style="height: 500px;"></div>
    <div class="container">
        <form id="formTambah" method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="searchAddress">Cari Alamat:</label>
                <input class="form-control" type="text" id="searchAddress" name="searchAddress">
                <button type="button" id="searchButton">Cari</button>
            </div>
            <div>
                <label for="nama_ruko" class="">nama ruko:</label>
                <input class="form-control" type="text" id="nama_ruko" name="nama_ruko">
            </div>
            <div>
                <label for="gambar_ruko">file:</label>
                <input class="form-control" type="file" id="gambar_ruko" name="gambar_ruko">
            </div>

            <div>
                <label for="latitude">Latitude:</label>
                <input class="form-control" type="text" id="latitude" name="latitude">
            </div>

            <div>
                <label for="longitude">Longitude:</label>
                <input class="form-control" type="text" id="longitude" name="longtitude">
            </div>

            <div style="text-align: end;">
                <button class="btn btn-primary  " type="submit">Submit</button>

            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

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


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#formTambah').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{ url('api/ruko/create') }}",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.message === 'check your validation') {
                        console.log(response)
                        let error = response.errors;
                        let errorMessage = "";
                      
                         alert('Ada form yang kosong tu');
                    }else{
                        alert('Suksess tambah ruko');
                        window.location.reload();
                    }
                  
                },  
                error: function (error) { 
                    console.log('Error' , error);
                 }
            });
        })
    </script>
</body>

</html>
