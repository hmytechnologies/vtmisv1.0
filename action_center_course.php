<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'center_programme_course';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYearID=$_POST['academicYearID'];
        $programmeID=$_POST['programmeID'];
        $programmeLevelID=$_POST['programmeLevelID'];

        //classnumber
        $centerCode=$db->getData("center_registration","centerCode","centerRegistrationID",$_SESSION['department_session']);
        $plvelCode=$db->getData("programme_level","programmeLevelCode","programmeLevelID",$_POST['programmeLevelID']);
        $pCode=$db->getData("programmes","programmeCode","programmeID",$_POST['programmeID']);
        $academicYear=$db->getData("academic_year","academicYear","academicYearID",$academicYearID);
        $year=substr($academicYear,2);

        if(!empty($_POST['course']))
        {
            $i=1;
            foreach ($_POST['course'] as $courseID)
            {
                $courseCode=$db->getData("course","courseCode","courseID",$courseID);
                $classNumber="$centerCode$plvelCode$pCode$year$courseCode";
                $userData = array(
                    'courseID' => $courseID,
                    'programmeID'=>$_POST['programmeID'],
                    'academicYearID' => $_POST['academicYearID'],
                    'programmeLevelID'=>$_POST['programmeLevelID'],
                    'classNumber'=>$classNumber,
                    'centerID'=>$_SESSION['department_session']
                );
                $insert = $db->insert($tblName,$userData);
            }
            unset($_POST['course']);
        }
        header("Location:index3.php?sp=center_semester_course&action=getRecords&msg=succ&programmeID=".$db->my_simple_crypt($programmeID,'e')."&academicYearID=".$db->my_simple_crypt($academicYearID,'e')."&levelID=".$db->my_simple_crypt($programmeLevelID,'e')."");
        unset($_SESSION['courseStatusID']);
    }elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        $academicYearID = $_GET['academicYearID'];
        $programmeLevelID=$_GET['levelID'];
        if(!empty($_GET['id'])){
            $condition = array('centerProgrammeCourseID' => $db->my_simple_crypt($_GET['id'],'d'));
            $delete = $db->delete($tblName,$condition);

            header("Location:index3.php?sp=center_semester_course&action=getRecords&msg=deleted&programmeID=$programmeID&academicYearID=$academicYearID&levelID=$programmeLevelID");
        }
    }
}