<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.112.5">
    <title><?= isset($title) ? $title : 'CiShop' ; ?> - CodeIgniter E-Commerce</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/navbar-fixed/">

    <!-- Boostrap core CSS-->
    <link rel="stylesheet" href="/assets/libs/boostrap/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="assets/css/app.css">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="/assets/libs/fontawesome/css/all.min.css">
    
  </head>
  <body>
   
  <!-- Navbar Start -->
  <?php $this->load->view('layouts/_navbar'); ?>
  <!-- Navbar End -->

  <!-- Content Start -->
  <?php $this->load->view($page); ?>
  <!-- Content End -->

    <script src="/assets/libs/jquery/jquery-3.7.0.min.js"></script>
    <script src="/assets/libs/boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>