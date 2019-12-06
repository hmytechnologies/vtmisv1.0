<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'assessment_configuration';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $courseID=$_POST['courseID'];
        $semesterID=$_POST['semesterSettingID'];
        $batchID=$_POST['batchID'];
        $instructorID=$_POST['instructorID'];
        $data=array(
            'assessmentTypeID'=>$_POST['assTypeID'],
            'courseID'=>$courseID,
            'semesterSettingID'=>$semesterID,
            'batchID'=>$batchID,
            'instructorID'=>$instructorID,
            'questionUpload'=>$_POST['qUpload'],
            'answerUpload'=>$_POST['aUpload'],
            'dueDate'=>$_POST['dueDate'],
            'maxMark'=>$_POST['mMark'],
            'weightedMark'=>$_POST['wMark']
        );

        $insert=$db->insert($tblName,$data);

        $statusMsg = true;
        if($statusMsg)
        {
            header("Location:index3.php?sp=marks_configuration&id=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&instID=".$db->encrypt($instructorID)."&bid=".$db->encrypt($batchID)."&msg=succ");

        }
        else
        {
            header("Location:index3.php?sp=marks_configuration&id=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&instID=".$db->encrypt($instructorID)."&bid=".$db->encrypt($batchID)."&msg=unsucc");
        }

    } else if($_REQUEST['action_type'] == 'edit'){
        $courseID=$_POST['courseID'];
        $semesterID=$_POST['semesterSettingID'];
        $batchID=$_POST['batchID'];
        $instructorID=$_POST['instructorID'];
        $edit_data=array(
            'assessmentTypeID'=>$_POST['assTypeID'],
            'questionUpload'=>$_POST['qUpload'],
            'answerUpload'=>$_POST['aUpload'],
            'dueDate'=>$_POST['dueDate'],
            'maxMark'=>$_POST['mMark'],
            'weightedMark'=>$_POST['wMark']
        );
        $edit_cond=array('assessmentConfigurationID'=>$_POST['assessmentConfigurationID']);
        $update=$db->update($tblName,$edit_data,$edit_cond);


        $statusMsg = true;
        if($statusMsg)
        {
            header("Location:index3.php?sp=marks_configuration&id=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&instID=".$db->encrypt($instructorID)."&bid=".$db->encrypt($batchID)."&msg=succ");

        }
        else
        {
            header("Location:index3.php?sp=marks_configuration&id=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&instID=".$db->encrypt($instructorID)."&bid=".$db->encrypt($batchID)."&msg=unsucc");
        }

    } elseif($_REQUEST['action_type'] == 'drop'){
        $condition = array('assessmentConfigurationID' => $db->decrypt($_REQUEST['assID']));
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=marks_configuration&id=".$_REQUEST['id']."&sid=".$_REQUEST['sid']."&instID=".$_REQUEST['instID']."&bid=".$_REQUEST['bid']."&msg=delete");
    }
}

