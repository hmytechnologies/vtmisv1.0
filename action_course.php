<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'course';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
     $courseCode=$_POST['code'];
     $s=ucfirst($courseCode);
     $bar = ucwords(strtolower($courseCode));
     $courseCode= strtoupper(preg_replace('/\s+/', '', $courseCode));
     $courseName=$_POST['name'];
     
     /*
     if(($db->isFieldExist('course','course_code',$courseCode)) OR ($db->isFieldExist('course','course_name',$courseName)))
     {
        $statusMsg=false;
        header("Location:index3.php?sp=course&msg=unsucc");
    }
      */
     if($_REQUEST['action_type'] == 'add'){
     if(($db->isFieldExist('course','courseCode',$courseCode)))
     {
        $statusMsg=false;
        header("Location:index3.php?sp=course&msg=unsucc");
    }
    else
    {
        $userData = array(
            'courseCode' => $courseCode,
            'courseName'=>$courseName,
            'capacity' => $_POST['capacity'],
            'units'=>$_POST['units'],
            'courseTypeID'=>$_POST['courseTypeID'],
            'numberOfHours'=>$_POST['nhrs'],
            'departmentID'=>$_POST['department_id'],
            'status'=>1
        );

        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        //var_dump($userData);
        header("Location:index3.php?sp=course&msg=succ");
    }
    
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
            'courseCode' => $courseCode,
            'courseName'=>$courseName,
            'capacity' => $_POST['capacity'],
            'units'=>$_POST['units'],
             'courseTypeID'=>$_POST['courseTypeID'],
             'numberOfHours'=>$_POST['nhrs'],
            'departmentID'=>$_POST['department_id'],
            'status'=>$_POST['status']
        );
            $condition = array('courseID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=course&msg=edited");
        }
    }
}
} catch (PDOException $ex) {
    header("Location:index3.php?sp=course&msg=error");
    //echo "Data Error".$ex->getMessage();
}