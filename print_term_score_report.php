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
    $courseID = $_REQUEST['cid'];
    $academicYearID = $_REQUEST['aid'];
    $levelID = $_REQUEST['levelID'];
    $centerID=$_REQUEST['centerID'];
    $termID=$_REQUEST['termID'];

    if($termID==1)
        $term="First Term";
    else 
        $termName="Second Term";

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
            $this->col = $col;
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
            $w = array(10, 30, 60, 15, 15, 20, 15, 30);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
            $this->Ln();

        }

        function CourseTable($header)
        {
            $w = array(30, 100, 40, 25);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'L', 0);
            $this->Ln();

        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
    if (!empty($course)) {
        foreach ($course as $c) {
            $courseCode = $c['courseCode'];
            $courseName = $c['courseName'];
            $courseTypeID = $c['courseTypeID'];
            $credits=$c['units'];
        }
    }
    $pdf->AddPage(P);
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 20);
    //$pdf->Text(50, 10, strtoupper($organizationName));
    $centerName = $db->getData("center_registration", "centerName", "centerRegistrationID", $centerID);
    $levelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
    $academicYear = $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); 
    $pdf = new PDF();
    $pdf->AliasNbPages();

    $pdf->AddPage();
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 15);
    $pdf->Banner($organizationName, $image);
    //$pdf->Text(50, 10, strtoupper($organizationName));
    $pdf->setFont('Arial', 'B', 13);
    $pdf->Text(50, 45, strtoupper($db->getData("center_registration", "centerName", "centerRegistrationID", $centerID)));
    //Arial bold 15
    //Arial bold 15
    $pdf->Ln(35);
    $pdf->setFont('Arial', 'B', 14);
    $pdf->Text(10, 50, $termName.' Results '. $academicYear."-".$levelName);
    $pdf->Line(10,52,205,52);

    $header = array('No', 'Reg.Number', 'Name', 'CA', 'UE', 'Total/100', 'Grade', 'Remarks');
    $courseHeader = array('Course Code', 'Course Name', 'Course Type', 'Credits');
    $pdf->Ln(10);

    //course details
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->CourseTable($courseHeader);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(30, 6, $courseCode, 1, 0);
    $pdf->Cell(100, 6, $courseName, 1, 0);
    $pdf->Cell(40, 6,$db->getData("course_type","courseType","courseTypeID",$courseTypeID), 1, 0);
    $pdf->Cell(25,6,$credits, 1, 0);

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
    $gBb = 0;
    $gB = 0;
    $gC = 0;
    $gD = 0;
    $gE = 0;
    $gI = 0;
    $gF = 0;
    $tpass = 0;
    $tfail = 0;

    $count = 0;
    //programmes
/*    $programmes = $db->getRows("courseprogramme", array('where' => array('courseID' => $courseID, 'semesterSettingID' => $semesterSettingID, 'batchID' => $batchID)));*/
    $programmes = $db->getCourseExamProgramme($courseID,$semesterSettingID);
    if (!empty($programmes)) {
        foreach ($programmes as $pg) {
            $programmeID = $pg['programmeID'];
            $programmeName = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
            $pdf->Cell(180, 6, "Programme Name:" . $programmeName, 0, 0, 'L');
            $pdf->Ln(6);
            $student = $db->getStudentExamResultReport($programmeID,$courseID,$semesterSettingID,$batchID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);


                foreach ($student as $st) {
                    $count++;
                    $regNumber = $st['regNumber'];
                    $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'registrationNumber ASC'));
                    foreach ($studentDetails as $std) {
                        # code...
                        $fname = $std['firstName'];
                        $mname = $std['middleName'];
                        $lname = $std['lastName'];
                        $name = $fname . " " . $mname[0] . " " . $lname;

                        $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                        $sfe = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 2));
                        $sup = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 3));
                        $spc = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 4));
                        $prj = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 5));
                        $pt = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 6));


                        /*if (!empty($sup))
                            $sfe = $sup;
                        else if (!empty($spc))
                            $sfe = $spc;
                        else if (!empty($prj))
                            $sfe = $prj;
                        else if (!empty($pt))
                            $sfe = $pt;
                        else
                            $sfe = $sfe;*/

                        if (!empty($sup)) {
                            $sfe = $sup;
                            $cwk = "NAN";
                        } else if (!empty($spc))
                            $sfe = $spc;
                        else if (!empty($prj)) {
                            $cwk = "NAN";
                            $sfe = $prj;
                        } else if (!empty($pt)) {
                            $sfe = $pt;
                            $cwk = "NAN";
                        } else
                            $sfe = $sfe;

                        $cwkarr[] = $cwk;
                        $sfearr[] = $sfe;

                        if ($maxcwk < $cwk)
                            $maxcwk = $cwk;

                        if ($mincwk > $cwk)
                            $mincwk = $cwk;

                        if ($maxsfe < $sfe)
                            $maxsfe = $sfe;

                        if ($minsfe > $sfe)
                            $minsfe = $sfe;

                        $tcwk += $cwk;
                        $tsfe += $sfe;


                        $grade = $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        if ($grade == "A")
                            $gA++;
                        else if ($grade == "B+")
                            $gBb++;
                        else if ($grade == "B")
                            $gB++;
                        else if ($grade == "C")
                            $gC++;
                        else if ($grade == "D")
                            $gD++;
                        else if ($grade == "E")
                            $gE++;
                        else if ($grade == "I")
                            $gI++;

                        /*if($db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)=="PASS")
                            $tpass++;
                        else
                            $tfail++;*/

                        if ($grade == "A" || $grade == "B+" || $grade == "B" || $grade == "C")
                            $tpass++;
                        else
                            $tfail++;
                        //$w = array(10,40,50,15,15,20,15,30);

                        $pdf->setFont('Arial', '', 10);
                        $pdf->Cell(10, 6, $count, 1);
                        /*            $pdf->Cell(50,6,html_entity_decode($name,ENT_COMPAT,'UTF-8'),1);*/
                        $pdf->Cell(30, 6, $regNumber, 1, 0);
                        $pdf->Cell(60, 6, $name, 1, 0);
                        $pdf->Cell(15, 6, $cwk, 1, 0, 'C');
                        $pdf->Cell(15, 6, $sfe, 1, 0, 'C');
                        $pdf->Cell(20, 6, $db->calculateTotal($cwk, $sfe, $sup, $spc, $pro, $pt), 1, 0, 'C');
                        $pdf->Cell(15, 6, $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt), 1, 0, 'C');
                        $pdf->Cell(30, 6, $db->courseRemarks($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt), 1, 0, 'C');
                        $pdf->Ln();
                    }
                }
            }
        }

    $avgcwk=$tcwk/$count;
    $avgsfe=$tsfe/$count;

    $sdvcwk=$db->standDeviation($cwkarr);
    $sdvsfe=$db->standDeviation($sfearr);
    $cwkpresent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,1,1);
    $cwkabsent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,1,0);
    $sfepresent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,2,1);
    $sfeabsent=$db->getExamStatus($courseID,$semesterSettingID,$batchID,2,0);
    $pdf->Ln(10);
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
    $pdf->Cell(30,6,number_format($sdvsfe,2),1,0,'C');
    //Grade Count
    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(50,6,"Overall Summary");

    $pdf->Ln(6);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,6,'A',1);
    $pdf->Cell(20,6,"B+",1);
    $pdf->Cell(20,6,"B",1,0,'C');
    $pdf->Cell(20,6,"C",1,0,'C');
    $pdf->Cell(20,6,"D",1,0,'C');
    $pdf->Cell(20,6,"E",1,0,'C');
    $pdf->Cell(20,6,"I",1,0,'C');
    $pdf->Cell(22,6,"Pass",1,0,'C');
    $pdf->Cell(22,6,"Fail",1,0,'C');

    $pdf->Ln(6);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(20,6,$gA,1);
    $pdf->Cell(20,6,$gBb,1);
    $pdf->Cell(20,6,$gB,1,0,'C');
    $pdf->Cell(20,6,$gC,1,0,'C');
    $pdf->Cell(20,6,$gD,1,0,'C');
    $pdf->Cell(20,6,$gE,1,0,'C');
    $pdf->Cell(20,6,$gI,1,0,'C');
    $pdf->Cell(22,6,$tpass,1,0,'C');
    $pdf->Cell(22,6,$tfail,1,0,'C');

    $pdf->Ln(20);
    /*$pdf->SetFont('Arial','',12);
    $pdf->Cell(100,6,"Internal Examiner's Name:.....................................");$pdf->Cell(100,6,"External Examiner's Name:.................................");
    $pdf->Ln(10);
    $pdf->Cell(100,6,"Internal Examiner Signature:...................Date............");$pdf->Cell(100,6,"External Examiner Signature:...............Date...........");*/

    $pdf->SetFont('Arial','',12);

    //instructor name

    $inID=$db->getRows("instructor_course",array('where'=>array('courseID'=>$courseID,'semesterSettingID'=>$semesterSettingID,'batchID'=>$batchID)));
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
    }

    if($instructorID !=0) {
        $inName = $db->getRows("instructor", array('where' => array('instructorID' => $instructorID)));
        if (!empty($inName)) {
            foreach ($inName as $inst) {
                $fname = $inst['firstName'];
                $lname = $inst['lastName'];
                $salutation = $inst['salutation'];
                $instructorName = "$salutation $fname $lname";
            }
        }
    }
    else
    {
        $instructorName="___________________________________________";
    }
    $pdf->Cell(100,6,$instructorName);$pdf->Cell(100,6,"_____________________________");
    $pdf->Ln(6);
    $pdf->Cell(100,6,"Instructor's Name");$pdf->Cell(100,6,"Signature");
    $pdf->Ln(10);
    $pdf->Cell(100,6,"______________________________________");$pdf->Cell(100,6,"_____________________________");
    $pdf->Ln(6);
    $pdf->Cell(100,6,"Date approved by Head of Department");$pdf->Cell(100,6,"Signature");
    $pdf->Ln(10);
    $pdf->Cell(100,6,"______________________________________");$pdf->Cell(100,6,"______________________________");
    $pdf->Ln(6);
    $pdf->Cell(100,6,"Date Approved by Dean");$pdf->Cell(100,6,"Signature");

}

    $pdf->Output();
}
?>
