<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'academic_year';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
            $academic_year=$_POST['academic_year'];
            $status = $_POST['status'];

    if($_REQUEST['action_type'] == 'add'){
         $acadYear=$db->getRows('academic_year',array('where'=>array('academicYear'=>$academic_year),'order_by'=>'academicYear ASC'));
        if(!empty($acadYear))
        {
            $boolStatus=false;
               header("Location:index3.php?sp=academicyear&msg=unsecc"); 
        }else
        {
             if($status==1)
            {
                $year=$db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                foreach ($year as $yr) 
                {
                    $academic_year_id=$yr['academicYearID'];
                    $academic_year=$yr['academicYear'];
                             $condition = array('academicYearID'=>$academic_year_id);
                             $userDataStatus = array('status' =>0);
                             $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }
        $userData = array(
            'academicYear'=>$_POST['academic_year'],
            'status' => $_POST['status']
        );
        $insert = $db->insert($tblName,$userData);
        $boolStatus=true;
            header("Location:index3.php?sp=academicyear&msg=secc");
        }

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            if($status==1)
            {
                $year=$db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                foreach ($year as $yr) 
                {
                    $academic_year_id=$yr['academicYearID'];
                    $academic_year=$yr['academicYear'];

                             $condition = array('academicYearID' => $academic_year_id);
                             $userDataStatus = array('status' => 0);
                            $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }
            $userData = array(
                'status' => $status
            );
            $condition = array('academicYearID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $boolStatus=true;
            if($boolStatus)
            header("Location:index3.php?sp=academicyear&msg=edited");
            else
               header("Location:index3.php?sp=academicyear&msg=fail"); 
        }
    }
}