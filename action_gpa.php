<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'gpa';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'gpaClass' =>$_POST['gpaClass'],
            'startPoint' => $_POST['startPoint'],
            'endPoint'=>$_POST['endPoint'],
            'programmeLevelID'=>$_POST['programmeLevelID'],
            'academicYearID'=>$_POST['academicYearID'],
            'remarkID'=>$_POST['remarkID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=gpa_system&msg=succ");
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
                'gpaClass' =>$_POST['gpaClass'],
                'startPoint' => $_POST['startPoint'],
                'endPoint'=>$_POST['endPoint'],
                'programmeLevelID'=>$_POST['programmeLevelID'],
                'academicYearID'=>$_POST['academicYearID'],
                'remarkID'=>$_POST['remarkID'],
                'status'=>$_POST['status']
            );
            $condition = array('gpaID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=gpa_system&msg=edited");
        }
    }
    elseif($_REQUEST['action_type'] == 'deactivate'){
        if(!empty($_POST['id'])){
            $userData = array(
                'status'=>0
            );
            $condition = array('gpaID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=gpa_system&msg=deactivate");
        }
    }
}