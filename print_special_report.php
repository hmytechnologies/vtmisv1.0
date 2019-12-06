<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
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
    $academicYearID=$_REQUEST['aid'];


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
            $w = array(10,50,10,160,15,15,20);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }
    }
    $pdf=new PDF();
    $pdf->AliasNbPages();

    $pdf->AddPage("L");
    $pdf->setFont('Arial', '', 8);
    $today=date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 14);
    $pdf->Text(10,10,strtoupper($organizationName));
    //Arial bold 15
    $pdf->setFont('Arial', 'B', 14);
    //get cwork and final exam marks per programme
    $sfemaxmarks=$db->getProgrammeMaxMarks($programmeID,2);
    $cwkmaxmarks=$db->getProgrammeMaxMarks($programmeID,1);
    $suppmaxmarks=$db->getProgrammeMaxMarks($programmeID,3);

    $pdf->Text(10,20,'SUPPLEMENTARY EXAMINATION REPORT-Weights('.$suppmaxmarks.')');
    $pdf->Text(10,26,$sYear." ".$db->getData("programmes","programmeName","programmeID",$programmeID)." ".$db->getData("academic_year","academicYear","academicYearID",$academicYearID)." ".$db->getData("batch","batchName","batchID",$batchID));
    $header = array('No','Registration Number','Sex','Courses','Credits','Points','Remark');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell('10',6,'');
    $pdf->Ln(25);

    $student = $db->getStudentSpecialProgramme($programmeID,$academicYearID,$studyYear,$batchID);
    if(!empty($student)) {

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->BasicTable($header);
        $pdf->SetFont('Arial', '', 11);

        $count = 0;

        $tsup = 0;
        $tpass = 0;
        $tfail = 0;
        $tothers = 0;
        $tsupf = 0;
        $tpassf = 0;
        $tfailf = 0;
        $tothersf = 0;

        $fclass = 0;
        $uclass = 0;
        $lcass = 0;
        $pclass = 0;
        $ffclass = 0;
        $fclassf = 0;
        $uclassf = 0;
        $lcassf = 0;
        $pclassf = 0;
        $ffclassf = 0;

        $maxgpa = 0;
        $mingpa = 6;
        $gpaarr = array();
        $tlgpa = 0;
        foreach ($student as $st) {
            $count++;
            $regNumber = $st['regNumber'];
            $course = $db->getAnnualSpecialCourseCredit($regNumber, $programmeID, $academicYearID);
            $number = 0;
            foreach ($course as $cs) {
                $number++;
            }
            $wdth = 160 / $number;
            $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));

            foreach ($studentDetails as $std) {
                $fname = $std['firstName'];
                $mname = $std['middleName'];
                $lname = $std['lastName'];
                $name = "$fname $mname $lname";
                $gender = $std['gender'];
                $statusID = $std['statusID'];
                $pdf->Cell(10, 6, $count, 1);
/*                $pdf->Cell(60, 6, $name, 1);*/
                $pdf->Cell(50, 6, $regNumber, 1);
                $pdf->Cell(10, 6, $gender, 1);

                $course = $db->getAnnualSpecialCourseCredit($regNumber, $programmeID, $academicYearID);
                $tunits = 0;
                $tpoints = 0;
                $countpass = 0;
                $countsupp = 0;
                $countincomplete = 0;

                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                    $units = $cs['units'];
                    $semesterID = $cs['semesterSettingID'];
                    $courseCode = $cs['courseCode'];
                    $student_course = $db->getStudentExamCourse($regNumber, $semesterID, $courseID);
                    if (!empty($student_course)) {
                        $cwk = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 1));
                        $sfe = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 2));
                        $sup = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 3));
                        $spc = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 4));
                        $prj = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 5));
                        $pt = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 6));

                        $totalMarks = $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);

                        $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);

                        $points = $gradePoint * $units;

                        $remarks = $db->courseRemarks($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        $grade = $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);

                        $tpoints += $points;
                        $tunits += $units;

                        $gpa = $db->getGPA($tpoints, $tunits);


                        if (($grade == "D") or ($grade == "F") or ($grade == "E") or ($grade == "I")) {
                            $countsupp = $countsupp + 1;
                            $gparemarks = "Supp";
                        } else {
                            $countpass = $countpass + 1;
                            $gparemarks = "Pass";
                        }


                    } else {
                        $cwk = "-";
                        $sfe = "-";
                        $totalMarks = "-";
                        $grade = "-";
                        $units = "-";
                        $points = "-";
                    }
                    $pdf->Cell($wdth, 6, $courseCode."[CE-".$cwk."-SPC-".$spc."]-".$grade, 1);
                }
                $pdf->Cell(15, 6, $tunits, 1);
                $pdf->Cell(15, 6, $tpoints, 1);
                $pdf->Cell(20, 6, $gparemarks, 1);

                $pdf->Ln();
            }


            if ($gender == "M") {
                if ($statusID == 1) {
                    if ($gparemarks == "Pass")
                        $tpass += 1;
                    else if ($gparemarks == "Supp")
                        $tsup += 1;
                    else if ($gparemarks == "Fail")
                        $tfail += 1;
                } else {
                    $tothers += 1;
                }

            } else {
                if ($statusID == 1) {
                    if ($gparemarks == "Pass")
                        $tpassf += 1;
                    else if ($gparemarks == "Supp")
                        $tsupf += 1;
                    else if ($gparemarks == "Fail")
                        $tfailf += 1;
                } else {
                    $tothersf += 1;
                }
            }
        }
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(50, 6, "SUMMARY OF PERFOMANCE STATISTICS");
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(30, 6, 'Remarks', 1);
        $pdf->Cell(30, 6, "Pass", 1);
        $pdf->Cell(30, 6, "Supp", 1);
        $pdf->Cell(30, 6, "Fail", 1);
        $pdf->Cell(30, 6, "Incomplete", 1, 0, 'C');
        $pdf->Cell(40, 6, "Other Remarks", 1, 0, 'C');

        $pdf->Ln(6);
        $pdf->Cell(30, 6, 'Male', 1);
        $pdf->Cell(30, 6, $tpass, 1);
        $pdf->Cell(30, 6, $tsup, 1);
        $pdf->Cell(30, 6, $tfail, 1);
        $pdf->Cell(30, 6, 0, 1, 0, 'C');
        $pdf->Cell(40, 6, $tothers, 1, 0, 'C');

        $pdf->Ln(6);
        $pdf->Cell(30, 6, 'Female', 1);
        $pdf->Cell(30, 6, $tpassf, 1);
        $pdf->Cell(30, 6, $tsupf, 1);
        $pdf->Cell(30, 6, $tfailf, 1);
        $pdf->Cell(30, 6, 0, 1, 0, 'C');
        $pdf->Cell(40, 6, $tothersf, 1, 0, 'C');

        $pdf->Ln(6);
        $pdf->Cell(30, 6, 'Subtotal', 1);
        $pdf->Cell(30, 6, $tpass + $tpassf, 1);
        $pdf->Cell(30, 6, $tsup + $tsupf, 1);
        $pdf->Cell(30, 6, $tfail + $tfailf, 1);
        $pdf->Cell(30, 6, 0, 1, 0, 'C');
        $pdf->Cell(40, 6, $tothers + $tothersf, 1, 0, 'C');
    }
    $pdf->Output();
}
?>
