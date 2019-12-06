<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_exemption';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $regNumber=$_POST['regNumber'];
        //fileupload
        $imgFile = $_FILES['photo']['name'];
        $tmp_dir = $_FILES['photo']['tmp_name'];
        $imgSize = $_FILES['photo']['size'];
        if(!empty($imgFile)){
            $upload_dir = 'uploaded_file/'; // upload directory
            
            $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
            
            // valid image extensions
            $valid_extensions = array('pdf','png','jpg'); // valid extensions
            
            // rename uploading image
            $userpic = rand(1000,1000000).".".$imgExt;
            
            // allow valid image file formats
            if(in_array($imgExt, $valid_extensions)){
                // Check file size '5MB'
                if($imgSize < 5000000){
                    move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                    //add infor after uploaded file
                    $userData = array(
                        'regNumber'=>$regNumber,
                        'semesterSettingID' => $_POST['semesterID'],
                        'dateOfExemption'=>$_POST['statusDate'],
                        'attachment' => $userpic
                    );
                    $insert = $db->insert($tblName,$userData);   
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
       
       
        
        $statusMsg = true;
        header("Location:index3.php?sp=waiver&action=getRecords&search_student=$regNumber&msg=succ");
        
        }
    }
    else if($_REQUEST['action_type'] == 'drop'){
        if(!empty($_REQUEST['id'])){
            $condition = array('studentExemptionID' => $_REQUEST['id']);
            $regNumber=$db->my_simple_crypt($_REQUEST['reg'],'d');
            $update = $db->delete($tblName,$condition);
            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index3.php?sp=waiver&action=getRecords&search_student=".$regNumber."&msg=deleted#status");
            }
            else
            {
                header("Location:index3.php?sp=waiver&getRecords&search_student=".$regNumber."&msg=unsucc#status");
            }
        }
    }
}