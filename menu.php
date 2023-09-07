<?php
$db = new DBHelper();
?>
<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    <li class="header">
        <h2>Main Menu</h2>
    </li>
    <li class="active"><a href="index3.php"><i class="glyphicon glyphicon-home"></i> <span>Home</span></a>
    </li>
    <?php
    foreach ($_SESSION['roleID'] as $role => $user_role) {
        if ($user_role == 8 || $user_role == 10  ) {
    ?>
            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-th-large"></i><span>Student Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                    <li><a href="index3.php?sp=rform">Student Registration</a></li>
                    <li><a href="index3.php?sp=study_progress">Manage Student</a></li>
                    <li><a href="index3.php?sp=upload_file">Batch Registration</a></li>
                    <li><a href="index3.php?sp=norminalroll">Norminal Roll</a></li>
                    <li><a href="index3.php?sp=searchstudents">Search Student</a></li>
                </ul>
            </li>
        <?php
        }
        if ($user_role == 2) {
        ?>
            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-th-large"></i><span>Registration Information</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="index3.php?sp=myprofile">Registration Status</a></li>
                </ul>
            </li>
    <?php
        }
    }
    ?>
    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large"></i><span>Academics</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <?php
            foreach ($_SESSION['roleID'] as $role => $user_role) {
                if ($user_role == 7 || $user_role == 1) {
            ?>
                    <!--<li><a href="index3.php?sp=courselist">Annual Subjects</a></li>
                                 <li><a href="index3.php?sp=semester_setting">Annual Settings</a></li>-->
                    <li><a href="index3.php?sp=semester_setting">Academic Settings</a></li>
                    <li><a href="index3.php?sp=pcurricullum">Trade Curricullum</a></li>
                    <li><a href="index3.php?sp=transfer_student">Promotion Student</a></li>
                <?php
                }
                if ($user_role == 10 ) {
                ?>
                    <!--<li><a href="index3.php?sp=courselist">Term Subjects</a></li>-->
                    <li><a href="index3.php?sp=instructor_course_setting">Course Allocation</a></li>
                    <li><a href="index3.php?sp=transfer_student">Transfer Student</a></li>
                    <li><a href="index3.php?sp=pcurricullum">Trade Curricullum</a></li>
                <?php
                }
                if ($user_role == 4 ) {
                ?>
                    <li><a href="index3.php?sp=semester_setting_hod">Academic Settings</a></li>
                    <!--<li><a href="index3.php?sp=courselist">Annual Courses</a></li>-->
                    <!-- <li><a href="index3.php?sp=course_register">Student Course Register</a></li>-->
                    <li><a href="index3.php?sp=transfer_student">Transfer Student</a></li>
                    <li><a href="index3.php?sp=pcurricullum">Trade Curricullum</a></li>
                <?php
                }
                if ($user_role == 3 || $user_role == 4) {
                ?>
                    <li><a href="index3.php?sp=instructor_mycourse">Course List</a></li>
                    <li><a href="index3.php?sp=ass_conf">Assessment Configuration</a></li>
                    <!--<li><a href="index3.php?sp=field_training">Field Training</a></li>
                                  <li><a href="index3.php?sp=project_research">Project/Research</a></li>-->

                <?php
                }
                if ($user_role == 2) {
                ?>
                    <li><a href="index3.php?sp=mycourse">Course List</a></li>
                    <li><a href="#">Internal Assessment</a></li>
                    <li><a href="index3.php?sp=pcurricullum">Trade Curricullum</a></li>
            <?php
                }
            }
            ?>
        </ul>
    </li>


    <li class="treeview">
        <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Examinations</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <?php
            foreach ($_SESSION['roleID'] as $role => $user_role) {
                if ($user_role == 7 || $user_role == 9  ) {
            ?>
                    <li><a href="index3.php?sp=search_student_result">Search Student Result</a></li>
                    <li><a href="index3.php?sp=search_student_terms_result">Search Student Terms Result</a></li>
                    <li><a href="index3.php?sp=addresult">Final Exam Management</a></li>
                    <li><a href="index3.php?sp=term_marks">Term Result Management</a></li>
                    <li><a href="index3.php?sp=internal_marks">Assessment Management</a></li>
                    <li><a href="index3.php?sp=supp_special">Supp/Special Results</a></li>
                    <li><a href="index3.php?sp=publish">Publish Results</a></li>
                    <li><a href="index3.php?sp=approve_graduands">Approve Graduands</a></li>
                    <li><a href="index3.php?sp=print_result">Term Report</a></li>
                    <li><a href="index3.php?sp=summary_report">Summary Report</a></li>
                    <li><a href="index3.php?sp=final_report">Final Report</a></li>
                    <li><a href="index3.php?sp=supp_report">Supp Report</a></li>
                    <li><a href="index3.php?sp=student_academic_reports">Academic Reports</a></li>
                    <li><a href="index3.php?sp=graduate_report">Graduate Reports</a></li>
                    <li><a href="index3.php?sp=register_exam">Exam Roster</a></li>
                    <li><a href="index3.php?sp=exam_list">Examination Statistics</a></li>

    </li>
<?php
                }
                if ($user_role == 3 || $user_role == 4 || $user_role == 9) {
?>
    <li><a href="index3.php?sp=term_marks">Term Result Management</a></li>
    <li><a href="index3.php?sp=internal_marks">Assessment Management</a></li>
    <li><a href="index3.php?sp=instructor_exam_results">Course Results</a></li>
<?php
                }
                if ($user_role == 2) {
?>
    <li><a href="index3.php?sp=exam_results">Exam Results</a></li>
    <li><a href="index3.php?sp=courseworkview">View Course Work</a></li>
    <li><a href="index3.php?sp=internal_marks">Internal Marks</a></li>
    <li><a href="#">Academic Performance</a> </li>
<?php
                }
            }
?>

</ul>
</li>


<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 6 || $user_role == 1) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=student_payment_list">Fees Reconciliation</a></li>
                <li><a href="index3.php?sp=process_payment">Process Payment</a></li>
                <li><a href="index3.php?sp=view_payment_history">Payment History</a></li>
                <li><a href="index3.php?sp=waiver">Financial Assistance</a></li>
                <li><a href="#">List of Defaulters</a></li>

            </ul>
        </li>
    <?php
    }
    if ($user_role == 2 || $user_role == 1) {
    ?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=payments">Payments Bills</a></li>
                <li><a href="index3.php?sp=payment_history">Payment Receipt</a></li>
                <li><a href="index3.php?sp=financial_assistant">Financial Assistance</a></li>
            </ul>
        </li>
    <?php
    }
    ?>
<?php
}
?>

<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 6 || $user_role == 1) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Payment Setting</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=feestype">Fees Type</a></li>
                <li><a href="index3.php?sp=programmefees">Programme Fees</a></li>
                <li><a href="index3.php?sp=hostelfees">Hostel Fees</a></li>
                <li><a href="index3.php?sp=other_fees">Miscellaneous Fees</a></li>
                <li><a href="index3.php?sp=payment_setting">Payment Setting</a></li>
            </ul>
        </li>
<?php
    }
}
?>
<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 7) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Statistical Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
            </ul>
        </li>
<?php
    }
}
?>

<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 1) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Center Management</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=center_reg">Center Registration</a></li>
                <li><a href="index3.php?sp=center_programmes">Center Programmes</a></li>
                <li><a href="#">Center Instructors</a></li>
            </ul>
        </li>

<?php
    }
}
?>

<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 6 || $user_role == 1) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Staff Management</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=register_staff">Register Staff</a></li>
                <li><a href="index3.php?sp=manage_staff">Manage Staff</a></li>
            </ul>
        </li>
<?php
    }
}
?>

<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 7 || $user_role == 9) {
?>
        <li class="treeview">
            <a href="index3.php?sp=sysconf"><i class="glyphicon glyphicon-th-large active"></i>
                <span>Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
        </li>
<?php
    }
}
?>



<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 1) {
?>
        <li class="treeview">
            <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>User Management</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="index3.php?sp=users&id=<?php echo $db->my_simple_crypt($_SESSION['user_session']); ?>&rtoken=<?php echo $db->my_simple_crypt($user_role); ?>"> <span>Manage User</span></a>
                </li>
                <li><a href="index3.php?sp=user_roles">User Roles</a></li>
                <li><a href="index3.php?sp=audit">Audit Control</a></li>
            </ul>
        </li>

<?php
    }
}
?>

<?php
foreach ($_SESSION['roleID'] as $role => $user_role) {
    if ($user_role == 1) {
?>
        <li class="treeview">
            <a href="index3.php?sp=organization&id=<?php echo $db->my_simple_crypt($_SESSION['user_session']); ?>&rtoken=<?php echo $db->my_simple_crypt($user_role); ?>"><i class="glyphicon glyphicon-th-large active"></i> <span>Organization Info</span>
                <i class="fa fa-angle-left pull-right"></i></a>
        </li>

<?php
    }
}
?>

<li class="treeview">
    <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>Infor Corner</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="index3.php?sp=academic_calendar">Academic Calendar</a></li>
        <?php
        foreach ($_SESSION['roleID'] as $role => $user_role) {
            if (($user_role == 4) || ($user_role == 9) || $user_role == 1) { ?>
                <li><a href="index3.php?sp=staffprofile">My Information</a></li>
                <li><a href="index3.php?sp=my_lecturer"> Instructor Info</a></li>
            <?php
            }
            if ($user_role == 3) {
            ?>
                <li><a href="index3.php?sp=staffprofile">My Information</a></li>
            <?php
            }
            if ($user_role == 2) {
            ?>
            <?php
            }
            if ($user_role == 7) {
            ?>
                <li><a href="index3.php?sp=instructor">Instructor Info</a></li>
            <?php
            }
            ?>
        <?php
        }
        ?>
        <li><a href="index3.php?sp=instructor_search">Instructor Search</a></li>
    </ul>
</li>


<li class="treeview">
    <a href="#"><i class="glyphicon glyphicon-th-large"></i> <span>My Account</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="index3.php?sp=st_profile&id=<?php echo $db->my_simple_crypt($_SESSION['user_session'], 'e'); ?>">Profile
                Information</a></li>
        <li><a href="index2.php?sz=changepwd">Change Password</a></li>
        <li><a href="logout.php?logout=true">Sign out</a></li>
    </ul>
</li>
</ul><!-- /.sidebar-menu -->