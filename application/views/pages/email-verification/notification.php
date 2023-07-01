<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Registrasi Berhasil
                </div>
                <div class="card-body">
                    <p>Terima Kasih <?= $this->session->userdata('name'); ?>, sudah Bergabung di Web ini</p>
                    <p>Silakan lakukan verifikasi Email yang, link dan otp sudah kami kirimkan ke email anda</p>
                </div>
            </div>
        </div>
    </div>
  </main>