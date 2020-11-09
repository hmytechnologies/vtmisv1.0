<script type="text/javascript">
    $(document).ready(function(){
        $("#select_all").change(function(){
            $(".checkbox_class").prop("checked", $(this).prop("checked"));
        });
    });
</script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<!--<script src="js/script.js"></script>
--><script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#onlydata').DataTable(
            {
                paging: false,
                dom: 'Blfrtip'
            });
    });
</script>
<?php
    include ('DB.php');
    $db=new DBHelper();
    $academicYearID=$_POST['academicYearID'];
    $programmeID=$_POST['programmeID'];
    $batchID=$_POST['batchID'];
    $graduationDate=$_POST['graduationDate'];
    $duration=$db->getData("programmes","programmeDuration","programmeID",$programmeID);
    $student=$db->approveGraduatedList($programmeID,$duration,$academicYearID,$batchID);
    if(!empty($student))
    {
        ?>
        <div class="row">
            <h4><span class="text-danger" id="titleheader">
                List of Student graduating in <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?> for the year
                    <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
            <hr>
            <form name="" method="post" action="action_approve_graduands.php">

            <table id="onlydata" class="table table-bordered table-responsive-xl table-hover display">
                <thead>
                <tr>
                    <th>No.</th>
                    <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Reg.Number</th>
                    <th>Required Credits</th>
                    <th>Credits Taken</th>
                    <th>Credits Pass</th>
                    <th>GPA</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                foreach($student as $st)
                {
                    $count++;
                    $regNumber=$st['registrationNumber'];
                    $fname=$st['firstName'];
                    $mname=$st['middleName'];
                    $lname=$st['lastName'];
                    $name="$fname $mname $lname";

                    //$course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                    $course=$db->getStudentCourseCredit($regNumber);
                    $tunits=0;
                    $tpoints=0;
                    $countpass=0;
                    $countsupp=0;
                    $creditsPass=0;
                    $creditsFail=0;
                    foreach ($course as $cs) {
                        $courseID=$cs['courseID'];
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

                           /* $totalMarks=$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);

                            $gradeID=$db->getMarksID($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                            $gradePoint=$db->getData("grades","gradePoints","gradeID",$gradeID);*/
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

                            $points=$gradePoint*$units;

                            //$grade=$db->getData("grades","gradeCode","gradeID",$gradeID);

                            $tpoints+=$points;
                            $tunits+=$units;
                        }
                        else
                        {
                            $cwk="-";
                            $sfe="-";
                            $tmarks="-";
                            $grade="-";
                            $units=0;
                        }
                        if(($grade=="D") || ($grade=="F") || ($grade=="E"))
                        {
                            $countsupp=$countsupp+1;
                            $creditsFail+=$units;
                        }
                        else
                        {
                            $countpass=$countpass+1;
                            $creditsPass+=$units;
                        }
                    }

                    $gpa=$db->getGPA($tpoints,$tunits);

                    if($gpa<2 || $countsupp>0)
                        $gparemarks="Fail";
                    else
                        $gparemarks="Pass";

                    $requiredCredits=$db->getData("programmes","programmeCredits","programmeID",$programmeID);
                    if($db->getStudentCredits($regNumber)>0)
                    {
                        $studentCreadits=$db->getStudentCredits($regNumber);
                    }
                    else
                    {
                        $studentCreadits="NA";
                    }
                    if($creditsPass>0)
                        $creditsPass=$creditsPass;
                    else
                        $creditsPass="NA";
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <?php
                        if(($creditsPass>=$requiredCredits)&&($gparemarks=="Pass"))
                        {
                            ?>
                            <td><input type='checkbox' class='checkbox_class' name='regNumber[]' value='<?php echo $st['registrationNumber'];?>'></td>
                            <?php
                        }else
                        {?>
                            <td>NA</td>
                            <?php
                        }?>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $st['gender']; ?></td>
                        <td><?php echo $st['registrationNumber']; ?></td>
                        <td><?php echo $requiredCredits;?></td>
                        <td><?php echo $studentCreadits;?></td>
                        <td><?php echo $creditsPass;?></td>
                        <td><?php echo $gpa;?></td>
                        <td><?php echo $gparemarks;?></td>
                        <input type="text" hidden name="gpa[]" value="<?php echo $gpa;?>">
                        </a></td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>

        <div class="row">
            <div class="col-lg-9"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $count;?>">
            <input type="hidden" name="semesterID" value="<?php echo $semesterSettingID;?>">
            <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
            <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
            <input type="hidden" name="graduationDate" value="<?php echo $graduationDate;?>">
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doAdmit" value="Approve" class="btn btn-success form-control">
            </div>
        </div>
        </form>
        </div>

        <?php
    }
    else
    {
        ?>
        <h4><span class="text-danger">No Student(s) found....</span> </h4>
        <?php
    }
    ?>