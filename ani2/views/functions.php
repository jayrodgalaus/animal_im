<?php
require '../controllers/connect.php';

function getAnimalInfo($conn,$id){
    $sql = "SELECT * FROM animals where id = '$id'";
    $result = $conn->query($sql);
    if($result && $result->num_rows == 1){
        return mysqli_fetch_assoc($result);
    }
}

?>