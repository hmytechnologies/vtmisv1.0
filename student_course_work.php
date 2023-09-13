<?php
/**
 * Created by PhpStorm.
 * User: massoudhamad
 * Date: 11/3/18
 * Time: 7:23 PM
 */
?>
<h1>Course Work View</h1>
<hr>
<?php
$db = new DBHelper();
?>
<div class="row">
    <form name="" method="post" action="">
        <div class="col-lg-12">
            <div class="row">

                <div class="col-lg-3">
                    <label for="FirstName">Semester Name</label>
                    <select name="semesterID" id="semesterID" class="form-control">
                        <?php
                        $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                        if(!empty($semister)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($semister as $sm){ $count++;
                                $semister_name=$sm['semesterName'];
                                $semister_id=$sm['semesterSettingID'];
                                ?>
                                <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </div>



            </div>
            <div class="row">

                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                <div class="col-lg-6"></div>

            </div>
        </div>
    </form>
</div>
<br><br>
 <div class="row">
        <?php
if(isset($_POST['doFind'])=="View Records") {
    $semesterSettingID = $_POST['semesterID'];
    $userID = $_SESSION['user_session'];
    $studentID = $db->getRows('student', array('where' => array('userID' => $userID), ' order_by' => ' studentID ASC'));
    if (!empty($studentID)) {
        foreach ($studentID as $std) {
            $regNumber = $std['registrationNumber'];
            // $batchID=$std['batchID'];
            $course = $db->getStudentCourse($regNumber,$semesterSettingID);
            if (!empty($course)) {
                ?>

                <div class="box box-solid box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Course Work Results for <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="" class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Course Type</th>
                                <th>Course Status</th>
                                <th>Units</th>
                                <th>Max Marks</th>
                                <th>Scored</th>
                                <th>Weighted</th>
                                <th>Class Average</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            $i = 1;
                            foreach ($course as $st)
                            {
                            $count++;
                            $courseID = $st['courseID'];
                            $courseStatus = $st['courseStatus'];
                            $course = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
                            if (!empty($course)) {
                                $i = 1;
                                foreach ($course as $c) {
                                }
                            }
                            $courseCode = $c['courseCode'];
                            $courseName = $c['courseName'];
                            $units = $c['units'];
                            $courseTypeID = $c['courseTypeID'];



                            ?>

                            <tr>
                                <?php
                                echo "<td>$count</td><td>$courseCode</td><td>$courseName</td><td>".$db->getData("course_type","courseTypeCode","courseTypeID",$courseTypeID)."</td>
                                <td>".$db->getData("coursestatus","courseStatusCode","courseStatusID",$courseStatus)."</td><td>$units</td><td>40</td>";

                                $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                                $published = $db->checkStatus($courseID, $semesterSettingID,'checked');
                                //if($published==1)
                                //{
                                    $scored=$cwk;
                                    $weighted=round(($scored/40)*40,2);
                                    $totalMarks=$db->getTotalMarks($courseID, $semesterSettingID);
                                    $numberStudent=$db->getStudentCourseSum($courseID, $semesterSettingID);
                                    $claverage=round($totalMarks/$numberStudent,2);
                                /*}
                                else
                                {
                                    $scored="No";
                                    $weighted="No";
                                    $claverage="No";
                                }*/

                                echo "<td>" . $scored . "</td>
                                <td>" . $weighted . "</td>
                                <td>" . $claverage . "</td>";
                                if($db->courseWorkRemarks($scored)=="Pass")
                                    $remarks="Pass";
                                else
                                    $remarks="<span class='text-danger'>Fail</span>";
                                echo "<td>".$remarks."</td>"
                                ?>

                            </tr>
                                    <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <h4 class="text-danger">No Result(s) found......</h4>
                <?php
            }
        }
    }
}?>

</div>
