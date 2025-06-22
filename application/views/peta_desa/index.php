<div class="container">
    <h2>Daftar Desa</h2>
    <div class="list-group">
        <?php foreach ($desa_list as $desa): ?>
            <a href="<?php echo site_url('peta_desa/peta/'.$desa->desa_id); ?>" class="list-group-item list-group-item-action">
                <?php echo $desa->desa_nama; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>