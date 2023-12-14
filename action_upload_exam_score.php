<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);


try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_result';
$tblFinal = 'final_result';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
  
    if($_REQUEST['action_type'] == 'add'){
        
        $examCategoryID=$_POST['examCategoryID'];
        
        $examDate=$_POST['examDate'];
       
        $courseID=$_POST['courseID'];
        
        $academicYearID=$_POST['academicYearID'];
        
        $levelID=$_POST['levelID'];
 

          $file = $_FILES['csv_file']['tmp_name'];
           $handle = fopen($file, "r");
              $boolStatus=false;
            if ($file == NULL) 
            {
                $boolStatus=false;
            }
        else
        {
                fgetcsv($handle);
                while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                
                    $examNumber=$filesop[0];
                    $examScore=$filesop[1];
                    $present=$filesop[2];

                    
                        $finalData = array(
                            'courseID' => $courseID,
                            'examNumber' => $examNumber,
                            'academicYearID' => $academicYearID,
                            // 'programmeLevelID' => $levelID,
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
                                    // 'ali simai';
                                } else {
                                    $insert = $db->insert($tblFinal, $finalData);
                                    $boolStatus = true;
                                    // echo 'dapry';
                                }
                            }
                        }
                
            }
                     
                
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


else {
    echo "error";
}

 } catch (PDOException $ex) {
     header("Location:index3.php?sp=import_score&cid=".$db->encrypt($courseID)."&sid=".$db->encrypt($semesterID)."&msg=error");
 }