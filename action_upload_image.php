<?php
session_start();
include 'DB.php';
$db=new DBHelper();
$tblName="student_picture";
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
if($_REQUEST['action_type'] == 'add')
{
  $studentID=$_POST['studentID'];
  
  $search=$db->getData("student","registration_number", "student_id", $studentID);
  $imgFile = $_FILES['student_image']['name'];
  $tmp_dir = $_FILES['student_image']['tmp_name'];
  $imgSize = $_FILES['student_image']['size'];
  
  
   if(empty($imgFile)){
   $errMSG = "Please Select Image File.";
   header("Location:index3.php?sp=transcript&msg=$errMSG&action=getRecords&search_student=$search");
  }
  else
  {
   $upload_dir = 'student_images/'; // upload directory
 
   $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
  
   // valid image extensions
   $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
  
   // rename uploading image
   $userpic = rand(1000,1000000).".".$imgExt;
    
   // allow valid image file formats
   if(in_array($imgExt, $valid_extensions)){   
    // Check file size '5MB'
    if($imgSize < 5000000)    {
     move_uploaded_file($tmp_dir,$upload_dir.$userpic);
    }
    else{
     $errMSG = "Sorry, your file is too large.";
     header("Location:index3.php?sp=transcript&msg=$errMSG&action=getRecords&search_student=$search");
    }
   }
   else{
    $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed."; 
    header("Location:index3.php?sp=transcript&msg=$errMSG&action=getRecords&search_student=$search");
   }
  }
  
  
  // if no error occured, continue ....
  if(!isset($errMSG))
  {
      $userData=array(
          'studentID'=>$studentID,
          'studentPic'=>$userpic
      );
      $stmt=$db->insert($tblName, $userData);
  // $stmt = $conn->prepare('INSERT INTO student_picture(studentID,studentPic) VALUES(:std,:upic)');
  // $stmt->bindParam(':std',$studentID);
   //$stmt->bindParam(':upic',$userpic);
   
   if($stmt)
   {
    $successMSG = "new record succesfully inserted ...";
    header("Location:index3.php?sp=transcript&msg= $successMSG&action=getRecords&search_student=$search");
   }
   else
   {
    $errMSG = "error while inserting....";
    header("Location:index3.php?sp=transcript&msg= $successMSG&action=getRecords&search_student=$search");
   }
  }
  header("Location:index3.php?sp=transcript&action=getRecords&search_student=$search");
 }
}

 ?>