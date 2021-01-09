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
    <h3>Batch Registration</h3>

    <h5 class="text-danger">NB:Your file must have this format(Registration,FirstName,MiddleName,LastName,Gender(M/F))</h5>
    <hr>
    <form name="" method="post" action="action_upload_student_list.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-2">
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

            <div class="col-lg-2">
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
                <select name="programmeID" id="programmeID" class="form-control" required>
                    <option value="">Select Here</option>
                </select>
            </div>

            <div class="col-lg-2">

                <label for="MiddleName">Admission Year</label>
                <select name="admissionYearID" class="form-control" required>
                    <?php
                    $adYear = $db->getRows('academic_year', array('order_by' => 'academicYear ASC'));
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
                <label for="FirstName">Attachment</label>
                <input type='file' name="csv_file" accept=".csv" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9"></div>
            <div class="col-lg-3">
                <label for=""></label>
                <input type="hidden" name="action_type" value="add" />
                <input type="submit" name="doFind" value="Upload File" class="btn btn-primary form-control" /></div>
        </div>
    </form>

    <div class="row"><br></div>

</div>