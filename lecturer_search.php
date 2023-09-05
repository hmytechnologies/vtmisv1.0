
<h1><b>Search Faculty</h1>
<hr>
<div class="form-group">
    <form name="" method="post" action="">
        <div class="col-xs-12">
            <label class="col-xs-3 control-label"> Enter Lecturer Name:</label>
            <div class="col-xs-4">
                <input type="text" name="search_instructor" id="search_text" class="form-control">
            </div>
            <div class="col-xs-4"><input  type="submit" class="btn btn-success" name="doSearch" value="Search Faculty"/>
            </div>
        </div>
    </form>
</div>
<br><br>
<div class="row">
    <div class="col-lg-12">
    <?php
    $db=new DBhelper();
    if((isset($_POST['doSearch'])=="Search Faculty")) {
        $searchInstructor = $_POST['search_instructor'];

        //$instructor = $db->getRows('instructor', array('where' => array('firstName' => $searchInstructor), ' order_by' => ' instructorID ASC'));
        $centerID = $db->getData("instructor","centerID","userID",$_SESSION['user_session']);
        $departmentID = $db->getData("instructor","departmentID","userID",$_SESSION['user_session']);
        // $db->getData("departments","departmentName","departmentID",$departmentID);
        $instructor = $db->searchLecturer($searchInstructor, $centerID, $departmentID);
        ?>
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Instructor Profile</h3>
                </div>
                <div class="box-body">
                    <table id="instructor" class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Full Name</th>
                            <th>Title</th>
<!--                            <th>Phone</th>
-->                            <th>Email</th>
                            <td>School</td>
                            <th>Department</th>
                            <th>Office No.</th>
<!--                            <th>Employment Status</th>
                           <th>Status</th>
-->                            <th>Picture</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($instructor)) {
                            $count = 0;
                            foreach ($instructor as $inst) {
                                $count++;
                                $fname=$inst['firstName'];
                                $lname=$inst['lastName'];
                                $name="$fname $lname";
                                if ($inst['instructorStatus'] == 1)
                                    $status = "Active";
                                else
                                    $status = "Not Active";

                                    
                                    // $centerID = $db->getData("instructor","centerID","userID",$_SESSION['user_session']);
                                  


                                echo $schooID=$db->getData("departments","departmentName","departmentID",$inst['departmentID']);
                                echo $schoolCode=$db->getData("center_registration","centerName","centerRegistrationID",$inst['centerID']);

                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $name ?></td>
                                    <td><?php echo $db->getData("instructor_title", "title","titleID",$inst['titleID']); ?></td>
<!--                                    <td><?php /*echo $inst['phoneNumber']; */?></td>
-->                                    <td><?php echo $inst['email']; ?></td>
                                    <td><?php echo $schoolCode;?></td>
                                    <td><?php echo $db->getData("departments", "departmentCode", "departmentID", $inst['departmentID']); ?></td>
                                    <td><?php echo $inst['officeNumber']; ?></td>
<!--                                    <td><?php /*echo $inst['employmentStatus']; */?></td>
                                   <td><?php /*echo $status; */?></td>
-->                                    <td><img id="image" src="student_images/<?php echo $inst['instructorImage']; ?>"
                                             height="100px" width="120px;"/></td>
                                </tr>
                            <?php }
                        }
                        else
                        {
                            echo "<tr><td colspan='10'>No Instructor</td></tr>";
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
    }
        ?>
    </div></div>
