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
        <h1>Summary Report</h1>
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

                    <div class="col-lg-3">
                        <label for="FirstName">Academic Year</label>
                        <select name="academicYearID" id="academicYearID" class="form-control" required>
                            <?php
                            // $academic_year = $db->getRows('academic_year', array('where' => array('status' => 1), 'order_by' => 'academicYear ASC'));

                            $academic_year = $db->getRows('academic_year', array( 'order_by' => 'academicYear ASC'));
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
            if (isset($_POST['doFind']) == "View Records") {
                $programmeID = $_POST["programmeID"];
                $levelID = $_POST["programmeLevelID"];
                $academicYearID = $_POST["academicYearID"];
                $centerID = $_POST["centerID"];

                $student = $db->getStudentTermList($centerID, $academicYearID, $levelID, $programmeID);

                if (!empty($student)) {
            ?>
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">Summary Report 
                               
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">

                            <!--End -->
                            <table id="example11" class="table table-hover table-bordered" cellspacing="0" width="100%" rules="groups">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Reg.Number</th>
                                        <th>Exam Number</th>
                                        <?php
                                        $course = $db->getCourseCredit($levelID, $programmeID);
                                        foreach ($course as $cs) {
                                            echo "<th>" . $cs['courseCode'] . "</th>";
                                        }
                                        ?>
                                        <th>CSAVG</th>
                                        <th>GSAVG</th>
                                        <th>Remarks</th>
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
                                            $examNumber = $db->getExamNumber($regNumber, $academicYearID);
                                            echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td><td>$examNumber</td>";

                                            $course = $db->getCourseCredit($levelID, $programmeID);
                                            $tunits = 0;
                                            $tpoints = 0;
                                            $countpass = 0;
                                            $countsupp = 0;
                                            $gstotal = 0;
                                            $cstotal = 0;
                                            $countgs = 0;
                                            $countcs = 0;
                                            foreach ($course as $cs) {
                                                $courseID = $cs['courseID'];
                                                $courseCategoryID = $cs['courseCategoryID'];
                                                $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 1));
                                                $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 2));
                                                $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));
                                                $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 5));



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
                                                if($suppScore>0)
                                                {
                                                    $finalm=$suppScore;
                                                    $totalMarks=$suppScore;
                                                }
                                                else 
                                                {
                                                    $finalm = ($finalScore / 100) * 50;
                                                    $totalMarks = $term1m + $term2m + $finalm;
                                                }

                                                

                                                if ($courseCategoryID == 1) {
                                                    $cstotal += $totalMarks;
                                                    $countcs++;
                                                } else {
                                                    $gstotal += $totalMarks;
                                                    $countgs++;
                                                }

                                                echo "<td>".round($totalMarks)."</td>";
                                            }
                                            $gsaverage = round(($gstotal / $countgs));
                                            $csaverage = round(($cstotal / $countcs));

                                            if ($csaverage >= 40)
                                                $gparemarks = "Pass";
                                            else
                                                $gparemarks = "Supp";

                                            echo "<td>$csaverage</td><td>$gsaverage</td><td>$gparemarks</td></tr>";
                                    ?>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>

                            <!--reports-->
                            <div class="row">
                                <div class="col-lg-3">
                                    <a href='print_term_report_grade.php?action=getPDF&pid=<?php echo $programmeID; ?>&lid=<?php echo $levelID; ?>&aid=<?php echo $academicYearID; ?>&cid=<?php echo $centerID; ?>' target='_blank'> <button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                                            <i class="fa fa-download"></i>Print Grade Report
                                        </button></a>
                                </div>
                                <div class="col-lg-3">
                                    <!-- <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Report in PDF</button> -->
                                    <a href='print_term_report_pdf.php?action=getPDF&pid=<?php echo $programmeID; ?>&lid=<?php echo $levelID; ?>&aid=<?php echo $academicYearID; ?>&cid=<?php echo $centerID; ?>' target='_blank'> <button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                                            <i class="fa fa-download"></i>Print PDF Report
                                        </button></a>
                                </div>
                                <!-- <div class="col-lg-3">
                                    <a href='print_semester_report_extended_xls.php?action=getExcel&pid=<?php echo $programmeID; ?>&styear=<?php echo $studyYear; ?>&sid=<?php echo $semesterID; ?>&bid=<?php echo $batchID; ?>' target='_blank'> <button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                                            <i class="fa fa-download"></i>Print Excel(Extended) Report
                                        </button></a>
                                </div>
                                <div class="col-lg-3">
                                    <a href='print_semester_report_nacte_format_xls.php?action=getExcel&pid=<?php echo $programmeID; ?>&styear=<?php echo $studyYear; ?>&sid=<?php echo $semesterID; ?>&bid=<?php echo $batchID; ?>' target='_blank'> <button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                                            <i class="fa fa-download"></i>Print NACTE Format
                                        </button></a>
                                </div> -->
                            </div>
                            <!--end of reports-->

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