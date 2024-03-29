<script type="text/javascript">
    $(document).ready(function() {
        $("#studentdata").DataTable({
            "dom": 'Blfrtip',
            "scrollX": true,
            "paging": true,
            "buttons": [{
                    extend: 'excel',
                    title: 'List of all Register',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    }
                }, ,
                {
                    extend: 'print',
                    title: 'List of all Register',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of all Register',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    },

                }

            ],
            "order": []
        });
    });
</script>
<?php
session_start();

$db = new DBHelper();

$instructorID = $db->getData("instructor", "instructorID", "userID", $_SESSION['user_session']);

if ($instructorID) {
?>

<div class="row">
    <div class="col-lg-12">
        <h1>Term Marks Management</h1>
    </div>
</div>

<div class="row">
    <form name="" method="post" action="">
        <div class="col-lg-12">
            <div class="row">

                <div class="col-lg-3">
                    <label for="MiddleName">Center Name</label>
                    <select name="centerID" class="form-control chosen-select" required="">
                        <?php
                        if ($_SESSION['main_role_session'] == 1) {
                            // This is the code for the main administrator

                            $center = $db->getRows('center_registration', array('order_by' => 'centerName ASC'));
                            if (!empty($center)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($center as $cnt) {
                                    $count++;
                                    $centerName = $cnt['centerName'];
                                    $centerID = $cnt['centerRegistrationID'];
                                    ?>
                                    <option value="<?php echo $centerID; ?>"><?php echo $centerName; ?></option>
                                    <?php
                                }
                            }
                        } else {
                            // This is the code for the instructors

                            $instructor = $db->getRows('instructor', array('where' => array('instructorID' => $instructorID), 'order_by' => 'instructorID ASC'));

                            if (!empty($instructor)) {
                                foreach ($instructor as $i) {
                                    $instructorID = $i['instructorID'];
                                    $centerID = $i['centerID'];
                                    $departmentID = $i['departmentID'];
                                    $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                }
                            }

                            $center = $db->getRows('center_registration', array('where' => array('centerRegistrationID' => $centerID), 'order_by' => 'centerName ASC'));
                            if (!empty($center)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($center as $cnt) {
                                    $count++;
                                    $centerName = $cnt['centerName'];
                                    $centerID = $cnt['centerRegistrationID'];
                                    ?>
                                    <option value="<?php echo $centerID; ?>"><?php echo $centerName; ?></option>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for="FirstName">Academic Year</label>
                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                        <?php
                        // $academic_year = $db->getRows('academic_year', array('where' => array('status' => 1), 'order_by' => 'academicYear ASC'));

                        $academic_year = $db->getRows('academic_year', array('order_by' => 'academicYear ASC'));
                        if (!empty($academic_year)) {
                            echo "<option value=''>Please Select Here</option>";
                            $count = 0;
                            foreach ($academic_year as $sm) {
                                $count++;
                                $academicYear = $sm['academicYear'];
                                $academicYearID = $sm['academicYearID'];
                        ?>
                                <option value="<?php echo $academicYearID; ?>"><?php echo $academicYear; ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="email">Choose Term</label>
                        <select name="examCategoryID" class="form-control" id="examCategoryID">
                            <?php
                            $term = $db->getRows("exam_category", array('where' => array('responsible' => 1), 'order by examCategoryID ASC'));
                            if (!empty($term)) {
                                echo "<option value=''>Please Select Here</option>";
                                foreach ($term as $trm) {
                                    $examCategory = $trm['examCategory'];
                                    $examCategoryID = $trm['examCategoryID'];
                                    echo "<option value='$examCategoryID'>$examCategory</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>



            </div>
            <div class="row">

                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                <div class="col-lg-6"></div>

            </div>
        </div>
    </form>
</div><?php
}
?>
<br><br>
<div class="row">
    <?php
    if (isset($_POST['doFind']) == "View Records") {
        $academicYearID = $_POST['academicYearID'];
        $examCategoryID=$_POST['examCategoryID'];
        //$courseprogramme = $db->getAssessmentCourse($_SESSION['department_session'],$roleID,$academicYearID);
        if ($_SESSION['main_role_session'] == 3) {
            $role = $_SESSION['main_role_session'];
        } else {
            $role = 'all';
        }

        if ($_SESSION['main_role_session'] == 7) {
            $courseprogramme = $db->getAssessmentCourse($_POST['centerID'], $academicYearID);
        } else if ($role == 3) {
            $instructorID = $db->getData("instructor", "instructorID", "userID", $_SESSION['user_session']);
            $courseprogramme = $db->getInstructorAssessmentCourse($academicYearID, $instructorID);
        } else {
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
            $courseprogramme = $db->getAssessmentCourse($centerID, $academicYearID);
        }
        if (!empty($courseprogramme)) {
    ?>
            <div class="col-md-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">List of Courses
                            for <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example" class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Class Number</th>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th>Subject Type</th>
                                    <th>Level</th>
                                    <th>Trade Name</th>
                                    <th>No.of Students</th>
                                    <th>Instructor</th>
                                    <th>Post</th>
                                    <th>Bulk</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                foreach ($courseprogramme as $std) {
                                    $count++;
                                    $courseID = $std['courseID'];
                                    $courseCode = $std['courseCode'];
                                    $courseName = $std['courseName'];
                                    $courseTypeID = $std['courseTypeID'];
                                    $programmeLevelID = $std['programmeLevelID'];
                                    $programmeID = $std['programmeID'];
                                    $classNumber = $std['classNumber'];
                                    $staffID = $std['staffID'];
                                    $cpcourseID = $std['centerProgrammeCourseID'];

                                    // echo  $cpcourseID;

                                //    echo $_SESSION['department_session'];

                                    $studentNumber = $db->getStudentCourseSum($_SESSION['department_session'], $academicYearID, $programmeLevelID, $programmeID);
                                    // $studentNumber = $db->getStudentNumber($academicYearID, $programmeLevelID, $programmeID);
                                    $addButton = '
                                <div class="btn-group">
                                        <a href="index3.php?sp=add_term_marks&cid=' . $db->encrypt($cpcourseID) . '"class="fa fa-plus"></a>
                                </div>';

                                    $excelButton = '
                                    <div class="btn-group">
                                        <a href="index3.php?sp=import_term_score&cid=' . $db->encrypt($cpcourseID) . '" class="glyphicon glyphicon-upload"></a>
                                    </div>';

                                    $viewButton = '
                                    <div class="btn-group">
                                        <a href="index3.php?sp=view_term_score&cid='.$db->encrypt($cpcourseID).'&staffid='.$db->encrypt($instructorID).'&lvid='.$db->encrypt($programmeLevelID).'&termID='.$db->encrypt($examCategoryID).'&centerID='.$db->encrypt($centerID).'" class="glyphicon glyphicon-eye-open"></a>
                                </div>';

                                ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $classNumber; ?></td>
                                        <td><?php echo $courseName; ?></td>
                                        <td><?php echo $courseCode; ?></td>
                                        <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                                        <td><?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?></td>
                                        <td><?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?></td>
                                        <td><?php echo $studentNumber; ?></td>
                                        <td>
                                            <?php echo $db->getData("instructor", "instructorName", "instructorID", $staffID); ?>
                                        </td>
                                        <td><?php echo $addButton; ?></td>
                                        <td><?php echo $excelButton; ?></td>
                                        <td><?php echo $viewButton; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <h4 class="text-danger">No Course Found</h4>
    <?php
        }
    }
    ?>
</div>