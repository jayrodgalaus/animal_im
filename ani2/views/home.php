<?php 
    require 'head.php';
    session_start();
    $navbar = "aside/nav-".$_SESSION['user']['usertype_id'].".php";
    $dash = "dashboard-".$_SESSION['user']['usertype_id'].".php";
    $_SESSION['current_page'] = 'home';
    include $navbar;
?>

<div class="pos-rel">
    <div class="container pt-5" id="main-content">
        <?php include $dash; ?> 
    </div>
</div>


<?php include 'foot.php'; ?>