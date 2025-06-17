<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lokasi GIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Daftar Lokasi GIS</h1>
        
        <?php if($this->session->flashdata('message')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
        <?php endif; ?>
        
        <a href="<?= site_url('lokasi/create') ?>" class="btn btn-primary mb-3">Tambah Lokasi</a>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Lokasi</th>
                    <th>Alamat</th>
                    <th>Koordinat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($lokasi as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item->kategori_nama ?></td>
                    <td><?= $item->lokasi_nama ?></td>
                    <td><?= $item->lokasi_alamat ?></td>
                    <td><?= $item->lokasi_lat ?>, <?= $item->lokasi_long ?></td>
                    <td>
                        <a href="<?= site_url('lokasi/edit/'.$item->lokasi_id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('foto_lokasi/index/'.$item->lokasi_id) ?>" class="btn btn-info btn-sm"><i class="bi bi-images"></i> Foto</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>