<?php 
    require 'head.php';
    session_start();
    
    $_SESSION['current_page'] = 'myanimals-r';
    $navbar = "../aside/nav-".$_SESSION['user']['usertype_id'].".php";
    include $navbar;
?>

<div class="pos-rel">
    <div class="container pt-5" id="main-content">
        
        <div class="alert alert-success d-flex d-none align-items-center" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>Added successfully!</div>
        </div>
        <div class="alert alert-danger d-flex d-none align-items-center" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <div>Unsuccessful. Try again later.</div>
        </div>

        <form class="form-group" id = "animal-form">
            <h3>General Info</h3>
            <div class="card">
                <div class="card-body">
                        <input type="hidden" value="add" name="crud-type">
                        <label for="name">Name</label>
                        <input required type="text" class="form-control" name="name" id="name">
                        <label for="animal_type">Animal Type</label>
                        <select class="form-select" id="animal_type" name="animal_type">
                            <option value="Cattle" selected>Cattle</option>
                            <option value="Goat" >Goat</option>
                        </select>
                        
                        
                        <div id="breed1-div" class="breeds">
                            <label for="breed1">Breed</label>
                            <select class="form-select" id="breed1" name="breed1">
                                <option value="Angus" selected>Angus</option>
                                <option value="Brahman" >Brahman</option>
                                <option value="Brown Swiss" >Brown Swiss</option>
                                <option value="Hereford" >Hereford</option>
                                <option value="Holstein" >Holstein</option>
                                <option value="Jersey" >Jersey</option>
                                <option value="Limousin" >Limousin</option>
                                <option value="Philippine Native" >Philippine Native</option>
                                <option value="Sahiwal" >Sahiwal</option>
                                <option value="Simmental" >Simmental</option>
                            </select>
                        </div>
                        <div id="breed2-div" class="breeds d-none">
                            <label for="breed2">Breed</label>
                            <select class="form-select" id="breed2" name="breed2">
                                <option value="Alpine" selected>Alpine</option>
                                <option value="Anglo Nubian" >Anglo Nubian</option>
                                <option value="Angora" >Angora</option>
                                <option value="Boer Swiss" >Brown Boer</option>
                                <option value="Cashmere" >Cashmere</option>
                                <option value="Dadiangas native" >Dadiangas native</option>
                                <option value="Kiko" >Kiko</option>
                                <option value="La Mancha" >La Mancha</option>
                                <option value="Nigerian Dwarf" >Nigerian Dwarf</option>
                                <option value="Pygmy" >Pygmy</option>
                                <option value="Saanen" >Saanen</option>
                                <option value="Spanish" >Spanish</option>
                                <option value="Tennessee Fainting (Myotonic)" >Tennessee Fainting (Myotonic)</option>
                            </select>
                        </div>
                        
                        <label for="weight">Weight(KG)</label>
                        <input required type="number"  class="form-control" name="weight" id="weight" min="0"> 
                        <span>Gender</span>
                        <div class="form-check">
                            <label class="form-check-label" for="g-male">Male</label>
                            <input type="radio" name="gender" id="g-male" class="form-check-input" value='M' checked>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" for="g-female">Female</label>
                            <input type="radio" name="gender" id="g-female" class="form-check-input" value='F'>
                        </div>
                        <label for="sire">Sire</label>
                        <select class="form-select" id="sire" name="sire">
                            <option selected>Untagged</option>
                            <?php if(count($_SESSION['animals'])> 0) : ?>
                                <?php foreach($_SESSION['animals'] as $ani) : ?>
                                    <?php if($ani['gender'] == 'M') : ?>
                                        <option value="<?=$ani['id']?>"><?=$ani['name']?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="dam">Dam</label>
                        <select class="form-select" id="dam" name="dam">
                            <option selected>Untagged</option>
                            <?php if(count($_SESSION['animals'])> 0) : ?>
                                <?php foreach($_SESSION['animals'] as $ani) : ?>
                                    <?php if($ani['gender'] == 'F') : ?>
                                        <option value="<?=$ani['id']?>"><?=$ani['name']?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <label for="name">Stud Count</label>
                        <input required type="number" min='0' required name="stud" class="form-control" id="stud">
                    <button class="btn btn-lg btn-primary my-3 float-end">Add <i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <hr>
        </form>
<!-- QR CARD -->        
        <div class="card d-none" id="qrcard">
            <div class="card-body pos-rel">
                <span class="mb-2">QR Code generated! Take a screenshot and use it on the animal's tag.</span>
                <div id="qrcode" class="my-3"></div>
                <a href="" id="qrlink"></a>
                <div class="mb-2">Next steps:</div>
                <button class="btn btn-info" id="setup-n">Setup nutrition</button>
                <button class="btn btn-info add-another">Add another</button>
            </div>
            
        </div>
<!-- NUTRITION CARD -->
        <form id="nutrition-form"  class="d-none"><!-- class="d-none" -->
            <h3>Nutrition</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" class="ani-id" name="animal_id">
                    <input type="hidden" class="crud-type" name="crud-type" value="add-nutrition">
                    <label for="feed">Select Feed</label>
                    <select class="form-select" id="feed" name="feed">
                        <option value="0" selected>None selected</option>    
                        <?php if(isset($_SESSION['type_feed']) && count($_SESSION['type_feed'])> 0) : ?>
                            <?php foreach($_SESSION['type_feed'] as $ani) : ?>
                                <option value="<?=$ani['id']?>"><?=$ani['name']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="mt-3">-OR-</p>
                    <label for="feed-new">Register New Feed</label>
                    <input type="text" class="form-control" name="feed-new" id="feed-new" placeholder="Feed Name">
                    <hr>
                    <label for="feed_amt">Amount</label>
                    <div class="row">
                        <div class="col">
                            <input required type="number" min="0" step="0.01" class="form-control" name="feed_amt" id="feed_amt">
                        </div>
                        <div class="col">
                            <select class="form-select" name="feed_unit" id="feed_unit">
                                <option value="g" selected>g (grams)</option>
                                <option value="kg">kg (kilograms)</option>
                                <option value="lb">lb (pounds)</option>
                                <option value="oz">oz (ounce)</option>
                                <option value="mL">mL (mililiters)</option>
                                <option value="L">L (Liters)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <label for="supplements">Select Supplements</label>
                    <select class="form-select" id="supplements" name="supplements">
                        <option value="0" selected>None selected</option>    
                        <?php if(isset($_SESSION['type_supplements']) && count($_SESSION['type_supplements'])> 0) : ?>
                            <?php foreach($_SESSION['type_supplements'] as $ani) : ?>
                                <option value="<?=$ani['id']?>"><?=$ani['name']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="mt-3">-OR-</p>
                    <label for="supplements-new">Register New Supplement</label>
                    <input type="text" class="form-control" name="supplements-new" id="supplement-new" placeholder="Supplement Name">
                    <hr>
                    <label for="supplement_amt">Amount</label>
                    <div class="row">
                        <div class="col">
                            <input type="number" min="0" step="0.01" class="form-control" name="supplement_amt" id="supplement_amt">
                        </div>
                        <div class="col">
                            <select class="form-select" name="supplement_unit" id="supplement_unit">
                                <option value="g" selected>g (grams)</option>
                                <option value="kg">kg (kilograms)</option>
                                <option value="lb">lb (pounds)</option>
                                <option value="oz">oz (ounce)</option>
                                <option value="mL">mL (mililiters)</option>
                                <option value="L">L (Liters)</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
            <button class="btn btn-lg btn-primary my-3 float-end" >Save <i class="fa-solid fa-check"></i></button>
        </form>
<!-- ADDITIONAL ACTIONS -->
        <div class="card d-none" id="nextcard"><!-- d-none -->
            <div class="card-body pos-rel">
                <div class="mb-2">Next steps:</div>
                <button class="btn btn-info" id="setup-l">Setup location</button>
                <button class="btn btn-info add-another">Add another</button>
            </div>
        </div>
        <div class="card d-none" id="nextcard2"><!-- d-none -->
            <div class="card-body pos-rel">
                <div class="mb-2">Next steps:</div>
                <button class="btn btn-info add-another">Add another</button>
            </div>
        </div>
<!-- LOCATION CARD -->
        <form id="location-form" class="d-none"><!-- class="d-none" -->
            <h3>Location</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" class="ani-id" name="animal_id">
                    <input type="hidden" class="crud-type" name="crud-type" value="add-loc">
                    <label for="location">Select Location</label>
                    <select class="form-select" id="location" name="location">
                        <option value="0" selected>None selected</option>    
                        <?php if(isset($_SESSION['type_location']) && count($_SESSION['type_location'])> 0) : ?>
                            <?php foreach($_SESSION['type_location'] as $ani) : ?>
                                <option value="<?=$ani['id']?>"><?=$ani['name']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="mt-3">-OR-</p>
                    <label for="location-new">Register New Location</label>
                    <input type="text" class="form-control" name="location-new" id="location-new" placeholder="Location Name">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description" placeholder="(Optional)">
                    <hr>
                    <label for="date">Date</label>
                    <input required type="date" id="date" name="date" class="form-control" >
                </div>
            </div>
            
            <button class="btn btn-lg btn-primary my-3 float-end" >Save <i class="fa-solid fa-check"></i></button>
        </form>
    </div>
    <script src="../../assets/qr/qrcode.min.js"></script>
    <script>
        $(document)
        .on('change','#animal_type',function(){
            $('.breeds').toggleClass('d-none ')
        })
        .on('click','.alert',function(e){
            $(e.currentTarget).addClass('d-none');
        })
        .on('submit','#animal-form', function(e){
            e.preventDefault();            
            var form = document.getElementById('animal-form');
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
                        $('.alert-success, #qrcard').removeClass('d-none');
                        $('#animal-form').addClass('d-none');
                        $('#qrlink').text(res.url).attr('href',res.url);
                        $('.ani-id').val(res.new_animal)
                        new QRCode(document.getElementById("qrcode"), res.url);
                    }else{
                        $('.alert-danger').removeClass('d-none');
                    }
                        
                }
            })
        })
        .on('click','#setup-n',function(){
            $('#qrcard,#nutrition-form').toggleClass('d-none');
        })
        .on('click','#setup-l',function(){
            $('#nextcard,#location-form').toggleClass('d-none');
        })
        .on('submit','#nutrition-form', function(e){
            e.preventDefault();
            if($('#feed').val() == "0" && $('#feed-new').val().trim() == ""){
                $('#feed-new').focus();
                return false;      
            }
            else if($('#supplements').val() != "0" && $('#supplement_amt').val() == ""){
                $('#supplement_amt').focus();
                return false;  
            }else{
                var form = document.getElementById('nutrition-form');
                var formData = new FormData(form);
                
                $.ajax({
                    url:"../../controllers/nutrition",
                    method: 'post',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    enctype: 'multipart/form-data',
                    success: function(res){
                        if(res.success){
                            $('.alert-success,#nextcard').removeClass('d-none');
                            $('#nutrition-form').addClass('d-none');
                        }else{
                            $('.alert-danger').removeClass('d-none');
                        }
                            
                    }
                })
            }
            
        })
        .on('submit','#location-form', function(e){
            e.preventDefault();
            if($('#location').val() == "0" && $('#location-new').val().trim() == ""){
                $('#location-new').focus();
                return false;      
            
            }else{
                var form = document.getElementById('location-form');
                var formData = new FormData(form);
                
                $.ajax({
                    url:"../../controllers/location",
                    method: 'post',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
                    enctype: 'multipart/form-data',
                    success: function(res){
                        if(res.success){
                            $('.alert-success,#nextcard2').removeClass('d-none');
                            $('#location-form').addClass('d-none');
                        }else{
                            $('.alert-danger').removeClass('d-none');
                        }
                            
                    }
                })
            }
            
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
        .on('change','#location',function(){
            if($(this).val() != 0){
                $("#location-new,#description").attr('disabled',true);
            }else{
                $("#location-new,#description").removeAttr('disabled');
            }
        })
        .on('click','.add-another',function(){
            window.location.reload(true);
        })
    </script>

<!-- </div> -->
