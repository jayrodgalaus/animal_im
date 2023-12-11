<?php
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['page'] != null ){
    $_SESSION['current_page'] = $_POST['page'];
    
}


?>