<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'courseprogramme';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYearID=$_POST['academicYearID'];
        $programmeID=$_POST['programmeID'];
        $programmeLevelID=$_POST['programmeLevelID'];


        if(!empty($_POST['course']))
        {
            $i=1;
            foreach ($_POST['course'] as $courseID)
                    {
                    $userData = array(
                    'courseID' => $courseID,
                    'programmeID'=>$_POST['programmeID'],
                    'academicYearID' => $_POST['academicYearID'],
                    'programmeLevelID'=>$_POST['programmeLevelID']
                    );
                        $insert = $db->insert($tblName,$userData);
                    }
                        unset($_POST['course']);
            }
            header("Location:index3.php?sp=semester_course&action=getRecords&msg=succ&programmeID=".$db->my_simple_crypt($programmeID,'e')."&academicYearID=".$db->my_simple_crypt($academicYearID,'e')."&levelID=".$db->my_simple_crypt($programmeLevelID,'e')."");
            unset($_SESSION['courseStatusID']);
    }elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        $academicYearID = $_GET['academicYearID'];
        $programmeLevelID=$_GET['levelID'];
        if(!empty($_GET['id'])){
            $condition = array('courseProgrammeID' => $db->my_simple_crypt($_GET['id'],'d'));
            $delete = $db->delete($tblName,$condition);

            //delete instructorCourse
            //$condition_2 = array('courseID' => $_GET['id']);

            header("Location:index3.php?sp=semester_course&action=getRecords&msg=deleted&programmeID=$programmeID&academicYearID=$academicYearID&levelID=$programmeLevelID");
        }
}
}