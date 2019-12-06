<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'departments';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        if($db->isFieldExist($tblName,"departmentName",$_POST['name']) ||($db->isFieldExist($tblName,"departmentCode",$_POST['code'])))
        {
            $statusMsg=false;
            $msg="exist";
        }
        else {
            $userData = array(
                'departmentName' => $_POST['name'],
                'departmentCode' => $_POST['code'],
                'departmentAddress' => $_POST['email'],
                'headOfDepartment' => $_POST['head_of_department'],
                'status' => 1
            );
            $insert = $db->insert($tblName, $userData);
            $statusMsg = true;
            $msg="succ";
        }
            header("Location:index3.php?sp=departments&msg=".$msg);
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
            'departmentName'=>$_POST['name'],
            'departmentCode' => $_POST['code'],
            'departmentAddress' => $_POST['email'],
               'headOfDepartment' => $_POST['head_of_department'],
            'status'=>$_POST['status']
        );
            $condition = array('departmentID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=departments&msg=edited");
        }
    }
}