<?php
session_start();
/**
 * Created by PhpStorm.
 * User: massoudhamad
 * Date: 11/3/18
 * Time: 6:13 PM
 */
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'exam_result';
    //if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
    //{
    $id=$_POST['id'];
    $batchID=$_POST['batchID'];
    $semesterID=$_POST['semesterID'];
    $status=false;
    if(isset($_POST['doAdmit']) == 'Publish')
    {
        if($_POST['id'])
        {
            foreach ($id as $courseID)
            {

                $userData=array(
                    'checked'=>1
                );
                $condition=array('courseID'=>$courseID,'semesterSettingID'=>$semesterID,'batchID'=>$batchID);
                $updateapp=$db->update($tblName, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=instructor_exam_results&msg=succ#publish");
        }
        else
        {
            header("Location:index3.php?sp=instructor_exam_results&msg=unsucc#publish");
        }
    }
    else if(isset($_POST['doReject']) == 'Unpublish')
    {

        if($_POST['id'])
        {
            foreach ($id as $courseID)
            {

                $userData=array(
                    'checked'=>0
                );
                $condition=array('courseID'=>$courseID,'semesterSettingID'=>$semesterID,'batchID'=>$batchID);
                $updateapp=$db->update($tblName, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=instructor_exam_results&msg=succ#publish");
        }
        else
        {
            header("Location:index3.php?sp=instructor_exam_results&msg=unsucc#publish");
        }
    }


    //}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=instructor_exam_results&msg=error#publish");
}
?>