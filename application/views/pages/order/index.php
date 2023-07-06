<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Orders</span>
                    <div class="float-end">
                            <?= form_open('order/search', ['method' => 'POST']); ?>
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control form-control-sm text-center" placeholder="Cari" value="<?= $this->session->userdata('keyword'); ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary btn-sm" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('order/reset'); ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eraser"></i>
                                    </a>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($content) :?>
                                <?php foreach($content as $row) : ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url("order/detail/$row->id"); ?>"><strong><?= $row->invoice; ?></strong></a>
                                        </td>
                                        <td><?= str_replace('-', '/', date("d-m-Y", strtotime($row->date))); ?></td>
                                        <td>Rp. <?= number_format($row->total, 0, ',', '.'); ?>,-</td>
                                        <td>
                                            <?php $this->load->view('layouts/_status', ['status' => $row->status]); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4"style="text-align: center;">Have not orders yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                  <nav aria-label="Page navigation example">
                    <?= $pagination; ?>
                  </nav> 
                </div>
              </div>
        </div>
    </div>
  </main>