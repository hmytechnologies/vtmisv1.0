<?php
session_start();
include("DB.php");
$db=new DBHelper();
if($_POST['examCategoryID'])
{
$examCategoryID = $_POST['examCategoryID'];
$assessmentTypeID = $_POST['assessmentTypeID'];
$courseID = $_POST['courseID'];
$academicYearID = $_POST['academicYearID'];
$examDate = $_POST['examDate'];
$programmeID=$_POST['programmeID'];
$programmeLevelID=$_POST['programmeLevelID'];
if ($examDate == "")
    $examDate = date("Y-m-d");
?>
<div class="col-lg-6">
    <form name="" method="post" action="action_assessment_exam_score.php">
        <?php
        $student = $db->getStudentTermList($_SESSION['department_session'],$academicYearID,$programmeLevelID,$programmeID);
        if (!empty($student))
        {
        $count = 0;
        ?>
        <h4>Add Result for <span class="text-danger">
                <?php echo $db->getData("assessment_type", "assessmentType", "assessmentTypeID", $assessmentTypeID); ?></span>
                -<?php echo $db->getData("exam_category", "examCategory", "examCategoryID", $examCategoryID); ?></span>
            <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
        </h4>

        <table id="onlydata" border=1 class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>No.</th>
                <th>Registration Number</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($student as $st) {
                $count++;
                $regNumber = $st['regNumber'];
                ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var current = $('#select<?php echo $count;?>').val();
                        if (current == '0') {
                            $('#select<?php echo $count;?>').css('color', 'red');
                        } else {
                            $('#select<?php echo $count;?>').css('color', 'green');
                        }
                        $('#select<?php echo $count;?>').change(function () {
                            var current = $('#select<?php echo $count;?>').val();
                            if (current == '0') {
                                $('#select<?php echo $count;?>').css('color', 'red');
                            } else {
                                $('#select<?php echo $count;?>').css('color', 'green');
                            }
                        });
                    });
                </script>
            <?php
            $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => ' registrationNumber ASC'));
            if (!empty($studentDetails))
            {
            foreach ($studentDetails

            as $std)
            {
            ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <input type='text' hidden name="regNumber[]" value="<?php echo $std['registrationNumber']; ?>">
                    <td><?php echo $std['registrationNumber']; ?></td>
                    <?php

                    ?>
                    <?php

                    $score = $db->getRows('assessment_result', array('where' => array('examCategoryID' => $examCategoryID,'assessmentTypeID'=>$assessmentTypeID, 'regNumber' => $regNumber, 'courseID' => $courseID, 'academicYearID' => $academicYearID), ' order_by' => 'regNumber ASC'));
                    if (!empty($score)) {
                        foreach ($score as $sc) {
                            $present = $sc['present'];
                            ?>
                            <td><input type="text" name="score[]" value="<?php echo $db->decrypt($sc['examScore']); ?>"
                                       class='form-control'></td>
                            <td>
                                <select name="status[]" class="form-control" id="select<?php echo $count; ?>">
                                    <?php
                                    if ($present == 1) {
                                        ?>
                                        <option value="1" selected><span class="text-primary">Present</span>
                                        </option>
                                        <option value="0">Absent without reason(A0)</option>
                                        <option value="-1">Absent with reason(A1)</option>
                                        <?php
                                    } else if ($present == -1) {
                                        ?>
                                        <option value="-1" selected><span
                                                style="color: red;">Absent with reason(A1)</span></option>
                                        <option value="0">Absent without reason(A0)</option>
                                        <option value="1"><span class="text-primary">Present</span></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="-1" selected><span
                                                style="color: red;">Absent with reason(A1)</span></option>
                                        <option value="1"><span class="text-primary">Present</span></option>
                                        <option value="0" selected><span
                                                style="color: red;">Absent without reason(A0)</span></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        ?>

                        <?php
                    } else {
                        ?>
                        <td><input type='text' name="score[]" class='form-control'></td>
                        <td>
                            <select name="status[]" class="form-control"
                                    id="select<?php echo $count; ?>">
                                <option value="1"><span class="text-primary">Present</span></option>
                                <option value="-1">Absent with reason(A1)</option>
                                <option value="0">Absent without reason(A0)</option>
                            </select>
                        </td>
                    <?php }
                    ?>
                </tr>
                <?php
            }
                ?>

                <?php
            }
            }
            ?>

            <?php

            ?>
            </tbody>
        </table>
        <br/>
        <div class="row">
            <div class="col-lg-6"></div>
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="add"/>
                <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
                <input type="hidden" name="number_student" value="<?php echo $count; ?>">
                <input type="hidden" name="academicYearID" value="<?php echo $academicYearID; ?>">
                <input type="hidden" name="examCategoryID" value="<?php echo $examCategoryID; ?>">
                <input type="hidden" name="examDate" value="<?php echo $examDate; ?>">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control"/>
            </div>
            <div class="col-lg-3">
                <input type="reset" value="Cancel" class="btn btn-danger form-control"/>
            </div>
        </div>
    </form>
    <?php
    }
        else
        {
            echo "No Student Found";
        }


?>
</div>
<?php
}
?>