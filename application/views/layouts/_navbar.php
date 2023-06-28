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
                  <a href="/admin-category.html" class="dropdown-item">Kategori</a>
                  <a href="/admin-product.html" class="dropdown-item">Produk</a>
                  <a href="/admin-order.html" class="dropdown-item">Order</a>
              </div>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="/cart.html" class="nav-link"><i class="fas fa-shopping-cart"></i> Cart [0]</a>
          </li>
          <li class="nav-item">
            <a href="/login.html" class="nav-link">Login</a>
          </li>
          <li class="nav-item">
            <a href="/register.html" class="nav-link">Register</a>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="dropdown-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ahmad Ilham</a>
            <div class="dropdown-menu" aria-labelledby="dropdown-2">
                <a href="/profile.html" class="dropdown-item">Profile</a>
                <a href="/orders.html" class="dropdown-item">Orders</a>
                <a href="#" class="dropdown-item">Logout</a>
            </div>
        </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>