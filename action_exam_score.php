<?php
session_start();
ini_set ('display_errors', 1);
// ob_start(); 
error_reporting (E_ALL | E_STRICT);
// try {
    include 'DB.php';
    $db = new DBHelper();
   echo $tblName = 'exam_result';
   echo $tblFinal = 'final_result';
    $err = array();
    $mess = array();
    // if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {

        // if ($_REQUEST['action_type'] == 'add') {

            if (isset($_POST['doSubmit'])) {
                # code...
                $number_student = $_POST['number_student'];
                $academicYearID = $_POST['academicYearID'];
                $courseID = $_POST['courseID'];
                $examDate = $_POST['examDate'];
                // $levelID = $_POST['levelID'];
                $examCategoryID = $_POST['examCategoryID'];
                
            foreach($_POST['examNumber'] as $key=>$exNumber)
            {
                $examNumber=$_POST['examNumber'][$key];
                $examScore=$_POST['score'][$key];
                $status=$_POST['status'][$key];
                $regNumber=$_POST['regNumber'][$key];

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
                                'examNumber' => $examNumber,
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
                            $score = $db->getRows('final_result', array('where' => array('examCategoryID' => $examCategoryID, 'examNumber' => $examNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'examNumber ASC'));
                            if (!empty($score)) {
                                $condition = array('examNumber' => $examNumber, 'academicYearID' => $academicYearID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                                $update = $db->update($tblFinal, $finalData, $condition);
                                $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                                $_SESSION['statusMsg'] = $statusMsg;
                            } else {
                                $insert = $db->insert($tblFinal, $finalData);
                                $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                                $_SESSION['statusMsg'] = $statusMsg;
                            }

                }

                header("Location:index3.php?sp=add_score&cid=" . $db->encrypt($courseID) ."");
            } else {
                # code...


               echo $number_student = $_POST['number_student'];
               echo $academicYearID = $_POST['academicYearID'];
              echo  $courseID = $_POST['courseID'];
             echo   $examDate = $_POST['examDate'];
               echo  $examCategoryID = $_POST['examCategoryID'];
echo 'error';
                foreach($_POST['examnumber'] as $key=>$exNumber)
                {
                    echo $examNumber=$_POST['examnumber'][$key];
                   echo  $examScore=$_POST['score'][$key];
                  echo  $status=$_POST['status'][$key];
                    // $regNumber=$_POST['regNumber'][$key];
                  
                    // $finalData = array(
                    //     'courseID' => $courseID,
                    //     'examNumber' => $examNumber,
                    //     'academicYearID' => $academicYearID,
                    //     'examCategoryID' => $examCategoryID,
                    //     'examDate' => $examDate,
                    //     'examSitting' => 1,
                    //     'examScore' => $db->encrypt($examScore),
                    //     'status' => 0,
                    //     'checked' => 0,
                    //     'present' => $status,
                    //     'comments' => 0
                    // );
                
                    // $score = $db->getRows('final_result', array('where' => array('examCategoryID' => $examCategoryID, 'examNumber' => $examNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'examNumber ASC'));
                    // if (!empty($score)) {
                    //     $condition = array('examNumber' => $examNumber, 'academicYearID' => $academicYearID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                    //     $update = $db->update($tblFinal, $finalData, $condition);
                    //     $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
                    //     $_SESSION['statusMsg'] = $statusMsg;
                    // } else {
                    //     $insert = $db->insert($tblFinal, $finalData);
                    //     $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
                    //     $_SESSION['statusMsg'] = $statusMsg;

                    //     // echo 'error';
                    // }
                
                }

                // header("Location:index3.php?sp=add_score&cid=" . $db->encrypt($courseID) ."");

            }
            
           

               // }
             
            // header("Location:index3.php?sp=add_score');


            // ob_end_flush();
       // } 
        
  //  }   
//     catch (PDOException $ex)
// {
//     // header("Location:index3.php?sp=add_score&cid=" . $db->encrypt($courseID) ."");

// }
        //sup
        /*else if ($_REQUEST['action_type'] == 'add_sup') {
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
                        if(empty($examScore))
                        {
                            unset($examScore);
                        }
                        else {

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
                                'present' => 1,
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
            }
            unset($_SESSION['student']);
            unset($_SESSION['regNumber']);
            header("Location:index3.php?sp=add_score_sup&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
        } //special*/
//        else if ($_REQUEST['action_type'] == 'add_special') {
//            $number_student = $_POST['number_student'];
//            $semesterID = $_POST['semesterID'];
//            $courseID = $_POST['courseID'];
//            $examDate = $_POST['examDate'];
//            $batchID = $_POST['batchID'];
//            $examCategoryID = 4;
//
//            if (!empty($_POST['number_student'])) {
//                for ($x = 1; $x <= $number_student; $x++) {
//                    $examScore = $_POST['score' . $x];
//                    $regNumber = $_POST['regNumber' . $x];
//                    $max_spc_mark = $db->getExamCategoryMaxMark(4, $regNumber);
//                    if ($examCategoryID == 4 && $examScore > $max_spc_mark) {
//                        $err[] = "Sory-Final Exam Marks must be less than " . $max_spc_mark . ", Review your Marks befor you submit to the system";
//                    }
//                    if (empty($err)) {
//                        if(empty($examScore))
//                        {
//                            unset($examScore);
//                        }
//                        else {
//                            if ($examScore < $db->getExamCategoryMark(4, $regNumber))
//                                $supStatus = 1;
//                            else
//                                $supStatus = 0;
//                            $examNumber = $_POST['examNumber' . $x];
//                            $finalData = array(
//                                'courseID' => $courseID,
//                                'examNumber' => $examNumber,
//                                'semesterSettingID' => $semesterID,
//                                'batchID' => $batchID,
//                                'examCategoryID' => $examCategoryID,
//                                'examDate' => $examDate,
//                                'examSitting' => 1,
//                                'examScore' => $db->encrypt($_POST['score' . $x]),
//                                'status' => 0,
//                                'checked' => 0,
//                                'present' => 1,
//                                'exam_remark' => $supStatus,
//                                'comments' => 0
//                            );
//                            $score = $db->getRows('final_result', array('where' => array('examCategoryID' => $examCategoryID, 'examNumber' => $examNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'examNumber ASC'));
//                            if (!empty($score)) {
//                                $condition = array('examNumber' => $examNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
//                                $update = $db->update($tblFinal, $finalData, $condition);
//                                $statusMsg = $update ? 'Exam Score data has been updated successfully.' : 'Some problem occurred, please try again.';
//                                $_SESSION['statusMsg'] = $statusMsg;
//                            } else {
//                                $insert = $db->insert($tblFinal, $finalData);
//                                $statusMsg = $insert ? 'Exam Score data has been inserted successfully.' : 'Some problem occurred, please try again.';
//                                $_SESSION['statusMsg'] = $statusMsg;
//                            }
//                        }
//                    }
//                }
//            }
//            unset($_SESSION['student']);
//            unset($_SESSION['examNumber']);
//            unset($_SESSION['regNumber']);
//            header("Location:index3.php?sp=add_score_special&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
//        }
//        else if($_REQUEST['action_type'] == 'edit_student_score')
//        {
//            $semesterID = $_POST['semesterID'];
//            $courseID = $_POST['courseID'];
//            $batchID = $_POST['batchID'];
//            $regNumber=$_POST['regNumber'];
//            $score=$_POST['score'];
//            $examScore=$_POST['examscore'];
//            $examDate = $_POST['examDate'];
//            $comments=$_POST['comments'];
//
//            $max_cw_mark = $db->getExamCategoryMaxMark(1, $regNumber);
//            if ($score > $max_cw_mark) {
//                $err[] = "Sorry-Course Work Marks must be less than " . $max_cw_mark . ", Review your Marks before you submit to the system";
//                $statusMsg = "Sorry-Course Work Marks must be less than " . $max_cw_mark . ", Review your Marks before you submit to the system";
//                $_SESSION['statusMsg'] = $statusMsg;
//            }
//            $max_sfe_mark = $db->getExamCategoryMaxMark(2, $regNumber);
//            if ($examScore > $max_sfe_mark) {
//                $err[] = "Sorry-Final Exam Marks must be less than " . $max_sfe_mark . ", Review your Marks before you submit to the system";
//                $statusMsg = "Sorry-Final Exam Marks must be less than " . $max_sfe_mark . ", Review your Marks before you submit to the system";
//                $_SESSION['statusMsg'] = $statusMsg;
//            }
//            if (empty($err)) {
//                if ($examScore < $db->getExamCategoryMark(2, $regNumber))
//                    $supStatus = 1;
//                else
//                    $supStatus = 0;
//                //cwk
//                $cwkData = array(
//                    'courseID' => $courseID,
//                    'regNumber' => $regNumber,
//                    'semesterSettingID' => $semesterID,
//                    'batchID' => $batchID,
//                    'examCategoryID' => 1,
//                    'examDate' => $examDate,
//                    'examScore' => $db->encrypt($score),
//                    'exam_remark' => $supStatus,
//                    'comments' => $comments
//                );
//                $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => 1, 'regNumber' => $regNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
//                if (!empty($score)) {
//                    $condition = array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => 1);
//                    $update = $db->update($tblName, $cwkData, $condition);
//                } else {
//                    $insert = $db->insert($tblName, $cwkData);
//                }
//
//                //final exam
//                $examNumber=$regNumber;
//                $finalData = array(
//                    'courseID' => $courseID,
//                    'examNumber' => $regNumber,
//                    'semesterSettingID' => $semesterID,
//                    'batchID' => $batchID,
//                    'examCategoryID' => 2,
//                    'examDate' => $examDate,
//                    'examSitting' => 1,
//                    'examScore' => $db->encrypt($examScore),
//                    'exam_remark' => $supStatus,
//                    'comments' => $comments
//                );
//                $score = $db->getRows('final_result', array('where' => array('examCategoryID' => 2, 'examNumber' => $examNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'examNumber ASC'));
//                if (!empty($score)) {
//                    $condition = array('examNumber' => $examNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => 2);
//                    $update = $db->update($tblFinal, $finalData, $condition);
//                } else {
//                    $insert = $db->insert($tblFinal, $finalData);
//                }
//
//            }
//
//            header("Location:index3.php?sp=view_score&cid=" . $db->encrypt($courseID) . "&sid=" . $db->encrypt($semesterID) . "=&bid=" . $db->encrypt($batchID) . "");
//
//        }


    //}
/*}catch (PDOException $ex)
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
}*/