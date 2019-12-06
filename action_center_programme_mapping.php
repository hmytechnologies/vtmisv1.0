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
    }elseif($_REQUEST['action_type'] == 'delete'){
        $centerRegID=$_GET['centerRegID'];
        if(!empty($_GET['id'])){
            $condition = array('centerProgrammeID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=center_programmes&msg=deleted&action=getRecords&centerID=".$db->my_simple_crypt($centerRegID,'e'));
        }
    }
}