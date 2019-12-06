<?php 
  require_once("session.php");
  require_once("DB.php");
  $auth_user = new DBHelper();
  $userID = $_SESSION['user_session'];
  $user_privilege=$_SESSION['user_privilege'];
    /*This is Mine*/
    
$err = array();
$msg = array();

if($_POST['doUpdate'] == 'Update')  
{
    $userID=$_POST['userID'];
$user=$auth_user->getRows("users",array('where'=>array('userID'=>$userID),'order_by'=>'userID'));
foreach($user as $usr)
{
    $password=$usr['password'];
    $old_salt = substr($password,0,9);
    
//check for old password in md5 format
    if($password === $auth_user->PwdHash($_POST['pwd_old'],$old_salt))
    {
        $newsha1 = $auth_user->PwdHash($_POST['pwd_new']);
        $userData=array(
            'password'=>$newsha1,
            'login'=>1
        );
        $condition=array('userID'=>$userID);
        $update=$auth_user->update("users",$userData, $condition);
        header("Location: index3.php");
    } 
    else
    {
         $err[] = "Your old password is invalid";
    }
}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head runat="server">
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet"/>
        <title>SCOPE Database System</title>
       <script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/register.js"></script>
<link href="css/validation.css" rel="stylesheet"> 
    </head>
    <body>
        
            <div class="middlePage">
                <div class="row"><h1>Change Password</h1><hr />
                <div class="row bg-danger alert-danger text-center " >
                    <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	  if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "$e <br>";
	    }
	  echo "</div>";	
	   }
	  /******************************* END ********************************/	  
	  ?>
                </div>
                <div class="row well">

                    <div class="col-md-6">
                        <img style="max-height:100%; max-width:100%" class="img-responsive" src="img/suza.png" />
                    </div>
                    <form name="" method="post" action="">                  
                    <div class="col-md-6">
                            <div class="row">
                                <div class="left-addon form-group has-feedback">
                                    <input type="password" id="pwd_old" name="pwd_old" placeholder="Old Password" class="form-control" required=""  />
                  
                                   <i class="form-control-feedback glyphicon glyphicon-user"></i>
                                </div>
                            </div>
							
                            <div class="row">
                                <div class=" left-addon form-group has-feedback">
                                    <input type='password' id="password" name="pwd_new" placeholder="New Password" class="form-control" required="" />
                                    <i class="form-control-feedback glyphicon glyphicon-lock"></i>
                                </div>
                            </div>
                         <div class="row">
                                <div class=" left-addon form-group has-feedback">
                                    <input type='password' id="confirm_password" name="pwd_new" placeholder="Confirm New Password" class="form-control" required="" />
                                    <i class="form-control-feedback glyphicon glyphicon-lock"></i>
                                </div>
                            </div>
							<div class="row">
							<br>
							</div>
                            <div class="row">
                                <input type="hidden" name="userID" value="<?php echo $_SESSION['user_session'];?>">
                                <input type="submit" name="doUpdate" class="form-control btn btn-default btn-primary" value="Update">
                                 <!--<button OnClick="LogIn" Text="Log in" class="form-control btn btn-default btn-primary">Sign In</button>-->
                            </div>
                          
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12" style="color:#369"> This product is licensed to The State University of Zanzibar  <strong>   &copy;2014-<?php echo date('Y');?> <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></strong></div>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>
 <script src="js/jquery.validate.js"></script>
    <script src="js/validation.js"></script>
