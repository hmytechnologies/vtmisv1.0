<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'student';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
{

    if($_REQUEST['action_type'] == 'upload')
    {

        if(!empty($_POST['regNumber'])){

            echo $_POST['regNumber'];
            $regNumber=$db->my_simple_crypt($_POST['regNumber'],'d');

             //upload image
             $imgFile = $_FILES['photo']['name'];
             $tmp_dir = $_FILES['photo']['tmp_name'];
             $imgSize = $_FILES['photo']['size'];

             if(!empty($imgFile)){
                $upload_dir = 'student_images/'; // upload directory

                $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

                // valid image extensions
                $valid_extensions = array('png','jpg','jpeg'); // valid extensions

                // rename uploading image
                $userpic = rand(1000,1000000).".".$imgExt;

                // allow valid image file formats
                if(in_array($imgExt, $valid_extensions)){
                    // Check file size '5MB'
                    if($imgSize < 5000000){
                        move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                        $pictureData=array(
                            'studentPicture'=>$userpic
                        );
                        $condition=array('registrationNumber'=>$regNumber);
                        $update = $db->update($tblName,$pictureData,$condition);
                        echo $update;
                    }
                    else{
                        $errMSG = "Sorry, your image file is too large.";
                        $boolStaus=false;
                    }
                }
                else
                {
                    $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                    $boolStaus=false;
                }
            }

            $statusFlag=true;
            // header("Location:index3.php?sp=student_academic_reports&regNumber=".$_POST['regNumber']."&msg=edited");
        }

    }
}

?>