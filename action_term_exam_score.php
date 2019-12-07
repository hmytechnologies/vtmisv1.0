<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_result';
$err = array();
$mess = array();
if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {

    if ($_REQUEST['action_type'] == 'add') {
        $number_student = $_POST['number_student'];
        $academicYearID = $_POST['academicYearID'];
        $courseID = $_POST['courseID'];
        $examDate = $_POST['examDate'];
       /* $levelID = $_POST['levelID'];*/
        $examCategoryID = $_POST['examCategoryID'];

        foreach ($_POST['regNumber'] as $key => $regNumber) {
            $regNumber = $_POST['regNumber'][$key];
            $examScore = $_POST['score'][$key];
            $status = $_POST['status'][$key];

            /* $max_sfe_mark = $db->getExamCategoryMaxMark(2, $regNumber);
             if ($examScore > $max_sfe_mark) {
                 $err[] = "Sorry-Final Exam Marks must be less than " . $max_sfe_mark . ", Review your Marks before you submit to the system";
                 $statusMsg = "Sorry-Final Exam Marks must be less than " . $max_sfe_mark . ", Review your Marks before you submit to the system";
                 $_SESSION['statusMsg'] = $statusMsg;
             }*/
            /* if (empty($err)) {
                 if ($examScore < $db->getExamCategoryMark(2, $regNumber))
                     $supStatus = 1;
                 else
                     $supStatus = 0;*/
            //$examNumber = $examNumber;
            $finalData = array(
                'courseID' => $courseID,
                'regNumber' => $regNumber,
                'academicYearID' => $academicYearID,
                'examCategoryID' => $examCategoryID,
                'examDate' => $examDate,
                'examSitting' => 1,
                'examScore' => $db->encrypt($examScore),
                'status' => 0,
                'checked' => 0,
                'present' => $status,
                'comments' => 0
            );
            $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'regNumber' => $regNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'examNumber ASC'));
            if (!empty($score)) {
                $condition = array('regNumber' => $regNumber, 'academicYearID' => $academicYearID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                $update = $db->update($tblName, $finalData, $condition);
                $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                $_SESSION['statusMsg'] = $statusMsg;
            } else {
                $insert = $db->insert($tblName, $finalData);
                $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                $_SESSION['statusMsg'] = $statusMsg;
            }

        }
    }
    header("Location:index3.php?sp=add_term_marks&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
}
}catch (PDOException $ex)
{
    if ($_REQUEST['action_type'] == 'add_sup') {
        header("Location:index3.php?sp=add_score_sup&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
    }
    else if($_REQUEST['action_type'] == 'add_special')
    {
        header("Location:index3.php?sp=add_score_special&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
    }
    else
    {
        header("Location:index3.php?sp=add_score&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
    }
}