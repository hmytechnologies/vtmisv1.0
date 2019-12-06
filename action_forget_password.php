<?php
session_start();
include 'DB.php';
$user = new DBHelper();
$tblName="users";
if(isset($_POST['forgotSubmit'])){
    //check whether email is empty
    if(!empty($_POST['email'])){
        //check whether user exists in the database
        $prevCon['where'] = array('email'=>$_POST['email']);
        $prevCon['return_type'] = 'count';
        $prevUser = $user->getRows($tblName,$prevCon);
        if($prevUser > 0){
            //generat unique string
            $uniqidStr = md5(uniqid(mt_rand()));;

            //update data with forgot pass code
            $conditions = array(
                'email' => $_POST['email']
            );
            $data = array(
                'ftoken' => $uniqidStr
            );
            $update = $user->update($tblName,$data, $conditions);
            if($update){

                $organization = $user->getRows('organization',array('order_by'=>'organizationName DESC'));
                if(!empty($organization))
                {
                    foreach($organization as $org)
                    {
                        $orgEmail=$org['organizationEmail'];
                        $orgName=$org['organizationName'];
                        $starlink=$org['starLink'];
                    }
                }
                else
                {
                    $orgEmail="hmy@hmytechnologies.com";
                    $starlink="http://www.star-demo.hmytechnologies.com";
                }

                $resetPassLink = $starlink.'/reset_password.php?fp_code='.$uniqidStr;

                //get user details
                $con['where'] = array('email'=>$_POST['email']);
                $con['return_type'] = 'single';
                $userDetails = $user->getRows($tblName,$con);

                //send reset password email
                $to = $userDetails['email'];
                $from = 'info@hmytechnologies.com';
                $subject = "Password Update Request";
                $mailContent = 'Dear '.$userDetails['first_name'].', 
                <br/>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.
                <br/>To reset your password, visit the following link: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a>
                <br/><br/>Regards,
                <br/>HM&Y Technologies';
                //set content-type header for sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                //additional headers
                $headers .= 'From: '.$orgName.'<'.$from.'>' . "\r\n";
                //send email
                mail($to,$subject,$mailContent,$headers);

                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'Please check your e-mail, we have sent a password reset link to your registered email.';
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
            }
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Given email is not associated with any account.';
        }

    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Enter email to create a new password for your account.';
    }
    //store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    //redirect to the forgot pasword page
    header("Location:fpassword.php");
}elseif(isset($_POST['resetSubmit'])){
    $fp_code = '';
    if(!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['fp_code'])){
        $fp_code = $_POST['fp_code'];
        //password and confirm password comparison
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirm password must match with the password.';
        }else{
            //check whether identity code exists in the database
            $prevCon['where'] = array('ftoken' => $fp_code);
            $prevCon['return_type'] = 'single';
            $prevUser = $user->getRows($tblName,$prevCon);
            if(!empty($prevUser)){
                //update data with new password
                $conditions = array(
                    'ftoken' => $fp_code
                );
                $data = array(
                    'password' => $user->PwdHash($_POST['password'])
                );
                $update = $user->update($tblName,$data, $conditions);
                if($update){
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Your account password has been reset successfully. Please login with your new password.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                }
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'You does not authorized to reset new password of this account.';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.';
    }
    //store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'reset_password.php?fp_code='.$fp_code;
    header("Location:".$redirectURL);
}
?>