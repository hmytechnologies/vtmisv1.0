<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'student_course';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        $studyYear = $_POST['studyYear'];
        $studyAcademicYearID = $_POST['academicYearID'];
        $regNumber = $_POST['regNumber'];
        $semesterID = $_POST['semisterID'];
        $programmeID = $_POST['programmeID'];
        $today = date("Y-m-d");
        $sm = $db->readSemesterSetting($today);
        foreach ($sm as $s) {
            $academicYearID = $s['academicYearID'];
        }

        if ($_REQUEST['action_type'] == 'add') {
            if (!empty($_POST['courseID'])) {
                foreach ($_POST['courseID'] as $courseID) {
                    $course_status = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID,'programmeID'=>$programmeID),'order_by'=>'courseStatus ASC'));                    if(!empty($course_status)) {
                        foreach ($course_status as $cstatus) {
                            $courseStatusID = $cstatus['courseStatusID'];
                        }
                    }
                    else
                    {
                        $courseStatusID=0;
                    }

                    $userData = array(
                        'regNumber' => $regNumber,
                        'courseID' => $courseID,
                        'semesterSettingID' => $semesterID,
                        'courseStatus' => $courseStatusID

                    );
                    $student_course = $db->getRows('student_course', array('where' => array('regNumber' => $regNumber,'courseID'=>$courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                    if(empty($student_course)) {
                        $insert = $db->insert($tblName, $userData);
                    }
                }

                //add regnumber in exam number; this is temporary for those who are not using examination number
                $exam_number = $db->getRows('exam_number', array('where' => array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                if(empty($exam_number)) {
                    $regData = array(
                        'semesterSettingID' => $semesterID,
                        'regNumber' => $regNumber,
                        'examNumber' => $regNumber
                    );
                    $insert = $db->insert("exam_number", $regData);
                }


                //assign next year

                if ($academicYearID != $studyAcademicYearID) {
                    $programmeDuration = $db->getData("programmes", "programmeDuration", "programmeID", $programmeID);
                    if ($programmeDuration > $studyYear) {
                        $maxStudyYear = $db->getMaxStudyYear($regNumber);
                        $studyYear = $maxStudyYear + 1;

                        $study_year = $db->getRows('student_study_year', array('where' => array('regNumber' => $regNumber, 'studyYear' => $studyYear), ' order_by' => 'regNumber ASC'));
                        if (empty($study_year)) {
                            $study_data = array(
                                'regNumber' => $regNumber,
                                'studyYear' => $studyYear,
                                'academicYearID' => $academicYearID,
                                'studyYearStatus' => 1
                            );
                            $resetData = array(
                                'studyYearStatus' => 0
                            );
                            $condition = array('regNumber' => $regNumber);
                            //update previous
                            $update = $db->update("student_study_year", $resetData, $condition);
                            //insert new
                            $insert = $db->insert("student_study_year", $study_data);
                        }
                        //}


                        $fees_year = $db->getRows('student_fees', array('where' => array('regNumber' => $regNumber, 'studyYear' => $studyYear), ' order_by' => 'regNumber ASC'));
                        if (empty($fees_year)) {
                            //assign fees for next year
                            $sumofallfees = $db->getAllFees($programmeID);
                            $sumoncefees = $db->getOnceFees($programmeID);

                            $amount = $sumofallfees - $sumoncefees;

                            //Student Account
                            $today = date('Y-m-d');
                            $invc = date('dm');
                            $invNumber = "INV" . $invc . rand(101, 999);
                            $account_data = array(
                                'regNumber' => $regNumber,
                                'studyYear' => $studyYear,
                                'academicYearID' => $academicYearID,
                                'feesID' => 1,
                                'amount' => $amount,
                                'invoiceNumber' => $invNumber,
                                'invoiceDate' => $today,
                                'feesDescription' => 'University/Tuition Fees'
                            );
                            if (!empty($sumofallfees)) {
                                $condition = array('regNumber' => $regNumber, 'academicYearID' => $academicYearID, 'studyYear' => $studyYear);
                                $update = $db->update("student_fees", $account_data, $condition);
                            } else {
                                $insert = $db->insert("student_fees", $account_data);
                            }
                        }
                    }
                }
            }
            header("Location:index3.php");

        } elseif ($_REQUEST['action_type'] == 'drop') {
            if (!empty($_GET['id'])) {
                $condition = array('studentCourseID' => $_GET['id']);
                $delete = $db->delete($tblName, $condition);
                header("Location:index3.php");
            }
        }
    }
}catch (PDOException $ex)
{
    header("Location:index3.php?sp=organization&msg=err");
}