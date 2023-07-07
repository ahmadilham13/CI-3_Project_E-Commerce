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
    <link rel="stylesheet" href="/assets/css/app.css">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="/assets/libs/fontawesome/css/all.min.css">
    
    <!-- OTP CSS -->
    <?php if(isset($otp_style)) : ?>
      <link rel="stylesheet" href="<?= $otp_style; ?>">
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

    <script src="/assets/libs/jquery/jquery-3.7.0.min.js"></script>
    <script src="/assets/libs/boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
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
              console.log(data)

              snap.pay(data, {
                onSuccess: function(result){
                  changeResult('success', result);
                  console.log(result.status_message);
                  console.log(result);
                  // $("#payment-form").submit();
                },
                onPending: function(result){
                  changeResult('pending', result);
                  console.log(result.status_message);
                  // $("#payment-form").submit();
                },
                onError: function(result){
                  changeResult('error', result);
                  console.log(result.status_message);
                  // $("#payment-form").submit();
                }
              })
            }
          })
        })
      </script>
    <?php endif; ?>
  </body>
</html>