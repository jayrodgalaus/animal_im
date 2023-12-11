
<div class="pos-rel t0">
    <button class="navbar-toggler pos-f mt-2 ms-3 top-layer" type="button"  id="main-toggle" ><i class="fa-solid fa-bars"></i></button>
    <div id="nav-container" class="pos-f">
        <div  id="navbar-main">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
                
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item  <?= $_SESSION['current_page'] == 'home'? 'active' : ''; ?> " target="home" >Dashboard</li>
                    <li class="nav-link <?= $_SESSION['current_page'] == 'patients-r'? 'active' : ''; ?> " target="patients-r">My Patients</a></li>
                </ul>
                <hr>
                <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?= $_SESSION['user']['name']?></strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout">Sign out</a></li>
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click','#main-toggle',function(){        
    $('#navbar-main').toggleClass('d-flex');        
})
.on('click','#navbar-main .nav-item', function(e){
    var page = $(e.target).attr('target');
    window.location.replace("pages/"+page);
})
</script>