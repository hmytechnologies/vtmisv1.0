<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'schools';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'schoolName'=>$_POST['name'],
            'schoolCode' => $_POST['code'],
            'campusID'=>$_POST['campusID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=departments#school&msg=succ");

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
            'schoolName'=>$_POST['name'],
            'schoolCode' => $_POST['code'],
            'campusID'=>$_POST['campusID'],
            'status'=>$_POST['status']
        );
            $condition = array('schoolID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=departments#school&msg=edited");
        }
    }
}