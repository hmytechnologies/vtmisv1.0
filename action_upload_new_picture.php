<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db=new DBHelper();
$tblName="student";
try {
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $regNumber = $_POST['regNumber'];
            $imgFile = $_FILES['student_image']['name'];
            $tmp_dir = $_FILES['student_image']['tmp_name'];
            $imgSize = $_FILES['student_image']['size'];

            if (empty($imgFile)) {
                $errMSG = "Please Select Image File.";
                header("Location:index3.php?sp=student_academic_reports&msg=$errMSG&action=getRecords&search_student=$regNumber");
            } else {
                $upload_dir = 'student_images/'; // upload directory

                $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
                // valid image extensions
                $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
                // rename uploading image
                $userpic = rand(1000, 1000000) . "." . $imgExt;
                // allow valid image file formats
                if (in_array($imgExt, $valid_extensions)) {
                    // Check file size '5MB'
                    if ($imgSize < 5000000) {
                        move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                        $pictureData = array(
                            'studentPicture' => $userpic
                        );
                        $condition = array('registrationNumber' => $regNumber);
                        $update = $db->update($tblName, $pictureData, $condition);
                        header("Location:index3.php?sp=student_academic_reports&action=getRecords&search_student=$regNumber");
                    } else {
                        $errMSG = "Sorry, your file is too large.";
                        header("Location:index3.php?sp=student_academic_reports&msg=$errMSG&action=getRecords&search_student=$regNumber");
                    }
                } else {
                    $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
                    header("Location:index3.php?sp=student_academic_reports&msg=$errMSG&action=getRecords&search_student=$regNumber");
                }
            }
        }
    }
}catch(PDOException $ex)
{
    header("Location:index3.php?sp=student_academic_reports&msg=error&action=getRecords&search_student=$regNumber");
}

?>