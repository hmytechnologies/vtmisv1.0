<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'payment_setting';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        //Restrict Same Semister Inserted
        if(!empty($_POST['semisterID']))
        {
            
            $update=$db->update($tblName,$updateData,'');
            $userData = array(
                'semesterSettingID' => $_POST['semisterID'],
                'startDate' => $_POST['startDate'],
                'endDate'=>$_POST['endDate'],
                'minimumAmount'=>$_POST['amount'],
                'penalty'=>$_POST['penalty'],
                'unitOfValue'=>$_POST['unit']
            );
            $insert = $db->insert($tblName,$userData);
            $statusMsg = true;
            header("Location:index3.php?sp=payment_setting&msg=succ");
        }
    }elseif($_REQUEST['action_type'] == 'delete'){
        if(!empty($_GET['id'])){
            $condition = array('paymentSettingID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=payment_setting&msg=deleted");
        }
    }
}