<?php
session_start();
if($_REQUEST['action']=="getPDF") {
    include 'DB.php';
    $db = new DBHelper();

    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $image = "img/" . $org['organizationPicture'];
        }
    } else {
        $organizationName = "Soft Dev Academy";
        $organizationCode = "SDVA";
        $image = "img/SkyChuo.png";
    }

    require('fpdf.php');

    class PDF extends FPDF
    {
        function Banner($organizationName, $image)
        {
            $today = date('M d,Y');
            //Logo . 
            $this->setFont('Arial', 'B', 13);

            $this->Image($image, 80, 0, 40.98, 35.22);
            $this->Text(60, 40, strtoupper($organizationName));
            $this->setFont('Arial', 'B', 14);
        }
        function SetCol($col)
        {
            // Set position at a given column
            $this->$col = $col;
            $x = 10 + $col * 65;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }

        function Footer()
        {
            $today2 = date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            //Page number
            $this->Cell(0, 0, 'Page ' . $this->PageNo() . 'of{nb}', 0, 1, 'C');

            $this->Cell(300, 0, 'Printed Date ' . $today2 . ' Zanzibar', 0, 1, 'C');

        }

        function BasicTable($header)
        {
            $w = array(10, 60, 40, 40, 20, 25, 30);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
            $this->Ln();

        }

        function CourseTable($header)
        {
            $w = array(75, 80, 40);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'L', 0);
            $this->Ln();

        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();

    $courseID = $db->decrypt($_REQUEST['cid']);
    $academicYearID = $db->decrypt($_REQUEST['aid']);
    $programmeLevelID = $db->decrypt($_REQUEST['lid']);
    $programmeID= $db->decrypt($_REQUEST['proid']);

    $course = $db->getCourseInfo($courseID,$programmeID);
    foreach ($course as $std) {
        // $count++;
        // $courseID = $std['courseID'];
        //  $courseCode = $std['courseCode'];
        // $courseName = $std['courseName'];
        // $courseTypeID = $std['courseTypeID'];
        //  $programmeLevelID = $std['programmeLevelID'];
        
        //  $classNumber = $std['classNumber'];
        $staffID = $std['staffID'];
        // $cpcourseID = $std['centerProgrammeCourseID'];
        
        // $centerID=$std['centerID'];
    }
    $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
    if (!empty($course)) {
        foreach ($course as $c) {
            $courseCode = $c['courseCode'];
            $courseName = $c['courseName'];
            $courseTypeID = $c['courseTypeID'];
        }
    }
    $pdf->AddPage();
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 20);
    //$pdf->Text(50, 10, strtoupper($organizationName));
    $levelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
    $academicYear = $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID);
    $programmeName = $db->getData("programmes", "programmeName", "programmeID", $programmeID); 
    $pdf = new PDF();
    $pdf->AliasNbPages();

    $pdf->AddPage();
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 15);
    $pdf->Banner($organizationName, $image);
    $pdf->setFont('Arial', 'B', 13);
  
    $pdf->Ln(35);
    $pdf->setFont('Arial', 'B', 14);
    $pdf->Text(10, 50,'Examination Results-'. $academicYear);
    $pdf->Line(10,52,205,52);

    $header = array('No', 'Exam Number','Gender','Score','Grade', 'Remarks');
    $courseHeader = array('Subject Name', 'Subject Type', 'Trade Level');
    $pdf->Ln(10);

    //course details
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->CourseTable($courseHeader);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(75, 6, $courseName, 1, 0);
    $pdf->Cell(80, 6, $db->getData('course_type','courseType','courseTypeID',$courseTypeID), 1, 0);
    $pdf->Cell(40, 6, $levelName, 1, 0);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell('10', 6, '');
    $pdf->Ln(10);

    $tcwk = 0;
    $tsfe = 0;
    $maxcwk = 0;
    $maxsfe = 0;
    $mincwk = 100;
    $minsfe = 100;
    $cwkarr = array();
    $sfearr = array();
    $gA = 0;
    $gB = 0;
    $gC = 0;
    $gD = 0;
    $gF = 0;
    $tpass = 0;
    $tfail = 0;

    $count = 0;
    $gAm = 0;
    $gBm = 0;
    $gCm = 0;
    $gDm = 0;
    $gFm = 0;

    $gAf = 0;
    $gBf = 0;
    $gCf = 0;
    $gDf = 0;
    $gFf = 0;

    $tmpass = 0;
    $tmfail = 0;
    $tfpass = 0;
    $tffail = 0;
    //centerName
    $centerProgramme = $db->getCenterExamProgramme($courseID,$academicYearID,$programmeLevelID);
    if (!empty($centerProgramme)) {
        foreach ($centerProgramme as $pg) {
            $centerID = $pg['centerID'];
            $pdf->SetFont('Arial', 'B', 12);
            //$centerName = $db->getData("center_registration", "centerName", "centerRegitrationID", $centerID);
            $pdf->Cell(180, 6, "Center Name:" . $db->getData('center_registration', 'centerName', 'centerRegistrationID', $centerID), 0, 0, 'L');
            $pdf->Ln(6);
            $student = $db->getCenterStudentExamList($centerID,$courseID, $academicYearID, $programmeLevelID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                foreach ($student as $st) {
                    $count++;
                    $examNumber = $st['examNumber'];
                    $regNumber = $st['regNumber'];
                    $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'registrationNumber ASC'));
                    foreach ($studentDetails as $std) {
                        # code...
                        $fname = $std['firstName'];
                        $mname = $std['middleName'];
                        $lname = $std['lastName'];
                        $name = $fname . " " . $mname[0] . " " . $lname;
                        $gender=$std['gender'];

                        $final_result = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));

                        $exam_category_marks = $db->getTermCategorySetting();
                        if (!empty($exam_category_marks)) {
                            foreach ($exam_category_marks as $gd) {
                                $mMark = $gd['mMark'];
                                $pMark = $gd['passMark'];
                                $wMark = $gd['wMark'];
                            }
                        }

                        $grade=$db->calculateTermGrade($final_result);

                        if ($grade == "A" || $grade == "B" || $grade == "C"|| $grade == "D") {
                            $tpass++;
                        } else {
                            $tfail++;
                        }

                        if ($grade == "A") {
                            $gA++;
                        } elseif ($grade == "B") {
                            $gB++;
                        } elseif ($grade == "C") {
                            $gC++;
                        } elseif ($grade == "D") {
                            $gD++;
                        } else {
                            $gF++;
                        }

                        if ($gender=='M') {
                            if ($grade == "A" || $grade == "B" || $grade == "C"|| $grade == "D") {
                                $tmpass++;
                            } else {
                                $tmfail++;
                            }

                            if ($grade=="A") {
                                $gAm++;
                            } elseif ($grade=="B") {
                                $gBm++;
                            } elseif ($grade == "C") {
                                $gCm++;
                            } elseif ($grade == "D") {
                                $gDm++;
                            } else {
                                $gFm++;
                            }
                        } else {
                            if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
                                $tfpass++;
                            } else {
                                $tffail++;
                            }

                            if ($grade == "A") {
                                $gAf++;
                            } elseif ($grade == "B") {
                                $gBf++;
                            } elseif ($grade == "C") {
                                $gCf++;
                            } elseif ($grade == "D") {
                                $gDf++;
                            } else {
                                $gFm++;
                            }
                        }

                        $pdf->setFont('Arial', '', 10);
                        $pdf->Cell(10, 6, $count, 1);
                        $pdf->Cell(60, 6, $examNumber, 1, 0);
                        $pdf->Cell(40, 6, $gender, 1, 0, 'C');
                        $pdf->Cell(40, 6, $final_result, 1, 0, 'C');
                        $pdf->Cell(20, 6, $db->calculateTermGrade($final_result), 1, 0, 'C');
                        $pdf->Cell(25, 6, $db->courseTermRemarks($final_result), 1, 0, 'C');
                        $pdf->Ln();
                    }
                }
            }
            $pdf->Ln(6);
        }
    }

    $avgcwk=$tcwk/$count;
    $avgsfe=$tsfe/$count;

    // $sdvcwk=$db->standDeviation($termScore);
    // $sdvsfe=$db->standDeviation($sfearr);
    // $cwkpresent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,1,1);
    // $cwkabsent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,1,0);
    // $sfepresent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,2,1);
    // $sfeabsent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,2,0);
    /* $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(50,6,"Summary");
    $pdf->Ln(6);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(30,6,'',1);
    $pdf->Cell(25,6,"Present",1);
    $pdf->Cell(25,6,"Absent",1);
    $pdf->Cell(25,6,"Maximum",1);
    $pdf->Cell(25,6,"Minimum",1,0,'C');
    $pdf->Cell(25,6,"Average",1,0,'C');
    $pdf->Cell(30,6,"Std Deviation",1,0,'C');
    $pdf->Ln(6);
    $pdf->Cell(30,6,'CA',1);
    $pdf->Cell(25,6,$cwkpresent,1);
    $pdf->Cell(25,6,$cwkabsent,1);
    $pdf->Cell(25,6,$maxcwk,1);
    $pdf->Cell(25,6,$mincwk,1,0,'C');
    $pdf->Cell(25,6,number_format($avgcwk,2),1,0,'C');
    $pdf->Cell(30,6,number_format($sdvcwk,2),1,0,'C');
    $pdf->Ln(6);
    $pdf->Cell(30,6,'UE',1);
    $pdf->Cell(25,6,$sfepresent,1);
    $pdf->Cell(25,6,$sfeabsent,1);
    $pdf->Cell(25,6,$maxsfe,1);
    $pdf->Cell(25,6,$minsfe,1,0,'C');
    $pdf->Cell(25,6,number_format($avgsfe,2),1,0,'C');
    $pdf->Cell(30,6,number_format($sdvsfe,2),1,0,'C'); */
    //Grade Count
    //percent
    $ppass=round(($tpass/($tpass+$tfail))*100,2);
    $pfail = round(($tfail / ($tpass + $tfail)) * 100, 2);

    $pA = round(($gA / ($gA+$gB+$gC+$gD+$gF)) * 100, 2);
    $pB = round(($gB / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    $pC = round(($gC / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    $pD = round(($gD / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    $pF = round(($gF / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    //end percent
    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(50,6,"Overall Summary");

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(25, 6, 'Grade', 1);
    $pdf->Cell(24, 6, 'A', 1,0,'C');
    $pdf->Cell(24, 6, "B", 1, 0, 'C');
    $pdf->Cell(24, 6, "C", 1, 0, 'C');
    $pdf->Cell(24, 6, "D", 1, 0, 'C');
    $pdf->Cell(24, 6, "F", 1, 0, 'C');
    $pdf->Cell(24, 6, "Pass", 1, 0, 'C');
    $pdf->Cell(24, 6, "Fail", 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(25, 6, "Gender", 1);
    $pdf->Cell(12, 6, 'M', 1);
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Cell(12, 6, "M", 1, 0, 'C');
    $pdf->Cell(12, 6, "F", 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(25, 6, "SubTotal", 1);
    $pdf->Cell(12, 6, $gAm, 1);
    $pdf->Cell(12, 6, $gAf, 1, 0, 'C');
    $pdf->Cell(12, 6, $gBm, 1, 0, 'C');
    $pdf->Cell(12, 6, $gBf, 1, 0, 'C');
    $pdf->Cell(12, 6, $gCm, 1, 0, 'C');
    $pdf->Cell(12, 6, $gCf, 1, 0, 'C');
    $pdf->Cell(12, 6, $gDm, 1, 0, 'C');
    $pdf->Cell(12, 6, $gDf, 1, 0, 'C');
    $pdf->Cell(12, 6, $gFm, 1, 0, 'C');
    $pdf->Cell(12, 6, $gFf, 1, 0, 'C');
    $pdf->Cell(12, 6, $tmpass, 1, 0, 'C');
    $pdf->Cell(12, 6, $tfpass, 1, 0, 'C');
    $pdf->Cell(12, 6, $tmfail, 1, 0, 'C');
    $pdf->Cell(12, 6, $tffail, 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(25, 6, "Total(%)", 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(24, 6, $gA . "(" . $pA . "%)", 1);
    $pdf->Cell(24, 6, $gB . "(" . $pB . "%)", 1, 0, 'C');
    $pdf->Cell(24, 6, $gC . "(" . $pC . "%)", 1, 0, 'C');
    $pdf->Cell(24, 6, $gD . "(" . $pD . "%)", 1, 0, 'C');
    $pdf->Cell(24, 6, $gF . "(" . $pF . "%)", 1, 0, 'C');
    $pdf->Cell(24, 6, $tpass."(".$ppass."%)", 1, 0, 'C');
    $pdf->Cell(24, 6, $tfail."(". $pfail."%)", 1, 0, 'C');

   

   /*  $pdf->Ln(6);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,6,$gA,1);
    $pdf->Cell(20,6,$gBb,1);
    $pdf->Cell(20,6,$gB,1,0,'C');
    $pdf->Cell(20,6,$gC,1,0,'C');
    $pdf->Cell(20,6,$gD,1,0,'C');
    $pdf->Cell(20,6,$gE,1,0,'C');
    $pdf->Cell(20,6,$gI,1,0,'C');
    $pdf->Cell(22,6,$tpass,1,0,'C');
    $pdf->Cell(22,6,$tfail,1,0,'C'); */

    $pdf->Ln(20);
    /*$pdf->SetFont('Arial','',12);
    $pdf->Cell(100,6,"Internal Examiner's Name:.....................................");$pdf->Cell(100,6,"External Examiner's Name:.................................");
    $pdf->Ln(10);
    $pdf->Cell(100,6,"Internal Examiner Signature:...................Date............");$pdf->Cell(100,6,"External Examiner Signature:...............Date...........");*/

    $pdf->SetFont('Arial','',12);

    //instructor name

    /* $inID=$db->getRows("instructor_course",array('where'=>array('courseID'=>$courseID,'semesterSettingID'=>$semesterSettingID,'batchID'=>$batchID)));
    if(!empty($inID))
    {
        foreach($inID as $ins)
        {
            $instructorID=$ins['instructorID'];
        }
    }
    else
    {
        $instructorID=0;
    } */

    // if($instructorID !=0) {
        $inName = $db->getRows("instructor", array('where' => array('instructorID' => $staffID)));
        if (!empty($inName)) {
            foreach ($inName as $inst) {
                $fname = $inst['firstName'];
                $lname = $inst['lastName'];
                $salutation = $inst['salutation'];
                $instructorName = "$salutation $fname $lname";
            }
        }
    // }
    // else
    // {
    //     $instructorName="___________________________________________";
    // }
    $pdf->Cell(100,6,$instructorName);$pdf->Cell(100,6,"_____________________________");
    $pdf->Ln(6);
    $pdf->Cell(100,6,"Examination Officer's Name");$pdf->Cell(100,6,"Signature");
    $pdf->Ln(10);
    $pdf->Cell(100,6,"______________________________________");$pdf->Cell(100,6,"_____________________________");
    $pdf->Ln(8);
    $pdf->Cell(100,6,"Date approved by Director of Training");$pdf->Cell(100,6,"Signature");
    /* $pdf->Ln(10);
    $pdf->Cell(100,6,"______________________________________");$pdf->Cell(100,6,"______________________________"); */
    /* $pdf->Ln(6);
    $pdf->Cell(100,6,"Date Approved by Academic Master");$pdf->Cell(100,6,"Signature"); */

//}

    $pdf->Output();
}
?>
