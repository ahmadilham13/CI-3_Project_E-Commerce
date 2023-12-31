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
    <link rel="stylesheet" href="<?= base_url('/assets/libs/boostrap/css/bootstrap.min.css'); ?>">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?= base_url('/assets/css/app.css'); ?>">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?= base_url('/assets/libs/fontawesome/css/all.min.css'); ?>">
    
    <!-- OTP CSS -->
    <?php if(isset($otp_style)) : ?>
      <link rel="stylesheet" href="<?= $otp_style; ?>">
    <?php endif; ?>
    
    <?php if(isset($sandbox_url) && isset($client_key)) : ?>
      <script type="text/javascript" src="<?= $sandbox_url; ?>" data-client-key="<?= $client_key; ?>"></script>
    <?php endif; ?>

  </head>
  <body>
   
  <?php if(!isset($otp_style) && !isset($otp_script)) : ?>
    <!-- Navbar Start -->
    <?php $this->load->view('layouts/_navbar'); ?>
    <!-- Navbar End -->
  <?php endif; ?>

  <!-- Content Start -->
  <?php $this->load->view($page); ?>
  <!-- Content End -->

    <script src="<?= base_url('/assets/libs/jquery/jquery-3.7.0.min.js'); ?>"></script>
    <script src="<?= base_url('/assets/libs/boostrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('/assets/js/app.js'); ?>"></script>
    <?php if(isset($otp_script)) : ?>
      <script src="<?= $otp_script; ?>"></script>
    <?php endif; ?>
    <?php if(isset($product_script)) : ?>
      <script src="<?= $product_script; ?>"></script>
    <?php endif; ?>
    
    <?php if(isset($pay_process)) : ?>
      <script type="text/javascript">
        $("#pay-button").click(function(e) {
          e.preventDefault()
          
          $.ajax({
            method: 'POST',
            url : '<?= base_url('/pay'); ?>',
            cache: false,
            data: {
              order_id: $("#order_id").html()
            },
            success: function(data) {
              // console.log(data)

              snap.pay(data, {
                onSuccess: function(result){
                  window.location = result.finish_redirect_url;
                },
                onPending: function(result){
                  window.location = result.finish_redirect_url;
                },
                onError: function(result){
                  window.location = result.finish_redirect_url + '&message=' + result.status_message;
                }
              })
              return false;
            }
          })
        })
      </script>
    <?php endif; ?>
  </body>
</html>