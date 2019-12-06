<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_payment';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $regNumber=$_POST['regNumber'];
        $academicYearID=$_POST['academicYearID'];
        $semesterSettingID=$_POST['semesterID'];
        $userData = array(
                'regNumber' => $_POST['regNumber'],
                'semesterSettingID'=>$semesterSettingID,
                'academicYearID'=>$academicYearID,
                'amount'=>$_POST['amount'],
                'receiptNumber' => $_POST['receiptno'],
                'paymentDate'=>$_POST['paymentDate']
        );
            $insert = $db->insert($tblName,$userData);
            $statusMsg = true;
            header("Location:index3.php?sp=process_payment&action=getRecords&search_student=".$db->my_simple_crypt($regNumber,'e')."&msg=succ#singlestudent");
        
    }elseif($_REQUEST['action_type'] == 'drop'){
        if(!empty($_GET['id'])){
            $regNumber=$_REQUEST['regNumber'];
            $condition = array('studentPaymentID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=process_payment&action=getRecords&search_student=".$db->my_simple_crypt($regNumber,'e')."&msg=deleted#singlestudent");
        }
    }
}