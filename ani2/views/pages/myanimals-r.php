<?php 
    require 'head.php';
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] == null){
        $_SESSION['intended'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//"pages/myanimals-view?id=".$_GET['id'];
        header("Location: ../login");
        
    }
    $_SESSION['current_page'] = 'myanimals-r';
    $navbar = "../aside/nav-".$_SESSION['user']['usertype_id'].".php";
    include $navbar;
?>

<div class="pos-rel">
    <div class="container pt-5" id="main-content">
        <div class="pos-f t5 w-75">
            <input type="text" class="form-control" placeholder="Search animal">
        </div>
        <div class="mt-5">
            <?php if(count($_SESSION['animals'])> 0) : ?>
                <?php foreach($_SESSION['animals'] as $ani) : ?>
                    <div class="card ani-card" data-id="<?=$ani['id']?>">
                        <div class="card-body">
                            <?=$ani['name'].'-'.$ani['id']?><br>
                            <span class="text-muted"><?=$ani['animal_type'].'-'.$ani['breed']?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card bg-info">
                    <div class="card-body">
                        You have no animals.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- <div class="container"> -->
    


<div class="pos-f b0 r0">
    <a href = "myanimals-c"><button id="add-animal" style="background-color:white; width: 100px; height: 100px; border-radius:50% 0 0 0; border:none; box-shadow: 0 0 15px 2px rgb(13,202,240)">
        <i class="fa-solid fa-plus display-6"></i>
    </button></a>
</div>

<script>
    $(document).on('click','.ani-card',function(e){
        let id = $(e.currentTarget).attr('data-id');
        // let newurl = window.location.host+"/ani2/views/pages/myanimals-view?id="+id;
        window.location.replace("myanimals-view?id="+id)
    })
</script>
<!-- </div> -->
