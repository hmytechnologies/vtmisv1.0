<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'exam_result';
    $tblFinal = 'exam_result';
    $err = array();
    $mess = array();
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {

        if ($_REQUEST['action_type'] == 'add') {
            $number_student = $_POST['number_student'];
            $semesterID = $_POST['semesterID'];
            $courseID = $_POST['courseID'];
            $examDate = $_POST['examDate'];
            $batchID = $_POST['batchID'];
            $examCategoryID = $_POST['examCategoryID'];

            if (!empty($_POST['number_student'])) {
                for ($x = 1; $x <= $number_student; $x++) {
                    $examScore = $_POST['score' . $x];
                    $regNumber = $_POST['regNumber' . $x];
                    $max_mark = $db->getExamCategoryMaxMark($examCategoryID, $regNumber);

                    if ($examScore > $max_mark) {
                        $err[] = "Sory-Your Marks must be less than " . $max_mark . ", Review your Marks before you submit to the system";

                    }
                    if (empty($err)) {
                            if ($examScore < $db->getExamCategoryMark($examCategoryID, $regNumber))
                                $supStatus = 1;
                            else
                                $supStatus = 0;
                            $userData = array(
                                'courseID' => $courseID,
                                'regNumber' => $regNumber,
                                'semesterSettingID' => $semesterID,
                                'batchID' => $batchID,
                                'examCategoryID' => $examCategoryID,
                                'examDate' => $examDate,
                                'examSitting' => 1,
                                'examScore' => $db->encrypt($_POST['score' . $x]),
                                'status' => 0,
                                'checked' => 0,
                                'present' => $_POST['status' . $x],
                                'exam_remark' => $supStatus,
                                'comments' => 0
                            );

                            $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'regNumber' => $regNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                            if (!empty($score)) {
                                $condition = array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                                $update = $db->update($tblName, $userData, $condition);
                                $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                                $_SESSION['statusMsg'] = $statusMsg;
                            } else {
                                $insert = $db->insert($tblName, $userData);
                                $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                                $_SESSION['statusMsg'] = $statusMsg;
                            }

                    }//end of if exam category
                }
            }
            unset($_SESSION['student']);
            unset($_SESSION['regNumber']);
            header("Location:index3.php?sp=add_score&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
        } //sup
        else if ($_REQUEST['action_type'] == 'add_sup') {
            $number_student = $_POST['number_student'];
            $semesterID = $_POST['semesterID'];
            $courseID = $_POST['courseID'];
            $examDate = $_POST['examDate'];
            $batchID = $_POST['batchID'];
            $examCategoryID = 3;

            if (!empty($_POST['number_student'])) {
                for ($x = 1; $x <= $number_student; $x++) {
                    $examScore = $_POST['score' . $x];
                    $regNumber = $_POST['regNumber' . $x];
                    $max_sup_mark = $db->getExamCategoryMaxMark(3, $regNumber);
                    if ($examCategoryID == 3 && $examScore > $max_sup_mark) {
                        $err[] = "Sory-Supplementary Marks must be less than " . $max_sup_mark . ", Review your Marks before you submit to the system";
                    }
                    if (empty($err)) {
                        if ($examScore < $db->getExamCategoryMark($examCategoryID, $regNumber))
                            $supStatus = -1;
                        else
                            $supStatus = 0;
                        $userData = array(
                            'courseID' => $courseID,
                            'regNumber' => $regNumber,
                            'semesterSettingID' => $semesterID,
                            'batchID' => $batchID,
                            'examCategoryID' => $examCategoryID,
                            'examDate' => $examDate,
                            'examSitting' => 2,
                            'examScore' => $db->encrypt($_POST['score' . $x]),
                            'status' => 0,
                            'checked' => 0,
                            'present' => $_POST['status' . $x],
                            'exam_remark' => $supStatus,
                            'comments' => 0
                        );

                        $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'regNumber' => $regNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                        if (!empty($score)) {
                            $condition = array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                            $update = $db->update($tblName, $userData, $condition);
                            $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                            $_SESSION['statusMsg'] = $statusMsg;
                        } else {
                            $insert = $db->insert($tblName, $userData);
                            $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                            $_SESSION['statusMsg'] = $statusMsg;
                        }

                    }
                }
            }
            unset($_SESSION['student']);
            unset($_SESSION['regNumber']);
            header("Location:index3.php?sp=add_score_sup&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
        } //special
        else if ($_REQUEST['action_type'] == 'add_special') {
            $number_student = $_POST['number_student'];
            $semesterID = $_POST['semesterID'];
            $courseID = $_POST['courseID'];
            $examDate = $_POST['examDate'];
            $batchID = $_POST['batchID'];
            $examCategoryID = 4;

            if (!empty($_POST['number_student'])) {
                for ($x = 1; $x <= $number_student; $x++) {
                    $examScore = $_POST['score' . $x];
                    $regNumber = $_POST['regNumber' . $x];
                    $max_spc_mark = $db->getExamCategoryMaxMark(4, $regNumber);
                    if ($examCategoryID == 4 && $examScore > $max_spc_mark) {
                        $err[] = "Sory-Final Exam Marks must be less than " . $max_spc_mark . ", Review your Marks befor you submit to the system";
                    }
                    if (empty($err)) {
                        if ($examScore < $db->getExamCategoryMark(4, $regNumber))
                            $supStatus = 1;
                        else
                            $supStatus = 0;
                        $examNumber = $_POST['examNumber' . $x];
                        $finalData = array(
                            'courseID' => $courseID,
                            'examNumber' => $examNumber,
                            'semesterSettingID' => $semesterID,
                            'batchID' => $batchID,
                            'examCategoryID' => $examCategoryID,
                            'examDate' => $examDate,
                            'examSitting' => 1,
                            'examScore' => $db->encrypt($_POST['score' . $x]),
                            'status' => 0,
                            'checked' => 0,
                            'present' => $_POST['status' . $x],
                            'exam_remark' => $supStatus,
                            'comments' => 0
                        );
                        $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'examNumber' => $examNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'examNumber ASC'));
                        if (!empty($score)) {
                            $condition = array('examNumber' => $examNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                            $update = $db->update($tblFinal, $finalData, $condition);
                            $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                            $_SESSION['statusMsg'] = $statusMsg;
                        } else {
                            $insert = $db->insert($tblFinal, $finalData);
                            $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                            $_SESSION['statusMsg'] = $statusMsg;
                        }
                    }
                }
            }
            unset($_SESSION['student']);
            unset($_SESSION['examNumber']);
            unset($_SESSION['regNumber']);
            header("Location:index3.php?sp=add_score_special&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
        }
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