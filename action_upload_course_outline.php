<?php
session_start();
include 'DB.php';
$db=new DBHelper();
$tblName="course";
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add')
    {
        $courseID=$_POST['courseID'];
        $courseCode=$db->getData("course","courseCode", "courseID", $courseID);
        $imgFile = $_FILES['user_image']['name'];
        $tmp_dir = $_FILES['user_image']['tmp_name'];
        $imgSize = $_FILES['user_image']['size'];


        if(empty($imgFile)){
            $errMSG = "Please Select Image File.";
            header("Location:index3.php?sp=department_course&msg=$errMSG");
        }
        else
        {
            $upload_dir = 'course_outline/'; // upload directory

            $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('pdf'); // valid extensions

            // rename uploading image
            $userpic = $courseCode."-".rand(1000,1000000).".".$imgExt;

            // allow valid image file formats
            if(in_array($imgExt, $valid_extensions)){
                // Check file size '5MB'
                if($imgSize < 5000000)    {
                    move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                }
                else{
                    $errMSG = "Sorry, your file is too large.";
                    header("Location:index3.php?sp=department_course&msg=$errMSG");
                }
            }
            else{
                $errMSG = "Sorry, only pdf files are allowed.";
                header("Location:index3.php?sp=department_course&msg=$errMSG");
            }
        }


        // if no error occured, continue ....
        if(!isset($errMSG))
        {
            $userData=array(
                'courseOutline'=>$userpic
            );
            $condition=array('courseID'=>$courseID);
            $stmt=$db->update($tblName, $userData,$condition);
            if($stmt)
            {
                $successMSG = "uploaded succesfully ...";
                header("Location:index3.php?sp=department_course&msg= $successMSG");
            }
            else
            {
                $errMSG2 = "error while uploading....";
                header("Location:index3.php?sp=department_course&msg= $errMSG2");
            }
        }
        header("Location:index3.php?sp=department_course");
    }
}

?>