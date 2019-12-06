<?php
session_start();
require_once '../DB.php';
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);

$db=new DBHelper();
$output = array('data' => array());


$academicYearID=$db->getCurrentAcademicYear();

if($_SESSION['main_role_session']==7)
    $center='all';
else
    $center=$_SESSION['department_session'];


$student = $db->getAdmittedStudentList($center,$academicYearID);

//$student = $db->getRows('student',array('order_by'=>'studentID ASC'));
if(!empty($student)){$count = 0;
foreach($student as $std){ $count++;
$studentID=$std['studentID'];
$fname=$std['firstName'];
$mname=$std['middleName'];
$lname=$std['lastName'];
$gender=$std['gender'];
$dob=$std['dateOfBirth'];
$email=$std['email'];
$registrationNumber=$std['registrationNumber'];
$admissionNumber=$std['admissionNumber'];
$regStatus=$std['rgStatus'];
$academicYearID=$std['academicYearID'];
$name="$fname $mname $lname";

//programme

    $student_prog=$db->getRows("student_programme",array("where"=>array("regNumber"=>$registrationNumber,'currentStatus'=>1)));
    if(!empty($student_prog))
    {
        foreach($student_prog as $spg)
        {
            $centerRegistrationID=$spg['centerID'];
            $programmeLevelID=$spg['programmeLevelID'];
            $programmeID=$spg['programmeID'];
        }
    }
    else
    {
        $centerRegistrationID="";
        $programmeLevelID="";
        $programmeID="";
    }

$editButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=edit_student&id='.$db->my_simple_crypt($studentID,'e').'" class="glyphicon glyphicon-edit"></a>
	</div>';
$viewButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=view_student_profile&id='.$db->my_simple_crypt($studentID,'e').'" class="glyphicon glyphicon-eye-open"></a>
	</div>';

if($regStatus==1)
    $rStatus="Registered";
else
    $rStatus="Not Registered";


$output['data'][] = array(
    $count,
    $name,
    $gender,
    date('d-m-Y',strtotime($dob)),
    $registrationNumber,
    $db->getData("center_registration","centerName","centerRegistrationID",$centerRegistrationID),
    $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID),
    $db->getData("programmes","programmeName","programmeID",$programmeID),
    $editButton,
    $viewButton
);

}

}

echo json_encode($output);
//$db->close();
