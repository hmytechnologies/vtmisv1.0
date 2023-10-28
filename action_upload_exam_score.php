<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_result';
$tblFinal = 'final_result';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        //upload file
        $examCategoryID=$_POST['examCategoryID'];
        $examDate=$_POST['examDate'];
        $courseID=$_POST['courseID'];
        $academicYearID=$_POST['academicYearID'];
        $levelID=$_POST['levelID'];
        $boolStatus=false;
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        if ($file == NULL)
        {
            $boolStatus=false;
        }
        else
        {
            fgetcsv($handle);
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                //if($examCategoryID==2)
                //{
                    $examNumber=$filesop[0];
                    $examScore=$filesop[1];
                    $present=$filesop[2];

                    //$regNumber=$examNumber;
                    //if($db->isFieldExist("student","registrationNumber",$regNumber)) {
                        /*if ($examScore < $db->getExamCategoryMark(2, $regNumber))
                            $supStatus = 1;
                        else
                            $supStatus = 0;
                        
                        $max_sfe_mark = $db->getExamCategoryMaxMark(2, $regNumber);
                         if ($examScore > $max_sfe_mark) {
                            $boolStatus = false;
                        } */

                        $finalData = array(
                            'courseID' => $courseID,
                            'examNumber' => $examNumber,
                            'academicYearID' => $academicYearID,
                            'programmeLevelID' => $levelID,
                            'examCategoryID' => $examCategoryID,
                            'examDate' => $examDate,
                            'examSitting' => 1,
                            'examScore' => $db->encrypt($examScore),
                            'status' => 0,
                            'checked' => 0,
                            'present' => $present,
                            'comments' => 0
                        );

                        if ($db->isExamNumberExist($examNumber, $academicYearID)) {
                            if (!empty($examScore)) {
                                $score = $db->getRows('final_result', array('where' => array('examCategoryID' => $examCategoryID, 'examNumber' => $examNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'examNumber ASC'));
                                if (!empty($score)) {
                                    $condition = array('examNumber' => $examNumber, 'academicYearID' => $academicYearID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                                    $update = $db->update($tblFinal, $finalData, $condition);
                                    $boolStatus = true;
                                } else {
                                    $insert = $db->insert($tblFinal, $finalData);
                                    $boolStatus = true;
                                }
                            }
                        }
                    //}
                //}
                /*else 
                {  
                    $regNumber=$filesop[0];
                    $examScore=$filesop[1];
                    $present=$filesop[2];
                    if($db->isFieldExist("student","registrationNumber",$regNumber))
                    {
                        if ($examScore < $db->getExamCategoryMark($examCategoryID, $regNumber))
                            $supStatus = 1;
                        else
                            $supStatus = 0;

                        $max_cw_mark = $db->getExamCategoryMaxMark(1, $regNumber);
                        if ($examScore > $max_cw_mark) {
                            //$err[] = "Sorry-Course Work Marks must be less than " . $max_cw_mark . ", Review your Marks before you submit to the system";
                            $boolStatus=false;
                            //continue;
                        }
                        $userData = array(
                            'courseID' => $courseID,
                            'regNumber'=>$regNumber,
                            'semesterSettingID'=>$semesterID,
                            'batchID'=>$batchID,
                            'examCategoryID'=>$examCategoryID,
                            'examDate'=>$examDate,
                            'examSitting'=>1,
                            'examScore'=>$db->encrypt($examScore),
                            'status'=>0,
                            'checked'=>0,
                            'present' => $present,
                            'exam_remark' => $supStatus,
                            'comments'=>0
                        );
                        if($db->isRegNumberExist($regNumber,$courseID,$semesterID)) {

                            if (!empty($examScore)) {
                                $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'regNumber' => $regNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterID), ' order_by' => 'regNumber ASC'));
                                if (!empty($score)) {
                                    $condition = array('regNumber' => $regNumber, 'semesterSettingID' => $semesterID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                                    $update = $db->update($tblName, $userData, $condition);
                                    //$statusMsg = $update?'Exam Score data has been updated successfully.':'Some problem occurred, please try again.';
                                    //$_SESSION['statusMsg'] = $statusMsg;
                                    $boolStatus = true;
                                } else {
                                    $insert = $db->insert($tblName, $userData);
                                    //$statusMsg = $insert?'Exam Score data has been inserted successfully.':'Some problem occurred, please try again.';
                                    //$_SESSION['statusMsg'] = $statusMsg;
                                    $boolStatus = true;
                                }
                            }
                        }
                    }
                }*/
            }
                     
                //$boolStatus=true;
        }
        
        if($boolStatus)
        {
            header("Location:index3.php?sp=import_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=import_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&msg=unsucc");
        }
        
    }
}

 } catch (PDOException $ex) {
     header("Location:index3.php?sp=import_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&msg=error");
 }