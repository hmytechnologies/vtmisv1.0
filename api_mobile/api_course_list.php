<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
if(isset($_GET['regNumber'])) {
    $regNumber = $_GET['regNumber'];
    $today = date("Y-m-d");
    $sm = $db->readSemesterSetting($today);
    foreach ($sm as $s) {
        $semisterID = $s['semesterID'];
        $academicYearID = $s['academicYearID'];
        $semesterName = $s['semesterName'];
        $semesterSettingID = $s['semesterSettingID'];
        $endDateRegistration = $s['endDateRegistration'];
    }

    $courseList = $db->getRows('student_course', array('where' => array('regNumber' => $regNumber, 'semesterSettingID' => $semesterSettingID), ' order_by' => ' semesterSettingID ASC'));

    $count = 0;
    $total_credits = 0;
    foreach ($courseList as $list) {
        $count++;
        $studentCourseID = $list['studentCourseID'];
        $courseID = $list['courseID'];
        $semisterID = $list['semesterSettingID'];

        $course = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
        if (!empty($course)) {
            foreach ($course as $c) {

                $output['data'][] = array(
                    $c['courseCode'],
                    $c['courseName'],
                    $c['units']
                );
            }
        }
    }
}
echo json_encode($output);