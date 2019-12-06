<?php
$db=new DBHelper();
?>
<!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header"><h2>Main Menu</h2> </li>
            <!-- Optionally, you can add icons to the links -->
            <li class=" active"><a href="index3.php"><i class="glyphicon glyphicon-home"></i> <span>Home</span></a></li> 
              <li class="treeview">
              <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Academics</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                  <li><a href="index3.php?sp=instructor_mycourse">My Course</a></li>
                  <li><a href="index3.php?sp=student_register">Student Register</a></li>
                  <li><a href="index3.php?sp=error">My Time Table</a></li>
                  <li><a href="index3.php?sp=error">Course Evaluation</a></li>
                  <li><a href="index3.php?sp=instructor_exam_results">Result Management</a></li>
<!--                  <li><a href="index3.php?sp=publish_course_work">Publish Course Work</a> </li>
-->              </ul>
            </li>
              <!--<li class="treeview">
                <a href="index3.php?sp=myprofile"><i class="glyphicon glyphicon-th-large"></i> <span>My Profile</span> <i class="fa fa-angle-left pull-right"></i></a>
              </li>


               <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>
              </li>

               <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
              </li>-->

              <li class="treeview">
                  <a href="index3.php?sp=staffprofile&id=<?php echo $db->my_simple_crypt($_SESSION['user_session'],'e');?>"><i class="glyphicon glyphicon-th-large active"></i> <span>My Profile</span> <i class="fa fa-angle-left pull-right"></i></a>

              </li>
            
          </ul><!-- /.sidebar-menu -->