<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Checkout Berhasil
                </div>
                <div class="card-body">
                    <h5>Nomor Order: <?= $content->invoice; ?></h5>
                    <p>Terima Kasih <b><?= $content->name; ?></b>, sudah berbelanja</p>
                    <p>Silakan lakukan pembayaran untuk bisa kami proses selanjutnya dengan cara:</p>
                    <ol>
                        <li>Lakukan pembayaran dengan klik <?= form_open('checkout/pay', ['method' => 'POST']); ?> <input type="submit" value="PAY"> <?= form_close(); ?></li>
                        <li>Mohon untuk memeriksa <strong>detail transaksi</strong> anda pada halaman pembayaran</li>
                        <li>Mohon untuk <strong>screenshot bukti pembayaran anda</strong>, untuk dikirimkan sebagai bukti pembayaran</li>
                        <li>Total pembayaran: <strong>Rp. <?= number_format($content->total, 0, ',', '.'); ?>,-</strong></li>
                    </ol>
                    <p>Jika sudah, silakan kirimkan bukti transfer di halaman konfirmasi atau bisa <a href="<?= base_url("myorder/detail/$content->invoice"); ?>">klik disini!</a></p>
                    <a href="<?= base_url('/'); ?>" class="btn btn-primary"><i class="fas fa-angle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
  </main>