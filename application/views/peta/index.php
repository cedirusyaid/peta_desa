<div class="container">
    <h2>Peta Desa</h2>
    <div class="list-group">
        <?php foreach ($desa_list as $desa): ?>
            <a href="<?php echo site_url('peta/detail/'.$desa->desa_id); ?>" class="list-group-item">
                <?php echo $desa->desa_nama; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>