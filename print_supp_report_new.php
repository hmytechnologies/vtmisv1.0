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


    /*$programmeID=59;
     $studyYear=1;
     $batchID=1;
     $semesterID=1;*/

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

        function Banner($name,$image,$faculty,$programme,$year,$semester)
        {
            $today=date('M d,Y');
            //Logo .
            //$this->Text(20,8,strtoupper($name));
            $this->Image($image,10,0,40,40);
            //$this->Image(file,x,y,w,h,type,link);
            $this->setFont('Arial', 'B', 24);
            $this->Text(55,10,strtoupper($name));
            $this->setFont('Arial', '', 16);
            //faculty
            $this->Text(55,18,strtoupper($faculty));
            $this->Text(55,26,"OVERALL SUMMARY OF EXAMINATIONS RESULTS -".$semester);
            $this->setFont('Arial', '', 14);
            $this->Text(55,34,strtoupper($programme)."-".strtoupper($year));
            $this->Line(10,40,292,40);

        }

        function Footer()
        {
            $today2=date('Y-m-d H:i:s');
            $this->SetY(-25);
            $this->SetFont('Arial','',10);
            $this->Cell(100,6,"Dr. Ali Said Sunkar");$this->Cell(100,6,"Prof. Msafiri M Mshewa");$this->Cell(100,6,"Prof. Dr. Amran Md Rasli");
            $this->Ln(6);
            $this->Cell(100,6,"Head, Examination Office");$this->Cell(100,6,"Acting DVC Academic, Research & Consultancy");$this->Cell(100,6,"Vice Chancellor");
            $this->Ln(6);
            $this->Cell(100,6,"SUMAIT University");$this->Cell(100,6,"SUMAIT University");$this->Cell(100,6,"SUMAIT University");
            $this->Ln(6);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,0,'Page '.$this->PageNo().'of{nb}',0,1,'C');
            $this->Cell(150,0,'Printed Date '.$today2.' Zanzibar',0,1,'C');
        }

        function BasicTable($header)
        {
            $w = array(10,25,40,170,15,24);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }
    }
    $pdf=new PDF();
    $pdf->AliasNbPages();

    $pdf->AddPage("L");
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 20);
    $programme = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
    $semester = $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID);

    $schoolID = $db->getData("programmes", "schoolID", "programmeID", $programmeID);
    $schoolName = $db->getData("schools", "schoolName", "schoolID", $schoolID);
    $pdf->Banner($organizationName, $organizationPicture, $schoolName, $programme, $sYear, strtoupper($semester));
    //Arial bold 15
    $pdf->setFont('Arial', 'B', 14);

    $header = array('No', 'Reg.Number', 'Name', 'Courses', 'GPA', 'Remark');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell('10', 6, '');
    $pdf->Ln(30);

    $course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
    $number = 0;
    foreach ($course as $cs) {
        $number++;
    }
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->BasicTable($header);
    $pdf->SetFont('Arial', '', 9);
    $course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
    $pdf->Cell(75, 6, "", 1);
    $wdth = 170 / $number;
    foreach ($course as $cs) {
        $pdf->Cell($wdth, 6, $cs['courseCode'] . "-" . $cs['units'], 1);
    }

    $student = $db->getStudentSuppProgramme($programmeID,$semesterID,$studyYear,$batchID,$academicYearID);
    if(!empty($student))
    {

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
            //number_supp,pass,fail,disco
            $number_supp=0;$number_pass=0;$number_fail=0;
            $number_supp2=0;$number_pass2=0;$number_fail2=0; $numbercarryover=0;

            $number_suppg=0;$number_passg=0;$number_failg=0; $numbercarryoverg=0;

            $gparemarksg="";

            foreach ($studentDetails as $std) {
                $fname = $std['firstName'];
                $mname = $std['middleName'];
                $lname = $std['lastName'];
                $fname = str_replace("&#039;","'",$std['firstName']);
                $lname = str_replace("&#039;","'",$std['lastName']);
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
                $countcarryover=0;
                $countcarryover2=0;
                $countspecial=0;

                //OverallGPA
                $tunitsg = 0;
                $tpointsg = 0;
                $countpassg = 0;
                $countsuppg = 0;
                $countincompleteg=0;
                $countcarryoverg=0;
                $countspecialg=0;



                //$cA=0;$Arr=array();
                $number_course=0;
                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                    $units = $cs['units'];
                    $number_course+=1;
                    //first sit
                    $student_course = $db->getStudentExamCourseBySitting($regNumber, $semesterID, $courseID,1);

                    if (!empty($student_course)) {
                        $cwk = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 1));
                        $sfe = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 2));
                        $spc = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 4));
                        $prj = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 5));
                        $pt = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 6));

                        $passCourseMark=$db->getExamCategoryMark(1,$regNumber,$studyYear);
                        $passFinalMark=$db->getExamCategoryMark(2,$regNumber,$studyYear);
                        $tmarks=$db->calculateTotalResults($cwk,$sfe,$spc,$prj,$pt);
                        if(!empty($pt))
                        {
                            $passMark=$db->getExamCategoryMark(6,$regNumber,$studyYear);
                            $gradeID = $db->getMarksOutputID($regNumber, $cwk, $sfe, $spc, $prj, $pt);
                            if($tmarks>=$passMark)
                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                            else
                                $grade="D";
                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                        }
                        else if(!empty($prj))
                        {
                            $passMark=$db->getExamCategoryMark(5,$regNumber,$studyYear);
                            $gradeID = $db->getMarksOutputID($regNumber, $cwk, $sfe, $spc, $prj, $pt);
                            if($tmarks>=$passMark)
                                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                            else
                                $grade="D";
                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                        }
                        else if(empty($cwk))
                        {
                            $grade="A0";
                            $gradePoint=0;
                        }
                        else if(empty($sfe))
                        {
                            $present=$db->getStudentExamStatus($regNumber,$courseID,$semesterID,2);
                            if($present == 0)
                            {
                                $grade="A0";
                            }
                            else if ($present == -1)
                            {
                                $grade="A1";
                            }

                        }
                        else if ($cwk < $passCourseMark)
                        {
                            $grade = "N"; //N means course repeat
                            $gradePoint = 0;
                        }
                        else if ($sfe < $passFinalMark)
                        {
                            $grade = "E"; //Supplementary Exam
                            $gradePoint = 0;
                        } else {
                            $gradeID = $db->getMarksOutputID($regNumber, $cwk, $sfe, $spc, $prj, $pt);
                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                            $grade = $db->calculateGradeFirstSit($regNumber, $cwk, $sfe, $spc, $prj, $pt);
                        }
                        $points=$gradePoint*$units;
                        $tpoints += $points;
                        $tunits += $units;

                        $gpa = $db->getGPA($tpoints, $tunits);

                        //$gpaarr[]=$gpa;
                        if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I") {
                            $number_fail += 1;
                        }
                        if (($grade == "D") || ($grade == "F") || ($grade == "E")) {
                            $countsupp = $countsupp + 1;
                            $number_supp += 1;
                        }
                        else if($grade == "I")
                        {
                            $countincomplete+=1;
                        }
                        else if($grade=="N" || $grade == "A0")
                        {
                            $countrepeat+=1;
                        }
                        else if($grade == "A1")
                        {
                            $countspecial+=1;
                        }
                        else
                        {
                            $countpass = $countpass + 1;
                            $number_pass+=1;
                        }

                    } else {
                        $cwk = "-";
                        $sfe = "-";
                        $tmarks = "-";
                        $grade = "-";
                        $units = 0;
                        $points="-";
                    }
                    if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I" || $grade == "A0" || $grade == "A1") {
                        $pdf->SetFillColor(169,169,169);
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1,0,'L',1);
                    }
                    else {
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1);

                    }
                    //$pdf->Cell($wdth,6,$tmarks."-".$grade,1);
                    //$pdf->SetFillColor(0xff,0xff,0x99);
                    //$pdf->SetFillColor(11, 47, 132);
                    //$pdf->Cell($wdth,6,$tmarks."-".$grade,1,0,'L',$fill);
                    //$pdf->SetFillColor(255, 255, 255);
                }
                $per_fail=round(($number_fail/$number_course)*100,1);
                if($per_fail>=60)
                {
                    $gparemarks = "DISCO";
                }
                else if($per_fail >= 40 && $per_fail < 60)
                {
                    $gparemarks = "RS"; //Repeat Semester
                }
                else if($countsupp>0) {
                    $gparemarks = $number_supp."SUPP";
                }
                else if($countincomplete>0) {
                    $gparemarks = "INC";
                }
                else if($countspecial>0) {
                    $gparemarks = $countspecial."SPECIAL";
                }
                else {
                    $gparemarks = "PASS";
                }
                //$pdf->Cell(13,6,$tunits,1);$pdf->Cell(13,6,$tpoints,1);
                $pdf->Cell(15,6,$gpa,1);$pdf->Cell(24,6,$gparemarks,1);
                $pdf->Ln(6);



                //second sit
                $pdf->Cell(75, 6, "", 0);
                $number_course=0;
                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                    $units = $cs['units'];
                    $number_course+=1;
                $student_course = $db->getStudentExamCourseBySitting($regNumber, $semesterID, $courseID,2);

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
                    else if(empty($cwk))
                    {
                        $grade="A0";
                        $gradePoint=0;
                    }
                    else if(empty($sfe))
                    {
                        $grade="A1";
                        $gradePoint=0;
                    }
                    else if ($cwk < $passCourseMark)
                    {
                        $grade = "N"; //N means course repeat
                        $gradePoint = 0;
                    }
                    else if ($sfe < $passFinalMark)
                    {
                        $grade = "E"; //Supplementary Exam
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

                    if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I") {
                        $number_fail2 += 1;
                    }
                    if (($grade == "D") || ($grade == "F") || ($grade == "E") || ($grade == "N") || ($grade == "A0")) {
                        $countcarryover = $countcarryover + 1;
                        $numbercarryover += 1;
                    }
                    else if($grade == "A1")
                    {
                        $countspecial+=1;
                    }
                    else if($grade=="N")
                    {
                        $countrepeat+=1;
                    }
                    else
                    {
                        $countpass = $countpass + 1;
                        $number_pass+=1;
                    }

                } else {
                    $cwk = " ";
                    $sfe = " ";
                    $tmarks = " ";
                    $grade = " ";
                    $units = 0;
                    $points=" ";
                }

                    if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I") {
                        $pdf->SetFillColor(169,169,169);
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1,0,'L',1);
                    }
                    else {
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1);

                    }
            }
                $per_fail2=round(($number_fail2/$number_course)*100,1);
                if($number_fail2 >=3)
                {
                    $gparemarks="STOP&CLEAR";
                }
                else if($gpa<2)
                {
                    $gparemarks="STOP&CLEAR";
                }
                else if($per_fail2>=60)
                {
                    $gparemarks = "DISCO";
                }
                else if($per_fail >= 40 && $per_fail < 60)
                {
                    $gparemarks = "RS"; //Repeat Semester
                }
                else if($countcarryover > 0) {
                    $gparemarks = $numbercarryover."CO";
                }
                else if($countincomplete>0) {
                    $gparemarks = $countspecial." SPECIAL";
                }
                else
                {
                    $gparemarks = "PASS";
                }
                //end of second sit

                //overall gpa
                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                    $units = $cs['units'];
                    $number_course+=1;
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
                        else if(empty($cwk))
                        {
                            $grade="A0";
                            $gradePoint=0;
                        }
                        else if(empty($sfe))
                        {
                            $grade="A1";
                            $gradePoint=0;
                        }
                        else if ($cwk < $passCourseMark)
                        {
                            $grade = "N"; //N means course repeat
                            $gradePoint = 0;
                        }
                        else if ($sfe < $passFinalMark)
                        {
                            $grade = "E"; //Supplementary Exam
                            $gradePoint = 0;
                        } else {
                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                            $grade = $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        }
                        $points=$gradePoint*$units;
                        $tpointsg += $points;
                        $tunitsg += $units;

                        $gpa = $db->getGPA($tpointsg, $tunitsg);

                        if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I" || ($grade == "A0")) {
                            $number_failg += 1;
                        }
                        if (($grade == "D") || ($grade == "F") || ($grade == "E") || ($grade == "N") || ($grade == "A0")) {
                            $countcarryoverg = $countcarryoverg + 1;
                            $numbercarryoverg += 1;
                        }
                        else if($grade == "A1")
                        {
                            $countspecialg +=1;
                        }
                        else if($grade=="N")
                        {
                            $countrepeatg +=1;
                        }
                        else
                        {
                            $countpassg = $countpassg + 1;
                            $number_pass+=1;
                        }

                    } else {
                        $cwk = " ";
                        $sfe = " ";
                        $tmarks = " ";
                        $grade = " ";
                        $units = 0;
                        $points=" ";
                    }
                }
                $per_failg=round(($number_failg/$number_course)*100,1);
                if($number_failg >=3)
                {
                    $gparemarksg="STOP&CLEAR";
                }
                else if($gpa<2)
                {
                    $gparemarksg="STOP&CLEAR";
                }
                else if($per_failg>=60)
                {
                    $gparemarksg = "DISCO";
                }
                else if($per_failg >= 40 && $per_failg < 60)
                {
                    $gparemarksg = "RS"; //Repeat Semester
                }
                else if($countcarryoverg > 0) {
                    $gparemarksg = $numbercarryoverg."CO";
                }
                else if($countincompleteg>0) {
                    $gparemarksg = $countspecialg." SPECIAL";
                }
                else
                {
                    $gparemarksg = "PASS";
                }

                $pdf->Cell(15,6,$gpa,1);$pdf->Cell(24,6,$gparemarksg,1);
            $pdf->Ln();
                //end of gpa

                if($count%8==0) {
                    $pdf->AddPage("L");
                    $pdf->setFont('Arial', '', 8);
                    $today = date('M d,Y');
                    //Logo .
                    $pdf->setFont('Arial', 'B', 20);
                    $programme = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
                    $semester = $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID);

                    $schoolID = $db->getData("programmes", "schoolID", "programmeID", $programmeID);
                    $schoolName = $db->getData("schools", "schoolName", "schoolID", $schoolID);
                    $pdf->Banner($organizationName, $organizationPicture, $schoolName, $programme, $sYear, strtoupper($semester));
                    //Arial bold 15
                    $pdf->setFont('Arial', 'B', 14);

                    $header = array('No', 'Reg.Number', 'Name', 'Courses', 'GPA', 'Remark');
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell('10', 6, '');
                    $pdf->Ln(30);

                    $course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
                    $number = 0;
                    foreach ($course as $cs) {
                        $number++;
                    }
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->BasicTable($header);
                    $pdf->SetFont('Arial', '', 9);
                    $course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
                    $pdf->Cell(75, 6, "", 1);
                    $wdth = 170 / $number;
                    foreach ($course as $cs) {
                        $pdf->Cell($wdth, 6, $cs['courseCode'] . "-" . $cs['units'], 1);

                    }
                    $pdf->Ln();
                }
            }
            $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);

           /* if($gender=="M"){
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

            $tlgpa+=$gpa;*/

        }
        //$pdf->SetAutoPageBreak(true);
        $pdf->AddPage("L");
        $pdf->setFont('Arial', '', 8);
        $today = date('M d,Y');
        //Logo .
        $pdf->setFont('Arial', 'B', 20);
        $programme = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
        $semester = $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID);

        $schoolID = $db->getData("programmes", "schoolID", "programmeID", $programmeID);
        $schoolName = $db->getData("schools", "schoolName", "schoolID", $schoolID);
        $pdf->Banner($organizationName, $organizationPicture, $schoolName, $programme, $sYear, strtoupper($semester));
        $pdf->Ln(30);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,6,"GRADING SYSTEM");
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
        $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
        $gradeCode=$db->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
        $pdf->Cell(30,6,'Grade Code',1);
        foreach($gradeCode as $grade)
        {
            $pdf->Cell(30,6,$grade['gradeCode'],1);
        }
        $pdf->Ln(6);
        $pdf->Cell(30,6,'Grade Points',1);
        foreach($gradeCode as $grade)
        {
            $pdf->Cell(30,6,$grade['gradePoints'],1);
        }
        $pdf->Ln(6);
        $pdf->Cell(30,6,'Marks Range',1);
        foreach($gradeCode as $grade)
        {
            $pdf->Cell(30,6,$grade['startMark'].'-'.$grade['endMark'],1);
        }
       /* $pdf->Ln(10);
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
         $pdf->Cell(35,6,"Other Remarks",1);
        $pdf->Cell(30,6,"Maximum GPA",1);
        $pdf->Cell(30,6,"Minimum GPA",1,0,'L');
        $pdf->Cell(30,6,"Average GPA",1,0,'L');
        $pdf->Cell(30,6,"Std Deviation",1,0,'L');
        $pdf->Ln(6);
        /*  $pdf->Cell(25,6,$present,1);
          $pdf->Cell(35,6,$absent,1);
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
        }
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
        }*/

        $pdf->AddPage("L");
        $pdf->setFont('Arial', '', 8);
        $today = date('M d,Y');
        //Logo .
        $pdf->setFont('Arial', 'B', 20);
        $programme = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
        $semester = $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID);

        $schoolID = $db->getData("programmes", "schoolID", "programmeID", $programmeID);
        $schoolName = $db->getData("schools", "schoolName", "schoolID", $schoolID);
        $pdf->Banner($organizationName, $organizationPicture, $schoolName, $programme, $sYear, strtoupper($semester));
        $pdf->Ln(30);

        /*        $pdf->Ln(10);*/
        $pdf->Cell(30,6,"Course Code",1);$pdf->Cell(130,6,"Course Name",1);$pdf->Cell(30,6,"Course Type",1); $pdf->Cell(20,6,"Credits",1);$pdf->Cell(70,6,"Instructor",1);
        $pdf->Ln(6);
        $course=$db->getCourseCredit($programmeID,$semesterID,$studyYear,$academicYearID);
        foreach ($course as $cs) {
            $courseID = $cs['courseID'];
            $instructor = $db->getRows('instructor_course', array('where' => array('courseID' => $courseID, 'batchID' => $batchID, 'semesterSettingID' => $semesterID), 'order_by' => 'courseID ASC'));
            if (!empty($instructor)) {
                foreach ($instructor as $i) {
                    $instructorID = $i['instructorID'];
                    $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                }
            } else {
                $instructorName = "Not assigned";
            }

            $pdf->Cell(30, 6, $cs['courseCode'], 1);
            $pdf->Cell(130, 6, $cs['courseName'], 1);
            $pdf->Cell(30, 6, $db->getData('course_type', 'courseTypeCode', 'courseTypeID', $cs['courseTypeID']), 1);
            $pdf->Cell(20, 6, $cs['units'], 1);
            $pdf->Cell(70, 6, $instructorName, 1);
            $pdf->Ln();
        }

    }

    $pdf->Output();
}
?>
