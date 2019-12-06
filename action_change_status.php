<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'student_status';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
            $userData = array(
                'regNumber' => $_POST['regNumber'],
                'statusID' => $_POST['statusID'],
                'statusDate' => $_POST['statusDate'],
                'academicYearID' => $_POST['academicYearID'],
                'status' => 1
            );
            $insert = $db->insert($tblName,$userData);
            $studentStatusID=$insert;    
            
            $studentData=array(
                'statusID'=>$_POST['statusID']
            );
            $condition=array('registrationNumber'=>$_POST['regNumber']);
            $update=$db->update("student",$studentData,$condition);
            
            //upload image
            $imgFile = $_FILES['photo']['name'];
            $tmp_dir = $_FILES['photo']['tmp_name'];
            $imgSize = $_FILES['photo']['size'];
            if(!empty($imgFile)){
                $upload_dir = 'uploaded_file/'; // upload directory
                
                $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
                
                // valid image extensions
                $valid_extensions = array('pdf'); // valid extensions
                
                // rename uploading image
                $userpic = rand(1000,1000000).".".$imgExt;
                
                // allow valid image file formats
                if(in_array($imgExt, $valid_extensions)){
                    // Check file size '5MB'
                    if($imgSize < 5000000){
                        move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                        $pictureData=array(
                            'uploadedFile'=>$userpic
                        );
                        $condition=array('studentStatusID'=>$studentStatusID);
                        $update = $db->update($tblName,$pictureData,$condition);
                    }
                    else{
                        $errMSG = "Sorry, your file is too large.";
                        $boolStaus=false;
                    }
                }
                else
                {
                    $errMSG = "Sorry, only pdf files are allowed.";
                    $boolStaus=false;
                }
            }
            
        
        $boolStatus=true;
        
        
        if($boolStatus)
        {
            header("Location:index3.php?sp=study_progress&action=getRecords&search_student=".$_POST['regNumber']."&msg=succ#status");
        }
        else
        {
            header("Location:index3.php?sp=study_progress&action=getRecords&&search_student=".$_POST['regNumber']."&msg=unsucc#status");
        }
    }elseif($_REQUEST['action_type'] == 'drop'){
        if(!empty($_REQUEST['id'])){
            $condition = array('studentStatusID' => $_REQUEST['id']);
            $regNumber=$db->my_simple_crypt($_REQUEST['reg'],'d');
            $update = $db->delete($tblName,$condition);
            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index3.php?sp=study_progress&action=getRecords&search_student=".$regNumber."&msg=deleted#status");
            }
            else
            {
                header("Location:index3.php?sp=study_progress&getRecords&search_student=".$regNumber."&msg=unsucc#status");
            }
        }
    }
}