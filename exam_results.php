<h1>Exam Results</h1>
<hr>

<?php
$db = new DBHelper();
$userID = $_SESSION['user_session'];
$studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
if(!empty($studentID))
{
    foreach($studentID as $std)
    {
         $regNumber=$std['registrationNumber'];
?>
<div class="row"> 
<?php
$debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
if(!empty($debit))
{
    $totalFees=0;
    foreach($debit as $dbt)
    {
        $amount=$dbt['amount'];
        $totalFees+=$amount;
    }
}
else
{
    $totalFees=0;
}

//Payment
$paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
if(!empty($paymentList))
{
    $totalPayments=0;
    foreach($paymentList as $list)
    {
        $amount=$list['amount'];
        $totalPayments+=$amount;
    }
}
else
{
    $totalPayments=0;
}

$balance=$totalFees-$totalPayments;

/*if($balance>0)
{
    */?><!--
    <div class="col-lg-12">
    <h4 class="text-danger">Sorry, You cant view your result,please contact Account Office to clear your payments<br></h4>
    </div>
--><?php
/*    }
else {*/

    $semester = $db->getStudentSemester($regNumber);
    if (!empty($semester)) {
        ?>
        <div class="col-md-9">
            <?php
            $totalPoints = 0;
            $totalUnits = 0;
            foreach ($semester as $sm) {
                $semesterSettingID = $sm['semesterSettingID'];
                $semesterName = $sm['semesterName'];
                $course = $db->getStudentResult($regNumber, $semesterSettingID);
                if (!empty($course)) {
                    ?>

                    <div class="box box-solid box-primary">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">Exam Result Information for <?php echo $semesterName; ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="" class="table table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Status</th>
                                    <th>Units</th>
                                    <th>Total Marks</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 0;
                                $i = 1;
                                $tunits = 0;
                                $tpoints = 0;
                                foreach ($course as $st) {
                                    $count++;
                                    $courseID = $st['courseID'];
                                    $courseStatus = $st['courseStatus'];
                                    $course = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
                                    if (!empty($course)) {
                                        ?>
                                        <?php
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
                                        echo "<td>$count</td><td>$courseCode</td><td>$courseName</td><td>".$db->getData("coursestatus","courseStatusCode","courseStatusID",$courseStatus)."</td><td>$units</td>";
                                        $tunits += $units;


                                        $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                                        $sfe = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 2));
                                        $sup = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 3));
                                        $spc = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 4));
                                        $prj = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 5));
                                        $pt = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 6));

                                        /*$gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                        $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);*/
                                        $passCourseMark=$db->getExamCategoryMark(1,$regNumber,$studyYear);
                                        $passFinalMark=$db->getExamCategoryMark(2,$regNumber,$studyYear);
                                        $tmarks=$db->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
                                        if(!empty($sup))
                                        {
                                            $passMark=$db->getExamCategoryMark(3,$regNumber,$studyYear);
                                            if($tmarks>=$passMark)
                                                $grade="C";
                                            else
                                                $grade="D";
                                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                        }
                                        else if(!empty($pt))
                                        {
                                            $passMark=$db->getExamCategoryMark(6,$regNumber,$studyYear);
                                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                            if($tmarks>=$passMark)
                                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                            else
                                                $grade="D";
                                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                        }
                                        else if(!empty($prj))
                                        {
                                            $passMark=$db->getExamCategoryMark(5,$regNumber,$studyYear);
                                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                            if($tmarks>=$passMark)
                                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                            else
                                                $grade="D";
                                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                        }
                                        else if(empty($cwk)||empty($sfe))
                                        {
                                            $grade="I";
                                            $gradePoint=0;
                                        }
                                        else if ($cwk < $passCourseMark)
                                        {
                                            $grade = "I";
                                            $gradePoint = 0;
                                        }
                                        else if ($sfe < $passFinalMark)
                                        {
                                            $grade = "E";
                                            $gradePoint = 0;
                                        } else {
                                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                            $grade = $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                        }
                                        $points = $gradePoint * $units;
                                        $tpoints += $points;

                                        //$published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);
                                        echo "<td>" . $tmarks . "</td>
                                <td>" .$grade . "</td>
                                <td>" . $db->courseRemarks($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt) . "</td>";
                                        ?>
                                        <td>
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                    data-target="#message<?php echo $courseID; ?>">
                                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </td>
                                    </tr>


                                    <!-- Result Details -->
                                    <div id="message<?php echo $courseID; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <h4 class="modal-title">Details
                                                        of <?php echo $courseCode . "-" . $courseName; ?></h4>
                                                </div>
                                                <form name="register" id="register" enctype="multipart/form-data"
                                                      method="post" action="action_add_exmption.php">
                                                    <div class="modal-body">
                                                        <div class="row" style="background-color:lightgray;">
                                                            <div class="col-lg-1"><strong>No</strong></div>
                                                            <div class="col-lg-3"><strong>Ass.Title</strong></div>
                                                            <div class="col-lg-2"><strong>Max.Marks</strong></div>
                                                            <div class="col-lg-2"><strong>Present</strong></div>
                                                            <div class="col-lg-2"><strong>Scored</strong></div>
                                                            <div class="col-lg-2"><strong>Weights</strong></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-1">1</div>
                                                            <div class="col-lg-3">Course Work</div>
                                                            <div class="col-lg-2">40</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($cwk > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $cwk; ?></div>
                                                            <div class="col-lg-2"><?php echo $cwk; ?></div>
                                                        </div>
                                                        <div class="row" style="background-color:lightgray;">
                                                            <div class="col-lg-1">2</div>
                                                            <div class="col-lg-3">Final Exam</div>
                                                            <div class="col-lg-2">60</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($sfe > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $sfe; ?></div>
                                                            <div class="col-lg-2"><?php echo $sfe; ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-1">3</div>
                                                            <div class="col-lg-3">Supplementary</div>
                                                            <div class="col-lg-2">100</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($sup > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $sup; ?></div>
                                                            <div class="col-lg-2"><?php echo $sup; ?></div>
                                                        </div>

                                                        <div class="row" style="background-color:lightgray;">
                                                            <div class="col-lg-1">4</div>
                                                            <div class="col-lg-3">Special Exam</div>
                                                            <div class="col-lg-2">60</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($spc > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $spc; ?></div>
                                                            <div class="col-lg-2"><?php echo $spc; ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-1">5</div>
                                                            <div class="col-lg-3">Project</div>
                                                            <div class="col-lg-2">100</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($prj > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $prj; ?></div>
                                                            <div class="col-lg-2"><?php echo $prj; ?></div>
                                                        </div>

                                                        <div class="row" style="background-color:lightgray;">
                                                            <div class="col-lg-1">6</div>
                                                            <div class="col-lg-3">Field Training</div>
                                                            <div class="col-lg-2">100</div>
                                                            <div class="col-lg-2">
                                                                <?php
                                                                if ($pt > 0)
                                                                    echo "Yes";
                                                                else
                                                                    echo "No";
                                                                ?>
                                                            </div>
                                                            <div class="col-lg-2"><?php echo $pt; ?></div>
                                                            <div class="col-lg-2"><?php echo $pt; ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-10">
                                                                <strong><span
                                                                            class="text-danger">Total Marks:</span></strong>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <strong><span
                                                                            class="text-danger"><?php echo $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt); ?></span></strong>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- End of Result Details -->
                                    <?php
                                }
                                $totalPoints += $tpoints;
                                $totalUnits += $tunits;
                                ?>
                                <tr>
                                    <td colspan="2" align="left" style="font-size: 20px;">
                                        <strong><span
                                                    class="text-danger">Total Credits:<?php echo $tunits; ?></span></strong>
                                    </td>
                                    <td colspan="2" align="left" style="font-size: 20px;">
                                        <strong><span
                                                    class="text-danger">Total Points:<?php echo $tpoints; ?></span></strong>
                                    </td>
                                    <td colspan="3" align="left" style="font-size: 20px;">
                                        <strong><span
                                                    class="text-danger">GPA:<?php echo $db->getGPA($tpoints, $tunits); ?></span></strong>
                                    </td>
                                </tr>
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
                ?>

                <?php
            }
            ?>
        </div>
        <div class="col-md-3">
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Perfomance</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="" class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>Total Credits:</th>
                            <td style="font-size: 18px;"><strong><span
                                            class="text-danger"><?php echo $totalUnits; ?></span></strong></td>
                        </tr>
                        <tr>
                            <th>Total Points</th>
                            <td style="font-size: 18px;"><strong><span
                                            class="text-danger"><?php echo $totalPoints; ?></span></strong></td>
                        </tr>
                        <tr>
                            <th>Overall GPA</th>
                            <td style="font-size: 18px;"><strong><span
                                            class="text-danger"><?php echo $db->convert_gpa($db->getGPA($totalPoints, $totalUnits)); ?></span></strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Remarks</th> <?php $gpa = $db->convert_gpa($db->getGPA($totalPoints, $totalUnits)); ?>
                            <td style="font-size:18px;"><strong><span
                                            class="text-danger"><?php echo $db->getGPARemarks($regNumber, $gpa); ?></span></strong>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<h3 class='text-danger'>No Result Found</h3>";
    }
}
	
//}
}?>
	
    
     </div>