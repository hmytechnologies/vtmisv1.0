<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'student_status';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $academicYearID=$_POST['academicYearID'];
            $graduationDate=$_POST['graduationDate'];
            if (!empty($_POST['regNumber'])) {
                $count = 0;
                foreach ($_POST['regNumber'] as $regNumber => $reg) {
                    //foreach ($_POST['gpa'] as $gpa => $gp) {
                        //if ($regNumber == $gpa) {
                            $graduateData = array(
                                'regNumber' => $reg,
                                'academicYearID' => $academicYearID,
                                //'gpa' => $gp,
                                'graduationDate'=>$graduationDate
                            );
                            $chech_graduate=$db->getRows("graduate_list",array('where'=>array('regNumber'=>$reg)));
                            if(!empty($chech_graduate))
                            {
                                $conditions=array('regNumber'=>$reg);
                                $update2=$db->update("graduate_list",$graduateData,$conditions);
                            }
                            else {
                                $insert = $db->insert("graduate_list", $graduateData);
                            }
                            /*$userData = array(
                                'regNumber' => $_POST['regNumber'],
                                'statusID' => 2,
                                'statusDate' => $graduationDate,
                                'semesterSettingID' => $_POST['semesterID'],
                                'status' => 1
                            );
                            $insert = $db->insert($tblName, $userData);
                            $studentStatusID = $insert;*/

                            $studentData = array(
                                'statusID' => 2
                            );
                            $condition = array('registrationNumber' => $reg);
                            $update = $db->update("student", $studentData, $condition);
                            $boolStatus = true;
                        //}
                    //}
                }
            }
            if ($boolStatus) {
                header("Location:index3.php?sp=approve_graduands&msg=succ");
            } else {
               header("Location:index3.php?sp=approve_graduands&msg=unsucc");
            }
        }
    }
}catch(PDOException $e)
{
    header("Location:index3.php?sp=approve_graduands&msg=error");
}