<?php 
session_start();
if(isset($_SESSION['user']) && $_SESSION['user'] != null){
    header("Location: views/home");
}else{
    header("Location: views/login");
    die();
}

?>