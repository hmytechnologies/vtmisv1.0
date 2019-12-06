<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_transcript';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $studyYear=$_POST['studyYearID'];
        $academicYearID=$_POST['academicYearID'];
        $regNumber=$_POST['regNumber'];
        $programmeLevelID=$_POST['programmeLevelID'];
        $programmeID=$_POST['programmeID'];


        $data=array(
            'regNumber'=>$regNumber,
            'academicYearID'=>$academicYearID,
            'programmeLevelID'=>$programmeLevelID,
            'programmeID'=>$programmeID,
            'studyYear'=>$studyYear
        );

        $insert=$db->insert($tblName,$data);

        $statusMsg = true;
        if($statusMsg)
        {
            header("Location:index3.php?sp=transcript_details&regNo=".$db->my_simple_crypt($regNumber,'e')."&msg=succ");

        }
        else
        {
            header("Location:index3.php?sp=transcript_details&regNo=".$db->my_simple_crypt($regNumber,'e')."&msg=unsucc");

        }

    }elseif($_REQUEST['action_type'] == 'drop'){
        $condition = array('ID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=transcript_details&regNo=".$_REQUEST['regNo']."&msg=drop");
    }
}

