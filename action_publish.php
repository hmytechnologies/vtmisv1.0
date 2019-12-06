<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'exam_result';
    $tblFinal='final_result';
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
                    'status'=>1,
                    'checked'=>1
                );
                $condition=array('courseID'=>$courseID,'semesterSettingID'=>$semesterID,'batchID'=>$batchID);
                $updateapp=$db->update($tblName, $userData, $condition);
                $updateapp=$db->update($tblFinal, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            //echo $programmeMajorID;
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    else if(isset($_POST['doReject']) == 'UnPublish')
    {
        
        if($_POST['id'])
        {
            foreach ($id as $courseID)
            {
                
                $userData=array(
                    'status'=>0
                );
                $condition=array('courseID'=>$courseID,'semesterSettingID'=>$semesterID,'batchID'=>$batchID);
                $updateapp=$db->update($tblName, $userData, $condition);
                $updateapp=$db->update($tblFinal, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    else if(isset($_POST['doCheck']) == 'Check')
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
                $updateapp=$db->update($tblFinal, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    else if(isset($_POST['doUncheck']) == 'UnCheck')
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
                $updateapp=$db->update($tblFinal, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    
    
    //}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=publish&msg=error");
}