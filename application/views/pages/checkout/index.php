<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Formulir Alamat Pengiriman
            </div>
            <div class="card-body">
                <form action="<?= base_url("/checkout/create"); ?>" method="POST">
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" class="form-control" name="first_name" placeholder="Masukkan First Name Penerima" value="<?= $input->first_name; ?>"/>
                        <?= form_error('first_name'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Masukkan Last Name Penerima" value="<?= $input->last_name; ?>"/>
                        <?= form_error('last_name'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <?php $readonly = !empty($input->email) ? "readonly" : ''; ?>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan E-Mail Valid Anda" value="<?= $input->email; ?>" <?= $readonly; ?>>
                        <?= form_error('email'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="address" id="" cols="30" rows="5" class="form-control"><?= $input->address; ?></textarea>
                        <?= form_error('address'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">City</label>
                        <input type="text" class="form-control" name="city" placeholder="Masukkan Provinsi Anda" value="<?= $input->city; ?>">
                        <?= form_error('city'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Postal Code</label>
                        <input type="text" class="form-control" name="postal_code" placeholder="Masukkan Kode Post" value="<?= $input->postal_code; ?>">
                        <?= form_error('postal_code'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Masukkan No Telepon Penerima" value="<?= $input->phone; ?>"/>
                        <?= form_error('phone'); ?>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Checkout</button>
                </form>
            </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
              <div class="card mb-3">
                <div class="card-header">Cart</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cart as $row) : ?>
                            <tr>
                                <td><?= $row->title; ?></td>
                                <td><?= $row->qty; ?></td>
                                <td>Rp. <?= number_format($row->price, 0, ',', '.'); ?>,-</td>
                            </tr>
                            <tr>
                                <td colspan="2">Subtotal</td>
                                <td>Rp. <?= number_format($row->subtotal, 0, ',', '.'); ?>,-</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th>Rp. <?= number_format(array_sum(array_column($cart, 'subtotal')), 0, ',', '.'); ?>,-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </main>