<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<!-- <script src="js/script.js"></script> -->

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
                }
            });

        });

    });
</script>

<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#programID").change(function() {
            var id = $(this).val();
            var dataString = 'id=' + id;
            $.ajax({
                type: "POST",
                url: "ajax_studyear.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#studyYear").html(html);
                }
            });

        });

    });
</script>

<script>
    function print_exam_result() {
        var programmeID = document.getElementById("programmeID").value;
        var levelID = document.getElementById("programmeLevelID").value;
        var academicYearID = document.getElementById("academicYearID").value;
        var centerID = document.getElementById("centerID").value;
        var examCategoryID = document.getElementById("examCategoryID").value;
        var dataString = 'centerID=' + centerID + '&levelID=' + levelID + '&programmeID=' + programmeID + '&academicYearID=' + academicYearID + '&examCategoryID=' + examCategoryID;
        console.log(dataString);
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            url: "ajax_view_term_result.php",
            data: dataString,
            cache: false,
            success: function(html) {
                $('#myPleaseWait').modal('hide');
                $("#result").html(html);
            }
        });
        return false;
    }
</script>

<?php $db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Supplementary Report</h1>
        <hr>
        <div class="tab-content">
            <!-- Current Semester -->
            <?php $db = new DBHelper(); ?>
            <form name="" method="post" action="">
                <!--   <form name="" method="post" action="" onsubmit="return print_exam_result();"> -->
                <div class="row">
                    <div class="col-lg-3">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerID" id="centerIDD" class="form-control chosen-select" required="">
                            <?php
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
                        <label for="MiddleName">Trade Name</label>
                        <select name="programmeID" id="programmeID" class="form-control" required>
                            <option value="">Select Here</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label for="FirstName">Academic Year</label>
                        <select name="academicYearID" id="academicYearID" class="form-control" required>
                            <?php
                            $academic_year = $db->getRows('academic_year', array('where' => array('status' => 1), 'order_by' => 'academicYear ASC'));
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



                </div>
                <div class="row">
                    <div class="col-lg-9"></div>
                    <!-- <div class="col-lg-3">
                            <label for=""></label>
                            <input type="submit" name="doPreview" value="Preview PDF" class="btn btn-primary form-control" /></div>-->
                    <div class="col-lg-3">
                        <label for=""></label>
                        <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" />
                    </div>
                </div>
            </form>

            <div class="row"><br></div>


        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="result">
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-time">
                    </span>Please wait...page is loading
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End -->