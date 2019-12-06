<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'addRegistrationType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $insert = $db->insert("center_registration_type",$userData);
            $boolStatus=true;
            header("Location:index3.php?sp=center_setting#registration&msg=succ");
        }
        else if($_REQUEST['action_type'] == 'editRegistrationType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $conditions=array('centerTypeID'=>$_POST['id']);
            $update = $db->update("center_registration_type",$userData,$conditions);
            header("Location:index3.php?sp=center_setting#registration&msg=edited");
        }
        else if($_REQUEST['action_type'] == 'addAccType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $insert = $db->insert("center_accreditation_type",$userData);
            $boolStatus=true;
            header("Location:index3.php?sp=center_setting#accredition&msg=succ");
        }
        else if($_REQUEST['action_type'] == 'editAccType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $conditions=array('ID'=>$_POST['id']);
            $update = $db->update("center_accreditation_type",$userData,$conditions);
            header("Location:index3.php?sp=center_setting#accredition&msg=edited");
        }
        else if($_REQUEST['action_type'] == 'addOwnershipType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $insert = $db->insert("center_owner_type",$userData);
            $boolStatus=true;
            header("Location:index3.php?sp=center_setting#ownership&msg=succ");
        }
        else if($_REQUEST['action_type'] == 'editOwnershipType')
        {
            $userData = array(
                'typeCode'=>$_POST['code'],
                'typeName'=>$_POST['name']
            );
            $conditions=array('ID'=>$_POST['id']);
            $update = $db->update("center_owner_type",$userData,$conditions);
            header("Location:index3.php?sp=center_setting#ownership&msg=edited");
        }
    }

} catch (PDOException $ex) {
    header("Location:index3.php?sp=center_setting&msg=error");
}