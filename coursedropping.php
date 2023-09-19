<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add your existing scripts here -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#select_all').click(function(event) { //on click
            if (this.checked) { // check select status
                $('.checkbox_class').each(function() { //loop through each checkbox
                    this.checked = true; //select all checkboxes with class "checkbox_class"
                });
            } else {
                $('.checkbox_class').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox_class"
                });
            }
        });

        // Update the click event handler for the "Delete Selected" button
        $('#deleteSelected').click(function() {
            var selectedItems = [];

            $('.checkbox_class:checked').each(function() {
                selectedItems.push($(this).val());
            });

            if (selectedItems.length === 0) {
                alert('Please select items to delete.');
            } else {
                // Set the selected items in the hidden input field
                $('#finalResultIDs').val(selectedItems.join(','));

                // Submit the form
                $('#deleteForm').submit();

                // Log selected items to the console
                console.log('Selected Items:', selectedItems);
            }
        });
    });
</script>

<?php $db=new DBHelper(); ?>
<div class="container">
    <h1>Trade Course Mapping</h1>
    <a href="index3.php?sp=sysconf" class="btn btn-warning pull-right">Back to Main Setting</a>
    <br>
    <hr>
    <div class="col-md-12">
        <div class="row">
            <h3>Select Programme to Map with Courses</h3>
            <div class="row">
                <form name="" method="post" action="">
                    <div class="col-lg-3">
                        <label for="MiddleName">Programme Name</label>
                        <select name="programmeID" class="form-control chosen-select" required="">
                            <?php
                            if ($_SESSION['role_session'] == 9) {
                                $programmes = $db->getRows('programmes', array('where' => array('departmentID' => $_SESSION['department_session']), 'order_by' => 'programmeName ASC'));
                            } else {
                                $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
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
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="FirstName">Academic Year</label>
                        <select name="academicYear" id="academicYearID" class="form-control" required>
                            <?php
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
                    <div class="col-lg-4">
                        <label for=""></label>
                        <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <hr>
            <div class="row">
                <?php
                if (!empty($_REQUEST['msg'])) {
                    if ($_REQUEST['msg'] == "succ") {
                        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Mapping data has been inserted successfully</strong>.
                        </div>";
                    } else if ($_REQUEST['msg'] == "deleted") {
                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Programme Mapping Data has been deleted successfully</strong>.
                        </div>";
                    }
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="example" class="display nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Registration Number</th>
                                <th>Course Name</th>
                                <th>Term Score</th>

                                <th>Final Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['doSearch']) && $_POST['doSearch'] == "Search Records") {
                                $programmeID = $_POST['programmeID'];
                                $academicYearID = $_POST['academicYear'];
                                $data = $db->dropCourse($programmeID, $academicYearID);
                                if (!empty($data)) {
                                    $count = 0;
                                    $total_credits = 0;
                                    foreach ($data as $dt) {
                                        $count++;
                                        $courseID = $dt['courseID'];
                                        $finalResultID = $dt['finalResultID'];
                                        // $examScore = $dt['examScore'];
                                        $courseName = $dt['courseName'];
                                        $academicYear = $dt['academicYearID'];
                                        $examCategoryID = $dt['examCategoryID'];
                                        $examNumber = $dt['examNumber'];
                                        $regNumber = $dt['regNumber'];


                                        $term1Score = $db->decrypt($db->getTermGrade($academicYear, $courseID, $regNumber, 1));
                                        $term2Score = $db->decrypt($db->getTermGrade($academicYear, $courseID, $regNumber, 2));
                                        $finalScore = $db->decrypt($db->getFinalTermGrade($academicYear, $courseID, $examNumber, 3));



                                        $exam_category_marks = $db->getTermCategorySetting();
                                                if (!empty($exam_category_marks)) {
                                                    foreach ($exam_category_marks as $gd) {
                                                        $mMark = $gd['mMark'];
                                                        $pMark = $gd['passMark'];
                                                        $wMark = $gd['wMark'];
                                                    }
                                                }

                                                $term1m = ($term1Score / $mMark) * $wMark;
                                                $term2m = ($term2Score / $mMark) * $wMark;
                                                $totalTearmMarks = $db-> calculateTermTotal($term1m, $term2m);

                                                $final = ($finalScore / 100) * 50;
                                                $totalMarks = $term1m + $term2m + $final;








                            ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td width="10"><input type='checkbox' class='checkbox_class' name='finalResult[]' value='<?php echo $finalResultID; ?>'></td>
                                            <td><?php echo  $regNumber; ?></td>
                                            <td><?php echo  $courseName; ?></td>
                                            <td><?php echo  $totalTearmMarks; ?></td>
                                            <td><?php echo  $totalMarks; ?></td>
                                            
                                            
                                            <td><a href="dropcourse_action.php?action_type=delete&course=<?php echo $courseID; ?>"
                                                    class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                                        </tr>
                            <?php
                                    }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
                                } else {
                                    echo "<h4 class='text-danger'>No Registered Course</h4>";
                                }
                            }
?>
<!-- Modified: Your hidden form to submit selected items -->
<form id="deleteForm" action="dropcourse_action.php" method="post">
    <input type="hidden" name="finalResultIDs" id="finalResultIDs">
</form>

<!-- Modified: "Delete Selected" button now submits the form -->
<button type="button" id="deleteSelected" class="btn btn-danger">Delete Selected</button>
