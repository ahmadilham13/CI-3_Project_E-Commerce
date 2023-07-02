<?php 
if($status == 'waiting') {
    $badgeStatus    = 'text-bg-info';
    $status         = 'Menunggu Pembayaran';
}

if($status == 'paid') {
    $badgeStatus    = 'text-bg-secondary';
    $status         = 'Dibayar';
}

if($status == 'delivered') {
    $badgeStatus    = 'text-bg-success';
    $status         = 'Dikirim';
}

if($status == 'cancel') {
    $badgeStatus    = 'text-bg-danger';
    $status         = 'Dibatalkan';
}
?>

<?php if($status) :  ?>
    <span class="badge rounded-pill <?= $badgeStatus; ?> text-white"><?= $status; ?></span>
<?php endif; ?>