<?php
session_start();
// ini_set ('display_errors', 1);
// error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYearID=$_POST['admissionYearID'];
        $programmeLevelID=$_POST['programmeLevelID'];
        $programmeID=$_POST['programmeID'];
        foreach($_POST['regNumber'] as $key=>$regNumber)
        {
            $regNumber=$_POST['regNumber'][$key];
            $hosteller=$_POST['hosteller'][$key];
           echo  $centerID=$_POST['centerID'][$key];

                $study_year= $db->getRows('student_programme',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
                if(empty($study_year))
                {
                    $resetdata = array(
                        'currentStatus' => 0
                    );
                    $condition = array('regNumber' => $regNumber);
                    $update=$db->update("student_programme",$resetdata,$condition);


                    $data = array(
                        'regNumber' => $regNumber,
                        'programmeLevelID' => $programmeLevelID,
                        'programmeID' => $programmeID,
                        'centerID' => $centerID,
                        'academicYearID' => $academicYearID,
                        'currentStatus' => 1
                    );
                    $insert=$db->insert("student_programme",$data);


                }

            $hosteller= $db->getRows('student_hostel',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
            if(empty($hosteller))
            {
                $hostelData = array(
                    'regNumber' => $regNumber,
                    'academicYearID' => $academicYearID,
                    'hostelStatus' => $hosteller
                );
                $insert=$db->insert("student_hostel",$hostelData);
            }


        }
        $statusMsg = true;
        header("Location:index3.php?sp=transfer_student&msg=succ");
    }else{


     }
}

