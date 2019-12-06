<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_financial_assistant';
if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'add') {
        $imgFile = $_FILES['image']['name'];
        $tmp_dir = $_FILES['image']['tmp_name'];
        $imgSize = $_FILES['image']['size'];

        if (empty($imgFile)) {
            $errMSG = "Please Select Document File.";
            header("Location:index3.php?sp=financial_assistant&msg=".$errMSG);
        } else {
            $upload_dir = 'img/'; // upload directory
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('pdf'); // valid extensions

            // rename uploading image
            $userpic = rand(1000, 1000000) . "." . $imgExt;

            // allow valid image file formats
            if (in_array($imgExt, $valid_extensions)) {
                // Check file size '5MB'
                if ($imgSize < 5000000) {
                    move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                } else {
                    $errMSG = "Sorry, your file is too large.";
                    header("Location:index3.php?sp=financial_assistant&msg=".$errMSG);
                }
            } else {
                $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
                header("Location:index3.php?sp=financial_assistant&msg=".$errMSG);
            }
        }

        $today=date('Y-m-d');
        $nextDay= date( "Y-m-d", strtotime( "$today +7 day" ) );

        // if no error occured, continue ....
        if (!isset($errMSG)) {
            $userData = array(
                'regNumber' => $_POST['regNumber'],
                'semesterSettingID' => $_POST['semesterID'],
                'financialAssistantID' => $_POST['financialID'],
                'reason' => $_POST['reason'],
                'appliedDate' => $today,
                'expiredDate' => $nextDay,
                'status' => 'Waiting',
                'attachment' => $userpic,
            );

            $insert = $db->insert($tblName, $userData);
            $statusMsg = true;
            header("Location:index3.php?sp=financial_assistant&msg=succ");
        }

    }
    elseif ($_REQUEST['action_type'] == 'edit') {
        if (!empty($_REQUEST['id'])) {

            }
            $statusMsg = true;
            header("Location:index3.php?sp=organization&msg=succ");
        }
}
}catch (PDOException $ex)
{
    header("Location:index3.php?sp=organization&msg=err");
}