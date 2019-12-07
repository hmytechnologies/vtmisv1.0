<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    $tblUserRole='userroles';


    $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
    if(!empty($organization))
    {
        foreach($organization as $org)
        {
            $organizationName=$org['organizationName'];
            $organizationCode=$org['organizationCode'];
            $organizationPicture="img/".$org['organizationPicture'];
            $starlink=$org['starLink'];
        }
    }
    else
    {
        $organizationName="Soft Dev Academy";
        $organizationCode="SDVA";
        $organizationPicture="img/SkyChuo.png";
        $starlink="None";
    }


if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'adduser') {
        $fname = htmlentities(strtoupper($_POST['fname']),ENT_QUOTES);
        $lname = htmlentities(strtoupper($_POST['lname']),ENT_QUOTES);
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $roleID=$_POST['roleID'];
        //$departmentID=$_POST['departmentID'];
        //$pwd = $db->generate_password(8);
        $pwd = strtoupper(trim($lname));
        $username = $email;
        $password = $db->PwdHash($pwd);
        if ($db->isFieldExist($tblName, 'userName', $username)) {
            $boolStatus = false;
            $msg = "exists";
        } else {
            if(!empty($_POST['centerID']))
                $centerID=$_POST['centerID'];
            else
                $officeID="";
            if(!empty($_POST['departmentID']))
                $departmentID=$_POST['departmentID'];
            else
                $departmentID='';

            $userData = array(
                'username' => $username,
                'password' => $password,
                'firstName' => $fname,
                'lastName' => $lname,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'departmentID'=>$centerID,
                'status' => 1,
                'login' => 0
            );
            $insert = $db->insert($tblName, $userData);
            $userID = $insert;

            $userRolesData = array(
                'userID' => $userID,
                'roleID' => $roleID,
                'status'=>1
            );
            $insert_role = $db->insert($tblUserRole, $userRolesData);

            //for Instructor,HoD,Deans-Information must be save to instructor table
            if($roleID == 3 || $roleID == 4)
            {
                $name="$fname $lname";
                $instructorData = array(
                    'firstName' => $fname,
                    'lastName' => $lname,
                    'instructorName'=>$name,
                    'phoneNumber' => $phoneNumber,
                    'email' => $email,
                    'centerID'=>$centerID,
                    'departmentID'=>$_POST['departmentID'],
                    'centerID'=>$centerID,
                    'instructorStatus'=>1,
                    'isLogin'=>1,
                    'userID'=>$userID,
                    'status'=>0
                );
                $insert = $db->insert("instructor",$instructorData);
            }

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
            $message .= '<p>To activate your account you must login using username and password below using this link:</p>';
            $message .= '<p><a href='.$starlink.' target="_blank">Student Academic Register(StAR)</a></p>';
            $message .= '<p style="color:#f40;font-size:18px;">UserName: ' . $username . '<br>Password: ' . $pwd . '</p>';
            $message .= '<p>Please do not expose your password to any other person. You may change your password at any time if you wish to do so. </p>';
           $message .= '<p>We hope you enjoy using Aspire StAR and all services offered by other software solutions under SkyChuo package.</p>';
            $message .= '<p></p>';
            $message .= '<p>Warm Regards,</p>';
            $message .= '<p></p>';
            $message .= '<p>_________________________</p>';
            $message .= '<p>SkyChuo Account Management Services </p>';
/*            $message .= '<p>HM&Y Technologies</p>';*/
            $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
            $message .= '</body></html>';
// Sending email
            mail($to, $subject, $message, $headers);
            $boolStatus = true;
        }
        if ($boolStatus) {
            header("Location:index3.php?sp=users&msg=succ");
        } else {
            header("Location:index3.php?sp=users&msg=unsucc");
        }
    }else if ($_REQUEST['action_type'] == 'edituser') {
        $fname = htmlentities(strtoupper($_POST['fname']),ENT_QUOTES);
        $lname = htmlentities(strtoupper($_POST['lname']),ENT_QUOTES);
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $roleID=$_POST['roleID'];

        if(!empty($_POST['centerID']))
        {
            $centterID=$_POST['centerID'];
        }
        else
        {
            $centterID=0;
        }
            $editUserData = array(
                'firstName' => $fname,
                'lastName' => $lname,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'departmentID' =>$centterID,
                'login'=>1
            );
        $userID=$_POST['userID'];
        $update_condition=array('userID'=>$userID);
        $update = $db->update($tblName, $editUserData,$update_condition);


        $roleData = array(
            'roleID' => $_POST['roleID'],
            'status'=>1
        );
        $condition_roles=array('userID'=>$userID,'status'=>1);
        $update_role = $db->update($tblUserRole,$roleData,$condition_roles);

       if($_POST['officeID'] == 2)
        {
            $chk_inst=$db->getRows("instructor",array('where'=>array('userID'=>$userID)));
            if(!empty($chk_inst))
            {
                $name = "$fname $lname";
                $instructorData = array(
                    'firstName' => $fname,
                    'lastName' => $lname,
                    'instructorName' => $name,
                    'phoneNumber' => $phoneNumber,
                    'email' => $email,
                    'centerID'=>$_POST['centerID'],
                    'departmentID' => $_POST['departmentID'],
                    'instructorStatus' => 1,
                    'status'=>0
                );
                $condition_roles=array('userID'=>$userID);
                $insert = $db->update("instructor", $instructorData,$condition_roles);
            } else {
                $name = "$fname $lname";
                $instructorData = array(
                    'firstName' => $fname,
                    'lastName' => $lname,
                    'instructorName' => $name,
                    'phoneNumber' => $phoneNumber,
                    'email' => $email,
                    'centerID'=>$_POST['centerID'],
                    'departmentID' => $_POST['departmentID'],
                    'instructorStatus' => 1,
                    'isLogin' => 1,
                    'userID' => $userID,
                    'status' => 0
                );
                $insert = $db->insert("instructor", $instructorData);

            }
        }

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
        $message .= '<p>Some changes has been updated to your Student Academic Register account, please login to your system to see any changes</p>';
        $message .= '<p>We hope you enjoy using Aspire StAR and all services offered by other software solutions under SkyChuo package.</p>';
        $message .= '<p></p>';
        $message .= '<p>Warm Regards,</p>';
        $message .= '<p></p>';
        $message .= '<p>_________________________</p>';
        $message .= '<p>SkyChuo Account Management Services </p>';
        /*            $message .= '<p>HM&Y Technologies</p>';*/
        $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
        $message .= '</body></html>';
// Sending email
        mail($to, $subject, $message, $headers);


        header("Location:index3.php?sp=users&msg=succ");
    }
    else if($_REQUEST['action_type']=="assign_roles") {
        $number_roles=$_POST['number_roles'];
        $userID=$db->my_simple_crypt($_POST['userID'],'d');
        $conditions=array('userID'=>$userID,'status'=>0);
        $delete=$db->delete("userroles",$conditions);
        foreach($_POST['roleID'] as $roleID)
        //for($x=0;$x<=$number_roles;$x++)
        {
            //$roleID=$_POST['roleID'.$x];
            //if(!empty($roleID))
            //{
                    $roleData = array(
                        'userID' => $userID,
                        'roleID' => $roleID,
                        'status'=>0
                    );
                    $insert=$db->insert("userroles",$roleData);
            //}
        }
        header("Location:index3.php?sp=users");
    } elseif ($_REQUEST['action_type'] == 'deactivate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'status' => 0
            );
            $condition = array('userID' => $db->my_simple_crypt($_GET['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=users&msg=block");
        }
    } elseif ($_REQUEST['action_type'] == 'activate') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'status' => 1
            );
            $condition = array('userID' => $db->my_simple_crypt($_GET['id'],'d'));
            $update = $db->update($tblName, $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=users&msg=unblock");
        }
    }
    elseif($_REQUEST['action_type'] == 'reset')
    {
        if(!empty($_GET['id'])){
            $users = $db->getRows('users',array('where'=>array('userID'=>$db->my_simple_crypt($_GET['id'],'d')),'order_by'=>'userID DESC'));
            if(!empty($users))
            {
                foreach ($users as $us) {
                    $lname=$us['lastName'];
                }
            }
            $userData=array(
                'password'=>$db->PwdHash(strtoupper(trim($lname))),
                'login'=>0
            );
            $condition = array('userID' => $db->my_simple_crypt($_GET['id'],'d'));
            $update = $db->update($tblName,$userData,$condition);
            $statusFlag=true;
            header("Location:index3.php?sp=users&msg=reset");
        }
    }

}

} catch(PDOException $ex)
{
    header("Location:index3.php?sp=users&msg=error");
}