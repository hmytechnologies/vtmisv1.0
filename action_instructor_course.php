<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'center_programme_course';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $centerID=$_POST['centerID'];
        $academicYearID=$_POST['academicYearID'];
        $programmeLevelID=$_POST['programmeLevelID'];
        $programmeID=$_POST['programmeID'];
        $centerCode=$db->getData("center_registration","centerCode","centerRegistrationID",$centerID);
        $academicYear=$db->getData("academic_year","academicYear","academicYearID",$academicYearID);

        $year=substr($academicYear,2);

            foreach ($_POST['instructorID'] as $key=>$instructorID)
            {
                $instructorID=$_POST['instructorID'][$key];
                $courseID=$_POST['courseID'][$key];
                $programmeLevelID=$_POST['levelID'][$key];
                if(!empty($instructorID))
                {
                $courseCode=$db->getData("course","courseCode","courseID",$courseID);
                $plvelCode=$db->getData("programme_level","programmeLevelCode","programmeLevelID",$programmeLevelID);
                $classNumber="$centerCode$plvelCode$year$courseCode";
                $userData = array(
                    'courseID' => $courseID,
                    'academicYearID' => $academicYearID,
                    'programmeLevelID'=>$programmeLevelID,
                    'programmeID'=>$programmeID,
                    'classNumber'=>$classNumber,
                    'centerID'=>$centerID,
                    'staffID'=>$instructorID
                );
                $insert = $db->insert($tblName,$userData);
            }
        }
            header("Location:index3.php?sp=instructor_course&action=getRecords&msg=succ&acaid=".$db->my_simple_crypt($academicYearID,'e')."");
    }elseif($_REQUEST['action_type'] == 'delete'){

        // echo $_GET['id'];
        if(!empty($_GET['id'])){
           echo  $condition = array('centerProgrammeCourseID' => $db->my_simple_crypt($_GET['id'],'d'));
           echo  $delete = $db->delete($tblName,$condition);
            // header("Location:index3.php?sp=instructor_course&action=getRecords&msg=delete&acaid=" . $db->my_simple_crypt($academicYearID, 'e') . "");
        }
}
}