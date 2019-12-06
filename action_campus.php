<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'campus';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'campusName'=>$_POST['name'],
            'campusAddress' => $_POST['code']
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=departments#campus&msg=succ");

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
                'campusName'=>$_POST['name'],
                'campusAddress' => $_POST['code'],
                'accountNumber'=>$_POST['accnumber'],
                'accountName'=>$_POST['accname'],
                'bankName'=>$_POST['bank'],
                'swiftCode'=>$_POST['swiftcode']
        );
            $condition = array('campusID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=departments#campus&msg=edited");
        }
    }
}