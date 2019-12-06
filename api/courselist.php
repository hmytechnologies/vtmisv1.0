<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

$today=date("Y-m-d");
$sm=$db->readSemesterSetting($today);
foreach ($sm as $s) {
    $semisterID=$s['semesterID'];
    $academicYearID=$s['academicYearID'];
    $semesterName=$s['semesterName'];
    $semesterSettingID=$s['semesterSettingID'];
}
$courseprogramme = $db->getSemesterCourse($semesterSettingID);
if(!empty($courseprogramme)){$count = 0; foreach($courseprogramme as $std){ $count++;
$courseID=$std['courseID'];
$courseProgrammeID=$std['courseProgrammeID'];
$batchID=$std['batchID'];

$course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
if(!empty($course))
{
    foreach($course as $c)
    {
        $courseCode=$c['courseCode'];
        $courseName=$c['courseName'];
        $courseTypeID=$c['courseTypeID'];
    }
}

$instructor = $db->getRows('instructor_course',array('where'=>array('courseProgrammeID'=>$courseProgrammeID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseProgrammeID ASC'));
if(!empty($instructor))
{
    foreach($instructor as $i)
    {
        $instructorID=$i['instructorID'];
        $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
    }
}
else
{
    $instructorName="Not assigned";
}

$studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);

$viewButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=viewresult&id='.$courseID.'" class="glyphicon glyphicon-eye-open"></a>
	</div>';


$output['data'][] = array(
    $count,
    $courseName,
    $courseCode,
    $db->getData("course_type","courseType","courseTypeID",$courseTypeID),
    $studentNumber,
    $db->getData("batch","batchName","batchID",$batchID),
    $instructorName,
    $viewButton
);

}

}

echo json_encode($output);
//$db->close();
