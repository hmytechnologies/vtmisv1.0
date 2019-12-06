<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_setting';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        //Restrict Same Semister Inserted
        $semesterSettingID = $_POST['semisterID'];
        $startDate = $_POST['startDate'];
        $endDate =$_POST['endDate'];

        $semester=$db->getRows('exam_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'semesterSettingID ASC'));
        if(!empty($semester))
        {
            $status=false;
            $msg="exist";
        }
        else {
            if ($endDate < $startDate) {
                $status = false;
                $msg="date";
            } else {
                $updateData = array(
                    'examStatus' => 0
                );

                $update = $db->update($tblName, $updateData, '');
                $userData = array(
                    'semesterSettingID' => $_POST['semisterID'],
                    'startDate' => $_POST['startDate'],
                    'endDate' => $_POST['endDate'],
                    'examStatus' => 1
                );
                $insert = $db->insert($tblName, $userData);
                $status = true;
                $msg='succ';
            }
        }
        if($status)
            header("Location:index3.php?sp=exam_setting&msg=".$msg);
        else
            header("Location:index3.php?sp=exam_setting&msg=".$msg);
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            /*$condition = array('examSettingID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;*/
            $semesterStatus=$_POST['status'];
            if($semesterStatus==1)
            {
                $semester=$db->getRows('exam_setting',array('where'=>array('examStatus'=>1),'order_by'=>'semesterSettingID ASC'));
                foreach ($semester as $sm)
                {
                    $semesterSettingID=$sm['semesterSettingID'];
                    $condition = array('semesterSettingID'=>$semesterSettingID);
                    $userDataStatus = array('examStatus' =>0);
                    $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }
            $userData = array(
                'semesterSettingID'=>$_POST['semisterID'],
                'startDate' => $_POST['startDate'],
                'endDate'=>$_POST['endDate'],
                'examStatus'=>$semesterStatus
            );
            $condition = array('examSettingID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=exam_setting&msg=edited");
        }
    }
}