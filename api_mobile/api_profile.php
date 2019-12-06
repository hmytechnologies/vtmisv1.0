<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
if(isset($_GET['regNumber'])){
    $regNumber=$_GET['regNumber'];
    $users = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), 'order_by' => 'userID ASC'));
    if (!empty($users)) {
        $count = 0;
        foreach ($users as $user) {
            $count++;
            $userID = $user['userID'];
            $fname = $user['firstName'];
            $mname = $user['middleName'];
            $lname = $user['lastName'];
            $email = $user['email'];
            $status = $user['status'];
            $departmentID = $user['departmentID'];
            $programmeID = $user['programmeID'];

            $programmeName = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
            $departmentID = $db->getData("programmes", "departmentID", "programmeID", $programmeID);

            $schoolID = $db->getData("programmes", "departmentID", "programmeID", $programmeID);


            $departmentCode = $db->getData("departments", "departmentCode", "departmentID", $departmentID);
            $schoolCode = $db->getData("schools", "schoolCode", "schoolID", $departmentID);
            $campusCode = $db->getData("departments", "departmentCode", "departmentID", $departmentID);


            $student_picture = $user['studentPicture'];
            $phoneNumber = $user['phoneNumber'];
            $name = "$fname $mname $lname";

            $output['data'][] = array(
                $name,
                $regNumber,
                $programmeName,
                $departmentCode,
                $schoolCode,
                $campusCode,
                $phoneNumber,
                $email,
                $student_picture
            );
        }
    }
}
echo json_encode($output);