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
                    <p>Anda Sudah Berhasil melakukan transaksi pembayaran Melalui Midtrans, dengan detail bayar:</p>
                    <ol>
                        <li>Transaction ID : <strong><?= $mercant_order->transaction_id; ?></strong></li>
                        <li>Payment Type : <strong><?= ucwords(str_replace("_", " ", $mercant_order->payment_type)); ?></strong></li>
                        <li>Date : <?= $mercant_order->transaction_time; ?></li>
                        <li>Gross Amount: <strong>Rp. <?= number_format($mercant_order->gross_amount, 0, ',', '.'); ?>,-</strong></li>
                    </ol>
                    <p> Klik <a href="<?= base_url("myorder/detail/$content->invoice"); ?>">disini</a> untuk melihat detail order anda</p>
                    <p>Terima Kasih :)</p>
                    <a href="<?= base_url('/'); ?>" class="btn btn-primary"><i class="fas fa-angle-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
  </main>