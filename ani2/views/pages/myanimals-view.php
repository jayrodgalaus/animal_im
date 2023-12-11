<?php 
    require 'head.php';
    require '../../controllers/connect.php';
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] == null){
        $_SESSION['intended'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//"pages/myanimals-view?id=".$_GET['id'];
        header("Location: ../login");
        
    }
    $_SESSION['current_page'] = 'myanimals-r';
    $navbar = "../aside/nav-".$_SESSION['user']['usertype_id'].".php";
    $id = $_GET['id'];
    $animal = [];
    $nutrition1 = [];
    $nutrition = [];
    $location = [];
    $sql = "SELECT * FROM animals where id = '$id'";
    $result = $conn->query($sql);
    if($result && $result->num_rows == 1){
        $animal = mysqli_fetch_assoc($result);
        $sql = "SELECT a.*,b.name as loc FROM hist_locations a join type_location b on a.location_id = b.id where animal_id = '$id' order by date desc limit 1";
        $result = $conn->query($sql);
        if($result && $result->num_rows == 1){
            $location = mysqli_fetch_assoc($result);
        }
        if($animal['nutrition_id'] != null){
            $sql = "SELECT * FROM nutrition where id = '".$animal['nutrition_id']."'";
            $result = $conn->query($sql);
            if($result && $result->num_rows == 1){
                $nutrition1 = mysqli_fetch_assoc($result);
                //feed only
                $sql = "SELECT a.*,b.name as feed_name FROM nutrition a join type_feed b on b.id = a.feed_id where a.id = '".$animal['nutrition_id']."'";
                //feed and supp
                if(isset($nutrition1['supplement_id']) && $nutrition1['supplement_id'] != null){
                    $sql = "SELECT a.*,b.name as feed_name,c.name as supp_name FROM nutrition a join type_feed b on b.id = a.feed_id join type_supplements c on c.id = a.supplement_id where a.id = '".$animal['nutrition_id']."'";
                }
                $result = $conn->query($sql);
                if($result && $result->num_rows == 1){
                    $nutrition = mysqli_fetch_assoc($result);
                }
                
            }
                
        }
        if($animal['sire_id'] != null && $animal['sire_id'] != 0){
            $sql = "SELECT id,name FROM animals where id = '".$animal['sire_id']."'";
            $result = $conn->query($sql);
            if($result && $result->num_rows == 1){
                $name = mysqli_fetch_assoc($result);
                $animal['sire'] =$name;
            }else{
                $animal['sire'] = "Not registered";
            }
        }else{
            $animal['sire'] = "Not registered";
        }
        if($animal['dam_id'] != null && $animal['dam_id'] != 0){
            $sql = "SELECT id,name FROM animals where id = '".$animal['dam_id']."'";
            $result = $conn->query($sql);
            if($result && $result->num_rows == 1){
                $name = mysqli_fetch_assoc($result);
                $animal['dam'] =$name;
            }else{
                $animal['dam'] = "Not registered";
            }
        }else{
            $animal['dam'] = "Not registered";
        }
    }
    if($_SESSION['user']['usertype_id']  != 3)
        include $navbar;
?>

<div class="pos-rel">
    <div class="container pt-5" id="main-content">
        <div class="alert alert-success d-flex d-none align-items-center" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div class="message">Added successfully!</div>
        </div>
        <div class="alert alert-danger d-flex d-none align-items-center" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div class="message">Unsuccessful. Try again later.</div>
        </div>
        <?php if(!empty($animal)): ?>
        <div class="card card-details">
            <div class="card-body">
                
                <div class="fs-3"><?=$animal['name']?>
                <?php if($_SESSION['user']['usertype_id'] == 1 && $animal['user_id'] == $_SESSION['user']['id']): ?>
                    <button class="btn btn-sm btn-outline-danger float-end" id="delete-animal"><i class="fa-solid fa-trash"></i></button>
                    <button class="btn btn-sm btn-outline-primary float-end mx-1" id="edit-animal"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-outline-primary float-end mx-1 get-qr" id="get-qr"><i class="fa-solid fa-qrcode"></i></button>
                <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="text-muted"><?=$animal['animal_type']?>, <?=$animal['breed']?></span>
                    </div>
                </div>
                <hr>
                
                <table class="table table-striped">
                    <tr>
                        <td><b>Weight:</b> <?=$animal['weight']."kg"?></td>
                        <td><b>Sire:</b> <?=isset($animal['sire']['name'])?$animal['sire']['name'] : $animal['sire'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Gender:</b> <?=$animal['gender'] == "M" ? "Male":"Female" ?></td>
                        <td><b>Dam:</b> <?=isset($animal['dam']['name'])?$animal['dam']['name'] : $animal['dam'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Stud Count:</b> <?=$animal['stud_count']?></td>
                        <td></td>
                    </tr>
                </table>
                    
            </div>
        </div>
        <div class="card card-details d-none mt-2" id="card-qr">
            <div class="card-body pos-rel">
                <div id="qrcode"></div>
                <button class="btn btn-sm btn-outline-primary float-end mx-1 mt-2 get-qr">Hide</i></button>
            </div>
        </div>
        <div class="card card-details mt-2">
            <div class="card-body">
                <div class="fs-4">Nutrition
                    <button class="btn btn-sm btn-outline-primary float-end mx-1"><i class="fa-solid fa-pen"></i></button>
                </div>
                <div class="row">
                    <div class="col">
                        <b>Feed:</b> <?=isset($nutrition['feed_name']) ?$nutrition['feed_name']:"None" ?><br>
                        <b>Amount:</b> <?=isset($nutrition['feed_name']) ? $nutrition['feed_amt'].$nutrition['feed_unit']: ""?><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <b>Supplements:</b> <?=isset($nutrition['supp_name']) ?$nutrition['supp_name']: "None"?><br>
                        <b>Amount:</b> <?=isset($nutrition['supplement_amt']) ?$nutrition['supplement_amt'].$nutrition['supplement_unit']: ""?>
                    </div>
                </div>
                <button class="btn btn-primary float-end">View Medical history</button>
            </div>
        </div>
        <div class="card card-details mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="fs-4">Location
                        <button class="btn btn-sm btn-outline-primary float-end mx-1"><i class="fa-solid fa-pen"></i></button>
                    </div>
                    <div class="col">
                        <b>Current Location:</b><br>
                        <?=isset($location['loc']) ?$location['loc']: "None"?><br>
                        <b>As of:</b><br>
                        <?=isset($location['date']) ? date('m/d/Y',strtotime($location['date'])): "None"?><br>
                    </div>
                </div>
                <button class="btn btn-primary float-end">View Location history</button>
            </div>
        </div>
        <?php else: ?>
            <h3>Animal ID does not exist</h3>
        <?php endif; ?>
        <div class="card d-none" id="edit-animal-card">
            <div class="card-body">
                <form id = "edit-ani-form">
                    <input type="hidden" value="edit" name="crud-type">
                    <input type="hidden" value="<?=$id?>" name="animal-id">
                    <h3>Edit details</h3>
                    <label for="name">Name</label>
                    <input required type="text" value="<?=$animal['name']?>" class="form-control" name="name" id="name">
                    <label for="animal_type">Animal Type</label>
                    <select class="form-select" id="animal_type" name="animal_type">
                        <option value="Cattle" <?=$animal['animal_type'] == "Cattle"?'selected':'' ?>>Cattle</option>
                        <option value="Goat" <?=$animal['animal_type'] == "Goat"?'selected':'' ?>>Goat</option>
                    </select>
                    
                    
                    <div id="breed1-div" class="breeds <?=$animal['animal_type'] == "Cattle" ? "":"d-none" ?> ">
                        <label for="breed1">Breed</label>
                        <select class="form-select" id="breed1" name="breed1">
                            <option <?=$animal['breed'] == "Angus" ? "selected": ""?> value="Angus" >Angus</option>
                            <option <?=$animal['breed'] == "Brahman" ? "selected": ""?> value="Brahman" >Brahman</option>
                            <option <?=$animal['breed'] == "Brown Swiss" ? "selected": ""?> value="Brown Swiss" >Brown Swiss</option>
                            <option <?=$animal['breed'] == "Hereford" ? "selected": ""?> value="Hereford" >Hereford</option>
                            <option <?=$animal['breed'] == "Holstein" ? "selected": ""?> value="Holstein" >Holstein</option>
                            <option <?=$animal['breed'] == "Jersey" ? "selected": ""?> value="Jersey" >Jersey</option>
                            <option <?=$animal['breed'] == "Limousin" ? "selected": ""?> value="Limousin" >Limousin</option>
                            <option <?=$animal['breed'] == "Philippine Native" ? "selected": ""?> value="Philippine Native" >Philippine Native</option>
                            <option <?=$animal['breed'] == "Sahiwal" ? "selected": ""?> value="Sahiwal" >Sahiwal</option>
                            <option <?=$animal['breed'] == "Simmental" ? "selected": ""?> value="Simmental" >Simmental</option>
                        </select>
                    </div>
                    <div id="breed1-div" class="breeds <?=$animal['animal_type'] == "Goat" ? "":"d-none" ?> ">
                        <label for="breed2">Breed</label>
                        <select class="form-select" id="breed2" name="breed2">
                            <option <?=$animal['breed'] ==  "Alpine" ? "selected": ""?> value="Alpine">Alpine</option>
                            <option <?=$animal['breed'] ==  "Anglo Nubian" ? "selected": ""?> value="Anglo Nubian" >Anglo Nubian</option>
                            <option <?=$animal['breed'] ==  "Angora" ? "selected": ""?> value="Angora" >Angora</option>
                            <option <?=$animal['breed'] ==  "Boer Swiss" ? "selected": ""?> value="Boer Swiss" >Brown Boer</option>
                            <option <?=$animal['breed'] ==  "Cashmere" ? "selected": ""?> value="Cashmere" >Cashmere</option>
                            <option <?=$animal['breed'] ==  "Dadiangas native" ? "selected": ""?> value="Dadiangas native" >Dadiangas native</option>
                            <option <?=$animal['breed'] ==  "Kiko" ? "selected": ""?> value="Kiko" >Kiko</option>
                            <option <?=$animal['breed'] ==  "La Mancha" ? "selected": ""?> value="La Mancha" >La Mancha</option>
                            <option <?=$animal['breed'] ==  "Nigerian Dwarf" ? "selected": ""?> value="Nigerian Dwarf" >Nigerian Dwarf</option>
                            <option <?=$animal['breed'] ==  "Pygmy" ? "selected": ""?> value="Pygmy" >Pygmy</option>
                            <option <?=$animal['breed'] ==  "Saanen" ? "selected": ""?> value="Saanen" >Saanen</option>
                            <option <?=$animal['breed'] ==  "Spanish" ? "selected": ""?> value="Spanish" >Spanish</option>
                            <option <?=$animal['breed'] ==  "Tennessee Fainting (Myotonic)" ? "selected": ""?> value="Tennessee Fainting (Myotonic)" >Tennessee Fainting (Myotonic)</option>
                        </select>
                    </div>
                    
                    <label for="weight">Weight(KG)</label>
                    <input required type="number"  class="form-control" name="weight" id="weight" min="0" value="<?=$animal['weight']?>"> 
                    <span>Gender</span>
                    <div class="form-check">
                        <label class="form-check-label" for="g-male">Male</label>
                        <input type="radio" name="gender" id="g-male" class="form-check-input" value='M' <?=$animal['gender'] == "M" ? "checked" : '' ?>>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label" for="g-female">Female</label>
                        <input type="radio" name="gender" id="g-female" class="form-check-input" value='F' <?=$animal['gender'] == "F" ? "checked" : '' ?>>
                    </div>
                    <label for="sire">Sire</label>
                    <select class="form-select" id="sire" name="sire">
                        <option <?=!isset($animal['sire']['name'])? "selected": ""?>>Untagged</option>
                        <?php if(count($_SESSION['animals'])> 0) : ?>
                            <?php foreach($_SESSION['animals'] as $ani) : ?>
                                <?php if($ani['gender'] == 'M') : ?>
                                    <option value="<?=$ani['id']?>" 
                                    <?= isset($animal['sire']['id']) && $animal['sire']['id'] == $ani['id'] ? "selected":"" ?>
                                    >
                                        <?=$ani['name']?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="dam">Dam</label>
                    <select class="form-select" id="dam" name="dam">
                        <option <?=!isset($animal['dam']['name'])? "selected": ""?>>Untagged</option>
                        <?php if(count($_SESSION['animals'])> 0) : ?>
                            <?php foreach($_SESSION['animals'] as $ani) : ?>
                                <?php if($ani['gender'] == 'F') : ?>
                                    <option value="<?=$ani['id']?>" 
                                    <?= isset($animal['dam']['id']) && $animal['dam']['id'] == $ani['id'] ? "selected":"" ?>
                                    >
                                        <?=$ani['name']?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="name">Stud Count</label>
                    <input required type="number" min='0' required name="stud" class="form-control" id="stud" value="<?=$animal['stud_count']?>">
                    <button class="btn btn-lg btn-primary my-3 float-end">Save <i class="fa-solid fa-check"></i></button>
                    <button type="button" class="btn btn-lg btn-secondary my-3 me-2 float-end" id="cancel-edit-animal">Cancel <i class="fa-solid fa-xmark"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div class="container"> -->

<script src="../../assets/qr/qrcode.min.js"></script>
<script>
    generateQR();
    $(document).on('click','#edit-animal, #cancel-edit-animal',function(){
        $('.card-details, #edit-animal-card').toggleClass('d-none');
    })
    .on('click','.alert',function(e){
        $(e.currentTarget).addClass('d-none');
    })
    .on('change','#supplements',function(){
        if($(this).val() != 0){
            $("#supplement-new").attr('disabled',true);
        }else{
            $("#supplement-new").removeAttr('disabled');
        }
    })
    .on('change','#feed',function(){
        if($(this).val() != 0){
            $("#feed-new").attr('disabled',true);
        }else{
            $("#feed-new").removeAttr('disabled');
        }
    })
    .on('change','#animal_type',function(){
        $('.breeds').toggleClass('d-none ')
    })
    .on('submit','#edit-ani-form', function(e){
        e.preventDefault();            
        var form = document.getElementById('edit-ani-form');
        var formData = new FormData(form);
        
        $.ajax({
            url:"../../controllers/animal",
            method: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            success: function(res){
                if(res.success){
                    showAlert('success','Update successful');
                    setTimeout(() => {
                        window.location.reload(true)
                    }, 500);
                    
                }else{
                    showAlert('danger','Update failed')
                }
                    
            }
        })
    })
    .on('click','#delete-animal',function(){
        if(confirm("Are you sure you want to delete this animal and all its records? This cannot be undone.")){
            var ani_id = <?=$id?>;
            var crud = "delete";
            $.ajax({
                url:"../../controllers/animal",
                method: 'post',
                data: {"crud-type":crud,"animal_id":ani_id},
                dataType: 'html',
                cache: false,
                success: function(res){
                    console.log(JSON.parse(res));
                    res = JSON.parse(res);
                    if(res.success){
                        showAlert('success','Delete successful');
                        setTimeout(() => {
                            window.location.replace('myanimals-r')
                        }, 1000);
                        
                    }else{
                        showAlert('danger',res.error)
                    }
                        
                }
            })
        }
        
    })
    .on('click','.get-qr',function(){
        $('#card-qr').toggleClass('d-none');
    })
    function showAlert(type,message){
        $('.alert-'+type+' .message').text(message);
        $('.alert-'+type).toggleClass('d-none')
    }
    function generateQR(){
        new QRCode(document.getElementById("qrcode"), window.location.href);
    }
</script>
<!-- </div> -->
