<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'programme_level';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'programmeLevel'=>$_POST['name'],
            'programmeLevelCode' => $_POST['code'],
            'units' => $_POST['number'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
         header("Location:index3.php?sp=programmes&msg=succ#plevels");
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
             $userData = array(
            'programmeLevel'=>$_POST['name'],
            'programmeLevelCode' => $_POST['code'],
            'units' => $_POST['number'],
            'status'=>$_POST['status']
            );
            $condition = array('programmeLevelID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
             header("Location:index3.php?sp=programmes&msg=edited#plevels");
        }
    }
}