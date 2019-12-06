<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'programmefees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $programmeID=$_POST['programmeID'];
        $academicYearID=$_POST['admissionYearID'];
        $number=$_POST['number'];
        for($i=0;$i<count($programmeID);$i++) {
            for ($x = 1; $x <= $number; $x++) {
                if ($_POST['amounttz' . $x] != '' && $_POST['amountus' . $x] != '') {
                    $data=array(
                        'programID'=> $programmeID[$i],
                        'academicYearID' => $academicYearID,
                        'feesTypeID' =>$_POST['feesTypeID' . $x],
                        'feesTz' =>$_POST['amounttz' . $x],
                        'feesUsa' => $_POST['amountus' . $x],
                        'paidOnce' => $_POST['paid' . $x],
                        'programFeesStatus'=>1
                    );

                    $insert=$db->insert($tblName,$data);
                    /*$stmt = $db->runQuery("INSERT INTO programmefees (programID,academicYearID,feesTypeID,feesTz,feesUsa,programFeesStatus,paidOnce) VALUES (:progID,:acadYearID,:fTypeID,:fTz,:fUsa,1,:paid)");
                    $stmt->bindParam(":progID", $programmeID, PDO::PARAM_INT);
                    $stmt->bindParam(":acadYearID", $academicYearID, PDO::PARAM_INT);
                    $stmt->bindParam(":fTypeID", $_POST['feesTypeID' . $x], PDO::PARAM_INT);
                    $stmt->bindParam(":fTz", $_POST['amounttz' . $x], PDO::PARAM_STR);
                    $stmt->bindParam(":fUsa", $_POST['amountus' . $x], PDO::PARAM_STR);
                    $stmt->bindParam(":paid", $_POST['paid' . $x], PDO::PARAM_INT);
                    $stmt->execute();*/
                }
            }
        }
            
        $statusMsg = true;
        header("Location:index3.php?sp=programmefees&msg=succ");

    }elseif($_REQUEST['action_type'] == 'drop'){
           $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=programmefees&msg=drop");
    }
    elseif ($_REQUEST['action_type'] == 'deactivate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'programFeesStatus' => 0
            );
            $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=programmefees&msg=deactivate");
        }
    } elseif ($_REQUEST['action_type'] == 'activate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'programFeesStatus' => 1
            );
            $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=programmefees&msg=activate");
        }
    }
}

