<?php 
  require_once("session.php");
  require_once("DB.php");
  $auth_user = new DBHelper();
  $userID = $_SESSION['user_session'];
  $departmentID=$_SESSION['department_session'];


  //main role
$main_role = $auth_user->getRows("userroles", array('where' => array('userID' => $userID,'status'=>1)));
if (!empty($main_role)) {
    foreach ($main_role as $mrole) {
        $mroleID = $mrole['roleID'];
        $_SESSION['main_role_session']=$mroleID;
    }
}

$userRole = $auth_user->getRows("userroles", array('where' => array('userID' => $userID)));
if (!empty($userRole)) {
    $role_arr=array();
    foreach ($userRole as $role) {
        $roleID = $role['roleID'];
        $_SESSION['role_session']=$roleID;
        $role_arr[]=$roleID;
    }
    $_SESSION['roleID']=$role_arr;
}

$login=$auth_user->getData("users","login","userID",$userID);
if($login==0)
{
    if($mroleID==2) {
        header("Location:index2.php?sz=student_update_user_profile");
        exit();
    }
    else
    {
        header("Location:index2.php?sz=changepwd");
        exit();
    }
}

  $role_session=$_SESSION['role_session'];
  if($mroleID==2)
  {
      $studentID=$auth_user->getData("student","studentID","userID",$userID);
      $_SESSION['studentID']=$studentID;

      /*$rgStatus=$auth_user->getData("student","rgStatus","userID",$userID);
      if($rgStatus==0)
      {
          header("Location:index2.php?sz=studentform");
          exit();
      }*/
  }
  else if($mroleID==3 || $mroleID == 4 || $mroleID == 9)
  {
      $status=$auth_user->getData("instructor","status","userID",$userID);
      if($status==0)
      {
          header("Location:index2.php?sz=instructor_form");
          exit();
      }
  }

$organization = $auth_user->getRows('organization',array('order_by'=>'organizationName DESC'));
if(!empty($organization))
{
    foreach($organization as $org)
    {
        $organizationName=$org['organizationName'];
        $organizationCode=$org['organizationCode'];
        $organizationPicture="img/".$org['organizationPicture'];
    }
}
else
{
    $organizationName="Soft Dev Academy";
    $organizationCode="SDVA";
    $organizationPicture="img/SkyChuo.png";
}
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>VTAIMS-<?php echo $organizationName;?></title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="Scripts/jquery-1.10.2.min.js" type="text/javascript"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" type="text/css" href="plugins/datepicker/css/datepicker.css" />

    <link href="plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="plugins/datatables/buttons.dataTables.min.css" rel="stylesheet" />
    
     <link href="plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" />
    
     <link rel="stylesheet" href="css/chosen.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    <link rel="stylesheet" href="css/bootstrap-chosen.css">

<script type="text/javascript">


$(document).ready(function () {
    var url = window.location;
    $('ul.nav a[href="' + url + '"]').parent().addClass('active');
    $('li.treeview a').filter(function () {
        return this.href == url;
    }).parent().addClass('active').parent().parent().addClass('active');
});
</script>



    <script type="text/javascript">
  $(document).ready(function () {
            $('#example').dataTable(
                {
                   responsive:true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Records',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Records',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleexample').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            /*exportOptions:{
                                columns:[0,1,2,3]
                            }*/
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Records',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Records',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleexampleexample').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            /*exportOptions:{
                                columns:[0,1,2,3]
                            }*/
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Records',
                            footer: false,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3]
                            }*/
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Records',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>

<script type="text/javascript">
$(document).ready(function(){ 
$("#select_all").change(function(){
  $(".checkbox_class").prop("checked", $(this).prop("checked"));
  });
});      
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleedit').DataTable(
                {
                    scrollX: true,
                    paging: false,
                   
                });
          });
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#onlydata').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip'
                });
          });
</script>



<script>
function goBack() {
    window.history.back();
}
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
  
  <script>
      $(document).ready(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
</script>
     
<style type="text/css">
    hr { border: 0.5px solid;}
    .vericaltext{
    width:1px;
    word-wrap: break-word;
    font-family: monospace /* this is just for good looks */
}
    </style> 

    <style>
        @media (min-width: 1000px){
    .container, 
    .navbar-static-top .container, 
    .navbar-fixed-top .container, 
    .navbar-fixed-bottom .container {
        width: 100%;
    }
}
       
            .table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
    
  #profileImage {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: #512DA8;
  font-size: 35px;
  color: #ffffff;
  text-align: center;
  line-height: 120px;
  margin: 0px 60px;
}     
    </style>
    
    
    
</head>
   
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header navbar-fixed-top" >

   <!-- Logo -->
       <a href="#" class="logo">
         <!-- mini logo for sidebar mini 50x50 pixels -->
         <span class="logo-mini">
             IMS
         </span>
         <!-- logo for regular state and mobile devices -->
         <span class="logo-lg">
             VTAIMS
                 <!--<img alt="StAR" style="max-width: 100%;" src="img/sar_logo_3.png"/>-->
         </span>
       </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top navbar-fixed-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
            
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
<?php
               // foreach($_SESSION['roleID'] as $role=>$user_role) {
                    if ($_SESSION['role_session'] == 1) {
                        ?>
                        <li role="presentation" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                &nbsp; System <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="~/About">About</a></li>
                                <li><a href="index3.php?sp=user">Manage Users</a></li>
                                <li><a href="#">Backup</a></li>
                                <li><a href="#">Restore</a></li>

                            </ul>
                        </li>
                    <?php //}
                }?>
              
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                   <?php
                   $studentPicture=$auth_user->getData("student","studentPicture","userID",$userID);
                   $instructorImage=$auth_user->getData("instructor","instructorImage","userID",$userID);
                   $userImage=$auth_user->getData("users","userImage","userID",$userID);
                  if(!empty($studentPicture))
                  {
                  ?>
                    <img src="student_images/<?php echo $auth_user->getData("student","studentPicture","userID",$userID);?>" class="user-image" alt="User Image">
                   <?php  
                  }
                  else if(!empty($instructorImage))
                  {
                      ?>
                      <img src="student_images/<?php echo $auth_user->getData("users","userImage","userID",$userID);?>" class="user-image" alt="User Image">
                      <?php
                  }
                  else if(!empty($userImage))
                  {
                      ?>
                      <img src="student_images/<?php echo $auth_user->getData("users","userImage","userID",$userID);?>" class="user-image" alt="User Image">
                      <?php
                  }
                  else 
                  {
                      ?>
                  <img src="img/sample.png" class="user-image" alt="User Image">
                      <?php 
                  }
                  ?>
                
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->

                  <span class="hidden-xs"><?php echo $auth_user->getData("users","username","userID",$userID);?>(<?php

                      //echo $auth_user->getData("departments","departmentCode","departmentID",$departmentID);
                      if($mroleID==4)
                          $officeCode=$auth_user->getData("center_registration","centerName","centerRegistrationID",$departmentID);
                      else if($mroleID==2)
                          $officeCode="Student";
                      else if($mroleID==3)
                          $officeCode=$auth_user->getData("departments","departmentCode","departmentID",$departmentID);
                      else
                          $officeCode="Academics";

                      echo $officeCode;

                      ?>)</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                 <?php
                 $studentPicture=$auth_user->getData("student","studentPicture","userID",$userID);
                 $instructorImage=$auth_user->getData("instructor","instructorImage","userID",$userID);
                 $userImage=$auth_user->getData("users","userImage","userID",$userID);
                 if(!empty($studentPicture))
                 {
                     ?>
                    <img src="student_images/<?php echo $auth_user->getData("student","studentPicture","userID",$userID);?>" class="img-circle" alt="User Image">
                   <?php  
                  }
                  else if(!empty($instructorImage))
                  {
                      ?>
                      <img src="student_images/<?php echo $auth_user->getData("users","userImage","userID",$userID);?>" class="user-image" alt="User Image">
                      <?php
                  }
                  else if(!empty($userImage))
                  {
                      ?>
                      <img src="student_images/<?php echo $auth_user->getData("users","userImage","userID",$userID);?>" class="img-circle" alt="User Image">
                      <?php
                  }
                  else 
                  {
                      ?>
                  <img src="img/sample.png" class="img-circle" alt="User Image">
                      <?php 
                  }
                  ?>
                 
                    <p>
                    
                      <?php 
                      $users=$auth_user->getRows("users",array('where'=>array('userID'=>$userID),'order_by userID ASC'));
                      if(!empty($users))
                      {
                          foreach($users as $user)
                          {
                              foreach($_SESSION['roleID'] as $role=>$user_role) {
                                  $roleName = $auth_user->getData("roles", "roleName", "roleID", $user_role);
                                  echo $roleName."<br>";
                              }
                          }
                      }
                      ?>
                     
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="index2.php?sz=changepwd" class="btn btn-default btn-flat">Change Password</a>
                    </div>
                    <div class="pull-right">
                        <a href="logout.php?logout=true" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              
            </ul>
          </div>
        </nav>

          <br />
          <br />
      </header>
        <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

         <?php 

            include("menu.php");

         ?>
        </section>
        <!-- /.sidebar -->
      </aside>
         <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php
                include "mainindex.php";
            ?>
        </section>
      </div><!-- /.content-wrapper -->
    <!-- Main Footer -->
    <br/><br>
      <?php include("footer.php");?>
    <!-- Bootstrap 3.3.5 -->
    <script type="js/jquery-1.12.4.js"></script>
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
        <link href="jquery-ui/jquery-ui.css"rel="stylesheet"/>
        <link href="jquery-ui/jquery-ui.min.css"rel="stylesheet"/>

    <script src="plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script src="plugins/knob/jquery.knob.min.js" type="text/javascript"></script>
    
    <script src="plugins/datatables/buttons.flash.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/buttons.html5.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/buttons.print.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/jszip.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/pdfmake.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/vfs_fonts.js" type="text/javascript"></script>
    <script src="plugins/datatables/buttons.colVis.min.js" type="text/javascript"></script>
    
    <script src="plugins/datatables/dataTables.fixedColumns.min.js" type="text/javascript"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/chart_script.js"></script>

        <script src="jquery-ui/jquery-ui.js"></script>


        <script src="js/chosen.jquery.js"></script>
        <script src="js/jquery.validate.min.js"></script>
        <script src="js/register.js"></script>
 </div>       
</body>
</html>