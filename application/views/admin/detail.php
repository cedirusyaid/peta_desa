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

<div class="container mt-4">
    <div class="mb-3 row   ">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h1 class="mb-4 "> <?= $lokasi->lokasi_nama ?></h1>
        <div>
            <a href="<?= base_url('admin/index?desa_id='.$lokasi->desa_id) ?>" class="btn btn-sm btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
<?php //print_r($lokasi);?>             
                <div class="card">
                    <div class="card-header  d-flex justify-content-between align-items-center">
                        <h4>Data Lokasi</h4>
                        <div>
                            <a href="<?= base_url('admin/edit/'.$lokasi->lokasi_id) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                <div class="mb-3 row">
                    <label for="lokasi_nama" class="form-label  col-md-4">Nama Lokasi</label>
                    <input type="text" class="form-control col-md-8" id="lokasi_nama" name="lokasi_nama" value="<?= $lokasi->lokasi_nama?>" disabled>
                </div>
                
                <div class="mb-3 row">
                    <label for="lokasi_kategori" class="form-label col-md-4">Kategori</label>
                    <input type="text" class="form-control col-md-8" value="<?= $lokasi->kategori_nama?>" disabled>
                </div>
     
                <div class="mb-3 row">
                    <label for="desa_id" class="form-label col-md-4">Desa</label>
                    <input type="text" class="form-control col-md-8" value="<?= $lokasi->desa_nama?>" disabled>
                </div>
                
                <div class="mb-3 row">
                    <label for="lokasi_alamat" class="form-label col-md-4">Alamat</label>
                    <textarea class="form-control col-md-8" disabled><?= $lokasi->lokasi_alamat ?></textarea>
                </div>

                <div class="mb-3 row">
                    <label for="koordinat" class="form-label col-md-4">Koordinat</label>
                    <input class="form-control col-md-8" disabled value=" <?= $lokasi->lokasi_lat ?>,<?= $lokasi->lokasi_long ?>">
                </div>
                
                
                <div class="mb-3 position-relative">
                    <div id="map"></div>
                    <button type="button" class="btn-geolocation" onclick="getCurrentLocation()" title="Dapatkan lokasi saya">
                        <i class="bi bi-geo-alt-fill"></i>
                    </button>
                </div>
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="">Foto</h4>
                        <!-- Button to trigger modal - moved to header -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadModal">
                            <i class="bi bi-cloud-arrow-up"></i> Upload Foto Baru
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Upload Modal -->
                        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="uploadModalLabel">
                                            <i class="bi bi-cloud-arrow-up"></i> Upload Foto Baru
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?= site_url('admin/foto_upload/' . $lokasi->lokasi_id) ?>" method="post" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="foto_file" class="form-label">Pilih File Foto</label>
                                                <input class="form-control" type="file" id="foto_file" name="foto_file" required>
                                                <div class="form-text">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-upload"></i> Upload
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                       

                        <?php if(empty($fotos)): ?>
                            <div class="alert alert-info">Belum ada foto untuk lokasi ini.</div>
                        <?php else: ?>
<div class="row">
    <?php foreach($fotos as $foto): ?>
        <div class="col-md-6">
            <div class="card photo-card">
                <div class="photo-container">
                    <a href="<?= base_url('uploads/foto_lokasi/' . $foto->foto_file) ?>" target="_blank" class="photo-link">
                        <img src="<?= base_url('uploads/foto_lokasi/' . $foto->foto_file) ?>" class="card-img-top photo-thumbnail" alt="Foto Lokasi">
                    </a>
                    <a href="<?= site_url('admin/foto_delete/' . $foto->foto_lokasi_id . '/' . $lokasi->lokasi_id) ?>" class="btn btn-sm btn-danger delete-btn" onclick="return confirm('Yakin ingin menghapus foto ini?')" title="hapus">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
                <div class="card-footer text-muted small">
                    Uploaded: <?= date('d M Y H:i', filemtime(FCPATH . 'uploads/foto_lokasi/' . $foto->foto_file)) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .photo-container {
        position: relative;
    }
    .photo-link {
        display: block;
    }
    .photo-link:hover {
        opacity: 0.9;
    }
    .delete-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.8;
    }
    .delete-btn:hover {
        opacity: 1;
    }
</style>
                        <?php endif; ?>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
