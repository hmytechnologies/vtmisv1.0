<?php $db=new DBHelper();
$regNumber=$db->my_simple_crypt($_REQUEST['regno'],'d');
$courseID=$db->my_simple_crypt($_REQUEST['cid'],'d');
$semesterSettingID=$db->my_simple_crypt($_REQUEST['sid'],'d');
$batchID=$db->my_simple_crypt($_REQUEST['bid'],'d');
?>
<div class="container">
    <h4>Edit Result of <span class="text-danger">
<?php
$course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
if(!empty($course))
{
    foreach($course as $c)
    {
        $courseCode=$c['courseCode'];
        $courseName=$c['courseName'];
        $courseTypeID=$c['courseTypeID'];
    }
}
echo $courseCode."-".$courseName."-".$db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);
?>
-<?php echo $db->getData("batch","batchName","batchID",$batchID);?></span></h4>
    <hr>
    <div class="row">
        <div class="row">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Payment data has been uploaded successfully</strong>.
                            </div>";
                }

                else if($_REQUEST['msg']=="error") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                }
                else if($_REQUEST['msg']=="unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                }
            }
            ?>
        </div>
        <?php



        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>' studentID ASC'));
        if(!empty($studentID))
        {
            $count = 0;
            foreach($studentID as $std)
            {
                $count++;
                $studentID=$std['studentID'];
                $fname=$std['firstName'];
                $mname=$std['middleName'];
                $lname=$std['lastName'];
                $gender=$std['gender'];
                $regNumber=$std['registrationNumber'];
                $programmeID=$std['programmeID'];
                $statusID=$std['statusID'];
                $batchID=$std['batchID'];
                $name="$fname $mname $lname";


                $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
                if(!empty($programme))
                {
                    foreach ($programme as $pro) {
                        $programmeName=$pro['programmeName'];
                        $programmeDuration=$pro['programmeDuration'];
                    }
                }



                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'studyYearStatus'=>1),' order_by'=>'regNumber ASC'));
                if(!empty($study_year))
                {
                    foreach ($study_year as $sy)
                    {
                        $studyYear=$sy['studyYear'];
                    }
                }


            }

            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#start_date").datepicker({
                        dateFormat:"yy-mm-dd",
                        changeMonth:true,
                        changeYear:true,

                        onSelect:function(dateText){
                            $("#end_date").datepicker('option','minDate',dateText);
                        }
                    });
                    $("#end_date").datepicker({
                        dateFormat:"yy-mm-dd",
                        changeMonth:true,
                        changeYear:true,
                        autoclose: true,

                        onSelect:function(dateText){
                            $("#examStart_Date").datepicker('option','minDate',dateText);
                        }
                    });
                });
            </script>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header with-border text-center">
                                <h3 class="box-title">Reg.Number: <?php echo $regNumber;?> Name: <?php echo $name;?></h3>
                            </div>
                            <form class="form-horizontal" method="post" action="action_exam_score.php">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Course Work</label>

                                        <div class="col-sm-9">
                                            <?php
                                            $score=$db->getRows('exam_result',array('where'=>array('examCategoryID'=>1,'regNumber'=>$regNumber,'courseID'=>$courseID,'semesterSettingID'=>$semesterSettingID),' order_by'=>'regNumber ASC'));
                                            if(!empty($score))
                                            {
                                                foreach ($score as $sc)
                                                {
                                                    ?>
                                                    <td><input type="text" name="score" value="<?php echo $db->decrypt($sc['examScore']);?>" class='form-control' required></td>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <td><input type='text' name="score" class='form-control' required></td>
                                            <?php }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Final Exam</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $score = $db->getRows('final_result', array('where' => array('examCategoryID' => 2, 'examNumber' => $regNumber, 'courseID' => $courseID, 'semesterSettingID' => $semesterSettingID), ' order_by' => 'examNumber ASC'));
                                            if (!empty($score)) {
                                                foreach ($score as $sc) {
                                                    ?>
                                                    <td><input type="text" name="examscore" value="<?php echo $db->decrypt($sc['examScore']); ?>" class='form-control' required>
                                                    </td>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <td><input type='text' name="examscore" class='form-control' required></td>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Remarks</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="comments" name="comments" placeholder="Why you make this editing?" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Action Date</label>

                                        <div class="col-sm-9">
                                            <input type="text" name="examDate" class="form-control" id="start_date" required>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <input type="hidden" name="action_type" value="edit_student_score"/>
                                    <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                                    <input type="hidden" name="semesterID" value="<?php echo $semesterSettingID;?>">
                                    <input type="hidden" name="courseID" value="<?php echo $courseID;?>">
                                    <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
                                    <input type="submit" name="doSubmit" value="Update Records" class="btn btn-primary pull-right">
                                </div>
                                <!-- /.box-footer -->
                            </form>

                        </div></div>
                </div></div>

            <?php
        }
        ?>
    </div>


    <div class="row">
        <div class="col-lg-3">
            <a href="index3.php?sp=view_score&cid=<?php echo $db->encrypt($courseID);?>&sid=<?php echo $db->encrypt($semesterSettingID);?>&bid=<?php echo $db->encrypt($batchID);?>" class="btn btn-success form-control">Go Back</a>
        </div>
    </div>
</div>