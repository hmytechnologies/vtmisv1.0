<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'programmefees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        //$programmeID=$_POST['programmeID'];
        $academicYearID=$_POST['academicYearID'];
        $today=date('Y-m-d');
        $invc=date('dm');
        foreach($_POST['regNumber'] as $regNumber)
        {
            $student_list = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
            foreach($student_list as $lst) {
                $programmeID = $lst['programmeID'];
                $progFees = $db->getAllFees($programmeID);
                $paidOnce = $db->getOnceFees($programmeID);

                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
                if(!empty($study_year))
                {
                    foreach ($study_year as $sy)
                    {
                        $studyYear=$sy['studyYear'];
                    }
                }

                if ($studyYear == 1)
                    $requiredFees = $progFees;
                else
                    $requiredFees = $progFees - $paidOnce;
                $invNumber = "INV" . $invc . rand(101, 999);
                $data = array(
                    'regNumber'=>$regNumber,
                    'academicYearID'=>$academicYearID,
                    'studyYear'=>$studyYear,
                    'feesID'=>1,
                    'amount' => $requiredFees,
                    'invoiceNumber' => $invNumber,
                    'invoiceDate' => $today,
                    'feesDescription' => 'University/Tuition Fees'
                );


                $fees_year = $db->getRows('student_fees', array('where' => array('regNumber' => $regNumber, 'studyYear' => $studyYear), ' order_by' => 'regNumber ASC'));
                if(!empty($fees_year))
                {
                    $condition = array('regNumber' => $regNumber, 'academicYearID' => $academicYearID, 'studyYear' => $studyYear);
                    $update=$db->update("student_fees",$data,$condition);
                }
                else
                {
                    $update=$db->insert("student_fees",$data);
                }

            }
        }
        $statusMsg = true;
        header("Location:index3.php?sp=student_payment_list&msg=succ");
    }
}

