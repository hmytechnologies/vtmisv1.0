<div class="row">

    <?php
    /*if(isset($_POST['doFind'])=="View Records")
    {*/
    session_start();
    include ('DB.php');
    $db=new DBHelper();
    $programmeID=$_POST['programmeID'];
    $studyYear=$_POST['studyYear'];
    $batchID=$_POST['batchID'];
    $semesterID=$_POST['semesterID'];

    $academicYearID=$db->getData("semester_setting","academicYearID","semesterSettingID",$semesterID);

    $student = $db->getStudentProgramme($programmeID,$semesterID,$studyYear,$batchID,$academicYearID);
    if(!empty($student))
    {
    ?>
    <div class="box box-solid box-primary">
        <div class="box-header with-border text-center">
            <h3 class="box-title">Semester Report for
                <?php
                if($studyYear==1)
                    $sYear="First Year";
                else if($studyYear==2)
                    $sYear="Second Year";
                else if($studyYear==3)
                    $sYear="Third Year";
                else if($studyYear==4)
                    $sYear="Fourth Year";
                ?>
                <?php echo $sYear;echo " ";echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterID);?>
                <?php echo $db->getData("batch","batchName","batchID",$batchID);?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding"
        <!--Modal-->
        <div class="row"><div class="pull-right"><div class="col-lg-12">
                    <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Report</button>
                </div></div></div>
        <!--End -->
        <table  id="" class="table table-hover table-bordered" cellspacing="0" border=0 width="100%" rules="groups">
            <thead>
            <tr><th colspan="6" style='text-align:center;'>Module Credits</th>
                <?php $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
                foreach ($course as $cs)
                {
                    echo "<th colspan='5' style='text-align:center;'>".$cs['units']."</th>";
                }
                ?></th>
                <th rowspan="4">GPA</th><th rowspan="4">Remarks</th>
            </tr>
            <tr><th colspan="6" style='text-align:center;'>Module Code</th>
                <?php $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
                foreach ($course as $cs)
                {
                    echo "<th colspan='5' style='text-align:center;'>".$cs['courseCode']."</th>";
                }
                ?></th>
            </tr>
            <tr><th colspan="6" style='text-align:center;'>Max Marks</th>
                <?php $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
                foreach ($course as $cs)
                {
                    echo "<th colspan='5' style='text-align:center;'>100</th>";
                }
                ?></th>
            </tr>

            </tr>
            <tr>

                <th>No.</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Reg.Number</th>
                <th>Date of Entry</th>
                <?php
                $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
                foreach($course as $cs){
                    ?>
                    <th>CA</th>
                    <th>SE</th>
                    <th>TL</th>
                    <th>GD</th>
                    <th>PT</th>
                    <?php
                }
                ?>

            </tr>
            </thead>
            <tbody>
            <?php
            $count = 0;
            foreach($student as $st)
            {
                $count++;
                $regNumber=$st['regNumber'];

                $studentDetails=$db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>'firstName ASC'));
                foreach($studentDetails as $std) {
                    # code...
                    $fname=$std['firstName'];
                    $mname=$std['middleName'];
                    $lname=$std['lastName'];
                    $name="$fname $mname $lname";
                    $gender=$std['gender'];
                    $dob=$std['dateOfBirth'];
                    $admissionYearID=$std['academicYearID'];
                    echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>".date('d-m-Y',strtotime($dob))."</td><td>$regNumber</td><td>".$db->getData("academic_year","academicYear","academicYearID",$admissionYearID)."</td>";

                    $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
                    $tunits=0;
                    $tpoints=0;
                    $countpass=0;
                    $countsupp=0;
                    foreach ($course as $cs)
                    {
                        $courseID=$cs['courseID'];
                        $units=$cs['units'];

                        $student_course=$db->getStudentExamCourse($regNumber,$semesterID,$courseID);
                        if(!empty($student_course))
                        {
                            $cwk=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,1));
                            $sfe=$db->decrypt($db->getFinalGrade($semesterID,$courseID,$regNumber,2));
                            $sup=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,3));
                            $spc=$db->decrypt($db->getFinalGrade($semesterID,$courseID,$regNumber,4));
                            $prj=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,5));
                            $pt=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,6));

                            //$totalMarks=$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);

                            /*$gradeID=$db->getMarksID($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                            $gradePoint=$db->getData("grades","gradePoints","gradeID",$gradeID);

                            $points=$gradePoint*$units;

                            $remarks=$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                            //$grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                            $grade=$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);*/



                            $passCourseMark=$db->getExamCategoryMark(1,$regNumber);
                            $passFinalMark=$db->getExamCategoryMark(2,$regNumber);
                            $tmarks=$db->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
                            if(!empty($sup))
                            {
                                $passMark=$db->getExamCategoryMark(3,$regNumber);
                                if($tmarks>=$passMark)
                                    $grade="C";
                                else
                                    $grade="D";
                                $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                            }
                            else if(!empty($pt))
                            {
                                $passMark=$db->getExamCategoryMark(6,$regNumber);
                                $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                if($tmarks>=$passMark)
                                    $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                else
                                    $grade="D";
                                $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                            }
                            else if(!empty($prj))
                            {
                                $passMark=$db->getExamCategoryMark(5,$regNumber);
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
                            $points=$gradePoint*$units;
                            $tpoints+=$points;
                            $tunits+=$units;
                            $gpa=$db->getGPA($tpoints,$tunits);


                            if(($grade=="D")or ($grade=="F") or ($grade=="E") or ($grade=="I"))
                            {
                                $countsupp=$countsupp+1;
                            }
                            else
                            {
                                $countpass=$countpass+1;
                            }

                            if($gpa<2)
                                $gparemarks="Fail";
                            else if($countsupp>0)
                                $gparemarks="Supp";
                            else
                                $gparemarks="Pass";
                        }
                        else
                        {
                            $cwk="-";
                            $sfe="-";
                            $totalMarks="-";
                            $grade="-";
                            $units="-";
                            $points="-";

                        }

                        echo "<td>$cwk</td><td>$sfe</td><td>$tmarks</td><td>$grade</td><td>$points</td>";
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

<!--<input type="button" id="btnExport" onclick="tableToExcel('nactereport', 'NACTE REPORT')" value="Export to Excel">-->

<?php
}
else
{
    ?>
    <h4 class="text-danger">No Result(s) found......</h4>
    <?php
}
//}
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

                <embed src="print_semester_report_sumait.php?action=getPDF&prgID=<?php echo $programmeID;?>&bid=<?php echo $batchID;?>&sid=<?php echo $semesterID;?>&syear=<?php echo $studyYear;?>" frameborder="0" width="100%" height="600px">


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
