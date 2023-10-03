<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>



<?php $db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Transfer Student</h1>
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#selecctall').click(function(event) { //on click
                    if (this.checked) { // check select status
                        $('.checkbox1').each(function() { //loop through each checkbox
                            this.checked = true; //select all checkboxes with class "checkbox1"
                        });
                    } else {
                        $('.checkbox1').each(function() { //loop through each checkbox
                            this.checked = false; //deselect all checkboxes with class "checkbox1"
                        });
                    }
                });

            });
        </script>

        <hr>
        <!-- <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#internal_transfer"><span style="font-size: 16px"><strong>Internal Transfer</strong></span></a></li>
            <li><a data-toggle="tab" href="#external_transfer"><span style="font-size: 16px"><strong>External Transfer</strong></span></a></li>
        </ul>

        <div class="tab-content"> -->
        <!-- Previous Semester -->

        <?php
        if ($_SESSION['main_role_session'] == 7) {
            $centerID = 'all';
        } else {
            $centerID = $_SESSION['user_session'];
        }
        ?>

        <div id="internal_transfer" class="tab-pane fade in active">
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#programmeLevelID").change(function() {
                        var programmeLevelID = $(this).val();
                        var centerID = $("#centerID").val();
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
                <form name="" method="post" action="">
                    <input type="text" hidden name="centerID" value="<?php echo $centerID; ?>" id="centerID">
                    <div class="col-lg-3">
                        <label for="middleName">Admission Year</label>
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
                        <select name="programmeID" class="form-control" required>
                            <?php
                            if ($_SESSION['main_role_session'] == 7) {
                                $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                            } else {

                                $userId = $_SESSION['user_session'];
                                $instructor = $db->getRows('instructor', array('where' => array('userID' => $userId), 'order_by' => 'instructorID ASC'));

                                if (!empty($instructor)) {
                                    foreach ($instructor as $i) {
                                        $instructorID = $i['instructorID'];
                                        $centerID = $i['centerID'];
                                        $departmentID = $i['departmentID'];
                                        $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                    }
                                }

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
                                <option value=""><?php echo "No Data Found"; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label for=""></label>
                        <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>



            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Transfer data has been inserted successfully</strong>.
            </div>";
                } else if ($_REQUEST['msg'] == "deleted") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Transfer data has been deleted successfully</strong>.
            </div>";
                }
            }
            ?>
            <div class="row">
                <?php
                if (isset($_POST['doFind']) == "Find Records") {

                    $academicYearID = $_POST['admissionYearID'];
                    $programmeID = $_POST['programmeID'];
                    if (($_SESSION['main_role_session'] == 7)) {
                        $centerID = $_POST['centerID'];
                    } else {

                        $userId = $_SESSION['user_session'];
                        $instructor = $db->getRows('instructor', array('where' => array('userID' => $userId), 'order_by' => 'instructorID ASC'));

                        if (!empty($instructor)) {
                            foreach ($instructor as $i) {
                                $instructorID = $i['instructorID'];
                                $centerID = $i['centerID'];
                                $departmentID = $i['departmentID'];
                                $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                            }
                        }
                    }

                    $levelID = $_POST['programmeLevelID'];
                    $student = $db->getStudentTransfer($centerID, $levelID, $programmeID, $academicYearID);
                    if (!empty($student)) {
                ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#programmeLevelID2").change(function() {
                                    var programmeLevelID2 = $(this).val();
                                    var centerID = $("#centerID").val();
                                    var dataString = 'programmeLevelID=' + programmeLevelID2 + '&centerID=' + centerID;
                                    $.ajax({
                                        type: "POST",
                                        url: "ajax_programme.php",
                                        data: dataString,
                                        cache: false,
                                        success: function(html) {
                                            $("#programmeID2").html(html);
                                            console.log(dataString);
                                        }
                                    });

                                });

                            });
                        </script>
                        <h4><span class="text-danger" id="titleheader">
                                List of Students in <?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID); ?> in <?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?>
                                <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
                            </span></h4>
                        <hr>
                        <form name="register" id="register" method="post" action="action_transfer_student.php">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="middleName">Admission Year</label>
                                    <select name="admissionYearID" class="form-control" required>
                                        <?php
                                        $adYear = $db->getRows('academic_year', array('order_by' => 'academicYear ASC'));
                                        if (!empty($adYear)) {
                                            echo "<option value=''>Please Select Here</option>";
                                            $count = 0;
                                            foreach ($adYear as $year) {
                                                $count++;
                                                $academic_year2 = $year['academicYear'];
                                                $academic_year_id2 = $year['academicYearID'];
                                        ?>
                                                <option value="<?php echo $academic_year_id2; ?>"><?php echo $academic_year2; ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="Physical Address">Trade Level</label>
                                    <select name="programmeLevelID" id="programmeLevelID2" class="form-control" required>
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
                                    <select name="programmeID" class="form-control" required>
                            <?php
                            if ($_SESSION['main_role_session'] == 7) {
                                $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                            } else {

                                $userId = $_SESSION['user_session'];
                                $instructor = $db->getRows('instructor', array('where' => array('userID' => $userId), 'order_by' => 'instructorID ASC'));

                                if (!empty($instructor)) {
                                    foreach ($instructor as $i) {
                                        $instructorID = $i['instructorID'];
                                        $centerID = $i['centerID'];
                                        $departmentID = $i['departmentID'];
                                        $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                    }
                                }

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
                                <option value=""><?php echo "No Data Found"; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                                </div>
                            </div>
                            <hr>
                            <table id="example" class="display nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                        <th>Full Name</th>
                                        <th>Reg.Number</th>
                                        <th>Center Name</th>
                                        <th>Student Status</th>
                                        <th>Hosteller</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($student as $st) {
                                        $count++;
                                        $regNumber = $st['registrationNumber'];
                                        $centerID = $st['centerID'];
                                        $student_list = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                                        foreach ($student_list as $lst) {
                                            $fname = $lst['firstName'];
                                            $mname = $lst['middleName'];
                                            $lname = $lst['lastName'];
                                            $sponsor = $lst['sponsor'];
                                            $name = "$fname $mname $lname";

                                    ?>
                                            <tr>
                                                <td width="10"><?php echo $count; ?></td>

                                                <td width="10"><input type='checkbox' class='checkbox_class' name='regNumber[]' value='<?php echo $regNumber; ?>'></td>
                                                <input type="text" hidden name="centerID[]" value="<?php echo $centerID; ?>">
                                                <td width="70"><?php echo $name ?></td>
                                                <td width="50"><?php echo $st['registrationNumber']; ?></td>
                                                <td width="40"><?php /*echo $db->getData('center_registration','centerName','centerRegistrationID',$centerID);*/ ?>
                                                    <select name="centerID[]" class="form-control">
                                                        <option value="<?php echo $centerID; ?>"><?php echo $db->getData('center_registration', 'centerName', 'centerRegistrationID', $centerID); ?></option>
                                                        <?php
                                                        $level_center = $db->getRows('center_registration', array('order_by' => 'centerRegistrationID ASC'));
                                                        if (!empty($level_center)) {

                                                            $count = 0;
                                                            foreach ($level_center as $clvl) {
                                                                $count++;
                                                                $centerLevelID = $clvl['centerRegistrationID'];
                                                                $centerName = $clvl['centerName'];
                                                        ?>
                                                                <option value="<?php echo $centerLevelID; ?>"><?php echo $centerName; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td width="20"><?php echo $db->getData("status", "statusValue", "statusID", $lst['statusID']); ?></td>
                                                <td width="40">
                                                    <select name="hosteller[]" class="form-control">
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                <?php
                            } else {
                                ?>
                                    <h4><span class="text-danger">No Student(s) found......</span></h4>
                                <?php
                            }
                                ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                                <div class="col-lg-3">
                                    <input type="hidden" name="action_type" value="add" />
                                    <input type="submit" name="doAdmit" value="Transfer Student" class="btn btn-success form-control">
                                </div>
                            </div>
                        <?php
                    }
                        ?>
            </div>
            <!-- </div> -->

            <!--            <div id="external_transfer" class="tab-pane">
                <script type="text/javascript">
                    $(document).ready(function()
                    {
                        $("#programmeLevelID").change(function()
                        {
                            var programmeLevelID=$(this).val();
                            var centerID=$("#centerID").val();
                            var dataString = 'programmeLevelID='+programmeLevelID+'&centerID='+centerID;
                            $.ajax
                            ({
                                type: "POST",
                                url: "ajax_programme.php",
                                data: dataString,
                                cache: false,
                                success: function(html)
                                {
                                    $("#programmeID").html(html);
                                    console.log(dataString);
                                }
                            });

                        });

                    });
                </script>
                <div class="row">
                    <form name="" method="post" action="">
                        <input type="text" hidden name="centerID" value="<?php /*echo $centerID;*/ ?>" id="centerID">
                        <div class="col-lg-3">
                            <label for="middleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                                <?php
                                /*                                $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                if(!empty($adYear)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($adYear as $year){ $count++;
                                        $academic_year=$year['academicYear'];
                                        $academic_year_id=$year['academicYearID'];
                                        */ ?>
                                        <option value="<?php /*echo $academic_year_id;*/ ?>"><?php /*echo $academic_year;*/ ?></option>
                                    <?php /*}
                                }
                                */ ?>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="Physical Address">Trade Level</label>
                            <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required>
                                <option value="">Select Here</option>
                                <?php
                                /*                                $level = $db->getRows('programme_level',array('order_by'=>'programmeLevelCode ASC'));
                                if(!empty($level)){

                                    $count = 0; foreach($level as $lvl){ $count++;
                                        $programmeLevelID=$lvl['programmeLevelID'];
                                        $programmeLevel=$lvl['programmeLevel'];
                                        */ ?>
                                        <option value="<?php /*echo $programmeLevelID;*/ ?>"><?php /*echo $programmeLevel;*/ ?></option>
                                    <?php /*}}*/ ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Physical Address">Trade Name</label>
                            <select name="programmeID" id="programmeID"  class="form-control" required>
                                <option value="">Select Here</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for=""></label>
                            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                    </form>
                </div>



                <?php
                /*                if(!empty($_REQUEST['msg']))
                {
                    if($_REQUEST['msg']=="succ")
                    {
                        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course data has been inserted successfully</strong>.
            </div>";
                    }
                    else if($_REQUEST['msg']=="deleted") {
                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course Data has been delete successfully</strong>.
            </div>";
                    }
                }
                */ ?>
                <div class="row">
                    <?php
                    /*                    if(isset($_POST['doFind'])=="Find Records") {
                    $academicYearID = $_POST['admissionYearID'];
                    $programmeID = $_POST['programmeID'];
                    $centerID = $_POST['centerID'];
                    $levelID=$_POST['programmeLevelID'];
                    $student = $db->getStudentTransfer($centerID,$levelID,$programmeID,$academicYearID);
                    if (!empty($student)) {
                    */ ?>
                    <script type="text/javascript">
                        $(document).ready(function()
                        {
                            $("#programmeLevelID2").change(function()
                            {
                                var programmeLevelID2=$(this).val();
                                var centerID=$("#centerID").val();
                                var dataString = 'programmeLevelID='+programmeLevelID2+'&centerID='+centerID;
                                $.ajax
                                ({
                                    type: "POST",
                                    url: "ajax_programme.php",
                                    data: dataString,
                                    cache: false,
                                    success: function(html)
                                    {
                                        $("#programmeID2").html(html);
                                        console.log(dataString);
                                    }
                                });

                            });

                        });
                    </script>
                    <h4><span class="text-danger" id="titleheader">
                List of Students in <?php /*echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID); */ ?> in <?php /*echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); */ ?>
                            <?php /*echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); */ ?>
                </span></h4>
                    <hr>
                    <form name="register" id="register" method="post" action="action_transfer_student.php">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="middleName">Admission Year</label>
                                <select name="admissionYearID" class="form-control" required>
                                    <?php
                                    /*                                    $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                    if(!empty($adYear)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($adYear as $year){ $count++;
                                            $academic_year2=$year['academicYear'];
                                            $academic_year_id2=$year['academicYearID'];
                                            */ ?>
                                            <option value="<?php /*echo $academic_year_id2;*/ ?>"><?php /*echo $academic_year2;*/ ?></option>
                                        <?php /*}
                                    }
                                    */ ?>
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="Physical Address">Trade Level</label>
                                <select name="programmeLevelID" id="programmeLevelID2"  class="form-control" required>
                                    <option value="">Select Here</option>
                                    <?php
                                    /*                                    $level = $db->getRows('programme_level',array('order_by'=>'programmeLevelCode ASC'));
                                    if(!empty($level)){

                                        $count = 0; foreach($level as $lvl){ $count++;
                                            $programmeLevelID=$lvl['programmeLevelID'];
                                            $programmeLevel=$lvl['programmeLevel'];
                                            */ ?>
                                            <option value="<?php /*echo $programmeLevelID;*/ ?>"><?php /*echo $programmeLevel;*/ ?></option>
                                        <?php /*}}*/ ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="Physical Address">Trade Name</label>
                                <select name="programmeID" id="programmeID2"  class="form-control" required>
                                    <option value="">Select Here</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <table  id="example" class="display nowrap">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Full Name</th>
                                <th>Reg.Number</th>
                                <th>Center Name</th>
                                <th>Student Status</th>
                                <th>Hosteller</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*                            $count = 0;
                            foreach ($student as $st) {
                                $count++;
                                $regNumber=$st['registrationNumber'];
                                $centerID=$st['centerID'];
                                $student_list = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                                foreach($student_list as $lst) {
                                    $fname = $lst['firstName'];
                                    $mname = $lst['middleName'];
                                    $lname = $lst['lastName'];
                                    $sponsor=$lst['sponsor'];
                                    $name = "$fname $mname $lname";

                                    */ ?>
                                    <tr>
                                        <td width="10"><?php /*echo $count; */ ?></td>

                                        <td width="10"><input type='checkbox' class='checkbox_class' name='regNumber[]' value='<?php /*echo $regNumber;*/ ?>'></td>
                                        <input type="text" hidden name="centerID[]" value="<?php /*echo $centerID;*/ ?>">
                                        <td width="70"><?php /*echo $name */ ?></td>
                                        <td width="50"><?php /*echo $st['registrationNumber']; */ ?></td>
                                        <td width="40"><?php /*echo $db->getData('center_registration','centerName','centerRegistrationID',$centerID);*/ ?></td>
                                        <td width="20"><?php /*echo $db->getData("status", "statusValue", "statusID", $lst['statusID']); */ ?></td>
                                        <td width="40">
                                            <select name="hosteller[]" class="form-control">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php
                                    /*                                }
                            }
                            */ ?>

                            <?php
                            /*                            } else {
                                */ ?>
                                <h4><span class="text-danger">No Student(s) found......</span></h4>
                                <?php
                                /*                            }
                            */ ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <input type="hidden" name="number_applicants" value="<?php /*echo $count;*/ ?>">
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="submit" name="doAdmit" value="Transfer Student" class="btn btn-success form-control">
                            </div>
                        </div>
                        <?php
                        /*                        }
                        */ ?>
                </div>
            </div>
-->
        </div>

    </div>
</div>