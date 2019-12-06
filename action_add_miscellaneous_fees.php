<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'other_fees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    $amount=$_POST['amount'];
    $feesTypeID=$_POST['id'];
    if($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
                'feesTypeID'=>$_POST['id'],
                'amount'=>$amount,
                'feesStatus'=>$_POST['feesTypeStatus']
            );
            $check=$db->getRows('other_fees',array('where'=>array('feesTypeID'=>$feesTypeID)));
            if(!empty($check))
            {
                $condition = array('feesTypeID' => $_POST['id']);
                $update = $db->update($tblName,$userData,$condition);
            }
            else
            {
                $insert=$db->insert($tblName,$userData);
            }

            $statusMsg = true;
            header("Location:index3.php?sp=other_fees&msg=edited");
        }
    }
//}
}