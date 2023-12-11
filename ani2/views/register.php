<?php 
    require 'head.php';
?>
<div class="container">
        <div class="row xs-mt-5">
            <div class="col-sm-3 col-xs-2"></div>
            <div class="col-sm-6 col-xs-8">
                <div class="card">
                    <div class="card-body">
                        <form action="../controllers/register" method="post" id="register-form">
                            <input required type="text" minlength="8" placeholder="Username" class="form-control form-control-sm" id="username" name='username'>
                            <input required type="password" placeholder="Password" class="form-control form-control-sm mt-2" id="password" name='password'>
                            <input required type="password" placeholder="Retype Password" class="form-control form-control-sm mt-2" id="password2" name='password2'>
                            <input required type="text" placeholder="Full Name" class="form-control form-control-sm mt-2" id="name" name='name'>
                            <input required type="text" placeholder="Contact" class="form-control form-control-sm mt-2" id="contact" name='contact'>
                            <input type="email" placeholder="Email(Optional)" class="form-control form-control-sm mt-2" id="email" name='email'>
                            <input required type="text" placeholder="Address" class="form-control form-control-sm mt-2" id="address" name='address'>
                            <p class="text-secondary">I am a:</p>
                            <div class="form-check">
                                <label class="form-check-label" for="farmer">Farmer</label>
                                <input type="radio" name="type" id="farmer" class="form-check-input" value='1' checked>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="vet">Vet</label>
                                <input type="radio" name="type" id="vet" class="form-check-input" value='2'>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label"for="other">Other</label>
                                <input type="radio" name="type" id="other" class="form-check-input" value='3'>
                            </div>
                            <button class="btn btn-sm btn-primary mt-3 w-100" id="register-btn">REGISTER</button>
                        </form>
                        <p class="text-secondary  mt-3">Already have an account? Login instead</p>
                        <a href="login"><button class="btn btn-sm btn-info w-100">LOGIN</button></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-2"></div>
        </div>
        
</div>

<script>
    $(document).on('submit','#register-form',function(e){
        e.preventDefault();
        var u = $('#username').val().trim();
        var p = $('#password').val().trim();
        var n = $('#name').val().trim();
        var c = $('#contact').val().trim();
        var e = $('#email').val().trim();
        var a = $('#address').val().trim();
        var t = $('input[name=type]:checked').val();
        var formData = {
            username:u, 
            password:p,
            name: n,
            contact:c,
            email:e,
            address:a,
            type:t,
        };
        $.ajax({
            url:"../controllers/register",
            method:'post',
            data:formData,
            dataType: 'json',
            success:function(r){
                if(r.success == true){
                    $('#register-btn').text('SUCCESS').removeClass('btn-primary').addClass('btn-success');
                    setTimeout(() => {
                        window.location.replace('home');
                    }, 1000);
                }else{
                    if(r.duplicate == true)
                        $('#register-btn').text('Username exists').removeClass('btn-primary').addClass('btn-danger');
                    else
                        $('#register-btn').text('INCORRECT').removeClass('btn-primary').addClass('btn-danger');
                }
            }
        })
    })
    .on('input','#username, #password, #password2',function(){
        var p = $('#password').val().trim();
        var p2 = $('#password2').val().trim();
        if(p != p2){
            $('#register-btn').attr('disabled',true);
        }else{
            $('#register-btn').removeAttr('disabled');
        }
        if($('#register-btn').hasClass('btn-danger'))
            $('#register-btn').text('REGISTER').removeClass('btn-danger').addClass('btn-primary');
    })

</script>

<?php 
    include 'foot.php';
?>