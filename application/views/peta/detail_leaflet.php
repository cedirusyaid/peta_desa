<div class="container mt-4">
    <!-- Header dengan Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('peta') ?>">Daftar Desa</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= html_escape($desa->desa_nama) ?></li>
        </ol>
    </nav>

    <!-- Card Header Desa -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <?= html_escape($desa->desa_nama) ?>
            </h3>
        </div>
        <div class="card-body">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="desaTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="peta-tab" data-toggle="tab" href="#peta" role="tab" aria-controls="peta" aria-selected="true">
                        <i class="fas fa-map mr-1"></i> Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lokasi-tab" data-toggle="tab" href="#lokasi" role="tab" aria-controls="lokasi" aria-selected="false">
                        <i class="fas fa-list-ul mr-1"></i> Daftar Lokasi
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content pt-3" id="desaTabContent">
                <!-- Tab Peta -->
                <div class="tab-pane fade show active" id="peta" role="tabpanel" aria-labelledby="peta-tab">
                    <!-- Filter Kategori -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kategoriFilter"><i class="fas fa-filter mr-1"></i> Filter Kategori:</label>
                                <select class="form-control select2" id="kategoriFilter" onchange="filterByKategori(this.value)">
                                    <option value="0" <?= ($selected_kategori == 0) ? 'selected' : '' ?>>Semua Kategori</option>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat->kategori_id ?>" <?= ($selected_kategori == $kat->kategori_id) ? 'selected' : '' ?>>
                                            <?php if(file_exists(FCPATH.'images/icons/'.$kat->kategori_icon)): ?>
                                                <img src="<?= base_url('images/icons/'.$kat->kategori_icon) ?>" width="20" height="20" class="mr-2">
                                            <?php endif; ?>
                                            <?= html_escape($kat->kategori_nama." (".$kat->jml.")") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle mr-2"></i> 
                                Gunakan kontrol layer di pojok kanan atas peta untuk menampilkan/menyembunyikan objek
                            </div>
                        </div>
                    </div>
                    
                    <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                </div>

                <!-- Tab Daftar Lokasi -->
                <div class="tab-pane fade" id="lokasi" role="tabpanel" aria-labelledby="lokasi-tab">
                    <div class="row">
                        <?php foreach ($lokasi as $loc): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header d-flex align-items-center" style="background-color: #f8f9fa;">
                                        <?php if(file_exists(FCPATH.'images/icons/'.$loc->kategori_icon)): ?>
                                            <img src="<?= base_url('images/icons/'.$loc->kategori_icon) ?>" width="32" height="32" alt="<?= html_escape($loc->kategori_nama) ?>" class="mr-2">
                                        <?php endif; ?>
                                        <h5 class="mb-0"><?= html_escape($loc->lokasi_nama) ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                            <?= html_escape($loc->lokasi_alamat) ?>
                                        </p>
                                        <?php if(!empty($loc->lokasi_ket)): ?>
                                            <p class="card-text">
                                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                                <?= html_escape($loc->lokasi_ket) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <button class="btn btn-sm btn-outline-primary btn-show-on-map" 
                                                data-lat="<?= floatval($loc->lokasi_lat) ?>" 
                                                data-lng="<?= floatval($loc->lokasi_long) ?>"
                                                data-kategori="<?= $loc->lokasi_kategori ?>">
                                            <i class="fas fa-map-marked-alt mr-1"></i> Tampilkan di Peta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS Script -->
<script>
    // Global variables
    var map;
    var markersLayer;
    var polygonLayer;
    var currentKategori = 'all';

    // Function to parse WKT polygon (Leaflet version)
    function parseWKTPolygon(wkt) {
        var start = wkt.indexOf('((') + 2;
        var end = wkt.indexOf('))');
        var coordsStr = wkt.substring(start, end);
        var coordPairs = coordsStr.split(',');
        var polygonCoords = [];
        
        coordPairs.forEach(function(pair) {
            var coords = pair.trim().split(' ');
            var lng = parseFloat(coords[0]);
            var lat = parseFloat(coords[1]);
            polygonCoords.push([lat, lng]);
        });
        
        return polygonCoords;
    }

    // Function to filter markers by category
    function filterMarkersByCategory(categoryId) {
        markersLayer.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                var markerCategory = layer.options.kategori_id;
                if (categoryId === '0' || markerCategory === categoryId) {
                    markersLayer.addLayer(layer);
                    
                    // Automatically open popup if only one marker is visible
                    var visibleMarkers = [];
                    markersLayer.eachLayer(function(m) {
                        if (m instanceof L.Marker) visibleMarkers.push(m);
                    });
                    if (visibleMarkers.length === 1 && visibleMarkers[0] === layer) {
                        layer.openPopup();
                    }
                } else {
                    markersLayer.removeLayer(layer);
                }
            }
        });
    }

    // Initialize Map
    document.addEventListener('DOMContentLoaded', function() {
        // Default center
        var center = [-5.1923857, 120.1096181];
        <?php if (!empty($lokasi)): ?>
            center = [<?= floatval($lokasi[0]->lokasi_lat) ?>, <?= floatval($lokasi[0]->lokasi_long) ?>];
        <?php endif; ?>
        
        // Create map
        map = L.map('map').setView(center, 14);
        
        // Add base tile layer (OpenStreetMap)
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Alternatif base layer
        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });
        
        // Layer groups untuk objek
        markersLayer = L.layerGroup();
        polygonLayer = L.layerGroup();
        
        // Add desa polygon if WKT data exists
        <?php if (!empty($desa->wkt)): ?>
            var polygonCoords = parseWKTPolygon('<?= $desa->wkt ?>');
            
            var desaPolygon = L.polygon(polygonCoords, {
                color: '#28a745',
                opacity: 0.8,
                weight: 2,
                fillColor: '#28a745',
                fillOpacity: 0.35
            }).addTo(polygonLayer);
            
            // Fit bounds to show the polygon fully
            map.fitBounds(desaPolygon.getBounds(), {padding: [50, 50]});
        <?php endif; ?>
        
        // Add markers
        <?php foreach ($lokasi as $loc): ?>
            var markerPosition = [<?= floatval($loc->lokasi_lat) ?>, <?= floatval($loc->lokasi_long) ?>];
            
            var customIcon = L.divIcon({
                html: '<?php if(file_exists(FCPATH.'images/icons/'.$loc->kategori_icon)): ?><img src="<?= base_url("images/icons/".$loc->kategori_icon) ?>" width="32" height="32"><?php else: ?><i class="fas fa-map-marker-alt" style="color: red; font-size: 24px;"></i><?php endif; ?>',
                className: 'custom-marker-icon',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });
            
            var marker = L.marker(markerPosition, {
                icon: customIcon,
                title: <?= json_encode($loc->lokasi_nama) ?>,
                kategori_id: '<?= $loc->lokasi_kategori ?>'
            }).addTo(markersLayer);
            
            // Popup content
            var popupContent = '<div class="map-info-window">' +
                     '<h6>' + <?= json_encode($loc->lokasi_nama) ?> + '</h6>' +
                     '<p class="mb-1"><i class="fas fa-map-marker-alt mr-1"></i>' + <?= json_encode($loc->lokasi_alamat) ?> + '</p>' +
                     '<p class="mb-1"><i class="fas fa-tag mr-1"></i>' + <?= json_encode($loc->kategori_nama) ?> + '</p>' +
                     <?php if(!empty($loc->lokasi_ket)): ?>
                     '<p class="mb-0"><i class="fas fa-info-circle mr-1"></i>' + <?= json_encode($loc->lokasi_ket) ?> + '</p>' +
                     <?php endif; ?>
                     '</div>';
            
            marker.bindPopup(popupContent);
        <?php endforeach; ?>
        
        // Definisikan base layers dan overlay layers
        var baseLayers = {
            "OpenStreetMap": osmLayer,
            "Citra Satelit": satelliteLayer
        };
        
        var overlayLayers = {
            "Batas Desa": polygonLayer,
            "Lokasi": markersLayer
        };
        
        // Tambahkan kontrol layer
        L.control.layers(baseLayers, overlayLayers, {
            collapsed: false,
            position: 'topright'
        }).addTo(map);
        
        // Aktifkan semua overlay secara default
        polygonLayer.addTo(map);
        markersLayer.addTo(map);
        
        // Jika tidak ada polygon, fit bounds ke markers
        <?php if (empty($desa->wkt)): ?>
            var bounds = new L.LatLngBounds();
            markersLayer.eachLayer(function(layer) {
                bounds.extend(layer.getLatLng());
            });
            if (!bounds.isEmpty()) {
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        <?php endif; ?>
        
        // Button to show on map
        document.querySelectorAll('.btn-show-on-map').forEach(function(button) {
            button.addEventListener('click', function() {
                // Switch to map tab
                $('#desaTab a[href="#peta"]').tab('show');
                
                // Filter by category
                var kategori = button.getAttribute('data-kategori');
                $('#kategoriFilter').val(kategori).trigger('change');
                
                // Pan to marker
                var lat = parseFloat(button.getAttribute('data-lat'));
                var lng = parseFloat(button.getAttribute('data-lng'));
                map.setView([lat, lng], 16);
                
                // Cari marker yang sesuai dan buka popup
                markersLayer.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        var markerPos = layer.getLatLng();
                        if (markerPos.lat === lat && markerPos.lng === lng) {
                            layer.openPopup();
                        }
                    }
                });
            });
        });
        
        // Initialize Select2 for kategori filter
        $('.select2').select2({
            placeholder: "Pilih kategori",
            allowClear: true,
            templateResult: formatKategoriOption,
            templateSelection: formatKategoriSelection
        });
        
        // Filter markers when kategori changes
        $('#kategoriFilter').on('change', function() {
            currentKategori = $(this).val();
            // Untuk filter client-side saja (tanpa reload)
            filterMarkersByCategory(currentKategori);
            
            // Untuk filter dengan reload (server-side)
            // filterByKategori(currentKategori);
        });
        // Enable Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
    
    // Format options for Select2 dropdown
    function formatKategoriOption(option) {
        if (!option.id) return option.text;
        
        var $option = $(
            '<span>' + 
            '<img src="' + $(option.element).data('icon') + '" class="img-fluid mr-2" width="20">' + 
            option.text + 
            '</span>'
        );
        return $option;
    }
    
    function formatKategoriSelection(option) {
        if (!option.id) return option.text;
        
        var $option = $(
            '<span>' + 
            '<img src="' + $(option.element).data('icon') + '" class="img-fluid mr-2" width="20">' + 
            option.text + 
            '</span>'
        );
        return $option;
    }

    
</script>

<!-- CSS -->
<style>
    /* Leaflet-specific styles */
    #map {
        z-index: 0;
    }
    
    .custom-marker-icon {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .leaflet-popup-content {
        margin: 12px;
    }
    
    .map-info-window {
        font-size: 14px;
        min-width: 200px;
    }
    .map-info-window h6 {
        color: #0d6efd;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .map-info-window i {
        width: 16px;
        text-align: center;
        margin-right: 4px;
    }
    
    /* Layer control styles */
    .leaflet-control-layers {
        background: white;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        padding: 10px;
    }
    .leaflet-control-layers-toggle {
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23333"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>');
        width: 30px;
        height: 30px;
    }
    .leaflet-control-layers label {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .leaflet-control-layers input {
        margin-right: 8px;
    }
    
    /* General styles */
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2 img {
        vertical-align: middle;
    }
</style>

<!-- Tambahkan di template/header.php -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>