<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
include("DB.php");
$userID = $_SESSION['user_session'];
$user_privilege=$_SESSION['role_session'];
$auth_user = new DBHelper();
/*$err = array();
$msg = array();*/

if(isset($_POST['doUpdate']) == 'Change Password')
{
   $userID=$_POST['userID'];
    $phoneNumber=$_POST['phoneNumber'];
    $email=$_POST['email'];
    $user=$auth_user->getRows("users",array('where'=>array('userID'=>$userID),'order_by'=>'userID'));
    foreach($user as $usr)
    {
         $password=$usr['password'];
        $old_salt = substr($password,0,9);

        //check for old password in md5 format
        if($password === $auth_user->PwdHash($_POST['pwd_old'],$old_salt))
        {
            $newsha1 = $auth_user->PwdHash($_POST['password']);
             $userData=array(
                'password'=>$newsha1,
                'login'=>1
            );
            $condition=array('userID'=>$userID);
            $update=$auth_user->update("users",$userData, $condition);
            header("Location:index3.php");
        }
        else
        {
            header("Location: index2.php?sz=changepwd&err=Your old password is invalid");
            //$err[] = "Your old password is invalid";
        }
    }
}
?>