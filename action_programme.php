<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'programmes';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'programmeName'=>$_POST['name'],
            'programmeCode' => $_POST['code'],
            'programmeDuration' => $_POST['duration'],
            'programmeTypeID' => $_POST['programme_type_id'],
            'departmentID' => $_POST['departmentID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);

        foreach($_POST['programme_level_id'] as $plID=>$programmeLevelID)
        {
            $pLevelID=$programmeLevelID;
            $programmeID=$insert;
            $status=1;
            $programmeLevelData=array(
                'programmeLevelID'=>$pLevelID,
                'programmeID'=>$programmeID,
                'status'=>$status
            );
            $insertData=$db->insert("programme_trade_level",$programmeLevelData);
        }

        header("Location:index3.php?sp=programmes&msg=succ");
    } elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
                'programmeName'=>$_POST['name'],
                'programmeCode' => $_POST['code'],
                'programmeDuration' => $_POST['duration'],
                'programmeTypeID' => $_POST['programme_type_id'],
                'departmentID' => $_POST['departmentID'],
                'status'=>$_POST['status']
            );
            $condition = array('programmeID' => $_POST['id']);
            $update = $db->update("programmes",$userData,$condition);

            if(!empty($_POST['programme_level_id']))
            {
                $condition = array('programmeID' => $_POST['id']);
                $delete = $db->delete("programme_trade_level",$condition);
                foreach($_POST['programme_level_id'] as $plID=>$programmeLevelID)
                {
                    $pLevelID=$programmeLevelID;
                    $programmeID=$_POST['id'];
                    $status=1;
                    $programmeLevelData=array(
                        'programmeLevelID'=>$pLevelID,
                        'programmeID'=>$programmeID,
                        'status'=>$status
                    );
                    $insertData=$db->insert("programme_trade_level",$programmeLevelData);
                }
            }

            $statusMsg =true;
            header("Location:index3.php?sp=programmes&msg=edited_prog");
        }
    }
}