<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'programmemaping';
$programmeID=$_POST['programmeID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        foreach($_POST['programme_level_id'] as $pID=>$programmeLevelID) {
            $userData = array(
                'programmeID' => $_POST['programmeID'],
                'courseID' => $_POST['courseID'],
                'programmeLevelID' => $programmeLevelID,
                'courseStatusID' => $_POST['courseStatusID'],
                'courseStatus' => 1
            );
            $insert = $db->insert($tblName, $userData);
        }
        $statusMsg = true;
        header("Location:index3.php?sp=pmapping&msg=succ&action=getRecords&programmeID=$programmeID");
    }elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        if(!empty($_GET['id'])){
            $condition = array('programmeMappingID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=pmapping&msg=deleted&action=getRecords&programmeID=$programmeID");
        }
    }
}