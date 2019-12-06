<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'courseprogramme';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYearID=$_POST['academicYearID'];
        // $number_subject=$_POST['number_subject'];
        $programmeID=$_POST['programmeID'];
        $batchID=$_POST['batchID'];
        $semesterID = $_POST['semisterID'];
        $studyYear=$_POST['studyYear'];

        $courseID=$_POST['courseID'];
        $courseGradeID=$_POST['courseGradeID'];
        $passMarkID=$_POST['passMarkID'];
        $instructorID=$_POST['instructorID'];


        $userData = array(
            'courseID' => $courseID,
            'programmeID'=>$_POST['programmeID'],
            'batchID'=>$_POST['batchID'],
            'semesterSettingID' => $_POST['semisterID'],
            'studyYear'=>$_POST['studyYear'],
            'instructorID'=>$instructorID,
            'courseGradeID'=>$courseGradeID,
            'passMarkID'=>$passMarkID,
            'courseStatus'=>$_POST['courseStatusID']
        );
        $insert = $db->insert($tblName,$userData);

        //instructor course
        $instructorData = array(
            'instructorID'=>$instructorID,
            'courseID'=>$courseID,
            'batchID'=>$batchID,
            'semesterSettingID' => $semesterID
        );
        $insert = $db->insert("instructor_course",$instructorData);
        /*if(!empty($_POST['course']))
        {
            $i=1;
            foreach ($_POST['course'] as $courseID)
                    {
                    $userData = array(
                    'courseID' => $courseID,
                    'programmeID'=>$_POST['programmeID'],
                    'batchID'=>$_POST['batchID'],
                    'semesterSettingID' => $_POST['semisterID'],
                    'studyYear'=>$_POST['studyYear'],
                    'courseStatus'=>1
                    );
                $insert = $db->insert($tblName,$userData);
                    }
                        unset($_POST['course']);
            }  */
        header("Location:index3.php?sp=semester_course&action=getRecords&msg=succ&programmeID=$programmeID&studyYear=$studyYear&semisterID=$semesterID&batchID=$batchID");
        unset($_SESSION['courseStatusID']);
    }elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        $batchID=$_GET['batchID'];
        $semesterID = $_GET['semisterID'];
        $studyYear=$_GET['studyYear'];
        if(!empty($_GET['id'])){
            $condition = array('courseProgrammeID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);

            //delete instructorCourse
            //$condition_2 = array('courseID' => $_GET['id']);

            header("Location:index3.php?sp=semester_course&action=getRecords&msg=deleted&programmeID=$programmeID&studyYear=$studyYear&semisterID=$semesterID&batchID=$batchID");
        }
    }
}