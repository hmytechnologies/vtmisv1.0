<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'semester_setting';


//echO $_POST['id'];

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        //Restrict Same Semister Inserted
        $academicYearID=$_POST['academicYearID'];
        $gradingYearID = $_POST['gradingYearID'];

        $t1startDate = $_POST['t1startDate'];
        $t1endDate= $_POST['t1endDate'];
        $vt1startDate = $_POST['vt1startDate'];
        $vt1endDate= $_POST['vt1endDate'];

        $t2startDate = $_POST['t2startDate'];
        $t2endDate= $_POST['t2endDate'];
        $vt2startDate = $_POST['vt2startDate'];
        $vt2endDate= $_POST['vt2endDate'];

        $finalStartDate=$_POST['finalStartDate'];
        $finalEndDate=$_POST['finalEndDate'];
        $vfinalStartDate=$_POST['vfinalStartDate'];
        $vfinalEndDate=$_POST['vfinalEndDate'];

        $academicYearStatus = $_POST['status'];



        $semester=$db->getRows('semester_setting',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>'semesterSettingID ASC'));
        if(!empty($semester))
        {
            $status=false;
            $msg="exist";
        }
        else {
                if ($academicYearStatus == 1) {
                    $semester = $db->getRows('semester_setting', array('where' => array('semesterStatus' => 1), 'order_by' => 'semesterSettingID ASC'));
                    foreach ($semester as $sm) {
                        $semesterSettingID = $sm['semesterSettingID'];
                        $condition = array('semesterSettingID' => $semesterSettingID);
                        $userDataStatus = array('semesterStatus' => 0);
                        $update = $db->update($tblName, $userDataStatus, $condition);
                    }
                }
                $userData = array(
                    'gradingYearID' => $gradingYearID,
                    'academicYearID' => $academicYearID,
                    't1startDate' => $t1startDate,
                    't1endDate' => $t1endDate,
                    'vt1startDate'=>$vt1startDate,
                    'vt1endDate'=>$vt1endDate,
                    't2startDate' => $t2startDate,
                    't2endDate' => $t2endDate,
                    'vt2startDate'=>$vt2startDate,
                    'vt2endDate'=>$vt2endDate,
                    'examStartDate'=>$finalStartDate,
                    'examEndDate'=>$finalEndDate,
                    'vestartDate'=>$vfinalEndDate,
                    'veendDate'=>$vfinalEndDate,
                    'semesterStatus' => $academicYearStatus

                );
                $insert = $db->insert($tblName, $userData);
                $status = true;
                $msg='succ';

        }
    if($status)
        header("Location:index3.php?sp=semester_date_setting&msg=".$msg);
    else
        header("Location:index3.php?sp=semester_date_setting&msg=".$msg);
    }elseif($_REQUEST['action_type'] == 'edit'){

        echo $_REQUEST['action_type'];

       

        
        // if(!empty($_POST['id'])){
            $semesterStatus=$_POST['status'];

            echo $_POST['id'];
             
            // if($semesterStatus==1)
            // {
            //     $semester=$db->getRows('semester_setting',array('where'=>array('semesterStatus'=>1),'order_by'=>'semesterSettingID ASC'));
            //     foreach ($semester as $sm)
            //     {
            //         $semesterSettingID=$sm['semesterSettingID'];
            //         $condition = array('semesterSettingID'=>$semesterSettingID);
            //         $userDataStatus = array('semesterStatus' =>0);
            //         $update = $db->update($tblName,$userDataStatus,$condition);
            //     }
            // }
            // $semesterName=$db->getData("semister","semisterName","semisterID",$_POST['semisterID'])." ".$db->getData("academic_year","academicYear","academicYearID",$_POST['academicYearID'])."-".$db->getData("batch","batchCode","batchID",$_POST['batchID']);
            $userData = array(

                'gradingYearID' => $_POST['gradingYearID'],
                'academicYearID'=>$_POST['academicID'],
                //'semesterName'=>$semesterName,
                't1startDate'=>$_POST['t1startDate'],
                't1endDate'=>$_POST['t1endDate'],
                'vt1startDate'=>$_POST['vt1startDate'],
                'vt1endDate'=>$_POST['vt1endDate'],
                't2startDate'=>$_POST['t2startDate'],
                't2endDate'=>$_POST['t2endDate'],
                'vt2startDate'=>$_POST['vt2startDate'],
                'vt2endDate'=>$_POST['vt2endDate'],
                'examStartDate'=>$_POST['finalStartDate'],
                'examEndDate'=>$_POST['finalEndDate'],
                'vestartDate'=>$_POST['vfinalStartDate'],
                'veendDate'=>$_POST['vfinalEndDate'],
                'semesterStatus'=>$semesterStatus,
                
            );
            $condition = array('semesterSettingID' => $_POST['id']);
            $update = $db->update('semester_setting',$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=semester_date_setting&msg=edited");
        // }



        // if(!empty($_POST['id'])){

        //     echo
        //     $userData = array(
        //     'courseCode' => $courseCode,
        //     'courseName'=>$courseName,
        //     'capacity' => $_POST['capacity'],
        //     'units'=>$_POST['units'],
        //      'courseTypeID'=>$_POST['courseTypeID'],
        //      'numberOfHours'=>$_POST['nhrs'],
        //     'departmentID'=>$_POST['department_id'],
        //     'status'=>$_POST['status']
        // );
        //     $condition = array('courseID' => $_POST['id']);
        //     $update = $db->update($tblName,$userData,$condition);
        //     $statusMsg = true;
        //     header("Location:index3.php?sp=course&msg=edited");
        // }
    }
}