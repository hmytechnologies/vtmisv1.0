<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_result';

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
                    $examNumber=$filesop[0];
                    $examScore=$filesop[1];
                    //$present=$filesop[2];

                        $finalData = array(
                            'courseID' => $courseID,
                            'regNumber' => $examNumber,
                            'academicYearID' => $academicYearID,
                            /* 'programmeLevelID' => $levelID, */
                            'examCategoryID' => $examCategoryID,
                            'examDate' => $examDate,
                            'examSitting' => 1,
                            'examScore' => $db->encrypt($examScore),
                            'status' => 0,
                            'checked' => 0,
                            'present' => 1,
                            'comments' => 0
                        );

                        //if ($db->isExamNumberExist($examNumber, $academicYearID)) {
                            if (!empty($examScore)) {
                                $score = $db->getRows('exam_result', array('where' => array('examCategoryID' => $examCategoryID, 'regNumber' => $examNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'regNumber ASC'));
                                if (!empty($score)) {
                                    $condition = array('regNumber' => $examNumber, 'academicYearID' => $academicYearID, 'courseID' => $courseID, 'examCategoryID' => $examCategoryID);
                                    $update = $db->update($tblName, $finalData, $condition);
                                    $boolStatus = true;
                                } else {
                                    $insert = $db->insert($tblName, $finalData);
                                    $boolStatus = true;
                                }
                            }
                        //}
                    
            }
        }
        
        if($boolStatus)
        {
            header("Location:index3.php?sp=import_term_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&bid=".$db->encrypt($batchID)."&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=import_term_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&bid=".$db->encrypt($batchID)."&msg=unsucc");
        }
        
    }
}

 } catch (PDOException $ex) {
     header("Location:index3.php?sp=import_term_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&bid=".$db->encrypt($batchID)."&msg=error");
 }