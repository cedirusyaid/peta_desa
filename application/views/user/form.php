<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $is_edit ? 'Edit' : 'Create' ?> User</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url($form_action) ?>" method="post">
                <div class="form-group">
                    <label for="username">Username*</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?= isset($user->username) ? $user->username : '' ?>" required>
                    <?= form_error('username', '<small class="text-danger">', '</small>') ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password<?= $is_edit ? ' (leave blank if not changing)' : '*' ?></label>
                    <input type="password" class="form-control" id="password" name="password" <?= !$is_edit ? 'required' : '' ?>>
                    <?= form_error('password', '<small class="text-danger">', '</small>') ?>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password<?= $is_edit ? '' : '*' ?></label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" <?= !$is_edit ? 'required' : '' ?>>
                    <?= form_error('password_confirmation', '<small class="text-danger">', '</small>') ?>
                </div>
                
                <div class="form-group">
                    <label for="desa_id">Desa</label>
                    <select class="form-control" id="desa_id" name="desa_id">
                        <option value="">-- Pilih Desa --</option>
                        <?php foreach ($desa_list as $desa): ?>
                            <option value="<?= $desa->desa_id ?>" 
                                <?= (isset($user->desa_id) && $user->desa_id == $desa->desa_id) ? 'selected' : '' ?>>
                                <?= $desa->desa_nama ?> (<?= $desa->desa_id ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1" 
                        <?= (isset($user->is_admin) && $user->is_admin) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_admin">Is Admin</label>
                </div>
                
                <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Update' : 'Submit' ?></button>
                <a href="<?= site_url('user') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Jika admin dicentang, kosongkan desa_id
    $('#is_admin').change(function() {
        if ($(this).is(':checked')) {
            $('#desa_id').val('').prop('disabled', true);
        } else {
            $('#desa_id').prop('disabled', false);
        }
    });

    // Trigger perubahan saat pertama kali load
    $('#is_admin').trigger('change');
});
</script>