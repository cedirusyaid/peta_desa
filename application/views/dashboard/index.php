<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Welcome, <?= $user['username'] ?></h6>
                </div>
                <div class="card-body">
                    <p>You are logged in as <strong><?= ucfirst($user['role']) ?></strong></p>
                    <?php if ($user['desa_id']): ?>
                        <p>Desa ID: <strong><?= $user['desa_id'] ?></strong></p>
                    <?php endif; ?>
                    <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>