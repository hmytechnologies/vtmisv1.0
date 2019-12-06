<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'hostelfees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $programmeID=$_POST['programmeID'];
        $academicYearID=$_POST['admissionYearID'];
        $number=$_POST['number'];
            for ($x = 1; $x <= $number; $x++) {
                if ($_POST['amounttz' . $x] != '' && $_POST['amountus' . $x] != '') {
                    $data=array(
                        'academicYearID' => $academicYearID,
                        'feesTypeID' =>$_POST['feesTypeID' . $x],
                        'feesTz' =>$_POST['amounttz' . $x],
                        'feesUsa' => $_POST['amountus' . $x],
                        'paidOnce' => $_POST['paid' . $x],
                        'FeesStatus'=>1
                    );

                    $insert=$db->insert($tblName,$data);
                }
            }

        $statusMsg = true;
        header("Location:index3.php?sp=hostelfees&msg=succ");

    }elseif($_REQUEST['action_type'] == 'drop'){
        $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=hostelfees&msg=drop");
    }
    elseif ($_REQUEST['action_type'] == 'deactivate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'programFeesStatus' => 0
            );
            $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=hostelfees&msg=deactivate");
        }
    } elseif ($_REQUEST['action_type'] == 'activate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'programFeesStatus' => 1
            );
            $condition = array('programID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=hostelfees&msg=activate");
        }
    }
}

