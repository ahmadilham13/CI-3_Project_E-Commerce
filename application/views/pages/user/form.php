<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Formulir Pengguna</span>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action, ['method' => 'POST']); ?>
                        <?= isset($input->id) ? form_hidden('id', $input->id) : ''; ?>
                        <div class="form-group">
                            <label for="">Name</label>
                            <?= form_input('name', $input->name, ['class' => 'form-control', 'placeholder' => 'Masukkan Nama Anda', 'required' => true, "autofocus" => true]); ?>
                            <?= form_error('name'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">E-Mail</label>
                            <?php 
                                $readonly = [];
                                if(isset($is_verify)) {
                                    $readonly = $is_verify == true ? ['readonly' => true] : [];
                                }
                            ?>
                            <?= form_input(array_merge(['type' => 'email', 'name' => 'email', 'value' => $input->email, 'class' => 'form-control', 'placeholder' => 'Masukkan alamat email aktif', 'required' => true], $readonly)); ?>
                            <?= form_error('email'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <?php 
                                $required = isset($input->id) ? '' : ['required' => true];
                            ?>
                            <?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'Masukkan Password min. 8 char'], $required); ?>
                            <?= form_error('password'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Role</label>
                            <select name="role" class="form-control">
                                <option value="">-- Select Role --</option>
                                <?php foreach(userRole() as $key => $value) : ?>
                                <option value="<?= $key; ?>" <?= $key === $input->role ? 'selected' : ''; ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <br />
                            <div class="form-check form-check-inline">
                                <?= form_radio(['name' => 'is_active', 'value' => 1, 'checked' => $input->is_active == 1 ? true : false, 'class' => 'form-check-input']); ?>
                                <label for="" class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <?= form_radio(['name' => 'is_active', 'value' => 0, 'checked' => $input->is_active == 0 ? true : false, 'class' => 'form-check-input']); ?>
                                <label for="" class="form-check-label">Non-Active</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Photo</label>
                            <br />
                            <?= form_upload('image_profile', '', ['id' => 'image_profile']); ?>
                            <?php if($this->session->flashdata('image_error')): ?>
                                <small class="form-text text-danger"><?= $this->session->flashdata('image_error'); ?></small>
                            <?php endif; ?>
                            <div id="images_display">
                                <?php if(isset($input->image_profile) && !empty($input->image_profile)) : ?>
                                    <img src="<?= base_url("images/profiles/$input->image_profile"); ?>" alt="" height="150">
                                    <span onclick="deleteImage(0)" style="cursor: pointer;">&times;</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    <?= form_close(); ?>
                </div>
              </div>
        </div>
    </div>
  </main>