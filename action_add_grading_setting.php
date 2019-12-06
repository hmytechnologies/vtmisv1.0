<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'grades';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $programmeLevelID=$_POST['programmeLevelID'];
        $academicYearID=$_POST['academicYearID'];
        $number=$_POST['number'];
        for($i=0;$i<count($programmeLevelID);$i++)
        {
            for($x=1;$x<=$number;$x++)
            {
                $userData = array(
                    'gradeCode' =>strtoupper($_POST['gradeCode'.$x]),
                    'gradePoints'=>$_POST['gradePoint'.$x],
                    'startMark' => $_POST['startMark'.$x],
                    'endMark'=>$_POST['endMark'.$x],
                    'programmeLevelID'=>$programmeLevelID[$i],
                    'gradingYearID'=>$_POST['gradingYearID'],
                    'remarkID'=>$_POST['remarks'.$x],
                    'status'=>1
                );
                $insert=$db->insert($tblName,$userData);
            }
        }

        $statusMsg = true;
        if($statusMsg)
        {
            header("Location:index3.php?sp=misc_setting&msg=succ#programme");
        }
        else
        {
            header("Location:index3.php?sp=misc_setting&msg=unsucc#programme");
        }

    }elseif($_REQUEST['action_type'] == 'drop'){
        $condition = array('gradeID' => $_REQUEST['id']);
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=misc_setting&msg=drop#programme");
    }
}

