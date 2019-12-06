<?php
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
$db=new DBHelper();
$courseID=$db->decrypt($_REQUEST['cid']);
$semesterID=$db->decrypt($_REQUEST['sid']);
$batchID=$db->decrypt($_REQUEST['bid']);
?>
<div class="container">
    <h4>Add Supplementary Result for <span class="text-danger">
<?php
$courseID=$db->decrypt($_REQUEST['cid']);
$semesterSettingID=$db->decrypt($_REQUEST['sid']);
$batchID=$db->decrypt($_REQUEST['bid']);

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
echo $courseCode."-".$courseName."-".$db->getData("semester_setting","semesterName","semesterSettingID",$db->decrypt($_REQUEST['sid']));
?>
            -<?php echo $db->getData("batch","batchName","batchID",$batchID);?></span></h4>
    <hr>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if(!empty($_SESSION['statusMsg'])){
                echo "<div class='alert alert-success fade in'>
              <a href='#' class='close' data-dismiss='alert'>&times;</a>
              <strong>".$_SESSION['statusMsg']."</strong>.
          </div>";
                unset($_SESSION['statusMsg']);
            }?>
        </div>
    </div>

    <input type="hidden" id="courseID" value="<?php echo $courseID;?>">
    <input type="hidden" id="semesterID" value="<?php echo $semesterSettingID;?>">
    <input type="hidden" id="batchID" value="<?php echo $batchID;?>">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#exam_date").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
            });
        });
    </script>

    <div class="row">
        <div class="col-lg-6">
            <?php
            $examCategoryID=3;
            $passmark=$db->encrypt($db->getPassMark($courseID,$semesterID,$batchID));
            $student= $db->getStudentSuppList($courseID,$semesterID,$batchID,$passmark);
            ?>

            <?php
            if(!empty($student))
            {
            ?>
            <h4>Add<span class="text-danger">
                    <?php echo $db->getData("exam_category","examCategory","examCategoryID",$examCategoryID);?></span> Result for
                <?php echo $db->getData("course","courseCode","courseID",$courseID);?>
                -
                <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterID);?>
            </h4>

            <form name="" method="post" action="action_exam_score.php">

                <div class="row">
                    <div class="col-lg-6">
                        <label for="MiddleName">Exam Date</label>
                        <input type="text" name="examDate" class="form-control" id="exam_date">

                        <!--<div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
                             data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" name="examDate" value="<?php /*echo date("Y-m-d"); */?>" id="pickyDate">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>-->
                    </div>
                </div>
                <table  id="onlydata" border=1 class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Registration Number</th>
                        <th>Score</th>
                        <th>Present</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach($student as $st)
                    {

                        $count++;
                        $regNumber=$st['regNumber'];
                        ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                var current = $('#select<?php echo $count;?>').val();
                                if (current == '0') {
                                    $('#select<?php echo $count;?>').css('color','red');
                                } else {
                                    $('#select<?php echo $count;?>').css('color','green');
                                }
                                $('#select<?php echo $count;?>').change(function() {
                                    var current = $('#select<?php echo $count;?>').val();
                                    if (current == '0') {
                                        $('#select<?php echo $count;?>').css('color','red');
                                    } else {
                                        $('#select<?php echo $count;?>').css('color','green');
                                    }
                                });
                            });
                        </script>
                    <?php
                    $studentDetails = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>' registrationNumber ASC'));
                    if(!empty($studentDetails))
                    {
                    foreach($studentDetails as $std)
                    {
                    ?>
                        <tr>

                        <td><?php echo $count;?></td>
                        <input type='text' hidden name="regNumber<?php echo $count;?>" value="<?php echo $std['registrationNumber'];?>">
                        <td><?php echo $std['registrationNumber'];?></td>

                        <?php
                        $score=$db->getRows('exam_result',array('where'=>array('examCategoryID'=>$examCategoryID,'regNumber'=>$regNumber,'courseID'=>$courseID,'semesterSettingID'=>$semesterID),' order_by'=>'regNumber ASC'));
                        if(!empty($score))
                        {
                            foreach ($score as $sc)
                            {
                                $present=$sc['present'];
                                ?>
                                <td><input type="text" name="score<?php echo $count;?>" value="<?php echo $db->decrypt($sc['examScore']);?>" class='form-control'></td>
                                <td>
                                    <select name="status<?php echo $count;?>" class="form-control" id="select<?php echo $count;?>">
                                        <?php
                                        if($present==1) {
                                            ?>
                                            <option value="1" selected><span class="text-primary">Present</span>
                                            </option>
                                            <option value="0">Absent</option>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <option value="1"><span class="text-primary">Present</span>
                                            </option>
                                            <option value="0" selected><span style="color: red;">Absent</span></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <?php
                            }
                            ?>

                            <?php
                        }
                        else
                        {
                            ?>
                            <td><input type='text' name="score<?php echo $count;?>" class='form-control'></td>
                            <td>
                                <select name="status<?php echo $count;?>" class="form-control" id="select<?php echo $count;?>">
                                    <option value="1"><span class="text-primary">Present</span></option>
                                    <option value="0">Absent</option>
                                </select>
                            </td>
                        <?php }
                    }
                        ?>
                    </tr>
                        <?php
                    }
                    }
                    ?>

                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <br />
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="add_sup"/>
                        <input type="hidden" name="courseID" value="<?php echo $courseID;?>">
                        <input type="hidden" name="number_student" value="<?php echo $count;?>">
                        <input type="hidden" name="semesterID" value="<?php echo $semesterID;?>">
                        <input type="hidden" name="batchID" value="<?php echo $batchID;?>">

                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                    </div>
                    <div class="col-lg-3">
                        <input type="reset" value="Cancel" class="btn btn-danger form-control" />
                    </div>
                </div>
            </form>


            <div class="row">
                <div class="col-lg-3">
                    <a href="index3.php?sp=supp_special" class="btn btn-success form-control">Go Back</a>
                </div>
            </div>

        </div>
    </div>