<?php
$db=new DBHelper();
?>
<!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header"><h2>Main Menu</h2> </li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="index3.php"><i class="glyphicon glyphicon-home"></i> <span>Home</span></a></li>
            
            
            <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i><span>Academics</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                 
<!--                    <li><a href="index3.php?sp=semester_course">Semester Registration</a></li>
-->                   <li><a href="index3.php?sp=courselist">Semester Courses</a></li>
                 <!-- <li><a href="index3.php?sp=register_course">Course Registration</a></li>-->
                  <li><a href="index3.php?sp=student_register">Student Register</a></li>
                   <li><a href="index3.php?sp=pcurricullum">Programme Curricullum</a></li>
                   <li><a href="index3.php?sp=error">Time Table</a></li>
                   <li><a href="index3.php?sp=error">Course Evaluation</a></li>
                   <li><a href="index3.php?sp=addresult">Results Management</a></li>    
                    <li><a href="index3.php?sp=semester_setting_hod">Semester Settings</a></li>
              </ul>
            </li>
             
              <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
               <!--  <li><a href="index3.php?sp=waiver">Temporary Waiver</a></li>
                
                <li><a href="index3.php?sp=financestudent">Search Student</a></li>
                <li><a href="index3.php?sp=feestype">Fees Type</a></li>
               <li><a href="index3.php?sp=programmefees">Programme Fees</a></li>
               <li><a href="index3.php?sp=payment_setting">Payment Setting</a></li>
                <li><a href="index3.php?sp=student_payment">Add Payment</a></li>
                <li><a href="index3.php?sp=viewpayment">View Payment</a></li>
                 <li><a href="index3.php?sp=viewpaymentpublished">View By Programme</a></li>-->
              </ul>
            </li>
            
            
            
             <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Statistical Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
              <li><a href="index3.php?sp=nactereport">NACTE Reports</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>E-Learning</span> <i class="fa fa-angle-left pull-right"></i></a>
              <!-- <ul class="treeview-menu">
                  <li><a href="index3.php?sp=rform">Registration Form</a></li>
                    <li><a href="index3.php?sp=norminalroll">Norminal Roll</a></li>
                    <li><a href="index3.php?sp=searchstudents">Search Student</a></li>
                   
              </ul> -->
            </li>

              <li class="treeview">
                  <a href="index3.php?sp=staffprofile&id=<?php echo $db->my_simple_crypt($_SESSION['user_session'],'e');?>"><i class="glyphicon glyphicon-th-large active"></i> <span>My Profile</span> <i class="fa fa-angle-left pull-right"></i></a>

              </li>

            </li>
            
          </ul><!-- /.sidebar-menu -->