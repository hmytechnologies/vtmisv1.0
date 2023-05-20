<?php
$db = new DBHelper(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#programmeLevelID").change(function() {
            var programmeLevelID = $(this).val();
            var centerID = $("#centerIDD").val();
            var dataString = 'programmeLevelID=' + programmeLevelID + '&centerID=' + centerID;
            $.ajax({
                type: "POST",
                url: "ajax_programme.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#programmeID").html(html);
                    console.log(dataString);
                }
            });

        });

    });
</script>

<div class="row">
    <div class="col-md-8">
        <h1>Staff Course Allocation</h1>
    </div>
    <div class="col-md-4">
        <!-- <div class="pull-right">
            <a href="index3.php?sp=semester_setting_hod" class="btn btn-warning">Back to Semester Settings</a>
        </div> -->
    </div>
</div>
<hr>
<h3>Assign Course In a Semester</h3>
<div class="row">
  

    <form name="" method="post" action="">

        <?php
        if ($_SESSION['role_session'] == 7 ||  $_SESSION['main_role_session']==1)
            $centerID = 'all';
        else
            $centerID = $_SESSION['department_session'];
           $userId = $_SESSION['user_session'];
        ?>
        <input type="text" hidden name="centerID" id="centerIDD" value="<?php echo $centerID; ?>">
        <?php 
             $userId = $_SESSION['user_session'];
            $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

            if(!empty($instructor))
             {
                foreach($instructor as $i)
              {
                     $instructorID=$i['instructorID'];
                     $centerID=$i['centerID'];
                     $departmentID=$i['departmentID'];
                    $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
                }
            }
        
        ?>
       

        <div class="col-lg-3">
            <label for="MiddleName">Academic Year</label>
            <select name="academicYearID" class="form-control" required>
                <?php
                $adYear = $db->getRows('academic_year', array('order_by' => 'academicYear DESC'));
                if (!empty($adYear)) {
                    echo "<option value=''>Please Select Here</option>";
                    $count = 0;
                    foreach ($adYear as $year) {
                        $count++;
                        $academic_year = $year['academicYear'];
                        $academic_year_id = $year['academicYearID'];
                ?>
                        <option value="<?php echo $academic_year_id; ?>"><?php echo $academic_year; ?></option>
                <?php }
                }
                ?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="Physical Address">Trade Level</label>
            <select name="programmeLevelID" id="programmeLevelID" class="form-control" required>
                <option value="">Select Here</option>
                <?php
                $level = $db->getRows('programme_level', array('order_by' => 'programmeLevelCode ASC'));
                if (!empty($level)) {

                    $count = 0;
                    foreach ($level as $lvl) {
                        $count++;
                        $programmeLevelID = $lvl['programmeLevelID'];
                        $programmeLevel = $lvl['programmeLevel'];
                ?>
                        <option value="<?php echo $programmeLevelID; ?>"><?php echo $programmeLevel; ?></option>
                <?php }
                } ?>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="Physical Address">Trade Name</label>
            <select name="programmeID" class="form-control" required="">
                <?php
                if ($_SESSION['main_role_session'] == 7) {
                    $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                } else {
                    $programmes = $db->getCenterMappingProgrammeList($centerID);
                }
                if (!empty($programmes)) {
                    echo "<option value=''>Please Select Here</option>";
                    $count = 0;
                    foreach ($programmes as $prog) {
                        $count++;
                        $programme_name = $prog['programmeName'];
                        $programme_id = $prog['programmeID'];
                ?>
                        <option value="<?php echo $programme_id; ?>"><?php echo $programme_name; ?></option>
                    <?php }
                } else {
                    ?>
                    <option value=""><?php echo "No Data Found";  ?></option>
                <?php
                }
                ?>
            </select>
        </div>


        <div class="col-lg-3">
            <label for=""></label>
            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
    </form>
</div>
<br>
<div class="row">
    <?php
    if (!empty($_REQUEST['msg'])) {
        if ($_REQUEST['msg'] == "succ") {
            echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Instructor Course data has been inserted successfully</strong>.
</div>";
        } else if ($_REQUEST['msg'] == "delete") {
            echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Instructor Course Data has been deleted successfully</strong>.
</div>";
        }
    }
    ?>
</div>
<div class="row">

       
    <div class="col-md-12">
        <?php
        if ((isset($_POST['doFind']) == "Find Records") || (isset($_REQUEST['action']) == "getRecords")) {
            if (isset($_POST['doFind']) == 'Find Records') {
                $academicYearID = $_POST['academicYearID'];
                $programmeID = $_POST['programmeID'];
                $programmeLevelID = $_POST['programmeLevelID'];
            } else if ($_REQUEST['action'] == 'getRecords') {
                $academicYearID = $db->my_simple_crypt($_REQUEST['acaid'], 'd');
            }

            $userId = $_SESSION['user_session'];
            $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

            if(!empty($instructor))
             {
                foreach($instructor as $i)
              {
                     $instructorID=$i['instructorID'];
                     $centerID=$i['centerID'];
                     $departmentID=$i['departmentID'];
                    $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
                }
            }


            // $courseData = $db->getCenterMappingCourseList($_SESSION['department_session'], $academicYearID, $programmeID, $programmeLevelID);
            $courseData = $db->getCenterMappingCourseList( $userId, $academicYearID, $programmeID, $programmeLevelID);
            if (!empty($courseData)) {
        ?>
                <h4 class="text-danger">List of Course <?php
                                                            if ($_SESSION['main_role_session'] == 7) {
                                                                echo "all centers";
                                                            } else {
                                                                echo $db->getData("center_registration", "centerName", "centerRegistrationID", $_SESSION['department_session']);
                                                                
                                                            }

                                                            ?> in <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID);

                                                                    ?></h4>
                <form name="" method="post" action="action_instructor_course.php">
                    <table id="example" border="0" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Subject Type</th>
                                <th>Course Status</th>
                                <th>Trade Level</th>
                                <th>Instructor </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            foreach ($courseData as $cdt) {
                                $count++;
                                $courseID = $cdt['courseID'];
                                $courseCode = $cdt['courseCode'];
                                $courseName = $cdt['courseName'];
                                $programmeLevelID = $cdt['programmeLevelID'];
                                $courseStatusID = $cdt['courseStatusID'];
                            ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $courseCode; ?></td>
                                    <td><?php echo $courseName; ?></td>
                                    <td><?php echo $db->getData('course_type', 'courseType', 'courseTypeID', $cdt['courseTypeID']); ?></td>
                                    <td><?php echo $db->getData('coursestatus', 'courseStatus', 'courseStatusID', $courseStatusID); ?></td>
                                    <td><?php echo $db->getData('programme_level', 'programmeLevel', 'programmeLevelID', $programmeLevelID); ?></td>
                                    <td>
                                        <input type="text" hidden name="courseID[]" value="<?php echo $courseID; ?>">
                                        <input type="text" hidden name="levelID[]" value="<?php echo $programmeLevelID; ?>">
                                        <div class="col-lg-6">
                                            <select name="instructorID[]" class="form-control chosen-select">
                                                <?php
                                                $instructor = $db->getRows('instructor', array('where' => array('instructorStatus' => 1), 'order_by' => ' instructorName ASC'));

                                                $instructor = $db->getInstructor($_SESSION['department_session']);

                                                if (!empty($instructor)) {
                                                    echo "<option value=''>Please Select Here</option>";
                                                    foreach ($instructor as $inst) {
                                                        $name = $inst['instructorName'];
                                                        $departID = $inst['departmentID'];
                                                        $instructorID = $inst['instructorID'];
                                                        $deptCode = $db->getData("departments", "departmentCode", "departmentID", $departID);
                                                ?>
                                                        <option value="<?php echo  $instructorID; ?>"><?php echo $name . "(" . $deptCode . ")"; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add" />
                            <input type="hidden" name="number_subject" value="<?php echo $count; ?>">
                            <input type="hidden" name="academicYearID" value="<?php echo $academicYearID; ?>">
                            <input type="hidden" name="programmeLevelID" value="<?php echo $programmeLevelID; ?>">
                            <input type="hidden" name="programmeID" value="<?php echo $programmeID; ?>">

                            <input type="hidden" name="centerID" value="<?php echo $_SESSION['department_session']; ?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="reset" value="Cancel" class="btn btn-primary form-control" />
                        </div>
                    </div>

                </form>
            <?php
            }
            ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        $userId = $_SESSION['user_session'];
        $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

        if(!empty($instructor))
         {
            foreach($instructor as $i)
          {
                 $instructorID=$i['instructorID'];
                 $centerID=$i['centerID'];
                 $departmentID=$i['departmentID'];
                $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
            }
        }
            // $data = $db->getCourseInstructor($_SESSION['department_session'], $academicYearID);
            $data = $db->getCourseInstructor($userId, $academicYearID);
            if (!empty($data)) {
        ?>

            <h4 class="text-danger"> Registered Course Instructor for <?php
                                                                        if ($_SESSION['main_role_session'] == 7) {
                                                                            echo "all centers";
                                                                        } else {
                                                                            echo $db->getData("center_registration", "centerName", "centerRegistrationID", $_SESSION['department_session']);
                                                                        }

                                                                        ?> in <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?></h4>
            <table id="exampleexample" class="display nowrap">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Class Number</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Trade Name</th>
                        <th>Programme Level</th>
                        <th>Instructor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 0;
                    foreach ($data as $dt) {
                        $count++;
                        $courseID = $dt['courseID'];
                        $instructorID = $dt['staffID'];
                        $classNumber = $dt['classNumber'];
                        $programmeLevelID = $dt['programmeLevelID'];
                        $centerProgrammeCourseID = $dt['centerProgrammeCourseID'];
                        $programmeID = $dt['programmeID'];
                        $centerID = $dt['centerID'];

                        $courseValue = $db->getRows("course", array('where' => array('courseID' => $courseID)));
                        foreach ($courseValue as $cv) {
                            $courseCode = $cv['courseCode'];
                            $courseName = $cv['courseName'];
                            $ctype = $cv['courseTypeID'];
                        }
                    ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $classNumber; ?></td>
                            <td><?php echo $courseCode; ?></td>
                            <td><?php echo $courseName; ?></td>
                            <td><?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?></td>
                            <td><?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?></td>
                            <td>
                                <?php echo $db->getData("instructor", "instructorName", "instructorID", $dt['staffID']); ?>
                            </td>

                            <td><a href="action_instructor_course.php?action_type=delete&academicYearID=<?php echo $db->my_simple_crypt($academicYearID, 'e'); ?>&id=<?php echo $db->my_simple_crypt($centerProgrammeCourseID, 'e'); ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
            } else {
        ?>
            <h4 class="text-danger">No Assigned Course(s) found...</h4>
        <?php
            }
        ?>
    </div>
</div>
<?php
        }
?>