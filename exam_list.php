<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">
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
<?php $db = new DBHelper();
?>
<div class="container">
    <h3>Exam List</h3>
    <hr>
    <form name="" method="post" action="">
        <div class="row">
            <div class="col-lg-4">
                <label for="Physical Address">Center Name</label>
                <select name="centerID" id="centerIDD" class="form-control" required>
                    <option value="">Select Here</option>
                    <?php
                    $center = $db->getRows('center_registration', array('order_by' => 'centerName ASC'));
                    if (!empty($center)) {

                        $count = 0;
                        foreach ($center as $cnt) {
                            $count++;
                            $centerRegistrationID = $cnt['centerRegistrationID'];
                            $centerName = $cnt['centerName'];
                    ?>
                            <option value="<?php echo $centerRegistrationID; ?>"><?php echo $centerName; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div class="col-lg-4">
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


            <div class="col-lg-4">

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


        </div>
        <div class="row">
            <div class="col-lg-9"></div>
            <div class="col-lg-3">
                <label for=""></label>
                <input type="hidden" name="action_type" value="add" />
                <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
        </div>
    </form>
    <div class="row">
        <hr>
    </div>
    <div class="row">
        <?php
        if (isset($_POST['doFind']) == "Find Records") {
            $programmeLevelID = $_POST['programmeLevelID'];
            $centerID = $_POST['centerID'];
            $academicYearID = $_POST['academicYearID'];

        ?> <?php
            $student = $db->getCenterStudentExamNumber($centerID, $programmeLevelID, $academicYearID);

            if (!empty($student)) {
            ?>
                <div class="row">
                    <div class="col-lg-9">
                        <h4 class="text-danger" id="titleheader">
                            List of Exam Number for Registered Student at <?php echo $db->getData("center_registration", "centerName", "centerRegistrationID", $centerID); ?>
                            <?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?>
                            - <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
                        </h4>
                    </div>
                    <div class="col-lg-3">
                        <a href='print_exam_list.php?action=getPDF&cid=<?php echo $centerID; ?>&lid=<?php echo $programmeLevelID; ?>&ay=<?php echo $academicYearID; ?>' target='_blank'> <button type="button" class="btn btn-success pull-right form-control " style="margin-right: 5px; margin-bottom: 5px; ">
                                <i class="fa fa-download"></i>Print Report
                            </button></a>

                            <a href='exam_dailyreport.php?action=getPDF&cid=<?php echo $centerID; ?>&lid=<?php echo $programmeLevelID; ?>&ay=<?php echo $academicYearID; ?>' target='_blank'> <button type="button" class="btn btn-success pull-right form-control" style="margin-right: 5px; margin-bottom: 5px; ">
                                <i class="fa fa-download"></i>Print Exam Attendance
                            </button></a>

                            <a href='marking_sheet.php?action=getPDF&cid=<?php echo $centerID; ?>&lid=<?php echo $programmeLevelID; ?>&ay=<?php echo $academicYearID; ?>' target='_blank'> <button type="button" class="btn btn-success pull-right form-control" style="margin-right: 5px; margin-bottom: 5px; ">
                                <i class="fa fa-download"></i>Print Marking Sheet
                            </button></a>
                            <a href='computer_application_sheet.php?action=getPDF&cid=<?php echo $centerID; ?>&lid=<?php echo $programmeLevelID; ?>&ay=<?php echo $academicYearID; ?>' target='_blank'> <button type="button" class="btn btn-success pull-right form-control" style="margin-right: 5px;">
                                <i class="fa fa-download"></i>Print C/Application Sheet
                            </button></a>
                    </div>
                </div>

                <hr>
                <table id="example" class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Reg.Number</th>
                            <th>Trade Name</th>
                            <th>Exam Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($student as $st) {
                            $count++;
                            $studentID = $st['studentID'];
                            $fname = $st['firstName'];
                            $mname = $st['middleName'];
                            $lname = $st['lastName'];
                            $name = "$fname $mname $lname";
                            $regNumber = $st['registrationNumber'];
                            $programmeID = $st['programmeID'];
                            $tradeName = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
                            $examNumber = $db->getRows("exam_number", array('where' => array('regNumber' => $regNumber, 'academicYearID' => $academicYearID)));
                            if (!empty($examNumber)) {
                                foreach ($examNumber as $exam) {
                                    $exam_number = $exam['examNumber'];
                                }
                            } else {
                                $exam_number = "None";
                            }
                        ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $regNumber; ?></td>
                                <td><?php echo $tradeName; ?></td>
                                <td><?php echo $exam_number; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>



            <?php
            } else {
            ?>
                <h4><span class="text-danger">No Student(s) found......</span> </h4>
            <?php
            }
            ?>

            <div class="row">
                <br><br>
            </div>
        <?php
        }
        ?>
    </div>

</div>