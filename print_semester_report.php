<?php
session_start();
if($_REQUEST['action']=="getPDF")
{
    include 'DB.php';
    $db=new DBHelper();

    $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
    if(!empty($organization))
    {
        foreach($organization as $org)
        {
            $organizationName=$org['organizationName'];
            $organizationCode=$org['organizationCode'];
            $organizationPicture="img/".$org['organizationPicture'];
        }
    }
    else
    {
        $organizationName="Soft Dev Academy";
        $organizationCode="SDVA";
        $organizationPicture="img/SkyChuo.png";
    }

    require('fpdf.php');
   $programmeID=$_REQUEST['prgID'];
    $studyYear=$_REQUEST['syear'];
    $batchID=$_REQUEST['bid'];
    $semesterID=$_REQUEST['sid'];

   /* $programmeID=59;
    $studyYear=1;
    $batchID=1;
    $semesterID=3;*/

    $academicYearID=$db->getData("semester_setting","academicYearID","semesterSettingID",$semesterID);

    if($studyYear==1)
        $sYear="First Year";
    else if($studyYear==2)
        $sYear="Second Year";
    else if($studyYear==3)
        $sYear="Third Year";
    else if($studyYear==4)
        $sYear="Fourth Year";
    class PDF extends FPDF
    {
        function SetCol($col)
        {
            // Set position at a given column
            $this->col = $col;
            $x = 10+$col*65;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }
        function Footer()
        {
            $today2=date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial','I',8);
            //Page number
            $this->Cell(0,0,'Page '.$this->PageNo().'of{nb}',0,1,'C');

            $this->Cell(150,0,'Printed Date '.$today2.' Zanzibar',0,1,'C');

        }

        function BasicTable($header)
        {
            $w = array(10,25,40,157,13,13,10,15);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }
    }
    $pdf=new PDF();
    $pdf->AliasNbPages();
   /* $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
    if(!empty($course))
    {
        foreach($course as $c)
        {
            $courseCode=$c['courseCode'];
            $courseName=$c['courseName'];
            $courseTypeID=$c['courseTypeID'];
        }
    }*/
    $pdf->AddPage("L");
    $pdf->setFont('Arial', '', 8);
    $today=date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B',20);
    $pdf->Text(10,10,strtoupper($organizationName));
    //Arial bold 15
    $pdf->setFont('Arial', 'B', 14);
    //get cwork and final exam marks per programme
    $sfemaxmarks=$db->getProgrammeMaxMarks($programmeID,2);
    $cwkmaxmarks=$db->getProgrammeMaxMarks($programmeID,1);

    $pdf->Text(10,20,'SEMESTER EXAMINATION REPORT-CA Weights('.$cwkmaxmarks.'),SFE Weights('.$sfemaxmarks.')');
    $pdf->Text(10,26,$sYear." ".$db->getData("programmes","programmeName","programmeID",$programmeID)." ".$db->getData("semester_setting","semesterName","semesterSettingID",$semesterID)." ".$db->getData("batch","batchName","batchID",$batchID));
    $header = array('No','Reg.Number','Name','Courses','Units','Points','GPA','Remark');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell('10',6,'');
    $pdf->Ln(30);

    $student = $db->getStudentProgramme($programmeID,$semesterID,$studyYear,$batchID,$academicYearID);
    if(!empty($student))
    {
    $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
    $number=0;
    foreach ($course as $cs)
    {
        $number++;
    }
        $pdf->SetFont('Arial','B',11);
        $pdf->BasicTable($header);
        $pdf->SetFont('Arial','',8);
        $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
        $pdf->Cell(75,6,"",1);
        $wdth=157/$number;
        foreach ($course as $cs)
        {
           $pdf->Cell($wdth,6,$cs['courseCode'],1);
        }
        $count = 0;
        $pdf->ln(6);

        $tsup=0;$tpass=0;$tfail=0;$tothers=0;$tincomp=0;
        $tsupf=0;$tpassf=0;$tfailf=0;$tothersf=0;$tincompf=0;

        $fclass=0;$uclass=0;$lcass=0;$pclass=0;$ffclass=0;
        $fclassf=0;$uclassf=0;$lcassf=0;$pclassf=0;$ffclassf=0;

        $maxgpa=0;$mingpa=6;$gpaarr=array(); $tlgpa=0;
        foreach($student as $st) {
            $count++;
            $regNumber = $st['regNumber'];
            $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));

            foreach ($studentDetails as $std) {
                $fname = $std['firstName'];
                $mname = $std['middleName'];
                $lname = $std['lastName'];
                $name = $fname." ".$mname[0]." ".$lname;
                $gender = $std['gender'];
                $statusID=$std['statusID'];
                $pdf->Cell(10, 6, $count, 1);
                $pdf->Cell(25, 6, $regNumber, 1);
               $pdf->Cell(40, 6, $name, 1);
               /* $pdf->Cell(10, 6, $gender, 1);*/
                $course = $db->getCourseCredit($programmeID, $semesterID,$studyYear,$academicYearID);
                $tunits = 0;
                $tpoints = 0;
                $countpass = 0;
                $countsupp = 0;
                $countincomplete=0;


                //$cA=0;$Arr=array();
                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                    $units = $cs['units'];
                    $student_course = $db->getStudentExamCourse($regNumber, $semesterID, $courseID);

                    if (!empty($student_course)) {
                        $cwk = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 1));
                        $sfe = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 2));
                        $sup = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 3));
                        $spc = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 4));
                        $prj = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 5));
                        $pt = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 6));

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
                        $tpoints += $points;
                        $tunits += $units;

                        $gpa = $db->getGPA($tpoints, $tunits);

                        //$gpaarr[]=$gpa;
                        if (($grade == "D") || ($grade == "F") || ($grade == "E")) {
                            $countsupp = $countsupp + 1;

                        }
                        else if($grade == "I")
                        {
                            $countincomplete+=1;
                        }
                        else {
                            $countpass = $countpass + 1;
                        }

                        if($gpa<2)
                            $gparemarks="Fail";
                        else if($countsupp>0)
                            $gparemarks="Supp";
                        else if($countincomplete>0)
                            $gparemarks="INC";
                        else
                            $gparemarks="Pass";

                    } else {
                        $cwk = "-";
                        $sfe = "-";
                        $tmarks = "-";
                        $grade = "-";
                        $units = 0;
                        $points="-";
                    }
                    /*$pdf->Cell($wdth,6,$tmarks."-".$grade,1);*/
                    $pdf->Cell($wdth,6,$grade,1);
                }

                $pdf->Cell(13,6,$tunits,1);$pdf->Cell(13,6,$tpoints,1);$pdf->Cell(10,6,$gpa,1);$pdf->Cell(15,6,$gparemarks,1);
                $pdf->Ln();
            }
            $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);

            if($gender=="M"){
                if($statusID==1) {
                    if ($gparemarks == "Pass")
                        $tpass += 1;
                    else if ($gparemarks == "Supp")
                        $tsup += 1;
                    else if ($gparemarks == "Fail")
                        $tfail += 1;
                    else if ($gparemarks == "INC")
                        $tincomp += 1;
                }
                else
                {
                    $tothers+=1;
                }

                //countsgpa
                if($programmeLevelID==1 || $programmeLevelID==2) {
                    if ($gpa >= 3.5)
                        $fclass += 1;
                    else if ($gpa >= 3.0)
                        $lcass += 1;
                    else if ($gpa >= 2)
                        $pclass += 1;
                    else
                        $ffclass += 1;
                }
                else
                {
                    if ($gpa >= 4.4)
                        $fclass += 1;
                    else if ($gpa >= 3.5)
                        $uclass += 1;
                    else if ($gpa >= 3.0)
                        $lcass += 1;
                    else if ($gpa >= 2)
                        $pclass += 1;
                    else
                        $ffclass += 1;
                }

            }
            else{
                if($statusID==1) {
                    if ($gparemarks == "Pass")
                        $tpassf += 1;
                    else if ($gparemarks == "Supp")
                        $tsupf += 1;
                    else if ($gparemarks == "Fail")
                        $tfailf += 1;
                    else if ($gparemarks == "INC")
                        $tincompf += 1;
                }
                else
                {
                    $tothersf+=1;
                }


                //countsgpa
                if($programmeLevelID==1 || $programmeLevelID==2) {
                    if($gpa>=3.5)
                        $fclassf+=1;
                    else if($gpa>=3.0)
                        $lcassf+=1;
                    else if($gpa>=2)
                        $pclassf+=1;
                    else
                        $ffclassf+=1;
                }
                else
                {
                    if($gpa>=4.4)
                        $fclassf+=1;
                    else if($gpa>=3.5)
                        $uclassf+=1;
                    else if($gpa>=3.0)
                        $lcassf+=1;
                    else if($gpa>=2)
                        $pclassf+=1;
                    else
                        $ffclassf+=1;
                }
            }
            $gpaarr[]=$gpa;

            if($maxgpa<$gpa)
                $maxgpa=$gpa;

            if($mingpa>$gpa)
                $mingpa=$gpa;

            $tlgpa+=$gpa;

        }
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,6,"SUMMARY OF PERFOMANCE STATISTICS");
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(30,6,'Remarks',1);
        $pdf->Cell(30,6,"Pass",1);
        $pdf->Cell(30,6,"Supp",1);
        $pdf->Cell(30,6,"Fail",1);
        $pdf->Cell(30,6,"Incomplete",1,0,'L');
        $pdf->Cell(40,6,"Other Remarks",1,0,'L');

        $pdf->Ln(6);
        $pdf->Cell(30,6,'Male',1);
        $pdf->Cell(30,6,$tpass,1);
        $pdf->Cell(30,6,$tsup,1);
        $pdf->Cell(30,6,$tfail,1);
        $pdf->Cell(30,6,$tincomp,1,0,'L');
        $pdf->Cell(40,6,$tothers,1,0,'L');

        $pdf->Ln(6);
        $pdf->Cell(30,6,'Female',1);
        $pdf->Cell(30,6,$tpassf,1);
        $pdf->Cell(30,6,$tsupf,1);
        $pdf->Cell(30,6,$tfailf,1);
        $pdf->Cell(30,6,$tincompf,1,0,'L');
        $pdf->Cell(40,6,$tothersf,1,0,'L');

        $pdf->Ln(6);
        $pdf->Cell(30,6,'Subtotal',1);
        $pdf->Cell(30,6,$tpass+$tpassf,1);
        $pdf->Cell(30,6,$tsup+$tsupf,1);
        $pdf->Cell(30,6,$tfail+$tfailf,1);
        $pdf->Cell(30,6,$tincompf+$tincomp,1,0,'L');
        $pdf->Cell(40,6,$tothers+$tothersf,1,0,'L');

        $avggpa=$tlgpa/$count;

        $sdvgpa=$db->standDeviation($gpaarr);

        $present=$db->getStudentStatus($semesterID,1);
        $absent=$db->getStudentStatus($semesterID,1);

        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,6,"Summary of GPA");
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
       /* $pdf->Cell(25,6,"Present",1);
        $pdf->Cell(35,6,"Other Remarks",1);*/
        $pdf->Cell(30,6,"Maximum GPA",1);
        $pdf->Cell(30,6,"Minimum GPA",1,0,'L');
        $pdf->Cell(30,6,"Average GPA",1,0,'L');
        $pdf->Cell(30,6,"Std Deviation",1,0,'L');
        $pdf->Ln(6);
      /*  $pdf->Cell(25,6,$present,1);
        $pdf->Cell(35,6,$absent,1);*/
        $pdf->Cell(30,6,$maxgpa,1);
        $pdf->Cell(30,6,$mingpa,1,0,'L');
        $pdf->Cell(30,6,number_format($avggpa,2),1,0,'L');
        $pdf->Cell(30,6,number_format($sdvgpa,2),1,0,'L');


        //Classess
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,6,"SUMMARY OF GPA CLASSES STATISTICS");
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
        $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
        $gpaclassess=$db->getRows("gpa",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
        $pdf->Cell(30,6,'Remarks',1);
        foreach($gpaclassess as $gpa)
        {
            $pdf->Cell(50,6,$gpa['gpaClass'],1);
        }
        /*$pdf->Ln(6);
        $pdf->Cell(30,6,'Male',1);
            foreach($gpamale as $ml)
        {
            $pdf->Cell(50,6,$ml,1);
        }
        $pdf->Ln(6);
        $pdf->Cell(30,6,'Female',1);
        foreach($gpafemale as $fml)
        {
            $pdf->Cell(50,6,$fml,1);
        }*/
        $pdf->Ln(6);
        if($programmeLevelID==1 || $programmeLevelID==2) {
            $pdf->Cell(30, 6, 'Male', 1);
            $pdf->Cell(50, 6, $fclass, 1);
            $pdf->Cell(50, 6, $lcass, 1);
            $pdf->Cell(50, 6, $pclass, 1, 0, 'L');
            $pdf->Cell(50, 6, $ffclass, 1, 0, 'L');

            $pdf->Ln(6);
            $pdf->Cell(30,6,'Female',1);
            $pdf->Cell(50,6,$fclassf,1);
            $pdf->Cell(50,6,$lcassf,1);
            $pdf->Cell(50,6,$pclassf,1,0,'L');
            $pdf->Cell(50,6,$ffclassf,1,0,'L');

            $pdf->Ln(6);
            $pdf->Cell(30,6,'Subtotal',1);
            $pdf->Cell(50,6,$fclass+$fclassf,1);
            $pdf->Cell(50,6,$lcass+$lcassf,1);
            $pdf->Cell(50,6,$pclass+$pclassf,1,0,'L');
            $pdf->Cell(50,6,$ffclass+$ffclassf,1,0,'L');
        }
        else
        {
            $pdf->Cell(30, 6, 'Male', 1);
            $pdf->Cell(50, 6, $fclass, 1);
            $pdf->Cell(50, 6, $uclass, 1);
            $pdf->Cell(50, 6, $lcass, 1);
            $pdf->Cell(50, 6, $pclass, 1, 0, 'L');
            $pdf->Cell(50, 6, $ffclass, 1, 0, 'L');

            $pdf->Ln(6);
            $pdf->Cell(30,6,'Female',1);
            $pdf->Cell(50,6,$fclassf,1);
            $pdf->Cell(50,6,$uclassf,1);
            $pdf->Cell(50,6,$lcassf,1);
            $pdf->Cell(50,6,$pclassf,1,0,'L');
            $pdf->Cell(50,6,$ffclassf,1,0,'L');

            $pdf->Ln(6);
            $pdf->Cell(30,6,'Subtotal',1);
            $pdf->Cell(50,6,$fclass+$fclassf,1);
            $pdf->Cell(50,6,$uclass+$uclassf,1);
            $pdf->Cell(50,6,$lcass+$lcassf,1);
            $pdf->Cell(50,6,$pclass+$pclassf,1,0,'L');
            $pdf->Cell(50,6,$ffclass+$ffclassf,1,0,'L');
        }


        //Grade Count
       /* $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $getGrades=$db->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
        $pdf->Cell(50,6,"Grade Summary",1);
        foreach ($getGrades as $gd) {
            $pdf->Cell(30, 6, $gd['gradeCode'], 1);
        }
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
        $course=$db->getCourseCredit($programmeID,$semesterID);
        foreach ($course as $cs)
        {
            $pdf->Cell(50,6,$cs['courseCode'],1);
            foreach ($Arr as $gd) {
                //$pdf->Cell(30, 6,$cA[$gd['cA']][$cs['courseID']],1);
            }
            $pdf->Ln();
        }

        $pdf->Cell(30,6,var_dump($Arr),1);*/
        $pdf->Ln(10);
        $pdf->Cell(50,6,"Course Code",1);$pdf->Cell(100,6,"Course Name",1);
        $pdf->Ln(6);
        $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
        foreach ($course as $cs)
        {
            $pdf->Cell(50,6,$cs['courseCode'],1);
            $pdf->Cell(100,6,$cs['courseName'],1);
            $pdf->Ln();
        }


        $pdf->Ln(20);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(100,6,"Signature of Dean/Director:.....................................");$pdf->Cell(100,6,"Date:.................................");
        $pdf->Ln(10);
        $pdf->Cell(100,6,"Signature of Senate Chairman:...............................");$pdf->Cell(100,6,"Date:..............................");
    }

    $pdf->Output();
}
?>
