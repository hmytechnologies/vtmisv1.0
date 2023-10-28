<?php
session_start();

// Check if the action is to generate a PDF
if ($_REQUEST['action'] == "getPDF") {
    // Include the database connection logic
    include 'DB.php';
    $db = new DBHelper();

    // Retrieve organization details
    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $image = "img/" . $org['organizationPicture'];
        }
    } else {
        // Use default values if organization data is not available
        $organizationName = "Soft Dev Academy";
        $organizationCode = "SDVA";
        $image = "img/SkyChuo.png";
    }

    // Include the FPDF library
    require('fpdf.php');

    // Get parameters
    $centerID = $_REQUEST['cid'];
    $programmeLevelID = $_REQUEST['lid'];
    $academicYearID = $_REQUEST['ay'];
    $centerName = $db->getData("center_registration", "centerName", "centerRegistrationID", $centerID);
    
    // Create a PDF class that extends FPDF
    class PDF extends FPDF
    {
        function Banner($organizationName, $image, $centerName)
        {
            $today = date('M d,Y');
            $this->SetFont('Arial', 'B', 20);
            $this->Image($image, 130, 9, 30, 30);
            $this->Cell(0, 67, $organizationName, 0, 0, 'C');
            // Add another line of text after the organization name
            $this->Ln(38); // Adjust the spacing as needed
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10,  $centerName, 0, 0, 'C');
        }

        // Other PDF customization methods here...

        // Function to create a basic table in the PDF
        function BasicTable($header)
        {
            $w = array(10,  65, 15, 40, 21.5, 21.5, 21.5, 21.5, 21.5,21.5, 21.5); // Adjust column widths
            $this->SetX(10); // Start at X=10
            $this->SetFont('Arial', 'B', 11);
            // Add table headers
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($w[$i], 10, $header[$i], 1, 0, 'C');
            }
            $this->Ln(); // Move to the next line
        }
    }

    // Get center, program level, and academic year details
    $levelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
    $academicYear = $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID);
    
  
    $header = array('No', 'Name', 'Sex', 'Exam Number', '', '', '', '', '', '', '');
    $trades = $db->getCenterTrade($centerID, $programmeLevelID, $academicYearID);

    if (!empty($trades)) {
     
        $pdf = new PDF('L'); // 'L' for landscape orientation
        $pdf->AliasNbPages();
        
        
        foreach ($trades as $pg) {
            $programmeID = $pg['programmeID'];
            $programmeName = $pg['programmeName'];
            $pdf->AddPage(); // Add a new page for each trade
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->Banner($organizationName, $image, $centerName);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Ln(10);
            $pdf->Cell(0, 10, 'Exam Attendance - ' . $levelName . " " . $academicYear, 0, 1, 'C');
            $pdf->Line(14, $pdf->GetY(), 288, $pdf->GetY());
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(180, 6, "Trade Name: " . $programmeName, 0, 0, 'L');
            $pdf->Ln(7);
            $student = $db->printCenterStudentExamNumber($centerID, $programmeLevelID, $programmeID, $academicYearID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                $count = 0;
                foreach ($student as $st) {
                    $count++;
                    $studentID = $st['studentID'];
                    $fname = $st['firstName'];
                    $mname = $st['middleName'];
                    $gender = $st['gender'];
                    $lname = $st['lastName'];
                    $name = "$fname $mname $lname";
                    $regNumber = $st['registrationNumber'];
                    $examNumber = $st['examNumber'];
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(10, 8, $count, 1);
                    $pdf->Cell(65, 8, $name, 1);
                    $pdf->Cell(15, 8, $gender, 1, 0, 'C');
                    $pdf->Cell(40, 8, $examNumber, 1, 0, 'C');
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Cell(21.5, 8, '', 1);
                    $pdf->Ln();
                }
            }
        }

        // Output the generated PDF
        $pdf->Output($centerName . "_" . $levelName . "_" . $academicYear . ".pdf", "D");
    }
}
?>
