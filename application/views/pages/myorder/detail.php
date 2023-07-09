<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view('layouts/_menu'); ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Detail Order <a id="order_id"><?= $order->invoice; ?></a>
                    <div class="float-end">
                        <?php $this->load->view('layouts/_status', ['status' => $order->status]); ?>
                    </div>
                </div>
                <div class="card-body">
                    <p>Tanggal: <?= str_replace('-', '/', date("d-m-Y", strtotime($order->date))); ?></p>
                    <p>Nama : <?= $order->name; ?></p>
                    <p>Phone : <?= $order->phone; ?></p>
                    <p>Alamat : <?= $order->address; ?></p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order_detail as $row) : ?>
                                <tr>
                                    <td>
                                        <p>
                                            <img src="<?= $row->image ? base_url("/images/products/$row->image") : base_url("/images/products/default.jpg") ; ?>" height="50">
                                            <strong><?= $row->title; ?></strong>
                                        </p>
                                    </td>
                                    <td class="text-center">Rp. <?= number_format($row->price, 0, ',','.'); ?>, -</td>
                                    <td class="text-center"><?= $row->qty; ?></td>
                                    <td class="text-center">Rp. <?= number_format($row->subtotal, 0, ',','.'); ?>,-</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td class="text-center"><strong>Rp. <?= number_format(array_sum(array_column($order_detail, 'subtotal')), 0, ',', '.'); ?>,-</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if($order->status === 'pending') : ?>
                    <div class="card-footer">
                        <a href="" class="btn btn-success" id="pay-button">Pay</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(isset($order_confirm)) : ?>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                Bukti Transfer
                            </div>
                            <div class="card-body">
                                <p>Dari Rekening: <?= $order_confirm->account_number; ?></p>
                                <p>Atas Nama: <?= $order_confirm->account_name; ?></p>
                                <p>Nominal: Rp. <?= number_format($order_confirm->nominal, 0, ',','.'); ?>,-</p>
                                <p>Catatan: <?= $order_confirm->note; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="<?= base_url("images/confirm_payment/$order_confirm->image"); ?>" alt="" height="200">
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($detailPayment)) : ?>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                Payment Detail
                            </div>
                            <div class="card-body">
                                <p>Order Id: <?= $detailPayment->order_id; ?></p>
                                <p>Payment Type: <?= $detailPayment->payment_type; ?></p>
                                <?php if(isset($detailPayment->bank) && isset($detailPayment->va_number)): ?>
                                    <p>Bank Name: <?= $detailPayment->bank; ?></p>
                                    <p>VA Number: <?= $detailPayment->va_number; ?></p>
                                <?php endif; ?>
                                <?php if(isset($detailPayment->bill_code) && isset($detailPayment->bill_key)): ?>
                                    <p>Bill Code: <?= $detailPayment->bill_code; ?></p>
                                    <p>Bill Key: <?= $detailPayment->bill_key; ?></p>
                                <?php endif; ?>
                                <p>Currency: <?= $detailPayment->currency; ?></p>
                                <p>Gross Amount: Rp. <?= number_format($detailPayment->gross_amount, 0, ',','.'); ?>,-</p>
                                <p>Transaction Status: <?= $detailPayment->transaction_status; ?></p>
                                <p>Transaction Time: <?= $detailPayment->transaction_time; ?></p>
                                <?php if(isset($detailPayment->transaction_expired)): ?>
                                    <p>Expiry Time: <?= $detailPayment->transaction_expired; ?></p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
  </main>