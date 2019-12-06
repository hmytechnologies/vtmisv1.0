<div class="row">
    <?php
    session_start();
    include ('DB.php');
    $db=new DBHelper();
    $programmeID=$_POST['programmeID'];
    $studyYear=$_POST['studyYear'];
    $batchID=$_POST['batchID'];
    $academicYearID=$_POST['academicYearID'];

    $student = $db->getStudentSpecialProgramme($programmeID,$academicYearID,$studyYear,$batchID);
    if(!empty($student))
    {
        ?>
        <div class="box box-solid box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Supplementary Examination Report for
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
                    <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?><?php echo"[".$sYear."]";?>
                    <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
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
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Reg.Number</th>
                    <th>Gender</th>
                    <th>Course</th>
                    <!--<th>Units</th>
                    <th>Points</th>
                    <th>GPA</th>-->
                    <th>Remarks</th>
                    <?php
                    /*                $course=$db->getAnnualSuppCourseCredit($programmeID,$academicYearID);
                                    foreach($course as $cs){
                                        */?><!--<
                        <th>Course</th>
                        --><?php
                    /*                }
                                    */?>
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
                        echo "<tr><td>$count</td><td>$name</td><td>$regNumber</td><td>$gender</td><td><table border='0'><tr>";

                        $course=$db->getAnnualSpecialCourseCredit($regNumber,$programmeID,$academicYearID);
                        $tunits=0;
                        $tpoints=0;
                        $countpass=0;
                        $countsupp=0;
                        foreach ($course as $cs)
                        {
                            $courseID=$cs['courseID'];
                            $courseCode=$cs['courseCode'];
                            $units=$cs['units'];
                            $semesterID=$cs['semesterSettingID'];

                            $student_course=$db->getStudentExamCourse($regNumber,$semesterID,$courseID);
                            if(!empty($student_course))
                            {
                                $cwk=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,1));
                                $sfe=$db->decrypt($db->getFinalGrade($semesterID,$courseID,$regNumber,2));
                                $sup=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,3));
                                $spc=$db->decrypt($db->getFinalGrade($semesterID,$courseID,$regNumber,4));
                                $prj=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,5));
                                $pt=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,6));

                                $totalMarks=$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);

                                $gradeID=$db->getMarksID($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                                $gradePoint=$db->getData("grades","gradePoints","gradeID",$gradeID);

                                $points=$gradePoint*$units;

                                $remarks=$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                                $grade=$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);

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
                            echo "<td>$courseCode($totalMarks-$grade)</td><td></td>";
                        }
                        echo "</tr></table></td><td>$gparemarks</td></tr>";
                        ?>

                        <?php
                    }
                }
                ?>

                </tbody>
            </table>
        </div>
        <?php
    }
    else
    {
        ?>
        <h4 class="text-danger">No Result(s) found......</h4>
        <?php
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

                <embed src="print_special_report.php?action=getPDF&prgID=<?php echo $programmeID;?>&bid=<?php echo $batchID;?>&aid=<?php echo $academicYearID;?>&syear=<?php echo $studyYear;?>"
                       frameborder="0" width="100%" height="600px">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
