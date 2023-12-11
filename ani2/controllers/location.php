<?php

require 'connect.php';
session_start();
if(isset($_POST['crud-type'])){
    $crud = $_POST['crud-type'];
    switch($crud){
        case 'add-loc':
            if(isset($_POST['animal_id']))
                addAnimalLoc($conn);
            break;
        case 'add':
            addLoc($conn);
            break;
        case 'edit':
            editLoc($conn);
            break;
        case 'delete':
            deleteLoc($conn);
            break;
        case 'get':
            getLoc($conn);
            break;
    }
}
function addAnimalLoc($conn){
    $ani_id = $_POST['animal_id'];
    $loc_id = $_POST['location'];
    $loc_new = isset($_POST['location-new']) ? $_POST['location-new'] : null;
    $date = $_POST['date'];
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    if($loc_id == 0 && $loc_new != null){
        $newloc = addLoc($conn,$loc_new,$description);
        if($newloc){
            $type_loc = ['id'=> $newloc,'user_id'=> $_SESSION['user']['id'],'name'=> $loc_new,'description'=>$description,'date'=>$date];
            $_SESSION['type_location'][] = $type_loc;
            $loc_id = $newloc;
        }else{
            $response['error'] = "failed to add location";
            echo json_encode($response);
            die();
        }
    }
    $sql = "INSERT INTO hist_locations (location_id,animal_id,date) values ('$loc_id','$ani_id','$date')";
    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        echo json_encode($response);
        die();
    }else{
        $response['error'] = "failed to add location history";
        echo json_encode($response);
        die();
    }

}
function addLoc($conn,$name,$desc=null){
    $uid = $_SESSION['user']['id'];
    $sql = "INSERT INTO type_location (user_id, name) values ('$uid','$name')";
    if($desc != null){
        $sql = "INSERT INTO type_location (user_id, name,description) values ('$uid','$name','$desc')";
    }
    if ($conn->query($sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);
        return $last_id;
    }
    return false;
}
function editLoc($conn){}
function deleteLoc($conn){}
function getLoc($conn){}
?>