<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Lokasi</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">Filter Lokasi</h6>
            <a href="<?php echo base_url('admin/create?desa_id='.$selected_desa); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create Lokasi
            </a>
        </div>        
        <div class="card-body">
            <form method="get" action="<?php echo site_url('admin'); ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pilih Desa:</label>
                    <div class="col-sm-8">
                        <select name="desa_id" class="form-control" onchange="this.form.submit()">
                            <?php foreach ($desa_list as $desa): ?>
                                <?php if ($kecamatan_id_last!=$desa->kecamatan_id): ?>
                                <option value="" disabled>
                                    - KECAMATAN <?php echo $desa->kecamatan_nama; ?> -
                                </option>
                                    
                                <?php endif ?>
                                <option value="<?php echo $desa->desa_id; ?>" <?php echo ($selected_desa == $desa->desa_id) ? 'selected' : ''; ?>>
                                    <?php echo $desa->desa_nama; ?>
                                </option>
                            <?php $kecamatan_id_last=$desa->kecamatan_id; endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Kategori</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($locations as $location): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $location->lokasi_nama; ?></td>
                                <td>
                                    <?php if (!empty($location->kategori_icon)): ?>
                                        <img src="<?php echo base_url('images/icons/') . $location->kategori_icon; ?>" width="20" alt="<?php echo $location->kategori_nama; ?>">
                                    <?php endif; ?>
                                    <?php echo $location->kategori_nama; ?>
                                </td>
                                <td><?php echo $location->lokasi_alamat; ?></td>
                                <td><?php echo $location->lokasi_lat; ?>,<?php echo $location->lokasi_long; ?></td>
                                                            <td>
                                <a href="<?= base_url('admin/edit/'.$location->lokasi_id) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $location->lokasi_id ?>)" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="<?= base_url('admin/detail/'.$location->lokasi_id) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Page level plugins -->

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>