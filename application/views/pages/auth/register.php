<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                  Formulir Registrasi
                </div>
                <div class="card-body">
                  <?= form_open('register', ['method' => 'POST']); ?>
                    <div class="form-group">
                        <label for="">Name</label>
                        <?= form_input('name', $input->name, ['class' => 'form-control', 'placeholder' => 'Masukkan Nama', 'required' => true, 'autofocus' => true]); ?>
                        <?= form_error('name'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <?= form_input(['type' => 'email', 'name' => 'email', 'value' => $input->email, 'class' => 'form-control', 'placeholder' => 'Masukkan alamat email aktif', 'required' => true]); ?>
                        <?= form_error('email'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'Masukkan Password min. 8 char', 'required' => true]); ?>
                        <?= form_error('password'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <?= form_password('password_confirmation', '', ['class' => 'form-control', 'placeholder' => 'Masukkan Konfirmasi Password', 'required' => true]); ?>
                        <?= form_error('password_confirmation'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Register</button>
                  <?= form_close(); ?>
                </div>
              </div>
        </div>
    </div>
  </main>