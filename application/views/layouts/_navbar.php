<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
    <div class="container">
      <a class="navbar-brand" href="/">ILM Shop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle" id="dropdown-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage</a>
              <div class="dropdown-menu" aria-labelledby="dropdown-1">
                  <a href="<?= base_url('category'); ?>" class="dropdown-item">Kategori</a>
                  <a href="<?= base_url('product'); ?>" class="dropdown-item">Produk</a>
                  <a href="/admin-order.html" class="dropdown-item">Order</a>
                  <a href="<?= base_url('user'); ?>" class="dropdown-item">Pengguna</a>
              </div>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="/cart.html" class="nav-link"><i class="fas fa-shopping-cart"></i> Cart [0]</a>
          </li>
          <?php if(!$this->session->userdata('is_login')) : ?>
          <li class="nav-item">
            <a href="<?= base_url('/login'); ?>" class="nav-link">Login</a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/register'); ?>" class="nav-link">Register</a>
          </li>
          <?php else : ?>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="dropdown-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $this->session->userdata('name'); ?></a>
            <div class="dropdown-menu" aria-labelledby="dropdown-2">
                <a href="<?= base_url('profile'); ?>" class="dropdown-item">Profile</a>
                <a href="/orders.html" class="dropdown-item">Orders</a>
                <a href="<?= base_url('/logout'); ?>" class="dropdown-item" onclick="return confirm('are you sure want to logout')">Logout</a>
            </div>
          </li>
          <?php endif; ?>
          
        </ul>
      </div>
    </div>
  </nav>