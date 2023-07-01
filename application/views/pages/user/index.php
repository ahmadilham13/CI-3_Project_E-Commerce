<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Pengguna</span>
                    <a href="<?= base_url('user/create'); ?>" class="btn btn-sm btn-secondary">Add New</a>

                    <div class="float-end">
                        <?= form_open('user/search', ['method' => 'POST']); ?>
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control form-control-sm text-center" placeholder="Cari" value="<?= $this->session->userdata('keyword'); ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary btn-sm" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('user/reset'); ?>" class="btn btn-secondary btn-sm">
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
                            <th scope="col">#</th>
                            <th scope="col">Users</th>
                            <th scope="col">E-Mail</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; foreach($content as $row): $no++; ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td>
                                    <p>
                                        <img src="<?= $row->image_profile ? base_url("images/profiles/$row->image_profile") : base_url("images/profiles/default.jpg"); ?>" alt="" height="50">
                                        <?= $row->name; ?>
                                    </p>
                                </td>
                                <td><?= $row->email; ?> <?= $row->email_verify == true ? '<span class="badge text-bg-success">Verify</span>' : '<span class="badge text-bg-warning">Not Verify</span>' ; ?></td>
                                <td><?= $row->role; ?></td>
                                <td><?= $row->is_active == true ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">Not Active</span>'; ?></td>
                                <td>
                                    <a href="<?= base_url("user/edit/$row->id"); ?>" class="btn btn-sm">
                                        <i class="fas fa-edit text-info"></i>
                                    </a>
                                    <?= form_open(base_url("user/delete/$row->id"), ['method' => 'POST']); ?>
                                        <?= form_hidden('id', $row->id); ?>
                                        <button class="btn btn-sm" type="submit" onclick="return confirm('are you sure?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    <?= form_close(); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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