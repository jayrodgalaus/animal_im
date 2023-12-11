<?php
require 'connect.php';
session_start();
if(isset($_POST['crud-type'])){
    $crud = $_POST['crud-type'];
    switch($crud){
        case 'add-nutrition':
            if(isset($_POST['animal_id'])){ // add to animal
                addNutrition($conn);
            }
            break;
        case 'edit-nutrition':
            editAnimal($conn);
            break;
        case 'delete-nutrition':
            deleteAnimal($conn);
            break;
        case 'get-nutrition':
            getAnimal($conn);
            break;
        case 'add-feed':
            addFeed($conn);
            break;
        case 'edit-feed':
            editAnimal($conn);
            break;
        case 'delete-feed':
            deleteAnimal($conn);
            break;
        case 'get-feed':
            getAnimal($conn);
            break;
        case 'add-supp':
            addFeed($conn);
            break;
        case 'edit-supp':
            editAnimal($conn);
            break;
        case 'delete-supp':
            deleteAnimal($conn);
            break;
        case 'get-supp':
            getAnimal($conn);
            break;
    }
}
function addNutrition($conn){
    $animal_id =$_POST['animal_id'];
    $feed =$_POST['feed'];
    $feed_new =isset($_POST['feed-new']) ? $_POST['feed-new'] : null;
    $feed_amt =$_POST['feed_amt'];
    $feed_unit =$_POST['feed_unit'];
    $supplements =$_POST['supplements'];
    $supplements_new =isset($_POST['supplements-new']) ? $_POST['supplements-new'] : null;
    $supplement_amt =$_POST['supplement_amt'];
    $supplement_unit =$_POST['supplement_unit'];
    $response['success'] = false;
    if($feed == 0 && $feed_new != null){
        $newfeed = addFeed($conn,$feed_new);
        if($newfeed){
            $type_feed = ['id'=> $newfeed,'user_id'=> $_SESSION['user']['id'],'name'=> $feed_new];
            $_SESSION['type_feed'][] = $type_feed;
            $feed = $newfeed;
        }else{
            $response['error'] = "failed to add feed";
            echo json_encode($response);
            die();
        }
    }
    if($supplements == 0 && $supplements_new != null){
        $newsupp = addSupp($conn,$supplements_new);
        if($newsupp){
            $type_supp = ['id'=> $newsupp,'user_id'=> $_SESSION['user']['id'],'name'=> $supplements_new];
            $_SESSION['type_supplements'][] = $type_supp;
            $supplements = $newsupp;
        }else{
            $response['error'] = "failed to add supplement";
            echo json_encode($response);
            die();
        }
    }

    //add nutrient
    $sql = "INSERT INTO nutrition (feed_id,feed_amt,feed_unit) VALUES ('$feed','$feed_amt','$feed_unit')";
    if($supplements != 0){
        $sql = "INSERT INTO nutrition (feed_id,feed_amt,feed_unit,supplement_id,supplement_amt,supplement_unit) VALUES ('$feed','$feed_amt','$feed_unit','$supplements','$supplement_amt','$supplement_unit')";
    }
    if($conn->query($sql) === true){
        $last_id = mysqli_insert_id($conn);
        //save to animal
        $sql = "UPDATE animals set nutrition_id = '$last_id' where id='$animal_id'";
        if($conn->query($sql) === true){
            $response['success'] = true;
            echo json_encode($response);
        }else{
            $response['error'] = "failed to update animal";
            echo json_encode($response);
            die();
        }
    }else{
        $response['error'] = "failed to add nutrient";
        echo json_encode($response);
        die();
    }

    
}
function editNutrition(){

}

function addFeed($conn,$name){
    $uid = $_SESSION['user']['id'];
    $sql = "INSERT INTO type_feed (user_id, name) values ('$uid','$name')";
    if ($conn->query($sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);
        return $last_id;
    }
    return false;
}
function addSupp($conn,$name){
    $uid = $_SESSION['user']['id'];
    $sql = "INSERT INTO type_supplements (user_id, name) values ('$uid','$name')";
    if ($conn->query($sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);
        return $last_id;
    }
    return false;
}
?>