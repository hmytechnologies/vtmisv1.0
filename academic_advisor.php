<?php
$db = new DBHelper();
$studentID = $db->getRows('student',array('where'=>array('userID'=>$_SESSION['user_session']),' order_by'=>' studentID ASC'));
if(!empty($studentID)) {
    foreach ($studentID as $std) {
        $regNumber = $std['registrationNumber'];
        ?>
        <div class="row">
            <div class="col-md-8">
                <h1>Academic Advisor Information</h1>
            </div>
        </div>
        <hr>
        <?php
        $academic_advisor = $db->getRows('academic_advisor',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
        if(!empty($academic_advisor))
        {
            foreach($academic_advisor as $advisor)
            {
                $instructorID=$advisor['instructorID'];
                $requestStatus=$advisor['requestStatus'];

                //list of academic advisor
                ?>
                <div class="box box-solid box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Academic Advisor Profile</h3>
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
                                <th>School</th>
                                <th>Office No.</th>
                                <th>Picture</th>
                                <th>Request Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $instructor=$db->getRows("instructor",array('instructorID'=>$instructorID));

                            if (!empty($instructor)) {
                                $count = 0;
                                foreach ($instructor as $inst) {
                                    $count++;

                                    $fname=$inst['firstName'];
                                    $lname=$inst['lastName'];
                                    $name="$fname $lname";

                                    $schooID=$db->getData("departments","schoolID","departmentID",$inst['departmentID']);
                                    $schoolCode=$db->getData("schools","schoolCode","schoolID",$schooID);
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $db->getData("instructor_title","title","titleID",$inst['titleID']); ?></td>
                                        <td><?php echo $inst['phoneNumber']; ?></td>
                                        <td><?php echo $inst['email']; ?></td>
                                        <td><?php echo $db->getData("departments", "departmentCode", "departmentID", $inst['departmentID']); ?></td>
                                        <td><?php echo $schoolCode; ?></td>
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
                <?php
            }
        }
        if(empty($academic_advisor) || $requestStatus==-1) {
            ?>
            <h3>Choose Your Academic Advisor, HoD/Faculty will approve your request</h3>
            <div class="row">

                <form name="" method="post" action="action_academic_advisor.php">
                    <div class="col-lg-3">
                        <label for="FirstName">Faculty List</label>
                        <select name="instructorID<?php echo $count; ?>" class="form-control chosen-select">
                            <?php
                            $instructor = $db->getAcademicAdvisor($_SESSION['user_session']);
                            if (!empty($instructor)) {
                                echo "<option value=''>Please Select Here</option>";
                                foreach ($instructor as $inst) {
                                    $fname = $inst['firstName'];
                                    $lname = $inst['lastName'];
                                    $salutation = $inst['salutation'];
                                    $name = "$salutation $fname $lname";
                                    $departID = $inst['departmentID'];
                                    $instructorID = $inst['instructorID'];
                                    $deptCode = $db->getData("departments", "departmentCode", "departmentID", $departID);
                                    ?>
                                    <option
                                        value="<?php echo $instructorID; ?>"><?php echo $name . "(" . $deptCode . ")"; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                    </div>


                    <div class="col-lg-3">
                        <label for=""></label>
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="hidden" name="regNumber" value="<?php echo $regNumber; ?>">
                        <input type="submit" name="doFind" value="Save Records" class="btn btn-primary form-control"/>
                    </div>
                </form>
            </div>
            <?php
        }
    }
}