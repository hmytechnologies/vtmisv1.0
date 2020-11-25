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
    $centerID = $_REQUEST['cid'];
    $programmeLevelID = $_REQUEST['lid'];
    $academicYearID = $_REQUEST['ay'];

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
            $w = array(10, 60, 80, 40);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
            $this->Ln();

        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
 
    $pdf->AddPage();
    $pdf->setFont('Arial', '', 8);
    $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 15);
    $pdf->Banner($organizationName,$image);
    //$pdf->Text(50, 10, strtoupper($organizationName));
    $pdf->setFont('Arial', 'B', 13);
    $pdf->Text(50, 45, strtoupper($db->getData("center_registration", "centerName", "centerRegistrationID", $centerID)));
    //Arial bold 15
    $pdf->Ln(35);
    $pdf->setFont('Arial', '', 14);
    $pdf->Text(10, 53, 'List of Exam Number - '.$db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID)." ". $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID));
    $pdf->Line(10,55,205,55);

    $header = array('No', 'Reg.Number', 'Name', 'Exam Number');
    $pdf->Ln(10);

    //course details
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Cell('10', 6, '');
    $pdf->Ln(6);

    $trades = $db->getCenterTrade($centerID,$programmeLevelID,$academicYearID);
    if (!empty($trades)) {
        foreach ($trades as $pg) {
            $programmeID = $pg['programmeID'];
            $programmeName=$pg['programmeName'];
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(180, 6, "Programme Name:" . $programmeName, 0, 0, 'L');
            $pdf->Ln(6);
            $student = $db->printCenterStudentExamNumber($centerID,$programmeLevelID, $programmeID, $academicYearID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                $count=0;
                foreach ($student as $st) {
                    $count++;
                    $studentID = $st['studentID'];
                    $fname = $st['firstName'];
                    $mname = $st['middleName'];
                    $lname = $st['lastName'];
                    $name = "$fname $mname $lname";
                    $regNumber = $st['registrationNumber'];
                    $examNumber = $st['examNumber'];

                        $pdf->setFont('Arial', '', 10);
                        $pdf->Cell(10, 6, $count, 1);
                        $pdf->Cell(60, 6, $regNumber, 1, 0);
                        $pdf->Cell(80, 6, $name, 1, 0);
                        $pdf->Cell(40, 6, $examNumber, 1, 0, 'C');
                        $pdf->Ln();
                    }
                }
        }
}

    $pdf->Output();
}
?>
