<script type="text/javascript">
    $(document).ready(function() {
        $('#view_score').dataTable({
            paging: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excel',
                    footer: false,
                    /*exportOptions:{
                        columns:[0,1,2,3]
                    }*/
                }, ,
                {
                    extend: 'print',
                    title: 'List of Records',
                    footer: false,
                    /* exportOptions: {
                         columns: [0, 1, 2, 3]
                     }*/
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of Records',
                    footer: true,
                    /* exportOptions: {
                         columns: [0, 1, 2, 3,5,6]
                     }*/
                    //orientation: 'portrait',
                }

            ]
        });
    });
</script>
<?php
$db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h4>View Result for <span class="text-danger">
                <?php

                $courseID = $db->decrypt($_REQUEST['cid']);
                $academicYearID = $db->decrypt($_REQUEST['acadID']);
                $programmeLevelID = $db->decrypt($_REQUEST['lvlID']);
                $programmeID=$db->decrypt($_REQUEST['pid']);

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
                -<?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID); ?></span></h4>
        <hr>
        <div class="row">
            <?php
            $student = $db->getStudentExamList($courseID, $academicYearID, $programmeLevelID,$programmeID);
            if (!empty($student)) {
            ?>
                <table id="view_score" class="display nowrap">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Exam Number</th>
                            <th>SFE</th>
                            <th>GRD</th>
                            <th>RMK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($student as $st) {
                            $count++;
                            $regNumber = $st['regNumber'];
                            $examNumber = $st['examNumber'];
                            $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                            foreach ($studentDetails as $std) {
                                # code...
                                $fname = $std['firstName'];
                                $mname = $std['middleName'];
                                $lname = $std['lastName'];
                                $name = "$fname $mname $lname";
                                $gender = $std['gender'];
                                echo "<tr><td>$count</td>";
                                echo "<td>$examNumber</td>";

                                $final_result = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));


                                /* $exam_category_marks = $db->getTermCategorySetting();
                        if (!empty($exam_category_marks)) {
                            foreach ($exam_category_marks as $gd) {
                                $mMark = $gd['mMark'];
                                $pMark = $gd['passMark'];
                                $wMark = $gd['wMark'];
                            }
                        }

                        $term1m = ($term1 / $mMark) * $wMark;
                        $term2m = ($term2 / $mMark) * $wMark; */


                                echo "<td>" . $final_result . "</td>";
                                echo "<td>" . $db->calculateTermGrade($final_result) . "</td>";
                                echo "<td>" . $db->courseTermRemarks($final_result) . "</td>";

                              /*   $editButton = '
                                <div class="btn-group">
                                        <a href="index3.php?sp=edit_score&cid=' . $db->my_simple_crypt($courseID, 'e') . '&sid=' . $db->my_simple_crypt($semesterSettingID, 'e') . '&regno=' . $db->my_simple_crypt($regNumber, 'e') . '&bid=' . $db->my_simple_crypt($batchID, 'e') . '" class="glyphicon glyphicon-edit"></a>
                                </div>'; */
                        ?>
                                <!--<td>
                                        <?php
                                        /*                                        echo $editButton;
                                        */ ?>
                                    </td>-->
                                </tr>
                                <!--<div id="#count<?php /*echo $count;*/ ?>&regNumber=<?php /*echo $regNumber;*/ ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Course Work For <?php /*echo $regNumber;*/ ?></h4>
                                                </div>
                                                <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="email">Score Marks</label>
                                                            <input type="text" id="marks" name="score_marks" value="<?php /*echo $cwk;*/ ?>" maxlength="2" class="form-control" required />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <input type="hidden" name="gradeID" value="<?php /*echo $gd['gradeID'];*/ ?>">
                                                        <input type="hidden" name="action_type" value="edit_grade"/>
                                                        <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>-->

                                <!--edit final exam-->
                                <!--<div id="message2" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Grade System</h4>
                                                </div>
                                                <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="email">Grade Code</label>
                                                            <input type="text" id="code" name="gradeCode" value="<?php /*echo $gd['gradeCode'];*/ ?>" maxlength="2" class="form-control" required />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <input type="hidden" name="gradeID" value="<?php /*echo $gd['gradeID'];*/ ?>">
                                                        <input type="hidden" name="action_type" value="edit_grade"/>
                                                        <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>-->
                                <!--end edit of final exam-->

                                <?php
                                ?>

                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
            ?>
                <h4 class="text-danger">No Result(s) found......</h4>
            <?php
            }
            ?>

        </div>
    </div>
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
        <div class="col-lg-3">
            <a href='print_exam_score.php?action=getPDF&cid=<?php echo $db->encrypt($courseID);?>&proid=<?php echo $db->encrypt($programmeID); ?>&aid=<?php echo $db->encrypt($academicYearID); ?>&lid=<?php echo $db->encrypt($programmeLevelID) ;?>' target='_blank'> <button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                    <i class="fa fa-download"></i>Print Report 
                </button></a>
        </div>
    </div>

</div>

<!-- <div id="add_new_atype_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview Course Result</h4>
            </div>
            <div class="modal-body">
                <embed src="print_score_report.php?action=getPDF&cid=<?php //echo $courseID; ?>&bid=<?php //echo $batchID; ?>&sid=<?php //echo $semesterSettingID; ?>"
                       frameborder="0" width="100%" height="600px">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div> -->