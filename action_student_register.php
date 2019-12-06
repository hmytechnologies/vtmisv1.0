<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_course';
$searchStudent=$_POST['searchStudent'];
$studentID=$_POST['studentID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'regNumber'=>$_POST['regNumber'],
            'courseID'=>$_POST['courseID'],
            'semesterSettingID' => $_POST['semesterID'],
            'courseStatus' => $_POST['courseStatusID'],
            'courseAssignBy'=>0
        );
        if($db->checkCourseExist($_POST['courseID'],$_POST['regNumber'],$_POST['semesterID'])==false)
        {
            $insert = $db->insert($tblName,$userData);
            $statusMsg = $insert?'succ':'unsucc';
            header("Location:index3.php?sp=course_register&action=getRecords&search_student=$searchStudent&msg=$statusMsg");
        }
        else 
        {
            $statusMsg = "exist";
            header("Location:index3.php?sp=course_register&action=getRecords&search_student=$searchStudent&msg=$statusMsg");
        }

        //register exam number
       /* $exam_number = $db->getRows('exam_number', array('where' => array('regNumber' => $_POST['regNumber'], 'semesterSettingID' => $_POST['semesterID']), ' order_by' => 'regNumber ASC'));
        if(empty($exam_number)) {
            $regData = array(
                'semesterSettingID' => $_POST['semesterID'],
                'regNumber' => $_POST['regNumber'],
                'examNumber' => $_POST['regNumber']
            );
            $insert = $db->insert("exam_number", $regData);
        }*/
        
    }
    else if($_REQUEST['action_type'] == 'add_course_register')
    {
        $number_subject=$_POST['number_subject'];
        $programmeID=$_POST['programmeID'];
        $programmeLevelID=$_POST['programmeLevelID'];
        $academicYearID = $_POST['academicYearID'];

            $reg_number=$db->getStudentList($programmeID,$programmeLevelID,$academicYearID);
            foreach($reg_number as $rgno) {
                $regNumber=$rgno['registrationNumber'];
                foreach ($_POST['course'] as $courseID) {
                    $userData = array(
                        'regNumber' => $regNumber,
                        'courseID' => $courseID,
                        'academicYearID' => $academicYearID,
                        'programmeID' => $programmeID,
                        'programmeLevelID'=>$programmeLevelID
                    );
                    if ($db->checkCourseExist($courseID, $regNumber, $academicYearID) == false) {
                        $insert = $db->insert($tblName, $userData);
                    }

                }

                //register for exam
                /*$exam_number = $db->getRows('exam_number', array('where' => array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                if(empty($exam_number)) {
                    $regData = array(
                        'semesterSettingID' => $semesterID,
                        'regNumber' => $regNumber,
                        'examNumber' => $regNumber
                    );
                    $insert = $db->insert("exam_number", $regData);
                }*/

            }
        header("Location:index3.php?sp=course_register");
    }
    elseif($_REQUEST['action_type'] == 'drop'){
        if(!empty($_REQUEST['id'])){
            $searchStudent=$db->my_simple_crypt($_REQUEST['regNumber'],'d');
            $condition = array('studentCourseID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->delete($tblName,$condition);
            $statusMsg = $update?'deleted':'error';
            header("Location:index3.php?sp=course_register&action=getRecords&search_student=".$db->my_simple_crypt($searchStudent,'e')."&msg=$statusMsg");
        }
    }
}