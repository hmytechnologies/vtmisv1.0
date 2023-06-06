<?php
session_start();
if($_REQUEST['action']=="getPDF")
{
    include 'DB.php';
    $db=new DBHelper();
    // require('fpdf.php');

    $level = $_POST['level'];
    $regNumber = $_POST['regNumber'];
    implode(',', $level);

    //echo  $level;
    // foreach ($level as $levelID) {
    //     # code...
    //     echo $levelID;
    // }

    header("Location:index3.php?sp=student_academic_reports&studentReg=$regNumber&level= $level");
    
  }  ?>