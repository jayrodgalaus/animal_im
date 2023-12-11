<?php
require 'connect.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && 
isset($_POST['username']) && $_POST['username'] != null && 
isset($_POST['password']) && $_POST['password'] !=null && 
isset($_POST['name']) && $_POST['name'] !=null && 
isset($_POST['contact']) && $_POST['contact'] !=null && 
isset($_POST['address']) && $_POST['address'] !=null && 
isset($_POST['type']) && $_POST['type'] != null
){
    if(!isset($_POST['email']) || $_POST['email'] == null || $_POST['email'] == '')
        $email = null;
    else
        $email = $_POST['email'];

    $response['success'] = false;
    $response['duplicate'] = false;
    //check record
    $sql = 'SELECT * from user where username =  "'.$_POST['username'].'"';
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $response['duplicate'] = true;
    }else{
        //insert record
        $sql = 'INSERT INTO user (username,password,name,contact,email,address,usertype_id) VALUES ("'.$_POST['username'].'","'.$_POST['password'].'","'.$_POST['name'].'","'.$_POST['contact'].'","'.$email.'","'.$_POST['address'].'","'.$_POST['type'].'")';

        if ($conn->query($sql) === TRUE) {
            session_start();
            $sql = 'SELECT id,username,name,contact,email,address,usertype_id from user where username =  "'.$_POST['username'].'" and password = "'.$_POST['password'].'"';
            $result = $conn->query($sql);
            $data = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $data;
            $_SESSION['animals'] = [];
            $_SESSION['type_feed'] = [];
            $_SESSION['type_supplements'] = [];
            $_SESSION['type_location'] = [];
            $_SESSION['current_page'] = 'home';
            $response['success'] = true;
        }
    }

    
    
    echo json_encode($response);

}
?>