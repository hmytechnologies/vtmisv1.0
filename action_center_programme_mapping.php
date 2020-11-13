<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'center_programme';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $centerRegID=$_POST['centerRegID'];
        foreach($_POST['programme_level_id'] as $pID=>$programmeLevelID) {
            $userData = array(
                'centerRegistrationID'=>$_POST['centerRegID'],
                'programmeID' => $_POST['programmeID'],
                'programmeLevelID' => $programmeLevelID,
                'status' => 1
            );
            $insert = $db->insert($tblName, $userData);
        }
        $statusMsg = true;
        header("Location:index3.php?sp=center_programmes&msg=succ&action=getRecords&centerID=".$db->my_simple_crypt($centerRegID,'e'));
    }else if($_REQUEST['action_type'] == 'delete'){
        if(!empty($_GET['pid'])){
            $condition = array('programmeID' => $db->my_simple_crypt($_GET['pid'],'d'),'centerRegistrationID'=>$db->my_simple_crypt($_GET['centerID'],'d'));
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=center_programmes&msg=deleted&action=getRecords&centerID=".$_GET['centerID']);
        }
    }
}