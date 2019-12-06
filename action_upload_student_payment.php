<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'student_payment';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $semesterID=$_POST['semesterID'];
            $academicYearID=$db->getData("semester_setting","academicYearID","semesterSettingID",$semesterID);
            //upload file
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");
            if ($file == NULL)
            {
                $boolStatus=false;
            }
            else
            {
                while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
                {
                    $regNumber = $filesop[0];
                    $amount = $filesop[1];
                    $receiptNo= $filesop[2];
                    $paymentDate= $filesop[3];
                    
                    if($db->isFieldExist("student","registrationNumber",$regNumber))
                    {   
                        //add users first
                        $userData = array(
                            'regNumber'=>$regNumber,
                            'semesterSettingID'=>$semesterID,
                            'academicYearID'=>$academicYearID,
                            'amount'=>$amount,
                            'receiptNumber'=>$receiptNo,
                            'paymentDate'=>$paymentDate
                        );
                        $insert = $db->insert($tblName,$userData);
                        $userID=$insert;
                        
                    }
                }
                        $boolStatus=true;
                }
                
            }
            
            if($boolStatus)
            {
                header("Location:index3.php?sp=process_payment&msg=usucc");
            }
            else
            {
                header("Location:index3.php?sp=process_payment&msg=unsuccu");
            }
            
    }  
} catch (PDOException $ex) {
     header("Location:index3.php?sp=process_payment&msg=uerror");
 }