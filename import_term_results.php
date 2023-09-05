<?php
$db = new DBHelper();
?>
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#onlydata').dataTable({
            paging: false,
            dom: 'Blfrtip'
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#examCategoryID").change(function() {
            var examCategoryID = $(this).val();
            var courseID = $("#courseID").val();
            var academicYearID = $("#academicYearID").val();
            var examDate = $("#exam_date").val();
            var programmeLevelID = $("#programmeLevelID").val();
            var programmeID = $("#programmeID").val();

            var dataString = 'examCategoryID=' + examCategoryID + '&courseID=' + courseID + '&academicYearID=' + academicYearID + '&examDate=' + examDate + '&programmeLevelID=' + programmeLevelID + '&programmeID=' + programmeID;
            console.log(dataString);
            $.ajax({
                type: "POST",
                url: "ajax_add_term_result.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#output").html(html);
                }
            });

        });

    });
</script>

<div class="container">
    <?php
    $centerProgrammeCourseID = $db->decrypt($_REQUEST['cid']);

    $course = $db->getCourseInfoo($centerProgrammeCourseID);

    foreach ($course as $std) {
        // $count++;
        $courseID = $std['courseID'];
        $courseCode = $std['courseCode'];
        $courseName = $std['courseName'];
        $courseTypeID = $std['courseTypeID'];
        $programmeLevelID = $std['programmeLevelID'];
        $programmeID = $std['programmeID'];
        $classNumber = $std['classNumber'];
        $staffID = $std['staffID'];
        $cpcourseID = $std['centerProgrammeCourseID'];
        $academicYearID = $std['academicYearID'];
    }
    $studentNumber = $db->getStudentCourseSum($_SESSION['department_session'], $academicYearID, $programmeLevelID, $programmeID);
    ?>

    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Assessment Results</h3>
            </div>
            <div class="box-body">
                <table id="" class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Class Number</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Subject Type</th>
                            <th>Level</th>
                            <th>Trade Name</th>
                            <th>No.of Students</th>
                            <th>Academic Year</th>
                            <th>File List</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $classNumber; ?></td>
                            <td><?php echo $courseName; ?></td>
                            <td><?php echo $courseCode; ?></td>
                            <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                            <td><?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?></td>
                            <td><?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?></td>
                            <td><?php echo $studentNumber; ?></td>
                            <td><?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?></td>
                            <td><a href="uploaded_file/exam_sheet.csv" class="glyphicon glyphicon-download-alt" target="_blank"></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

    <input type="hidden" name="programmeID" id="programmeID" value="<?php echo $programmeID; ?>">
    <input type="hidden" name="programmeLevelID" id="programmeLevelID" value="<?php echo $programmeLevelID; ?>">
    <input type="hidden" name="courseID" id="courseID" value="<?php echo $courseID; ?>">
    <input type="hidden" name="academicYearID" id="academicYearID" value="<?php echo $academicYearID; ?>">

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (!empty($_SESSION['statusMsg'])) {
                echo "<div class='alert alert-success fade in'>
              <a href='#' class='close' data-dismiss='alert'>&times;</a>
              <strong>" . $_SESSION['statusMsg'] . "</strong>.
          </div>";
                unset($_SESSION['statusMsg']);
            }
            ?>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_date").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>
    <form name="" method="post" action="action_upload_term_score.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-3">
                <label for="MiddleName">Exam Date</label>
                <input type="text" name="examDate" class="form-control" id="exam_date">
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

            <div class="col-lg-3">
                <label for="FirstName">Attachment</label>
                <input type='file' name="csv_file" accept=".csv" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6"></div>
            <div class="col-lg-3">
                <label for=""></label>
                <input type="hidden" name="action_type" value="add" />
                <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
                <input type="hidden" name="academicYearID" value="<?php echo $academicYearID; ?>">
                <input type="hidden" name="levelID" value="<?php echo $levelID; ?>">
                <input type="submit" name="doFind" value="Upload File" class="btn btn-primary form-control" /></div>
        </div>
    </form>



</div>
</form>

<!-- <div class="row">
    <div id="output">

    </div>
</div> -->

<!-- </form> -->
<div class="row">
    <div class="col-lg-3">
        <?php
        if ($_SESSION['main_role_session'] == 3) {
        ?>
            <a href="index3.php?sp=term_marks" class="btn btn-success form-control">Go Back</a>
        <?php
        } else {
        ?>
            <a href="index3.php?sp=term_marks" class="btn btn-success form-control">Go Back</a>
        <?php
        }
        ?>
    </div>
</div>

</div>