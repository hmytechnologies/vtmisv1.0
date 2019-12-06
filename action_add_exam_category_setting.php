<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_category_setting';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $programmeLevelID=$_POST['programmeLevelID'];
        $academicYearID=$_POST['gradingYearID'];
        $number=$_POST['number'];
        for($i=0;$i<count($programmeLevelID);$i++)
        {
            for($x=1;$x<=$number;$x++)
            {
                $data=array(
                    'examCategoryID'=>$_POST['examCategoryID'.$x],
                    'programmeLevelID'=>$programmeLevelID[$i],
                    'academicYearID'=>$_POST['gradingYearID'],
                    'mMark'=>$_POST['maxMark'.$x],
                    'wMark'=>$_POST['wMark'.$x],
                    'passMark'=>$_POST['passMark'.$x],
                    'status'=>1
                );
                $insert=$db->insert($tblName,$data);
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
        $condition = array('examCategorySettingID' => $_REQUEST['id']);
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=misc_setting&msg=drop#programme");
    }
}

