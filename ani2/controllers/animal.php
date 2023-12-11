<?php


session_start();

require 'connect.php';

if(isset($_POST['crud-type'])){
    $crud = $_POST['crud-type'];
    switch($crud){
        case 'add':
            addAnimal($conn);
            break;
        case 'edit':
            editAnimal($conn);
            break;
        case 'delete':
            deleteAnimal($conn);
            break;
        case 'get':
            getAnimal($conn);
            break;
    }
}
function addAnimal($conn){

    $response['success'] = false;
    $user_id = $_SESSION['user']['id'];
    $animal_type = $_POST['animal_type'];
    if($animal_type == 'Cattle'){
        $breed = $_POST['breed1'];
    }else if($animal_type == 'Goat'){
        $breed = $_POST['breed2'];
    }
    $weight = $_POST['weight'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $sire_id = strlen(trim($_POST['sire'])) == 0 ? NULL : $_POST['sire'];
    $dam_id = strlen(trim($_POST['dam'])) == 0 ? NULL : $_POST['dam'];
    $stud_count = $_POST['stud'];
    $sql = "INSERT INTO animals (user_id,animal_type,breed,weight,name,gender,sire_id,dam_id,stud_count) VALUES ('$user_id','$animal_type','$breed','$weight','$name','$gender','$sire_id','$dam_id','$stud_count')";
    
    if ($conn->query($sql) === TRUE) {
        $last_id = mysqli_insert_id($conn);
        $response['url'] = "http://" . $_SERVER['SERVER_NAME'] .'/views/pages/myanimals-view?id='.$last_id;
        $response['new_animal'] = $last_id;
        $uid = $_SESSION['user']['id'];
        $sql = "SELECT * from animals where user_id = '$uid'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) { 
            $data = [];
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            $_SESSION['animals'] = $data;
            $response['animal'] = $_SESSION['animals'];
            $response['data'] = $data;
            $response['success'] = true;
        }
    }

    echo json_encode($response);
}
function editAnimal($conn){
    $response['success'] = false;
    $animal_id = $_POST['animal-id'];
    $animal_type = $_POST['animal_type'];
    if($animal_type == 'Cattle'){
        $breed = $_POST['breed1'];
    }else if($animal_type == 'Goat'){
        $breed = $_POST['breed2'];
    }
    $weight = $_POST['weight'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $sire_id = strlen(trim($_POST['sire'])) == 0 || $_POST['sire'] == "Untagged" ? 0 : $_POST['sire'];
    $dam_id = strlen(trim($_POST['dam'])) == 0 || $_POST['dam'] == "Untagged" ? 0 : $_POST['dam'];
    $stud_count = $_POST['stud'];
    $sql = "UPDATE animals set 
    animal_type = '$animal_type',
    breed = '$breed',
    weight = '$weight',
    name = '$name',
    gender = '$gender',
    sire_id = '$sire_id',
    dam_id = '$dam_id',
    stud_count = '$stud_count' where id = '$animal_id'";
    
    if ($conn->query($sql) === TRUE) {
        $uid = $_SESSION['user']['id'];
        $sql = "SELECT * from animals where user_id = '$uid'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) { 
            $data = [];
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            $_SESSION['animals'] = $data;
            $response['animal'] = $_SESSION['animals'];
            $response['data'] = $data;
            $response['success'] = true;
        }
    }

    echo json_encode($response);
}
function deleteAnimal($conn){
    $resp['success'] =false;
    $uid = $_SESSION['user']['id'];
    $aid = $_POST['animal_id'];
    //check if its a sire or dame
    $sql = "SELECT * from animals where sire_id = '$aid' OR dam_id = '$aid' ";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $resp['error'] = "Already registered as sire or dam";
        echo json_encode($resp);
        die();
    }else{
        $sql = "DELETE FROM animals where user_id = '$uid' and id = '$aid' ";
        $resp['sql'] = $sql;
        if ($conn->query($sql) === TRUE) {
            $animals = $_SESSION['animals'];
            $resp['ses'] = $animals;
            for($i = 0; $i < count($animals); $i++){
                if($animals[$i]['id'] == $aid){
                    array_splice($animals,$i,1);
                    $resp['new'] = $animals;
                    $_SESSION['animals'] = $animals;

                }
            }
            $resp['success'] = true;
            echo json_encode($resp);
        }
    }
    
}
function getAnimal($conn){

}
?>