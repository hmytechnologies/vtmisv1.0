<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'documents';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
{
    if($_REQUEST['action_type'] == 'add')
    {
        $documentType = $_POST['documentType'];// user name
        $imgFile = $_FILES['user_image']['name'];
        $tmp_dir = $_FILES['user_image']['tmp_name'];
        $imgSize = $_FILES['user_image']['size'];


        if(empty($documentType)){
            $errMSG = "Please Select Document Type.";
        }
        else if(empty($imgFile)){
            $errMSG = "Please Select File.";
        }

        else
        {
            $upload_dir = 'student_uploaded_document/'; // upload directory

            $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('pdf','jpg','png'); // valid extensions

            // rename uploading image
            $userpic = rand(1000,1000000).".".$imgExt;

            // allow valid image file formats
            if(in_array($imgExt, $valid_extensions)){
                // Check file size '5MB'
                if($imgSize < 5000000){
                    move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                }
                else{
                    $errMSG = "Sorry, your file is too large.";
                }
            }
            else{
                $errMSG = "Sorry, only PDF,JPG and PNG files are allowed.";
            }
        }

        $status=false;
        // if no error occured, continue ....
        if(!isset($errMSG))
        {

            $documentData=array(
                'studentID'=>$_POST['studentID'],
                'documentType'=>$documentType,
                'fileUrl'=>$userpic
            );
            $insert=$db->insert($tblName,$documentData);
            $status=true;
            if($status)
            {
                $successMSG="succ";
                header("Location:index3.php?sp=edit_student&id=".$db->my_simple_crypt($_POST['studentID'],'e')."&msg=".$successMSG);
            }
            else
            {
                header("Location:index3.php?sp=edit_student&id=".$db->my_simple_crypt($_POST['studentID'],'e')."&msg=".$errMSG);
            }
        }
    }
     else  if($_REQUEST['action_type'] == 'drop')
        {
            $condition = array('documentID' => $db->my_simple_crypt($_REQUEST['did'],'d'));
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=edit_student&id=".$_REQUEST['id']."&msg=deleted");
        }
}

