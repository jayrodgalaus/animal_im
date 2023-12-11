<?php
require 'connect.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && ($_POST['username'] != null || !isset($_POST['username'] ))  ){
    $sql = 'SELECT id,username,name,contact,email,address,usertype_id from user where username =  "'.$_POST['username'].'" and password = "'.$_POST['password'].'"';
    $result = $conn->query($sql);
    session_start();
    if ($result && $result->num_rows == 1) {        
        $data = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $data;
        $_SESSION['animals'] = $data['usertype_id'] == 1 ? getAnimalData($conn,$data['id']): [];
        $_SESSION['type_feed'] = getFeedData($conn,$data['id']);
        $_SESSION['type_supplements'] = getSupplementData($conn,$data['id']);
        $_SESSION['type_location'] = getLocData($conn,$data['id']);
        $_SESSION['current_page'] = 'home';
        $resp['success'] = true;
        $resp['animals'] = $_SESSION['animals'];
        if(isset($_SESSION['intended'])){
            $resp['intended'] = $_SESSION['intended'];
            unset($_SESSION['intended']);
        }
        mysqli_close($conn);
    }else{
        $resp['success'] = false;
    }
    
    echo json_encode($resp);
}

function getAnimalData($conn,$user_id){
    $sql = 'SELECT * from animals where user_id =  "'.$user_id.'"';
    $result = $conn->query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) { 
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return $data;
    }else{
        return [];
    }
}
function getFeedData($conn,$user_id){
    $sql = 'SELECT * from type_feed where user_id =  "'.$user_id.'"';
    $result = $conn->query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) {   
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return $data;
    }else{
        return [];
    }
}
function getLocData($conn,$user_id){
    $sql = 'SELECT * from type_location where user_id =  "'.$user_id.'"';
    $result = $conn->query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) {   
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return $data;
    }else{
        return [];
    }
}
function getSupplementData($conn,$user_id){
    $sql = 'SELECT * from type_supplements where user_id =  "'.$user_id.'"';
    $result = $conn->query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) {   
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        return $data;
    }else{
        return [];
    }
}
?>