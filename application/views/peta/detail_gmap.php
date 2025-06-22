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
                                <select class="form-control select2" id="kategoriFilter">
                                    <option value="all">Semua Kategori</option>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat->kategori_id ?>">
                                            <?php if(file_exists(FCPATH.'images/icons/'.$kat->kategori_icon)): ?>
                                                <img src="<?= base_url('images/icons/'.$kat->kategori_icon) ?>" width="20" height="20" class="mr-2">
                                            <?php endif; ?>
                                            <?= html_escape($kat->kategori_nama."(".$kat->jml.")") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle mr-2"></i> 
                                Area berwarna hijau menunjukkan batas wilayah <?= html_escape($desa->desa_nama) ?>
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

<!-- Google Maps Script -->
<script>
    // Global variables
    var map;
    var markers = [];
    var infoWindows = [];
    var desaPolygon;
    var currentKategori = 'all';

    // Function to parse WKT polygon
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
            polygonCoords.push({lat: lat, lng: lng});
        });
        
        return polygonCoords;
    }

    // Function to filter markers by category
    function filterMarkersByCategory(categoryId) {
        markers.forEach(function(marker, index) {
            var markerCategory = marker.get('kategori_id');
            if (categoryId === 'all' || markerCategory === categoryId) {
                marker.setVisible(true);
                
                // Automatically show info window if only one marker is visible
                var visibleMarkers = markers.filter(m => m.getVisible());
                if (visibleMarkers.length === 1 && visibleMarkers[0] === marker) {
                    infoWindows[index].open(map, marker);
                }
            } else {
                marker.setVisible(false);
                infoWindows[index].close();
            }
        });
        
        // Adjust map view to show all visible markers
        var bounds = new google.maps.LatLngBounds();
        markers.forEach(function(marker) {
            if (marker.getVisible()) {
                bounds.extend(marker.getPosition());
            }
        });
        
        // Also include polygon bounds if exists
        if (desaPolygon) {
            desaPolygon.getPath().forEach(function(vertex) {
                bounds.extend(vertex);
            });
        }
        
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds);
        }
    }

    // Initialize Map
    window.initMap = function() {
        // Default center
        var center = {lat: -5.1923857, lng: 120.1096181};
        <?php if (!empty($lokasi)): ?>
            center = {lat: <?= floatval($lokasi[0]->lokasi_lat) ?>, lng: <?= floatval($lokasi[0]->lokasi_long) ?>};
        <?php endif; ?>
        
        // Create map
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: center,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });
        
        // Add desa polygon if WKT data exists
        <?php if (!empty($desa->wkt)): ?>
            var polygonCoords = parseWKTPolygon('<?= $desa->wkt ?>');
            
            desaPolygon = new google.maps.Polygon({
                paths: polygonCoords,
                strokeColor: '#28a745',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#28a745',
                fillOpacity: 0.35,
                map: map
            });
        <?php endif; ?>
        
        // Add markers
        <?php foreach ($lokasi as $loc): ?>
            var markerPosition = {lat: <?= floatval($loc->lokasi_lat) ?>, lng: <?= floatval($loc->lokasi_long) ?>};
            
            var marker = new google.maps.Marker({
                position: markerPosition,
                map: map,
                title: <?= json_encode($loc->lokasi_nama) ?>,
                <?php if(file_exists(FCPATH.'images/icons/'.$loc->kategori_icon)): ?>
                    icon: '<?= base_url("images/icons/".$loc->kategori_icon) ?>'
                <?php endif; ?>
            });
            
            // Store category ID with marker
            marker.set('kategori_id', '<?= $loc->lokasi_kategori ?>');
            
            // Store marker reference
            markers.push(marker);
            
            // Info window
            var infoWindow = new google.maps.InfoWindow({
                content: '<div class="map-info-window">' +
                         '<h6>' + <?= json_encode($loc->lokasi_nama) ?> + '</h6>' +
                         '<p class="mb-1"><i class="fas fa-map-marker-alt mr-1"></i>' + <?= json_encode($loc->lokasi_alamat) ?> + '</p>' +
                         '<p class="mb-1"><i class="fas fa-tag mr-1"></i>' + <?= json_encode($loc->kategori_nama) ?> + '</p>' +
                         <?php if(!empty($loc->lokasi_ket)): ?>
                         '<p class="mb-0"><i class="fas fa-info-circle mr-1"></i>' + <?= json_encode($loc->lokasi_ket) ?> + '</p>' +
                         <?php endif; ?>
                         '</div>'
            });
            
            infoWindows.push(infoWindow);
            
            marker.addListener('click', function() {
                // Close all info windows first
                infoWindows.forEach(function(window) {
                    window.close();
                });
                infoWindow.open(map, marker);
            });
        <?php endforeach; ?>
        
        // Fit bounds to show all markers and polygon
        var bounds = new google.maps.LatLngBounds();
        markers.forEach(function(marker) {
            bounds.extend(marker.getPosition());
        });
        <?php if (!empty($desa->wkt)): ?>
            polygonCoords.forEach(function(coord) {
                bounds.extend(new google.maps.LatLng(coord.lat, coord.lng));
            });
        <?php endif; ?>
        map.fitBounds(bounds);
        
        // Button to show on map
        document.querySelectorAll('.btn-show-on-map').forEach(function(button, index) {
            button.addEventListener('click', function() {
                // Switch to map tab
                $('#desaTab a[href="#peta"]').tab('show');
                
                // Filter by category
                var kategori = button.getAttribute('data-kategori');
                $('#kategoriFilter').val(kategori).trigger('change');
                
                // Pan to marker
                map.panTo(markers[index].getPosition());
                map.setZoom(16);
                
                // Open info window
                infoWindows.forEach(function(window) {
                    window.close();
                });
                infoWindows[index].open(map, markers[index]);
            });
        });
    }

    // Load Google Maps API
    function loadGoogleMaps() {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=<?= $google_maps_api_key ?>&callback=initMap&libraries=geometry';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
    
    // Load when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        loadGoogleMaps();
        
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
            filterMarkersByCategory(currentKategori);
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />