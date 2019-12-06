<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'misc_date_setting';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $semesterID = $_POST['semisterID'];
        $examCategoryID=$_POST['examCategoryID'];
        $startDate = $_POST['startDate'];
        $endDate= $_POST['endDate'];
        $semester=$db->getRows('misc_date_setting',array('where'=>array('semesterSettingID'=>$semesterID,'examCategoryID'=>$examCategoryID),'order_by'=>'semesterSettingID ASC'));
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
                $userData = array(
                    'semesterSettingID' => $semesterID,
                    'examCategoryID'=>$examCategoryID,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'status'=>1
                );
                $insert = $db->insert($tblName, $userData);
                $status = true;
                $msg='succ';
            }
        }
        if($status)
            header("Location:index3.php?sp=misc_date_setting&msg=".$msg);
        else
            header("Location:index3.php?sp=misc_date_setting&msg=".$msg);
    }
    else if($_REQUEST['action_type']=='delete')
    {
        $id=$db->my_simple_crypt($_REQUEST['id'],'d');
        $conditions=array('miscDateID'=>$id);
        $delete=$db->delete($tblName,$conditions);

        header("Location:index3.php?sp=misc_date_setting&msg=deleted");
    }
}