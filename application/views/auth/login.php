<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                        </div>

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= $this->session->flashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <form class="user" action="<?= site_url('auth/login') ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" 
                                       id="username" name="username" placeholder="Username" 
                                       value="<?= set_value('username') ?>" required>
                                <?= form_error('username', '<small class="text-danger pl-3">', '</small>') ?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" 
                                       id="password" name="password" placeholder="Password" required>
                                <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Login
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="#">Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-login-image {
        background: url(<?= base_url('assets/img/login.jpg') ?>);
        background-position: center;
        background-size: cover;
    }
    body {
        background-color: #f8f9fc;
    }
</style>