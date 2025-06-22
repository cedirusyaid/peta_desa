<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>
    
    <!-- Filter dan Pencarian -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/lokasi') ?>" method="get">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Desa/Kelurahan</label>
                        <select name="desa_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Desa</option>
                            <?php foreach($all_desa as $desa): ?>
                            <option value="<?= $desa->desa_id ?>" 
                                <?= ($selected_desa == $desa->desa_id) ? 'selected' : '' ?>>
                                <?= $desa->desa_nama ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php foreach($kategori as $kat): ?>
                            <option value="<?= $kat->kategori_id ?>" 
                                <?= ($this->input->get('kategori') == $kat->kategori_id) ? 'selected' : '' ?>>
                                <?= $kat->kategori_nama ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pencarian</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari lokasi..." value="<?= $this->input->get('search') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    <a href="<?= base_url('admin/lokasi') ?>" class="btn btn-secondary mt-4">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
    
    <!-- Tabel Data -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Lokasi</h6>
            <a href="<?= base_url('admin/lokasi/tambah') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Lokasi
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lokasi</th>
                            <th>Kategori</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1 + ($this->uri->segment(3) ?? 0); ?>
                        <?php foreach($lokasi as $loc): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $loc->lokasi_nama ?></td>
                            <td><?= $loc->kategori_nama ?? '-' ?></td>
                            <td><?= $loc->lokasi_alamat ?? '-' ?></td>
                            <td><?= $loc->lokasi_lat ?>, <?= $loc->lokasi_long ?></td>
                            <td>
                                <a href="<?= base_url('admin/lokasi/edit/'.$loc->lokasi_id) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $loc->lokasi_id ?>)" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="<?= base_url('admin/lokasi/detail/'.$loc->lokasi_id) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <?= $pagination ?>
                </div>
            </div>
        </div>
    </div>
</div>
