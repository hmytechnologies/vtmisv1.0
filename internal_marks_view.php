<div class="container">
    <?php $db=new DBHelper();
    ?>
    <?php
    $courseID=$db->decrypt($_REQUEST['id']);
    $academicYearID=$db->decrypt($_REQUEST['acID']);
    $programmeLevelID=$db->decrypt($_REQUEST['levelID']);
    $programmeID=$db->decrypt($_REQUEST['pID']);
    $classNumber=$db->decrypt($_REQUEST['cnumber']);

    ?>
    <?php
    $courseprogramme = $db->getSemesterBatchCourse($courseID,$batchID,$instructorID);
    if (!empty($courseprogramme)) {
        ?>
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Marks Configuration</h3>
                </div>
                <div class="box-body">
                    <table id="" class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>Class Number</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Subject Type</th>
                            <th>Level</th>
                            <th>Trade Name</th>
                            <th>No.of Students</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalHours = 0;
                        $count = 0;
                        foreach ($courseprogramme as $std) {
                            $count++;
                            $courseID = $std['courseID'];
                            $batchID = $std['batchID'];

                            $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
                            if (!empty($course)) {
                                foreach ($course as $c) {
                                    $courseCode = $c['courseCode'];
                                    $courseName = $c['courseName'];
                                    $courseTypeID = $c['courseTypeID'];
                                    $units = $c['units'];
                                    $nhours = $c['numberOfHours'];
                                    $totalHours += $nhours;
                                }
                            }

                            $studentNumber = $db->getStudentCourseSum($courseID, $semesterSettingID, $batchID);
                            ?>

                            <tr>
                                <td></td>
                                <td><?php echo $courseName; ?></td>
                                <td><?php echo $courseCode; ?></td>
                                <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                                <td><?php echo $units; ?></td>
                                <td><?php echo $nhours; ?></td>
                                <td><?php echo $studentNumber; ?></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    ?>


    <br>
    <br>
    <?php
    $markConfiguration = $db->getRows("assessment_configuration",array('where'=>array('semesterSettingID'=>$semesterSettingID,'courseID'=>$courseID,'batchID'=>$batchID,'instructorID'=>$instructorID)));
    if (!empty($markConfiguration)) {
        ?>
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Configuration Setting</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="" class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Assessment Type</th>
                            <th>Max Marks</th>
                            <th>Weigh.Marks</th>
                            <th>Add Result</th>
                            <th>Viw Result</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalMarks = 0;
                        $count = 0;
                        foreach ($markConfiguration as $std) {
                            $count++;

                            $viewButton = '
<button type="button" class="btn btn-primary" style="margin-right: 5px;">
<a href="index3.php?sp=add_marks&id=' . $db->encrypt($courseID) . '&sid=' . $db->encrypt($semesterSettingID) . '&bid=' . $db->encrypt($batchID) . '"></a><i class="fa fa-plus"></i>
';
                            ?>

                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $db->getData("assessment_type","assessmentType","assessmentTypeID",$std['assessmentTypeID']);?></td>
                                <td><?php if($std['questionUpload']==0) echo "No"; else { ?>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $std['assessmentConfigurationID'];?>">
                                            <span class="fa fa-upload" aria-hidden="true" title="Upload Question">
                                    <?php };?></td>
                                <td><?php if($std['answerUpload']==0) echo "No"; else echo "Yes";?></td>
                                <td><?php echo date('d-m-Y',strtotime($std['dueDate']));?></td>
                                <td><?php echo $std['maxMark'];?></td>
                                <td><?php echo $std['weightedMark'];?></td>
                                <td><?php echo $viewButton; ?></td>
                            </tr>

                            <div id="message<?php echo $std['assessmentConfigurationID'];?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <h4 class="modal-title">Upload Question for <?php echo $db->getData("assessment_type","assessmentType","assessmentTypeID",$std['assessmentTypeID']); ?></h4>
                                        </div>
                                        <form name="register" id="register" method="post" enctype="multipart/form-data" action="action_upload_question.php">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="message-text" class="control-label">File Upload:</label>
                                                    <input type="file" name="user_image" accept="application/pdf">
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="hidden" name="assessmentConfigurationID" value="<?php echo $user['assessmentConfigurationID'];?>">
                                                <input type="hidden" name="action_type" value="add"/>
                                                <input type="submit" name="doUpdate" value="Save Records" class="btn btn-success">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>





                            <?php
                            $totalMarks+=$std['weightedMark'];
                        } ?>
                        </tbody>
                        <tr>
                            <th colspan="6" style="font-size:16px;">Total Marks
                            </th>
                            <th style="font-size:16px;"><?php echo $totalMarks; ?></th>
                            <th>
                                <button type="button" class="btn btn-primary" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> Generate Report
                            </th>

                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <h4 class="text-danger">No Marks Configuration</h4>
        <?php
    }
    /*if($totalMarks==100) {
    }else
    {
        */?><!--
        <script type="text/javascript">
            $(document).ready(function () {
                $("#exam_date").datepicker({
                    dateFormat:"yy-mm-dd",
                    changeMonth:true,
                    changeYear:true,
                });
            });
        </script>
    <?php
/*    if($currentSemesterSettingID==$semesterSettingID) {
    */?>
        <form name="" method="post" action="action_marks_configuration.php">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Assessment Type</label>
                        <select name="assTypeID" id="asseTypeID" class="form-control" required>
                            <?php
/*                            echo "<option value=''>Please Select Here</option>";
                            if ($courseTypeID == 2)
                                $examCategoryID = 2;
                            else
                                $examCategoryID = 1;
                            $assType = $db->getRows("assessment_type", array('where' => array('examCategoryID' => $examCategoryID, 'status' => 1)));
                            if (!empty($assType)) {
                                foreach ($assType as $at) {
                                    */?>
                                    <option value="<?php /*echo $at['assessmentTypeID']; */?>"><?php /*echo $at['assessmentType']; */?></option>
                                    <?php
/*                                }
                            } */?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Question Upload</label>
                        <select name="qUpload" id="qqUpload" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Answer Upload</label>
                        <select name="aUpload" id="aqUpload" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>

                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Due Date</label>
                        <input type="text" name="dueDate" class="form-control" id="exam_date">
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Max.Marks</label>
                        <input type="text" name="mMark" class="form-control" required>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email">Weighted Marks</label>
                        <input type="text" name="wMark" class="form-control" required>
                    </div>
                </div>

            </div>

            <br>
            <div class="row">
                <div class="col-lg-6"></div>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="hidden" name="batchID" value="<?php /*echo $batchID; */?>">
                    <input type="hidden" name="semesterSettingID" value="<?php /*echo $semesterSettingID; */?>">
                    <input type="hidden" name="courseID" value="<?php /*echo $courseID; */?>">
                    <input type="hidden" name="instructorID" value="<?php /*echo $instructorID; */?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control"/>
                </div>
                <div class="col-lg-3">
                    <input type="reset" value="Cancel" class="btn btn-primary form-control"/>
                </div>
            </div>
        </form>
        <?php
/*    }
        */?>
        --><?php
/*    }
    */?>

    <br><br>
    <div class="row">
        <div class="col-lg-3">
            <?php
            if ($_SESSION['role_session'] == 3) {
                ?>
                <a href="index3.php?sp=internal_marks" class="btn btn-success form-control">Go Back</a>
                <?php
            } else {
                ?>
                <a href="index3.php?sp=internal_marks" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            ?>
        </div>
    </div>

</div>
<?php
?>