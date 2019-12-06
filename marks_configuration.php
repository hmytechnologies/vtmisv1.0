<div class="container">
    <?php $db=new DBHelper();
    ?>
    <?php
    $courseID=$db->decrypt($_REQUEST['id']);
    $semesterSettingID=$db->decrypt($_REQUEST['sid']);
    $batchID=$db->decrypt($_REQUEST['bid']);
    $instructorID=$db->decrypt($_REQUEST['instID']);

    $today=date("Y-m-d");
    $sm=$db->readSemesterSetting($today);
    foreach ($sm as $s) {
        $semisterID=$s['semesterID'];
        $academicYearID=$s['academicYearID'];
        $semesterName=$s['semesterName'];
        $currentSemesterSettingID=$s['semesterSettingID'];
    }

    ?>
    <?php
    $courseprogramme = $db->getSemesterBatchCourse($semesterSettingID, $courseID,$batchID,$instructorID);
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
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Course Type</th>
                            <th>Credits</th>
                            <th>Hours</th>
                            <th>No.of Students</th>
                            <th>Batch</th>
                            <!--<th>Course Marks</th>
                            <th>Pass Mark</th>-->
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
                                <td><?php echo $courseName; ?></td>
                                <td><?php echo $courseCode; ?></td>
                                <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                                <td><?php echo $units; ?></td>
                                <td><?php echo $nhours; ?></td>
                                <td><?php echo $studentNumber; ?></td>
                                <td><?php echo $db->getData("batch", "batchName", "batchID", $batchID); ?></td>
                                <!--<td><?php /*echo $db->getData("course_grade", "courseGrade", "courseGradeID", $std['courseGradeID']); */?></td>
                                <td><?php /*echo $db->getData("pass_mark", "passMark", "passMarkID", $std['passMarkID']); */?></td>-->
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
        $markConfiguration = $db->getRows("assessment_configuration",array('where'=>array('semesterSettingID'=>$semesterSettingID,'courseID'=>$courseID,'batchID'=>$batchID,'instructorID'=>$instructorID),'order by dueDate DESC'));
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
                     <th>ANo.</th>
                     <th>Assessment Type</th>
                     <th>Que.Upload</th>
                     <th>Ans.Upload</th>
                     <th>Due Date</th>
                     <th>Max Marks</th>
                     <th>Weigh.Marks</th>
                     <!--<th>Add</th>-->
                     <th>Edit</th>
                     <th>Drop</th>
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
                         <td><?php if($std['questionUpload']==0) echo "No"; else echo "Yes"; ?></td>
                         <td><?php if($std['answerUpload']==0) echo "No"; else echo "Yes";?></td>
                         <td><?php echo date('d-m-Y',strtotime($std['dueDate']));?></td>
                         <td><?php echo $std['maxMark'];?></td>
                         <td><?php echo $std['weightedMark'];?></td>
                         <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_assessment<?php echo $std['assessmentConfigurationID'];?>" style="margin-right: 5px;"><i class="fa fa-pencil"></i>
                         </td>
                         <td>
                             <?php
                             if(!empty($std['marksUploaded']))
                                 echo "No";
                             else {
                                 ?>
                                 <a href="action_marks_configuration.php?action_type=drop&id=<?php echo $db->encrypt($courseID);?>&sid=<?php echo $db->encrypt($semesterSettingID);?>&bid=<?php echo $db->encrypt($batchID);?>&instID=<?php echo $db->encrypt($instructorID);?>&assID=<?php echo $db->encrypt($std['assessmentConfigurationID']); ?>">
                                     <button type="button" class="btn btn-primary" style="margin-right: 5px;"><i
                                                 class="fa fa-trash"></i></button>
                                 </a>
                                 <?php
                             }
 ?>
                         </td>

                     </tr>



                                          <div id="edit_assessment<?php echo $std['assessmentConfigurationID'];?>" class="modal fade" role="dialog">
                         <div class="modal-dialog">

                             <!-- Modal content-->
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal">&times;</button>

                                     <h4 class="modal-title">Edit Assessment of <?php echo $db->getData("assessment_type","assessmentType","assessmentTypeID",$std['assessmentTypeID']); ?></h4>
                                 </div>


                                 <form name="register" id="register" method="post" action="action_marks_configuration.php">
                                     <div class="modal-body">
                                         <script type="text/javascript">
                                             $(document).ready(function () {
                                                 $("#due_date<?php echo $count;?>").datepicker({
                                                     dateFormat:"yy-mm-dd",
                                                     changeMonth:true,
                                                     changeYear:true,
                                                 });
                                             });
                                         </script>
                                                 <div class="form-group">
                                                     <label for="email">Assessment Type</label>
                                                     <select name="assTypeID" id="asseTypeID" class="form-control" required>
                                                         <option value="<?php echo $std['assessmentTypeID']; ?>"><?php echo $db->getData("assessment_type","assessmentType","assessmentTypeID",$std['assessmentTypeID']); ?></option>
                                                         <?php
                                                         if ($courseTypeID == 2)
                                                             $examCategoryID = 2;
                                                         else
                                                             $examCategoryID = 1;
                                                         $assType = $db->getRows("assessment_type", array('where' => array('examCategoryID' => $examCategoryID, 'status' => 1)));
                                                         if (!empty($assType)) {
                                                             foreach ($assType as $at) {
                                                                 ?>
                                                                 <option value="<?php echo $at['assessmentTypeID']; ?>"><?php echo $at['assessmentType']; ?></option>
                                                                 <?php
                                                             }
                                                         } ?>
                                                     </select>
                                             </div>
                                         <div class="row">
                                             <div class="col-lg-6">
                                                 <div class="form-group">
                                                     <label for="email">Question Upload</label>
                                                     <select name="qUpload" id="qqUpload" class="form-control" required>
                                                         <?php
                                                         if($std['questionUpload']==1) {
                                                             ?>
                                                             <option value="1" selected>Yes</option>
                                                             <option value="0">No</option>
                                                             <?php
                                                            }
                                                         else
                                                         {
                                                             ?>
                                                             <option value="1">Yes</option>
                                                             <option value="0" selected>No</option>
                                                             <?php
                                                         }
                                                            ?>

                                                     </select>
                                                 </div></div>
                                             <div class="col-lg-6">
                                                 <div class="form-group">
                                                     <label for="email">Answer Upload</label>
                                                     <select name="aUpload" id="aqUpload" class="form-control" required>
                                                          <?php
                                                         if($std['answerUpload']==1) {
                                                             ?>
                                                             <option value="1" selected>Yes</option>
                                                             <option value="0">No</option>
                                                             <?php
                                                         }
                                                         else
                                                         {
                                                            ?>
                                                             <option value="1">Yes</option>
                                                             <option value="0" selected>No</option>
                                                            <?php
                                                         }
                                                         ?>
                                                     </select>
                                                 </div>
                                             </div></div>


                                                <div class="row">
                                                    <div class="col-lg-4">
                                                 <div class="form-group">
                                                     <label for="email">Max.Marks</label>
                                                     <input type="text" name="mMark" value="<?php echo $std['maxMark'];?>" class="form-control" required>
                                                 </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                 <div class="form-group">
                                                     <label for="email">Weighted Marks</label>
                                                     <input type="text" name="wMark" value="<?php echo $std['weightedMark'];?>" class="form-control" required>
                                                 </div></div>

                                                    <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label for="email">Due Date</label>
                                                        <input type="text" name="dueDate" value="<?php echo $std['dueDate'];?>" class="form-control" id="due_date<?php echo $count;?>">
                                                    </div></div>


                                                </div>

                                         </div>
                                     <div class="modal-footer">
                                         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                         <input type="hidden" name="action_type" value="edit"/>
                                         <input type="hidden" name="assessmentConfigurationID" value="<?php echo $std['assessmentConfigurationID'];?>">
                                         <input type="hidden" name="batchID" value="<?php echo $batchID; ?>">
                                         <input type="hidden" name="semesterSettingID" value="<?php echo $semesterSettingID; ?>">
                                         <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
                                         <input type="hidden" name="instructorID" value="<?php echo $instructorID; ?>">
                                         <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
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
                     <!--<th colspan="3">
                         <button type="button" class="btn btn-primary" style="margin-right: 5px;">
                             <i class="fa fa-download"></i> Generate Report
                     </th>-->

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
        if($totalMarks==100) {
        }else
        {
            ?>
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
        if($currentSemesterSettingID==$semesterSettingID) {
        ?>
            <form name="" method="post" action="action_marks_configuration.php">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="email">Assessment Type</label>
                            <select name="assTypeID" id="asseTypeID" class="form-control" required>
                                <?php
                                echo "<option value=''>Please Select Here</option>";
                                if ($courseTypeID == 2)
                                    $examCategoryID = 2;
                                else
                                    $examCategoryID = 1;
                                $assType = $db->getRows("assessment_type", array('where' => array('examCategoryID' => $examCategoryID, 'status' => 1)));
                                if (!empty($assType)) {
                                    foreach ($assType as $at) {
                                        ?>
                                        <option value="<?php echo $at['assessmentTypeID']; ?>"><?php echo $at['assessmentType']; ?></option>
                                        <?php
                                    }
                                } ?>
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
                        <input type="hidden" name="batchID" value="<?php echo $batchID; ?>">
                        <input type="hidden" name="semesterSettingID" value="<?php echo $semesterSettingID; ?>">
                        <input type="hidden" name="courseID" value="<?php echo $courseID; ?>">
                        <input type="hidden" name="instructorID" value="<?php echo $instructorID; ?>">
                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control"/>
                    </div>
                    <div class="col-lg-3">
                        <input type="reset" value="Cancel" class="btn btn-primary form-control"/>
                    </div>
                </div>
            </form>
            <?php
        }
            ?>
            <?php
        }
        ?>

    <br><br>
    <div class="row">
        <div class="col-lg-3">
            <?php
            if ($_SESSION['role_session'] == 3) {
                ?>
                <a href="index3.php?sp=ass_conf" class="btn btn-success form-control">Go Back</a>
                <?php
            } else {
                ?>
                <a href="index3.php?sp=ass_conf" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            ?>
        </div>
    </div>

</div>
<?php
?>