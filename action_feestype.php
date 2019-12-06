<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'feestype';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
     $feesTypeDesc=$_POST['code'];
     $feesTypeName=$_POST['name'];
    $feesID=$_POST['feesCategoryID'];
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'feesType' => $feesTypeName,
            'feesID'=>$feesID,
            'feesTypeDesc'=>$feesTypeDesc,
            'feesTypeStatus'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=feestype&msg=succ");
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
            'feesType' => $feesTypeName,
            'feesID'=>$feesID,
            'feesTypeDesc'=>$feesTypeDesc,
            'feesTypeStatus'=>$_POST['feesTypeStatus']
        );
            $condition = array('feesTypeID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=feestype&msg=edited");
        }
    }
//}
}