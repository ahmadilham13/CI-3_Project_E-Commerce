<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Formulir Product</span>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action, ['method' => 'POST']); ?>
                        <?= isset($input->id) ? form_hidden('id', $input->id) : ''; ?>
                        <div class="form-group">
                            <label for="">Product</label>
                            <?= form_input('title', $input->title, ['class' => 'form-control', 'id' => 'title', 'required' => true, 'onkeyup' => 'createSlug()', "autofocus" => true]); ?>
                            <?= form_error('title'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Slug</label>
                            <?= form_input('slug', $input->slug, ['class' => 'form-control', 'id' => 'slug', 'required' => true, "readonly" => true]); ?>
                            <?= form_error('slug'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <?= form_textarea(['name' => 'description', 'value' => $input->description, 'row' => 4, 'class' => 'form-control']); ?>
                            <?= form_error('description'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <?= form_input(['type' => 'number', 'name' => 'price', 'value' => $input->price, 'class' => 'form-control', 'required' => true]); ?>
                            <?= form_error('price'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <?= form_dropdown('id_category', getDropdownList('category', ['id', 'title']), $input->id_category, ['class' => 'form-control']); ?>
                            <?= form_error('id_category'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Stock</label>
                            <br />
                            <div class="form-check form-check-inline">
                                <?= form_radio(['name' => 'is_available', 'value' => 1, 'checked' => $input->is_available == 1 ? true : false, 'class' => 'form-check-input']); ?>
                                <label for="">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <?= form_radio(['name' => 'is_available', 'value' => 0, 'checked' => $input->is_available == 0 ? true : false, 'class' => 'form-check-input']); ?>
                                <label for="">Kosong</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Image Product</label>
                            <br />
                            <?= form_upload('image', '', ['id' => 'image', 'class' => 'images']); ?>
                            <?php if($this->session->flashdata('image_error')): ?>
                                <small class="form-text text-danger"><?= $this->session->flashdata('image_error'); ?></small>
                            <?php endif; ?>
                            <div id="images_display">
                                <?php if(isset($input->image) && !empty($input->image)) : ?>
                                    <img src="<?= base_url("images/products/$input->image"); ?>" alt="" height="150">
                                    <span onclick="deleteImage(0)" style="cursor: pointer;">&times;</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <?= form_close(); ?>
                </div>
              </div>
        </div>
    </div>
  </main>