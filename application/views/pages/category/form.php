<main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Formulir Category</span>
                </div>
                <div class="card-body">
                <?= form_open($form_action, ['method' => 'POST']); ?>
                    <?= isset($input->id) ? form_hidden('id', $input->id) : ''; ?>
                        <div class="form-group">
                            <label for="">Category</label>
                            <?= form_input('title', $input->title, ['class' => 'form-control', 'id' => 'title', 'required' => true, 'onkeyup' => 'createSlug()', "autofocus" => true]); ?>
                            <?= form_error('title'); ?>
                        </div>
                        <div class="form-group">
                            <label for="">Slug</label>
                            <?= form_input('slug', $input->slug, ['class' => 'form-control', 'id' => 'slug', 'required' => true, "readonly" => true]); ?>
                            <?= form_error('slug'); ?>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <?= form_close(); ?>
                </div>
              </div>
        </div>
    </div>
  </main>