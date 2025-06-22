<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Management</h1>
    

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
            <a href="<?= site_url('user/create') ?>" class="btn btn-primary btn-sm float-right">Add New User</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Desa</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): 
                            $desa = $user->desa_id ? $this->User_model->get_desa_by_id($user->desa_id) : null;
                        ?>
                            <tr>
                                <td><?= $user->username ?></td>
                                <td><?= $this->User_model->get_role($user) ?></td>
                                <td>
                                    <?= $desa ? $desa->desa_nama.' ('.$desa->desa_id.')' : '-' ?>
                                </td>
                                <td><?= $user->created_at ?></td>
                                <td>
                                    <a href="<?= site_url('user/edit/'.$user->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('user/delete/'.$user->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>