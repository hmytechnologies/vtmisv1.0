<?php
session_start();
include_once "DB.php";
$user = new DBHelper();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Academic Register</title>

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500"> -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">


    <!-- Favicon and touch icons -->
    <!--<link rel="shortcut icon" href="assets/ico/favicon.png">-->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

</head>
<?php
$organization = $user->getRows('organization',array('order_by'=>'organizationName DESC'));
if(!empty($organization)) {
    foreach ($organization as $org) {
        $organizationName = $org['organizationName'];
        $organizationCode = $org['organizationCode'];
        $organizationPicture = "img/" . $org['organizationPicture'];
    }
}
else {
    $organizationName = "Soft Dev Academy";
    $organizationCode = "SDVA";
    $organizationPicture = "img/SkyChuo.png";
}
?>
<body>
<!-- Top content -->
<div class="row">
    <div class="col-sm-12 col-sm-offset-0 text">
        <h1><img src="<?php echo $organizationPicture;?>" alt="<?php echo $organizationCode;?>" width="85" height="70"> <strong><font color="LightSlateGrey">
                    <?php
                    echo $organizationName;
                    ?>
                </font></strong></h1>
        <hr border-color="LightSlateGrey">
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 text">
        <h2><strong><font color="white">Student Academic Register</font></strong></h2>
    </div>
</div>

<script type="text/javascript">

    function checkPassword(str)
    {
        var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        return re.test(str);
    }

    function checkForm(form)
    {
        var password=document.getElementById("password");
        var confirm_password=document.getElementById("confirm_password");
        if(password.value != "" && password.value == confirm_password.value) {
            if(!checkPassword(password.value)) {
                alert("Password must contain at least 8 characters, including UPPER Case,lowercase and numbers");
                password.focus();
                return false;
            }
        } else {
            alert("Error: Please check that you've entered and confirmed your password!");
            password.focus();
            return false;
        }
        return true;
    }

</script>
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <h3>Reset Account Password</h3>
                            <?php
                            $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
                            if(!empty($sessData['status']['msg'])){
                                $statusMsg = $sessData['status']['msg'];
                                $statusMsgType = $sessData['status']['type'];
                                unset($_SESSION['sessData']['status']);
                                ?>
                                <div class="alert alert-danger">
                                    <i class="glyphicon glyphicon-warning-sign"></i>
                                    <p><?php echo !empty($statusMsg)?'<p>'.$statusMsg.'</p>':''; ?></p>
                                </div>
                            <?php

                            }
                            ?>
                        </div>
                        <div class="form-top-right">
                            <i class="fa fa-lock"></i>
                        </div>

                    </div>
                    <div class="form-bottom">

                        <form role="form" action="action_forget_password.php"  method="post" class="login-form" onsubmit="return checkForm(this);">
                            <div class="form-group">
                                <label class="sr-only" for="form-username">New Password</label>
                                <input type="password" name="password" id="password" placeholder="New Password..." class="form-username form-control" id="username">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-password">Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password..." class="form-password form-control" id="password">
                            </div>
                           <input type="hidden" name="fp_code" value="<?php echo $_REQUEST['fp_code'];?>"/>

                            <input type="submit" name="resetSubmit" class="form-control btn btn-default btn-success input-lg" value="Reset Password">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/scripts.js"></script>

<!--[if lt IE 10]>
<script src="assets/js/placeholder.js"></script>
<![endif]-->

</body>
<footer class="main-footer">
    <p>Aspire SAR. This product is licensed to the <?php
        echo $organizationName;
        ?> | <strong>&copy;2014-<?php echo date('Y');?> <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></strong></p>
</footer>
</html>
