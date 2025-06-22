<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Lokasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .photo-thumbnail {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .photo-card {
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .photo-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Foto Lokasi</h1>
            <a href="<?= site_url('lokasi') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Lokasi
            </a>
        </div>


        <!-- Upload Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-cloud-arrow-up"></i> Upload Foto Baru
            </div>
            <div class="card-body">
                <form action="<?= site_url('foto_lokasi/upload/' . $lokasi_id) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="foto_file" class="form-label">Pilih File Foto</label>
                        <input class="form-control" type="file" id="foto_file" name="foto_file" required>
                        <div class="form-text">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </form>
            </div>
        </div>

        <!-- Photo Gallery -->
        <h3 class="mb-3">Daftar Foto</h3>
        <?php if(empty($fotos)): ?>
            <div class="alert alert-info">Belum ada foto untuk lokasi ini.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach($fotos as $foto): ?>
                    <div class="col-md-4">
                        <div class="card photo-card">
                            <img src="<?= base_url('uploads/foto_lokasi/' . $foto->foto_file) ?>" class="card-img-top photo-thumbnail" alt="Foto Lokasi">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('uploads/foto_lokasi/' . $foto->foto_file) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-eye"></i> Lihat Full
                                    </a>
                                    <a href="<?= site_url('foto_lokasi/delete/' . $foto->foto_lokasi_id . '/' . $lokasi_id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer text-muted small">
                                Uploaded: <?= date('d M Y H:i', filemtime(FCPATH . 'uploads/foto_lokasi/' . $foto->foto_file)) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>