  <!-- Content Start -->
  <main class="container">
    <?php $this->load->view('layouts/_alert'); ?>
    <div class="row">
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-3">
              <div class="card-body">
                Category: <strong><?= isset($category) ? $category : 'Semua Category'; ?></strong>
                <span class="float-end">
                  Urutkan Harga: <a href="<?= base_url("/shop/sortby/asc"); ?>" class="badge rounded-pill text-bg-primary">Termurah</a> | <a href="<?= base_url("/shop/sortby/desc"); ?>" class="badge rounded-pill text-bg-primary">Termahal</a>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <?php foreach($content as $row) : ?>
          <div class="col-md-6">
            <div class="card mb-3">
              <img src="<?= $row->image ? base_url("/images/products/$row->image") : base_url("/images/products/default.jpg"); ?>" class="card-img-top">
              <div class="card-body">
                <h5 class="card-title"><?= $row->product_title; ?></h5>
                <p class="card-text"><strong>Rp. <?= number_format($row->price, 0, ',', '.'); ?>,-</strong></p>
                <a href="<?= base_url("shop/category/$row->category_slug"); ?>" class="badge rounded-pill text-bg-primary"><i class="fas fa-tags"></i> <?= $row->category_title; ?></a>
                <!-- rating -->
                <div class="product-ratting">
                  <div class="review-score" data-score="2"></div>
                  <span>2 (2 Rating)</span>
                </div>
              </div>
              <div class="card-footer">
                <form action="<?= base_url("cart/add"); ?>" method="POST">
                <input type="hidden" name="id_product" value="<?= $row->id; ?>">
                  <div class="input-group">
                    <input type="number" name="qty" value="1" class="form-control" />
                    <div class="input-group-append">
                      <button class="btn btn-primary">Add To Cart</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
      <?php endforeach; ?>

        </div>

        <nav aria-label="Page navigation example">
          <?= $pagination; ?>
        </nav> 
      </div>

      <div class="col-md-3">
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-3">
              <div class="card-header">Pencarian</div>
              <div class="card-body">
                <form action="<?= base_url("shop/search"); ?>" method="POST">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari" value="<?= $this->session->userdata('keyword'); ?>">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-3">
              <div class="card-header">Category</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="<?= base_url('/'); ?>">Semua Category</a></li>
                <?php foreach(getCategories() as $category): ?>
                <li class="list-group-item"><a href="<?= base_url("/shop/category/$category->slug"); ?>"><?= $category->title; ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Content End -->
