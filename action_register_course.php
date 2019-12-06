<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'courseprogramme';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYearID=$_POST['academicYearID'];
        $number_subject=$_POST['number_subject'];
        $programmeID=$_POST['programmeID'];
        $batchID=$_POST['batchID'];
        $semesterID = $_POST['semisterID'];
        $studyYear=$_POST['studyYear'];
        if(!empty($_POST['course'])) {
            $i = 1;
            $studRegNumber = $db->getStudentProgrammeList($programmeID, $semesterID, $studyYear, $batchID);
            if(!empty($studRegNumber)) {
                foreach($studRegNumber as $regNo) {
                    $regNumber=$regNo['registrationNumber'];
                    foreach ($_POST['course'] as $courseID) {
                        $userData = array(
                            'regNumber'=>$regNumber,
                            'courseID' => $courseID,
                            'semesterSettingID' => $_POST['semisterID']
                        );
                        if($db->isCourseExist($regNumber,$courseID,$semesterID))
                        {
                            unset($_POST['course']);
                        }
                        else {
                            $insert = $db->insert($tblName, $userData);
                        }
                    }
                    unset($_POST['course']);
                }
            }
        }
        header("Location:index3.php?sp=register_course&action=getRecords&msg=succ&programmeID=$programmeID&studyYear=$studyYear&semisterID=$semesterID&batchID=$batchID");
        unset($_SESSION['courseStatusID']);
    }/*elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        $batchID=$_GET['batchID'];
        $semesterID = $_GET['semisterID'];
        $studyYear=$_GET['studyYear'];
        if(!empty($_GET['id'])){
            $condition = array('courseProgrammeID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            header("Location:index3.php?sp=semester_course&action=getRecords&msg=deleted&programmeID=$programmeID&studyYear=$studyYear&semisterID=$semesterID&batchID=$batchID");
        }
    }*/
}