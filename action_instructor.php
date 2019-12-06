<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'instructor';
$tbl='users';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
      //add user first
        if($_POST['islogin']==1)
        {
            $isLogin=1;
        }
        else
        {
            $isLogin=0;
        }
        $email=$_POST['email'];
        $username=$email;
        $pwd=$db->generate_password(8);
        $password=$db->PwdHash($pwd);
        if($db->isFieldExist('users','username',$username))
        {
            $boolStatus=false;
        }
        else
        {/*
            $userData = array(
                'firstName' => trim($_POST['fname']),
                'middleName' => trim($_POST['mname']),
                'lastName' => trim($_POST['lname']),
                'phoneNumber' => $_POST['phone'],
                'email' => $_POST['email'],
                'username'=>$username,
                'password'=>$password,
                'departmentID'=>$_POST['departmentID'],
                'status'=>$isLogin,
                'login'=>0
            );
            $insert = $db->insert($tbl,$userData);
            $userID=$insert;*/
        
     //add instructor first
        $salutation=$_POST['salutation'];
        $fname = trim($_POST['fname']);
        $mname = trim($_POST['mname']);
        $lname = trim($_POST['lname']);
        $name="$salutation $fname $mname $lname";
        $officeNumber=$_POST['officeNumber'];
        $userData = array(
            'salutation'=>$salutation,
            'firstName' => $fname,
            'middleName' => $mname,
            'lastName' => $lname,
            'instructorName'=>$name,
            'gender'=>$_POST['gender'],
            'phoneNumber' => $_POST['phone'],
            'email' => $_POST['email'],
            'departmentID'=>$_POST['departmentID'],
            'employmentStatus'=>$_POST['employmentStatus'],
            'officeNumber'=>$officeNumber,
            'instructorstatus'=>1,
            'isLogin'=>$_POST['islogin'],
            'userID'=>$userID
        );
        $insert = $db->insert($tblName,$userData);
        $instructorID=$insert;

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
                        'instructorImage'=>$userpic
                    );
                    $condition=array('instructorID'=>$instructorID);
                    $update = $db->update($tblName,$pictureData,$condition);
                }
                else{
                    $errMSG = "Sorry, your image file is too large.";
                    $boolStaus=false;
                }
            }
            else
            {
                $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                $boolStatus=false;
            }
    }
    
    $userRolesData = array(
        'userID' =>$userID,
        'roleID'=>3,
        'status'=>1
    );
    $insert = $db->insert("userroles",$userRolesData);

            //send mail
            $to = $email;
            $subject = 'Login details for StAR';
            $from = 'info@hmytechnologies.com';

// To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
            $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $name = "$fname  $lname";
// Compose a simple HTML email message
            $message = '<html><body>';
            $message .= '<h1 style="color:#080;">Dear ' . $name . '</h1>';
            $message .= '<p>Welcome to Aspire StAR, member of SkyChuo Enterprise Resource Planning Management Information System for your university/college.</p>';
            $message .= '<p>To activate your account you must login using username and password below:</p>';
            $message .= '<p style="color:#f40;font-size:18px;">UserName: ' . $username . '<br>Password: ' . $pwd . '</p>';
            $message .= '<p>Please do not expose your password to any other person. You may change your password at any time if you wish to do so. </p>';
            $message .= '<p>We hope you enjoy using Aspire StAR and all services offered by other software solutions under SkyChuo package.</p>';
            $message .= '<p></p>';
            $message .= '<p>Warm Regards,</p>';
            $message .= '<p></p>';
            $message .= '<p>_________________________</p>';
            $message .= '<p>SkyChuo Account Management Services </p>';
            $message .= '<p>Institute of Tourism Development</p>';
            $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
            $message .= '</body></html>';
// Sending email
            mail($to, $subject, $message, $headers);
    $boolStatus=true;
    //end of email
    }
    //$boolStatus=true;
    if($boolStatus)
    {
        header("Location:index3.php?sp=instructor&msg=succ");
    }
    else
    {
        header("Location:index3.php?sp=instructor&msg=unsucc");
    }
}elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['instructorID'])){
            $officeNumber=$_POST['officeNumber'];
            $fname = trim($_POST['fname']);
            $lname=trim($_POST["lname"]);
            $name_ins="$fname $lname";
            $instructorID=$_POST['instructorID'];

            $instructorData = array(
                'salutation'=>$_POST['salutation'],
                'firstName' => $fname,
                'lastName' => $lname,
                'instructorName'=>$name_ins,
                'title'=>$_POST['title'],
                'gender'=>$_POST['gender'],
                'phoneNumber' => $_POST['phoneNumber'],
                'email' => $_POST['email'],
                'departmentID'=>$_POST['departmentID'],
                'employmentStatus'=>$_POST['employmentStatus'],
                'officeNumber'=>$officeNumber,
                'instructorstatus'=>$_POST['status'],
            );
            $condition = array('instructorID' => $instructorID);
            $update = $db->update($tblName,$instructorData,$condition);

             $userID=$db->getData("instructor","userID","instructorID",$instructorID);
                /* $userData = array(
                    'firstName' => trim($_POST['fname']),
                    'middleName' => trim($_POST['mname']),
                    'username'=>$_POST['email'],
                    'lastName' => trim($_POST['lname']),
                    'phoneNumber' => $_POST['phone'],
                    'email' => $_POST['email']
                );
                $condition2 = array("userID" => $userID);
                $updateuser = $db->update("users", $userData, $condition2);*/

                //upload image
                $imgFile = $_FILES['photo']['name'];
                $tmp_dir = $_FILES['photo']['tmp_name'];
                $imgSize = $_FILES['photo']['size'];
                if (!empty($imgFile)) {
                    $upload_dir = 'student_images/'; // upload directory

                    $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                    // valid image extensions
                    $valid_extensions = array('png', 'jpg', 'jpeg'); // valid extensions

                    // rename uploading image
                    $userpic = rand(1000, 1000000) . "." . $imgExt;

                    // allow valid image file formats
                    if (in_array($imgExt, $valid_extensions)) {
                        // Check file size '5MB'
                        if ($imgSize < 5000000) {
                            move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                            $pictureData = array(
                                'instructorImage' => $userpic
                            );
                            $condition = array('instructorID' => $instructorID);
                            $update = $db->update($tblName, $pictureData, $condition);

                            $userImage=array(
                                'userImage'=>$userpic
                            );
                            $condition_user = array('userID' => $userID);
                            $update = $db->update("users", $userImage, $condition_user);

                        } else {
                            $errMSG = "Sorry, your image file is too large.";
                            $boolStaus = false;
                        }
                    } else {
                        $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                        $boolStaus = false;
                    }
                }



           // }
            $boolStatus=true;
            
            
            if($boolStatus)
            {
                header("Location:index3.php?sp=staffprofile&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=staffprofile&msg=unsucc");
            }
        }
}
}
} catch(PDOException $ex)
{
    header("Location:index3.php?sp=staffprofile&msg=error");
}