<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<!-- <script src="js/script.js"></script> -->

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

<style type="text/css">
    .bs-example {
        margin: 10px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#nactereport').removeAttr('width').dataTable({
            scrollY: "100%",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            //dom: 'Blfrtip',
            columnDefs: [{
                width: "200px",
                targets: 1
            }],
            fixedColumns: {
                leftColumns: 2
            }
        });
    });
</script>

<?php $db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Term Report</h1>
        <hr>

        <div class="tab-content">
            <!-- Current Semester -->
            <?php $db = new DBHelper(); ?>
            <form name="" method="post" action="">
                <!--   <form name="" method="post" action="" onsubmit="return print_exam_result();"> -->
                <div class="row">
                    <div class="col-lg-3">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerID" class="form-control chosen-select" required="">
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


                    <div class="col-lg-2">
                        <label for="MiddleName">Trade Name</label>
                        <select name="programmeID" id="programmeID" class="form-control" required>
                            <?php
                            $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                            if (!empty($programmes)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($programmes as $prog) {
                                    $count++;
                                    $programme_name = $prog['programmeName'];
                                    $programmeID = $prog['programmeID'];
                            ?>
                                    <option value="<?php echo $programmeID; ?>"><?php echo $programme_name; ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-lg-2">
                        <label for="FirstName">Term Name</label>
                        <select name="examCategoryID" class="form-control" id="examCategoryID">
                            <?php
                            $term = $db->getRows("exam_category", array('order by examCategoryID ASC'));
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

                    <div class="col-lg-2">
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

        <!-- <div class="row">
            <div class="col-md-12">
                <div id="result">

                </div>
            </div>
        </div> -->

        <div class="row">
            <?php
        if(isset($_POST['doFind'])=="View Records")
        {

            //session_start();
            //include('DB.php');
            //$db = new DBHelper();

            $programmeID = $_POST["programmeID"];
            $levelID = $_POST["programmeLevelID"];
            $academicYearID = $_POST["academicYearID"];
            $centerID = $_POST["centerID"];
            $examCategoryID = $_POST["examCategoryID"];


            $student = $db->getStudentTermList($centerID, $academicYearID, $levelID, $programmeID);
            if (!empty($student)) {
            ?>
                <div class="box box-solid box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Term Report for
                            <?php echo $sYear;
                            echo " ";
                            echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?>
                            <?php echo $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID); ?>
                            <?php echo $db->getData("batch", "batchName", "batchID", $batchID); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">

                        <div class="row">
                            <div class="pull-right">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Report</button>
                                </div>
                            </div>
                        </div>
                        <!--End -->
                        <table id="" class="table table-hover table-bordered" cellspacing="0" width="100%" rules="groups">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Reg.Number</th>
                                    <?php
                                    $course = $db->getCourseCredit($levelID,$programmeID);
                                    foreach ($course as $cs) {
                                        echo "<th>".$cs['courseCode']."</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                foreach ($student as $st) {
                                    $count++;
                                    $regNumber = $st['regNumber'];
                                    $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                                    foreach ($studentDetails as $std) {
                                        # code...
                                        $fname = $std['firstName'];
                                        $mname = $std['middleName'];
                                        $lname = $std['lastName'];
                                        $name = "$fname $mname $lname";
                                        $gender = $std['gender'];
                                        $dob = $std['dateOfBirth'];
                                        $admissionYearID = $std['academicYearID'];
                                        echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td>";

                                        $course = $db->getCourseCredit($levelID,$programmeID);
                                        $tunits = 0;
                                        $tpoints = 0;
                                        $countpass = 0;
                                        $countsupp = 0;
                                        foreach ($course as $cs) {
                                            $courseID = $cs['courseID'];
                                            $termScore = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, $examCategoryID));


                                           /*  $passCourseMark = $db->getExamCategoryMark(1, $regNumber, $studyYear);
                                            $passFinalMark = $db->getExamCategoryMark(2, $regNumber, $studyYear);
                                            $tmarks = $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);
                                            if (!empty($sup)) {
                                                $passMark = $db->getExamCategoryMark(3, $regNumber, $studyYear);
                                                if ($tmarks >= $passMark)
                                                    $grade = "C";
                                                else
                                                    $grade = "D";
                                                $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                            } else if (!empty($pt)) {
                                                $passMark = $db->getExamCategoryMark(6, $regNumber, $studyYear);
                                                $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                                if ($tmarks >= $passMark)
                                                    $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                                                else
                                                    $grade = "D";
                                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                            } else if (!empty($prj)) {
                                                $passMark = $db->getExamCategoryMark(5, $regNumber, $studyYear);
                                                $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                                if ($tmarks >= $passMark)
                                                    $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                                                else
                                                    $grade = "D";
                                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                            } else if (empty($cwk) || empty($sfe)) {
                                                $grade = "I";
                                                $gradePoint = 0;
                                            } else if ($cwk < $passCourseMark) {
                                                $grade = "I";
                                                $gradePoint = 0;
                                            } else if ($sfe < $passFinalMark) {
                                                $grade = "E";
                                                $gradePoint = 0;
                                            } else {
                                                $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                                $grade = $db->calculateGrade($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                            }
                                            $points = $gradePoint * $units;
                                            $tpoints += $points;
                                            $tunits += $units;
                                            $gpa = $db->getGPA($tpoints, $tunits);


                                            if (($grade == "D") or ($grade == "F") or ($grade == "E") or ($grade == "I")) {
                                                $countsupp = $countsupp + 1;
                                            } else {
                                                $countpass = $countpass + 1;
                                            }

                                            if ($gpa < 2)
                                                $gparemarks = "Fail";
                                            else if ($countsupp > 0)
                                                $gparemarks = "Supp";
                                            else
                                                $gparemarks = "Pass"; */
                                            //}
                                            /*else
                            {
                                $cwk="-";
                                $sfe="-";
                                $totalMarks="-";
                                $grade="-";
                                $units="-";
                                $points="-";

                            }*/

                                            echo "<td>$termScore</td>";
                                        }


                                        echo "<td>$gpa</td><td>$gparemarks</td></tr>";
                                ?>

                                <?php
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                <?php
            } else {
                ?>
                    <h4 class="text-danger">No Result(s) found......</h4>
                <?php
            }
            }
                ?>
                </div>
                <div id="add_new_atype_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Preview Course Result</h4>
                            </div>
                            <div class="modal-body">

                                <embed src="print_semester_report_sumait_overall.php?action=getPDF&prgID=<?php echo $programmeID; ?>&bid=<?php echo $batchID; ?>&sid=<?php echo $semesterID; ?>&syear=<?php echo $studyYear; ?>" frameborder="0" width="100%" height="600px">


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!--end-->
        </div>
    </div>



    <!-- <div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
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
</div> -->


    <!--<div id="add_new_atype_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    Modal content-->
    <!-- <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview Course Result</h4>
            </div>
            <div class="modal-body">-->

    <?php
    /*                $programmeID=$_POST['programmeID'];
                $studyYear=$_POST['studyYear'];
                $batchID=$_POST['batchID'];
                $semesterID=$_POST['semesterID'];
                */ ?>

    <!-- <embed src="print_semester_report_sumait.php?action=getPDF&prgID=<?php /*echo $programmeID;*/ ?>&bid=<?php /*echo $batchID;*/ ?>&sid=<?php /*echo $semesterID;*/ ?>&syear=<?php /*echo $studyYear;*/ ?>" frameborder="0" width="100%" height="600px">
-->

    <!--<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>--->


    <!-- End -->