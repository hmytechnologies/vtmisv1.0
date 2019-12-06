<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'academic_advisor';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'instructorID'=>$_POST['instructorID'],
            'regNumber' => $_POST['regNumber'],
            'requestStatus'=>0,
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=academic_advisor");

    }
}