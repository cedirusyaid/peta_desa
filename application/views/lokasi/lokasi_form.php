<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($lokasi) ? 'Edit' : 'Tambah' ?> Lokasi GIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .coordinates {
            background: #f8f9fa;
            padding: 5px;
            border-radius: 3px;
            margin-top: 5px;
            font-family: monospace;
        }
        .btn-geolocation {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            padding: 6px 8px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4"><?= isset($lokasi) ? 'Edit' : 'Tambah' ?> Lokasi GIS</h1>
        
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger"><?= validation_errors() ?></div>
        <?php endif; ?>
        
        <form action="<?= isset($lokasi) ? site_url('lokasi/update/'.$lokasi->lokasi_id) : site_url('lokasi/store') ?>" method="post">
            <div class="mb-3">
                <label for="lokasi_nama" class="form-label">Nama Lokasi</label>
                <input type="text" class="form-control" id="lokasi_nama" name="lokasi_nama" value="<?= isset($lokasi) ? $lokasi->lokasi_nama : set_value('lokasi_nama') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="lokasi_kategori" class="form-label">Kategori</label>
                <select class="form-select" id="lokasi_kategori" name="lokasi_kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach($kategori_options as $kategori): ?>
                        <option value="<?= $kategori->kategori_id ?>" <?= (isset($lokasi) && $lokasi->lokasi_kategori == $kategori->kategori_id) ? 'selected' : '' ?>>
                            <?= $kategori->kategori_nama ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="lokasi_alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="lokasi_alamat" name="lokasi_alamat" required><?= isset($lokasi) ? $lokasi->lokasi_alamat : set_value('lokasi_alamat') ?></textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lokasi_lat" class="form-label">Latitude</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="lokasi_lat" name="lokasi_lat" value="<?= isset($lokasi) ? $lokasi->lokasi_lat : set_value('lokasi_lat') ?>" required readonly>
                        <button type="button" class="btn btn-outline-secondary" onclick="getCurrentLocation()">
                            <i class="bi bi-geo-alt"></i> Lokasi Saya
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="lokasi_long" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="lokasi_long" name="lokasi_long" value="<?= isset($lokasi) ? $lokasi->lokasi_long : set_value('lokasi_long') ?>" required readonly>
                </div>
            </div>
            
            <div class="mb-3 position-relative">
                <label class="form-label">Pilih Lokasi di Peta</label>
                <div id="map"></div>
                <button type="button" class="btn-geolocation" onclick="getCurrentLocation()" title="Dapatkan lokasi saya">
                    <i class="bi bi-geo-alt-fill"></i>
                </button>
                <div class="coordinates mt-2">
                    Klik pada peta untuk menentukan koordinat atau geser marker
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('lokasi') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Variabel global untuk map dan marker
        let map, marker;
        let defaultLat = <?= isset($lokasi) ? $lokasi->lokasi_lat : '-5.1619' ?>;
        let defaultLng = <?= isset($lokasi) ? $lokasi->lokasi_long : '119.4387' ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            initMap();
        });

        function initMap() {
            map = L.map('map').setView([defaultLat, defaultLng], 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Add a marker
            marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);
            
            // Update coordinates when marker is moved
            marker.on('dragend', function(e) {
                const latLng = marker.getLatLng();
                updateCoordinates(latLng.lat, latLng.lng);
            });
            
            // Update marker position and coordinates when clicking on the map
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });
            
            // Initialize coordinates display
            updateCoordinates(defaultLat, defaultLng);
        }
        
        // Function to update coordinate fields and display
        function updateCoordinates(lat, lng) {
            document.getElementById('lokasi_lat').value = lat;
            document.getElementById('lokasi_long').value = lng;
            document.querySelector('.coordinates').textContent = `Koordinat terpilih: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }
        
        // Function to get current geolocation
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Update map view
                        map.setView([lat, lng], 15);
                        
                        // Update marker position
                        marker.setLatLng([lat, lng]);
                        
                        // Update form fields
                        updateCoordinates(lat, lng);
                        
                        // Show success message
                        alert('Lokasi Anda berhasil ditemukan!');
                    },
                    function(error) {
                        let errorMessage;
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Pengguna menolak permintaan geolokasi.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Informasi lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "Permintaan untuk mendapatkan lokasi pengguna habis waktunya.";
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                                break;
                        }
                        alert("Error: " + errorMessage);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolokasi tidak didukung oleh browser ini.");
            }
        }
    </script>
</body>
</html>