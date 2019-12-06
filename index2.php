<?php 
  require_once("session.php");
  require_once("DB.php");
  $db = new DBHelper();
  $userID = $_SESSION['user_session'];
  $userRoleID=$db->getData("userroles","roleID","userID",$userID);
      $userData=$db->getRows('users',array('where'=>array('userID'=>$userID),'order_by'=>'userID ASC'));
     if(!empty($userData)){ 
       foreach($userData as $apps)
       {
           $fname=$apps['firstName'];
           $mname=$apps['middleName'];
           $lname=$apps['lastName'];
           $name="$fname $lname";
       }
     }
  
  ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ASPIRE Student Registration System</title>
    <!-- Bootstrap Core CSS -->
     <script type="js/jquery-1.12.3.js"></script>
   <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/applicant.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/chosen.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    <link rel="stylesheet" href="css/bootstrap-chosen.css">
    <style>
            .table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
        
    </style>
</head>

   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

   
</script>
<script>
      $(document).ready(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
</script>

<script>// Additional logic goes here
  $(document).ready(function(){
  //Chosen
  $("#limitedNumbChosen").chosen({
    //max_selected_options: 3,
    placeholder_text_multiple: "Select Here"
    })
    .bind("chosen:maxselected", function (){
        window.alert("You reached your limited number of selections which is 2 selections!");
    })
});
    </script>
 <style type="text/css">
    hr { border: 0.5px solid;}
    </style>    
<body id="page-top" class="index">

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#page-top">Student Academic Register</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    
                    <li class="page-scroll">
                        <a href="">Logged in as <?php echo $name;?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="index3.php">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="index2.php?sz=changepwd">Change Password</a>
                    </li>
                    
                    <li class="page-scroll">
                        <a href="logout.php?logout=true">Logout</a>
                    </li>
                   
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <section class="success">

<div class="container">
    <br>
<div class="col-lg-12">
    <?php
    if($userRoleID==2)
    {
    ?>
    <h3 class="text text-info">Student Registration</h3>
    <?php
    }
    else
    {
        ?>
    <h3 class="text text-info">Profile/Password Change</h3>
    <?php
    }
    ?>
</div>
            <div class="row">
                <div class="col-lg-12">
                    <?php 
                    session_start();
                    switch((isset($_GET['sz'])?$_GET['sz'] : ''))
                    {
                        
                       case 'changepwd':
                       include 'changepwd.php';
                       break;

                        case 'student_update_user_profile':
                            include('student_update_user_profile.php');
                            break;


                        case 'studentform':
                       include 'registration_form.php';
                       break;

                        case 'instructor_form':
                            include 'update_instructor_profile.php';
                            break;
                       
                        default:
                        include('frontpage.php');
                    }
                    ?>
                 
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include("footeru.php")?>

    <!-- jQuery -->
  
    
   
    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <!-- Plugin JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/jquery-1.12.3.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>

</body>

</html>
