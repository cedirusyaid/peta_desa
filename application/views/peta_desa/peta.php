<?php
// Get parameters from URL
$kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : null;
$notitle = isset($_GET['notitle']) ? $_GET['notitle'] : 0;
$nomargin = isset($_GET['nomargin']) ? $_GET['nomargin'] : 0;

// Determine container class based on nomargin parameter
$containerClass = $nomargin == 1 ? 'container-fluid' : '';
?>

<div class="<?php echo $containerClass; ?>">
    <?php if ($notitle != 1): ?>
    <h2>PETA DESA <?php echo $desa->desa_nama; ?></h2>
    <?php endif; ?>
    
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="desaTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="peta-tab" data-toggle="tab" href="#peta" role="tab" aria-controls="peta" aria-selected="true">Peta</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="false">Data</a>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="desaTabsContent">
        <!-- Tab Peta -->
        <div class="tab-pane fade show active" id="peta" role="tabpanel" aria-labelledby="peta-tab">
            <div id="map" style="height: 600px; width: 100%; margin-top: 15px;"></div>
        </div>
        
        <!-- Tab Data -->
        <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
            <div style="margin-top: 20px;">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Lokasi</th>
                                <th>Kategori</th>
                                <th>Alamat</th>
                                <th>Koordinat</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lokasi_list as $index => $lokasi): ?>
                            <?php if ($kategori_id==null or $kategori_id==$lokasi->kategori_id): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><a href="<?php echo base_url('/peta_desa/detail/').$lokasi->lokasi_id; ?>"> <?php echo $lokasi->lokasi_nama; ?></a></td>
                                <td><img src="<?php echo base_url("images/icons/".$lokasi->kategori_icon); ?>"> <?php echo $lokasi->kategori_nama; ?></td>
                                <td><?php echo $lokasi->lokasi_alamat; ?></td>
                                <td><?php echo $lokasi->lokasi_lat . ', ' . $lokasi->lokasi_long; ?></td>
                                <td><?php echo $lokasi->lokasi_keterangan ?? '-'; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan CSS Bootstrap jika belum ada -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Tambahkan Select2 untuk dropdown yang lebih baik -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Tambahkan Leaflet Fullscreen CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />

<!-- Tambahkan JS Bootstrap dan dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<!-- Tambahkan Leaflet Fullscreen JS -->
<script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>

<script>
    // Fungsi untuk parsing WKT Polygon
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

    // Fungsi untuk mendapatkan centroid dari polygon
    function getPolygonCenter(coords) {
        var x = 0, y = 0, z = 0;
        var totalPoints = coords.length;
        
        coords.forEach(function(coord) {
            var lat = coord[0] * Math.PI / 180;
            var lng = coord[1] * Math.PI / 180;
            
            x += Math.cos(lat) * Math.cos(lng);
            y += Math.cos(lat) * Math.sin(lng);
            z += Math.sin(lat);
        });
        
        x = x / totalPoints;
        y = y / totalPoints;
        z = z / totalPoints;
        
        var centralLng = Math.atan2(y, x);
        var centralLat = Math.atan2(z, Math.sqrt(x * x + y * y));
        
        return [centralLat * 180 / Math.PI, centralLng * 180 / Math.PI];
    }

    // Inisialisasi peta
    var map = L.map('map').setView([-5.18, 120.1], 13);
    
    // Tambahkan kontrol fullscreen
    map.addControl(new L.Control.FullScreen({
        position: 'topleft',
        title: 'Tampilkan layar penuh',
        titleCancel: 'Keluar dari layar penuh',
        forceSeparateButton: true
    }));
    
    // Layer peta dasar
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });
    
    var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });
    
    osmLayer.addTo(map);
    
    // Parsing WKT dan membuat layer batas wilayah desa
    var desaPolygon = parseWKTPolygon('<?php echo $desa->wkt; ?>');
    var desaBoundary = L.polygon(desaPolygon, {
        color: '#ff7800',
        weight: 3,
        opacity: 0.65,
        fillOpacity: 0.2,
        fillColor: '#ff7800'
    }).addTo(map);
    
    // Layer untuk label nama desa
    var desaLabel = L.layerGroup();
    
    // Buat label untuk nama desa
    var desaCenter = getPolygonCenter(desaPolygon);
    var desaNameLabel = L.marker(desaCenter, {
        icon: L.divIcon({
            className: 'desa-label',
            html: '<div style="color: black; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff; font-weight: bold; font-size: 16px;"><?php echo addslashes(preg_replace('/\s+/', ' ', trim($desa->desa_nama ?? 'Desa'))); ?></div>',
            iconSize: [150, 40],
            iconAnchor: [75, 20]
        }),
        interactive: false
    }).addTo(desaLabel);
    
    // Layer untuk batas dusun dengan warna berbeda
    var dusunBoundaries = L.layerGroup();
    var dusunLabels = L.layerGroup();
    var colorPalette = [
        '#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33F3',
        '#33FFF3', '#8A2BE2', '#FF7F50', '#7FFF00', '#D2691E',
        '#20B2AA', '#9370DB', '#32CD32', '#FF4500', '#4B0082'
    ];
    
    <?php foreach ($dusun_list as $index => $dusun): ?>
        var dusunPolygon = parseWKTPolygon('<?php echo $dusun->wkt; ?>');
        var colorIndex = <?php echo $index; ?> % colorPalette.length;
        var fillColor = colorPalette[colorIndex];
        
        var polygon = L.polygon(dusunPolygon, {
            color: fillColor,
            weight: 2,
            opacity: 0.8,
            fillOpacity: 0.4,
            fillColor: fillColor
        }).addTo(dusunBoundaries);
        
        polygon.bindPopup("<b><?php echo addslashes(preg_replace('/\s+/', ' ', trim($dusun->dusun_nama ?? 'Desa'))); ?></b>");
        
        var center = getPolygonCenter(dusunPolygon);
        
        var label = L.marker(center, {
            icon: L.divIcon({
                className: 'dusun-label',
                html: '<div style="color: black; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff; font-weight: bold; font-size: 14px;"><?php echo $dusun->dusun_nama; ?></div>',
                iconSize: [100, 40],
                iconAnchor: [50, 20]
            }),
            interactive: false
        }).addTo(dusunLabels);
        
        polygon.dusunName = '<?php echo $dusun->dusun_nama; ?>';
        polygon.dusunColor = fillColor;
    <?php endforeach; ?>
    
    // Layer lokasi dengan custom icon
    var lokasiLayer = L.layerGroup();
    var allMarkers = []; // Simpan semua marker untuk filtering
    
    <?php foreach ($lokasi_list as $lokasi): ?>
        // Buat custom icon untuk lokasi
        var iconSize = [20, 20]; // Ukuran icon 16x16 pixel (50% dari 32x32)
        var customIcon = L.icon({
            iconUrl: '<?php echo base_url("images/icons/".$lokasi->kategori_icon); ?>',
            iconSize: iconSize,
            iconAnchor: [iconSize[0]/2, iconSize[1]], // Anchor di tengah bawah
            popupAnchor: [0, -iconSize[1]] // Popup di atas icon
        });
        
        var marker = L.marker(
            [<?php echo $lokasi->lokasi_lat; ?>, <?php echo $lokasi->lokasi_long; ?>],
            {
                icon: customIcon,
                categoryId: <?php echo $lokasi->kategori_id; ?> // Simpan kategori_id di marker
            }
        ).addTo(lokasiLayer);
        
        marker.bindPopup(
            "<img src='<?php echo base_url("images/icons/".$lokasi->kategori_icon); ?>'> <b><?php echo $lokasi->lokasi_nama; ?></b><br>" +
            "Kategori : <?php echo $lokasi->kategori_nama; ?><br>" +
            "<?php echo $lokasi->lokasi_alamat; ?><br>" +
            "<?php if(!empty($lokasi->lokasi_keterangan)): ?>" +
            "<br><?php echo $lokasi->lokasi_keterangan; ?><?php endif; ?>" +
            "<br><a target=_blank href=\"https://maps.google.com/maps?q=<?php echo $lokasi->lokasi_nama; ?>@<?php echo $lokasi->lokasi_lat; ?>,<?php echo $lokasi->lokasi_long; ?>&z=17 \">Lihat di peta google</a>" +
            "<br><a href=\"<?php echo base_url('/peta_desa/detail/').$lokasi->lokasi_id; ?>\">Lihat detail</a>"
        );
        
        allMarkers.push(marker); // Tambahkan ke array allMarkers
    <?php endforeach; ?>
    
    lokasiLayer.addTo(map);

    // Membuat kontrol custom dengan tombol minimize
    var ControlPanel = L.Control.extend({
        options: {
            position: 'topright'
        },
        
        onAdd: function(map) {
            var container = L.DomUtil.create('div', 'leaflet-control-transparent');
            
            // Buat tombol toggle untuk mobile
            this._toggleButton = L.DomUtil.create('button', 'control-panel-toggle', container);
            this._toggleButton.innerHTML = '☰ Pengaturan';
            this._toggleButton.style.cssText = `
                position: absolute;
                right: 0;
                top: 0;
                z-index: 1000;
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 6px 10px;
                cursor: pointer;
                font-size: 13px;
                box-shadow: 0 0 10px rgba(0,0,0,0.2);
            `;
            
            this._panel = L.DomUtil.create('div', 'map-control-panel', container);
            this._panel.innerHTML = `
                <div class="panel-header">
                    <h4>Pengaturan Peta</h4>
                    <button class="close-panel-btn">&times;</button>
                </div>
                <div class="panel-content">
                    <div class="control-group">
                        <label>Peta Dasar:</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="baseMap" value="osm" checked> OSM
                            </label>
                            <label>
                                <input type="radio" name="baseMap" value="satellite"> Satelit
                            </label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label>Batas Wilayah:</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="boundaryType" value="desa" checked> Desa
                            </label>
                            <label>
                                <input type="radio" name="boundaryType" value="dusun"> Dusun
                            </label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="showLocations" checked>
                            <span>Tampilkan Lokasi</span>
                        </label>
                    </div>
                    
                    <div class="control-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="showLabels" checked>
                            <span>Tampilkan Label Wilayah</span>
                        </label>
                    </div>
                    
                    <?php if (!$kategori_id): ?>
                    <div class="control-group">
                        <label>Filter Kategori:</label>
                        <select id="categoryFilter" multiple="multiple" style="width: 100%;">
                            <option value="all" selected>Semua Kategori</option>
                            <?php foreach ($kategori_list as $kategori): ?>
                                <option value="<?php echo $kategori->kategori_id; ?>"><?php echo $kategori->kategori_nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="control-group" id="dusunLegend" style="display: none;">
                        <label>Legenda Dusun:</label>
                        <div id="legendItems" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>
                </div>
            `;
            
            // Sembunyikan panel secara default di mobile
            if (window.innerWidth <= 768) {
                this._panel.style.display = 'none';
                this._toggleButton.style.display = 'block';
            } else {
                this._panel.style.display = 'block';
                this._toggleButton.style.display = 'none';
            }
            
            // Event untuk tombol toggle
            L.DomEvent.on(this._toggleButton, 'click', function() {
                if (this._panel.style.display === 'none') {
                    this._panel.style.display = 'block';
                } else {
                    this._panel.style.display = 'none';
                }
            }, this);
            
            // Event untuk tombol close
            var closeBtn = this._panel.querySelector('.close-panel-btn');
            L.DomEvent.on(closeBtn, 'click', function() {
                this._panel.style.display = 'none';
            }, this);
            
            L.DomEvent.disableClickPropagation(this._panel);
            
            return container;
        }
    });

    // Tambahkan kontrol ke peta
    var controlPanel = new ControlPanel();
    map.addControl(controlPanel);
    
    // Fungsi untuk memperbarui legenda dusun
    function updateLegend() {
        var legendContainer = document.getElementById('legendItems');
        legendContainer.innerHTML = '';
        
        dusunBoundaries.eachLayer(function(layer) {
            if (layer.dusunName && layer.dusunColor) {
                var legendItem = document.createElement('div');
                legendItem.style.display = 'flex';
                legendItem.style.alignItems = 'center';
                legendItem.style.margin = '3px 0';
                legendItem.style.cursor = 'pointer';
                
                var colorBox = document.createElement('div');
                colorBox.style.width = '15px';
                colorBox.style.height = '15px';
                colorBox.style.backgroundColor = layer.dusunColor;
                colorBox.style.marginRight = '5px';
                colorBox.style.border = '1px solid #333';
                
                var nameSpan = document.createElement('span');
                nameSpan.textContent = layer.dusunName;
                nameSpan.style.fontSize = '12px';
                
                legendItem.onclick = function() {
                    if (map.hasLayer(layer)) {
                        layer.remove();
                        legendItem.style.opacity = '0.5';
                    } else {
                        layer.addTo(dusunBoundaries);
                        legendItem.style.opacity = '1';
                    }
                };
                
                legendItem.appendChild(colorBox);
                legendItem.appendChild(nameSpan);
                legendContainer.appendChild(legendItem);
            }
        });
    }
    
    // Fungsi untuk filter marker berdasarkan kategori
    function filterMarkersByCategory() {
        <?php if ($kategori_id): ?>
        // Jika kategori_id ada di URL, filter berdasarkan kategori tersebut
        lokasiLayer.clearLayers();
        allMarkers.forEach(function(marker) {
            if (marker.options.categoryId == <?php echo $kategori_id; ?>) {
                lokasiLayer.addLayer(marker);
            }
        });
        <?php else: ?>
        // Jika tidak ada kategori_id, gunakan filter dari select box
        var selectedCategories = $('#categoryFilter').val();
        lokasiLayer.clearLayers();
        
        // Jika 'all' dipilih atau tidak ada yang dipilih, tampilkan semua
        if (selectedCategories === null || selectedCategories.includes('all')) {
            allMarkers.forEach(function(marker) {
                lokasiLayer.addLayer(marker);
            });
            return;
        }
        
        // Filter marker berdasarkan kategori yang dipilih
        allMarkers.forEach(function(marker) {
            if (selectedCategories.includes(marker.options.categoryId.toString())) {
                lokasiLayer.addLayer(marker);
            }
        });
        <?php endif; ?>
    }
    
    // Fungsi untuk memperbarui peta berdasarkan pengaturan
    function updateMap() {
        var baseMapType = document.querySelector('input[name="baseMap"]:checked').value;
        var boundaryType = document.querySelector('input[name="boundaryType"]:checked').value;
        var showLocations = document.getElementById('showLocations').checked;
        var showLabels = document.getElementById('showLabels').checked;
        
        if (baseMapType === 'osm') {
            map.removeLayer(satelliteLayer);
            osmLayer.addTo(map);
        } else {
            map.removeLayer(osmLayer);
            satelliteLayer.addTo(map);
        }
        
        if (boundaryType === 'desa') {
            map.removeLayer(dusunBoundaries);
            map.removeLayer(dusunLabels);
            desaBoundary.addTo(map);
            
            if (showLabels) {
                desaLabel.addTo(map);
            } else {
                map.removeLayer(desaLabel);
            }
            
            document.getElementById('dusunLegend').style.display = 'none';
        } else {
            map.removeLayer(desaBoundary);
            map.removeLayer(desaLabel);
            dusunBoundaries.addTo(map);
            
            if (showLabels) {
                dusunLabels.addTo(map);
            } else {
                map.removeLayer(dusunLabels);
            }
            
            document.getElementById('dusunLegend').style.display = 'block';
            updateLegend();
        }
        
        if (showLocations) {
            filterMarkersByCategory();
            if (!map.hasLayer(lokasiLayer)) {
                lokasiLayer.addTo(map);
            }
        } else {
            if (map.hasLayer(lokasiLayer)) {
                map.removeLayer(lokasiLayer);
            }
        }
    }
    
    $(document).ready(function() {
        <?php if (!$kategori_id): ?>
        // Inisialisasi Select2 hanya jika tidak ada kategori_id di URL
        $('#categoryFilter').select2({
            placeholder: "Pilih kategori",
            allowClear: true,
            width: 'resolve'
        });
        
        // Event listener untuk perubahan filter kategori
        $('#categoryFilter').on('change', function() {
            // Jika 'all' dipilih, unselect yang lain
            if ($(this).val() && $(this).val().includes('all')) {
                $(this).val(['all']).trigger('change');
            }
            updateMap();
        });
        <?php endif; ?>
        
        var baseMapRadios = document.querySelectorAll('input[name="baseMap"]');
        var boundaryRadios = document.querySelectorAll('input[name="boundaryType"]');
        var showLocations = document.getElementById('showLocations');
        var showLabels = document.getElementById('showLabels');
        
        baseMapRadios.forEach(function(radio) {
            radio.addEventListener('change', updateMap);
        });
        
        boundaryRadios.forEach(function(radio) {
            radio.addEventListener('change', updateMap);
        });
        
        showLocations.addEventListener('change', updateMap);
        showLabels.addEventListener('change', updateMap);
        
        // Jika ada kategori_id di URL, langsung filter marker
        <?php if ($kategori_id): ?>
        updateMap();
        <?php endif; ?>
    });
    
    map.fitBounds(desaBoundary.getBounds());
    
    // Event handler untuk fullscreen
    map.on('enterFullscreen', function() {
        console.log('Peta masuk mode fullscreen');
    });
    
    map.on('exitFullscreen', function() {
        console.log('Peta keluar dari mode fullscreen');
    });
    
    // Responsive design untuk panel kontrol
    window.addEventListener('resize', function() {
        var toggleButton = document.querySelector('.control-panel-toggle');
        var panel = document.querySelector('.map-control-panel');
        
        if (window.innerWidth <= 768) {
            toggleButton.style.display = 'block';
            panel.style.display = 'none';
        } else {
            toggleButton.style.display = 'none';
            panel.style.display = 'block';
        }
    });
</script>

<style>
    .leaflet-control-transparent {
        background: none !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    .map-control-panel {
        background: rgba(255, 255, 255, 0.95);
        padding: 12px;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        font-family: Arial, sans-serif;
        min-width: 220px;
        border: 1px solid #ddd;
        z-index: 1000;
        position: relative;
    }
    
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }
    
    .panel-header h4 {
        margin: 0;
        font-size: 15px;
        color: #333;
    }
    
    .close-panel-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #777;
        padding: 0 5px;
        line-height: 1;
    }
    
    .close-panel-btn:hover {
        color: #333;
    }
    
    .control-group {
        margin-bottom: 12px;
    }
    
    .control-group label {
        display: block;
        font-size: 13px;
        color: #555;
        margin-bottom: 6px;
        font-weight: bold;
    }
    
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .radio-group label {
        display: flex;
        align-items: center;
        font-size: 13px;
        cursor: pointer;
        margin: 0;
        font-weight: normal;
    }
    
    .radio-group input[type="radio"] {
        margin-right: 6px;
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin: 0;
        font-size: 13px;
    }
    
    .checkbox-label input {
        margin-right: 6px;
    }
    
    .leaflet-control-transparent .map-control-panel:hover {
        background: rgba(255, 255, 255, 0.95);
    }
    
    #legendItems::-webkit-scrollbar {
        width: 5px;
    }
    
    #legendItems::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #legendItems::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    #legendItems::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .desa-label, .dusun-label {
        background: transparent !important;
        border: none !important;
    }
    
    .desa-label div {
        white-space: nowrap;
        pointer-events: none;
        color: black !important;
        text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white !important;
        font-weight: bold !important;
        font-size: 16px !important;
    }
    
    .dusun-label div {
        white-space: nowrap;
        pointer-events: none;
        color: black !important;
        text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white !important;
        font-weight: bold !important;
        font-size: 14px !important;
    }
    
    /* Style untuk Select2 */
    .select2-container {
        width: 100% !important;
        font-size: 13px;
    }
    
    .select2-selection--multiple {
        min-height: 34px !important;
        padding: 3px !important;
        border: 1px solid #ced4da !important;
    }
    
    .select2-selection__choice {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
        margin-top: 3px !important;
    }
    
    .select2-dropdown {
        border: 1px solid #ced4da !important;
    }
    
    /* Style untuk tombol fullscreen */
    .leaflet-control-fullscreen a {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
    }
    
    .leaflet-control-fullscreen a:hover {
        background: #f4f4f4;
    }
    
    .leaflet-touch .leaflet-control-fullscreen a {
        font-size: 22px;
        line-height: 30px;
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .map-control-panel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 300px;
            max-height: 80vh;
            overflow-y: auto;
            z-index: 1001;
        }
        
        .control-panel-toggle {
            display: block !important;
            z-index: 1000;
        }
    }
</style>