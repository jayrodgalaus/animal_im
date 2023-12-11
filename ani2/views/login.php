<?php 
    require 'head.php';
?>
<div class="container">
        <div class="row xs-mt-5">
            <div class="col-sm-3 col-xs-2"></div>
            <div class="col-sm-6 col-xs-8">
                <div class="card">
                    <div class="card-body">
                        <form id="login-form">
                            <input required type="text" minlength="8" placeholder="Username" class="form-control form-control-sm" id="username" name='username'>
                            <input required type="password" placeholder="Password" class="form-control form-control-sm mt-2" id="password" name='password'>
                            <button class="btn btn-sm btn-primary mt-3 w-100" id="login-btn">LOGIN</button>
                        </form>
                        <a href="register"><button class="btn btn-sm btn-info mt-1 w-100" id="reg-btn">REGISTER</button></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-2"></div>
        </div>
        
</div>

<script>
    $(document).on('submit','#login-form',function(e){
        e.preventDefault();
        var u = $('#username').val().trim();
        var p = $('#password').val().trim();
        var formData = {username:u, password:p};
        $.ajax({
            url:"../controllers/login",
            // headers: {'X-Requested-With': 'XMLHttpRequest'},
            method:'post',
            // processData: false,
            data:formData,
            dataType: 'json',
            success:function(r){
                if(r.success){
                    $('#login-btn').text('SUCCESS').removeClass('btn-primary').addClass('btn-success');
                    setTimeout(() => {
                        if(r.intended != null || r.intended != undefined)
                            // alert(r.intended)
                            window.location.replace(r.intended);
                        else
                            window.location.replace('home');
                    }, 1000);
                }else{
                    $('#login-btn').text('INCORRECT').removeClass('btn-primary').addClass('btn-danger');
                }
            }
        })
    })
    .on('input','#username, #password',function(){
        if($('#login-btn').hasClass('btn-danger'))
            $('#login-btn').text('LOGIN').removeClass('btn-danger').addClass('btn-primary');
    })

</script>


<?php include 'foot.php'; ?>