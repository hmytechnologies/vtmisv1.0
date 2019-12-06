<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
?>
<h1><b>Dean and HoD Information</h1>
<hr>
<div class="row">
    <div class="col-lg-12">
        <?php
        $db=new DBhelper();
        /* if($_SESSION['role_session']==9)
         {
             $instructor=$db->getHoDDeanList($_SESSION['role_session'],$_SESSION['department_session']);
         }
         else if($_SESSION['role_session']==7)
         {
             $instructor=$db->getHoDDeanList($_SESSION['role_session'],$_SESSION['department_session']);
         }
         else if($_SESSION['role_session']==2)
         {*/
        //}

        ?>
        <div class="box box-solid box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Dean Profile</h3>
            </div>
            <div class="box-body">
                <table id="instructor" class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Title</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <td>School</td>
                        <th>Office No.</th>
                        <th>Picture</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //echo $_SESSION['main_role_session'];
                    /* if($_SESSION['main_role_session']==9)
                     {

                     }
                     else if($_SESSION['main_role_session']==7)
                     {*/
                    $instructor=$db->getDeanListExam();
                    //}
                    /*else if($_SESSION['main_role_session']==4)
                    {

                    }
                    else if($_SESSION['main_role_session']==3)
                    {

                    }
                    else if($_SESSION['main_role_session']==2)
                    {
                        $instructor=$db->getDeanListStudent($_SESSION['user_session']);
                    }*/


                    if (!empty($instructor)) {
                        $count = 0;
                        foreach ($instructor as $inst) {
                            $count++;
                            $fname=$inst['firstName'];
                            $lname=$inst['lastName'];
                            $name="$fname $lname";

                            $schoolCode=$db->getData("schools","schoolCode","schoolID",$inst['officeID']);
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $db->getData("instructor_title","title","titleID",$inst['titleID']); ?></td>
                                <td><?php echo $inst['phoneNumber']; ?></td>
                                <td><?php echo $inst['email']; ?></td>
                                <td><?php echo $schoolCode;?></td>
                                <td><?php echo $inst['officeNumber']; ?></td>
                                <td><img id="image" src="student_images/<?php echo $inst['instructorImage']; ?>"
                                         height="120px" width="120px;"/></td>
                            </tr>
                        <?php }
                        ?>
                        <?php
                    }
                    else
                    {
                        echo "<tr><td>No Dean</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-solid box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Head Profile</h3>
            </div>
            <div class="box-body">
                <table id="instructor" class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Title</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Office No.</th>
                        <th>Picture</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /* if($_SESSION['main_role_session']==9)
                     {

                     }
                     else if($_SESSION['main_role_session']==7)
                     {*/
                    $instructor=$db->getHoDListExam();
                    //}
                    /*else if($_SESSION['main_role_session']==4)
                    {

                    }
                    else if($_SESSION['main_role_session']==3)
                    {

                    }
                    else if($_SESSION['main_role_session']==2)
                    {

                    }*/

                    if (!empty($instructor)) {
                        $count = 0;
                        foreach ($instructor as $inst) {
                            $count++;

                            $fname=$inst['firstName'];
                            $lname=$inst['lastName'];
                            $name="$fname $lname";

                            //$schooID=$db->getData("departments","schoolID","departmentID",$inst['offi']);
                            $schoolCode=$db->getData("schools","schoolCode","schoolID",$inst['officeID']);
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $db->getData("instructor_title","title","titleID",$inst['titleID']); ?></td>
                                <td><?php echo $inst['phoneNumber']; ?></td>
                                <td><?php echo $inst['email']; ?></td>
                                <td><?php echo $db->getData("departments", "departmentCode", "departmentID", $inst['departmentID']); ?></td>
                                <td><?php echo $inst['officeNumber']; ?></td>
                                <td><img id="image" src="student_images/<?php echo $inst['instructorImage']; ?>"
                                         height="120px" width="120px;"/></td>
                            </tr>
                        <?php }
                        ?>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div></div>
