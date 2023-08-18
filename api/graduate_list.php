<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

$academicYearID=$_GET['academicYearID'];
$programmeID=$_GET['programmeID'];

$duration=$db->getData("programmes","programmeDuration","programmeID",$programmeID);

$student=$db->graduateList($programmeID,$duration,$academicYearID);
if(!empty($student))
{
    foreach($student as $st) {
        $count++;
        $regNumber = $st['registrationNumber'];
        $fname = $st['firstName'];
        $mname = $st['middleName'];
        $lname = $st['lastName'];
        $name = "$fname $mname $lname";

        $course=$db->getStudentCourseCredit($regNumber);
        $tunits=0;
        $tpoints=0;
        /*$countpass=0;
        $countsupp=0;
        $creditsPass=0;
        $creditsFail=0;*/
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

                $passCourseMark=$db->getExamCategoryMark(1,$regNumber);
                $passFinalMark=$db->getExamCategoryMark(2,$regNumber);
                $tmarks=$db->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
                $tunits+=$units;
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
                    if($tmarks>=$passMark)
                        $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                    else
                        $grade="D";
                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                    $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                }
                else if(!empty($prj))
                {
                    $passMark=$db->getExamCategoryMark(5,$regNumber,$studyYear);
                    if($tmarks>=$passMark)
                        $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                    else
                        $grade="D";
                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
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
            }
            else
            {
                $cwk="-";
                $sfe="-";
                $totalMarks="-";
                $grade="-";
                $units=0;
            }
            /*if(($grade=="D") || ($grade=="F") || ($grade=="E"))
            {
                $countsupp=$countsupp+1;
                $creditsFail+=$units;
            }
            else
            {
                $countpass=$countpass+1;
                $creditsPass+=$units;
            }*/
        }

        $gpa=$db->getGPA($tpoints,$tunits);
        $gpaRemarks=$db->getGPARemarks($regNumber,$gpa);

        $output['data'][] = array(
            $count,
            $name,
            $regNumber,
            $st['gender'],
            $gpa,
            $gpaRemarks,
            $st['gdate']
        );
    }
}
echo json_encode($output);
