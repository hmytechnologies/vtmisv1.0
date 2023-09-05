<?php
$db = new DBHelper();
?>
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
            var levelID = $("#levelID").val();
            var pID=$("#pID").val();

            var dataString = 'examCategoryID=' + examCategoryID + '&courseID=' + courseID + '&academicYearID=' + academicYearID + '&examDate=' + examDate + '&levelID=' + levelID+'&pID='+pID;
            console.log(dataString);
            $.ajax({
                type: "POST",
                url: "ajax_add_score.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    console.log(dataString);
                    $("#output").html(html);
                }
            });

        });

    });
</script>

<div class="container">
    <h4>Add Result for <span class="text-danger">
    <!-- cid=' . $db->encrypt($courseID) . '&acadID=' . $db->encrypt($academicYearID) . '&lvlID=' .
     $db->encrypt($programmeLevelID) . '&pid=' . $db->encrypt($programmeID) -->
            <?php
            $courseID = $db->decrypt($_REQUEST['cid']);
            $academicYearID = $db->decrypt($_REQUEST['acadID']);
            $programmeLevelID = $db->decrypt($_REQUEST['lvlID']);
            $programmeID = $db->decrypt($_REQUEST['pid']);

            $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
            if (!empty($course)) {
                foreach ($course as $c) {
                    $courseCode = $c['courseCode'];
                    $courseName = $c['courseName'];
                    $courseTypeID = $c['courseTypeID'];
                }
            }
            echo $courseCode . "-" . $courseName . "-" . $db->getData("academic_year", "academicYear", "academicYearID", $db->decrypt($_REQUEST['acadID']));
            ?>
            -<?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?>-<?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?></span></h4>
    <hr>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (!empty($_SESSION['statusMsg'])) {
                echo "<div class='alert alert-success fade in'>
              <a href='#' class='close' data-dismiss='alert'>&times;</a>
              <strong>" . $_SESSION['statusMsg'] . "</strong>.
          </div>";
                unset($_SESSION['statusMsg']);
            } ?>
        </div>
    </div>
    <!-- <form name="" method="post" action=""> -->

    <input type="hidden" id="courseID" value="<?php echo $courseID; ?>">
    <input type="hidden" id="academicYearID" value="<?php echo $academicYearID; ?>">
    <input type="hidden" id="levelID" value="<?php echo $programmeLevelID; ?>">
    <input type="hidden" id="pID" value="<?php echo $programmeID;?>">

    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_date").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>

    <div class="row">
        <div class="col-lg-3">
            <label for="MiddleName">Exam Date</label>
            <input type="text" name="examDate" class="form-control" id="exam_date">

            <!--<div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
    <input class="form-control" size="16" type="text" name="examDate" value="" id="pickyDate">
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>-->
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="email">Exam Category</label>
                <select name="examCategoryID" class="form-control" id="examCategoryID">
                    <?php
                    $exam_category = $db->getFinalExamCategory();
                    if (!empty($exam_category)) {
                        echo "<option value=''>Please Select Here</option>";
                        foreach ($exam_category as $prg) {
                            $examCategory = $prg['examCategory'];
                            $examCategoryID = $prg['examCategoryID'];
                            echo "<option value='$examCategoryID'>$examCategory</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="output">

        </div>
    </div>

    <!-- </form> -->
    <div class="row">
        <div class="col-lg-3">
            <?php
            if ($_SESSION['role_session'] == 3) {
            ?>
                <a href="index3.php?sp=instructor_exam_results" class="btn btn-success form-control">Go Back</a>
            <?php
            } else {
            ?>
                <a href="index3.php?sp=addresult" class="btn btn-success form-control">Go Back</a>
            <?php
            }
            ?>
        </div>
    </div>

</div>