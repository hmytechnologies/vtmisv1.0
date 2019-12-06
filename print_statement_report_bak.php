<?php
session_start();
if($_REQUEST['action']=="getPDF")
{
    include 'DB.php';
    $db=new DBHelper();
    require('fpdf.php');
    $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
    if(!empty($organization))
    {
        foreach($organization as $org)
        {
            $organizationName=$org['organizationName'];
            $organizationCode=$org['organizationCode'];
            $organizationPicture="img/".$org['organizationPicture'];
            $phone=$org['organizationPhone'];
            $email=$org['organizationEmail'];
            $website=$org['organizationWebsite'];
            $postal=$org['organizationPostal'];
        }
    }
    else
    {
        $organizationName="Soft Dev Academy";
        $organizationCode="SDVA";
        $organizationPicture="img/SkyChuo.png";
        $phone="+0773500429";
        $email="hmy@hmytechnologies.com";
        $website="http://www.hnytechnologies.com";
        $postal="P.O.BOX XXX Zanzibar-Tanzania";
    }
    //$organizationPicture="img/mum.png";
    $regNumber=$_REQUEST['regNo'];
    class PDF extends FPDF
    {
//Page header
        function Banner($name,$image,$phone,$email,$website,$box)
        {
            $today=date('M d,Y');
            //Logo .
            $this->setFont('Arial', 'B', 16);
            $this->Text(45,15,strtoupper($name));
           $this->Image($image,15,5,30,30);
            //$this->Image(file,x,y,w,h,type,link);
            $this->setFont('Arial', '', 12);
            $this->Text(45,21,$box." Zanzibar-Tanzania");
            $this->Text(45,26,'Telephone: +255'.$phone.' Fax:');
            $this->Text(45,32,'Email:'.$email.' Website:http://www.ipa.ac.tz');
            $this->Line(15,36,200,36);
            //left address
           /* $this->setFont('Arial', '', 10);
            $this->Text(39,20,'Phone:'.$phone);
            $this->Text(39,24,'Fax:+255776523744');
            $this->Text(39,28,'Email:'.$email);*/
            //Right Address
           /* $this->setFont('Arial', '', 10);
            $this->Text(106,20,'P.O.BOX 169');
            $this->Text(106,24,'Zanzibar-Tanzania');
            $this->Text(106,28,'Website:http://www.ipa.ac.tz');*/


            //Arial bold 15
            $this->setFont('Arial', 'B', 14);
            $this->Text(50,43,'STATEMENT OF EXAMINATION RESULTS');
        }

        function SetCol($col)
        {
            // Set position at a given column
            $this->col = $col;
            $x = 10+$col*65;
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }

        /*function AcceptPageBreak()
        {
            // Method accepting or not automatic page break
            if($this->col<2)
            {
                // Go to next column
                $this->SetCol($this->col+1);
                // Set ordinate to top
                $this->SetY($this->y0);
                // Keep on page
                return false;
            }
            else
            {
                // Go back to first column
                $this->SetCol(0);
                // Page break
                return true;
            }
        }*/

//Page footer
        function Footer()
        {
            $today2=date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            //Arial italic 8
            $this->SetFont('Arial','I',8);
            //Page number
            $this->Cell(0,0,'Page '.$this->PageNo().'of{nb}',0,1,'C');

            $this->Cell(300,0,'Printed Date '.$today2.' Zanzibar',0,1,'C');

        }

        function BasicTable($header)
        {
            // Header

            $w = array(30,110,15,15,15);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L');

            $this->Ln();
            // Color and font restoration
        }
    }
    // Set text color to blue.

    $pdf=new PDF();
    $pdf->AliasNbPages();

    $header = array('Code', 'Course Name', 'Unit', 'Grade','Status');
    $student = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>' student_id ASC'));
    if(!empty($student))
    {
        $count = 0;
        $countpass = 0;
        $countsupp = 0;
        $countincomplete=0;
        foreach($student as $std) {
            $count++;
            $studentID = $std['studentID'];
            $fname = $std['firstName'];
            $mname = $std['middleName'];
            $lname = $std['lastName'];
            $gender = $std['gender'];
            $regNumber = $std['registrationNumber'];
            $programmeID = $std['programmeID'];
            $statusID = $std['statusID'];
            $admissionYearID = $std['academicYearID'];
            $name = "$fname $mname $lname";

            $pdf->AddPage("P");
            $pdf->setFont('Arial', '', 10);
            $pdf->Banner($organizationName, $organizationPicture, $phone, $email, $website, $postal);
            /*$stdPicture=$db->getRows('student_picture',array('where'=>array('studentID'=>$studentID),' order_by'=>'studentID ASC'));
            if(!empty($stdPicture))
            {
                foreach($stdPicture as $pct)
                {
                    $studentPic=$pct['studentPic'];
                    $pdf->Image("student_images/".$studentPic,155,12,25,27);
                }
            }*/

            $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);
            $level = $db->getRows('programme_level', array('where' => array('programmeLevelID' => $programmeLevelID), ' order_by' => ' programmeLevelCode ASC'));
            if (!empty($level)) {
                foreach ($level as $lvl) {
                    $programme_level_code = $lvl['programmeLevelCode'];
                }
            } else {
                $programme_level_code = "";
            }

            $programme = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID), ' order_by' => ' programmeName ASC'));
            if (!empty($programme)) {
                foreach ($programme as $pro) {
                    $programmeName = $pro['programmeName'];
                }
            } else {
                $programmeName = "";
            }
            $pdf->Ln(36);
            $pdf->setFont('Arial', 'B', 10);
            $pdf->Cell(5, 6, '');
            $pdf->Cell(185, 6, 'Reg.No: ' . $regNumber . '  Name: ' . $name . '  Gender: ' . $gender . '  Admitted Year: ' . $db->getData('academic_year', 'academicYear', 'academicYearID', $admissionYearID), 1);
            $pdf->Ln(6);
            $pdf->Cell(5, 6, '');
            $pdf->Cell(185, 6, 'Level & Programme Name: ' . $programme_level_code . '-' . $programmeName, 1);

            $pdf->Ln(8);
            $semester = $db->getSemester($regNumber);
            if (!empty($semester)) {
                $totalPoints = 0;
                $totalUnits = 0;
                foreach ($semester as $sm) {
                    $semesterSettingID = $sm['semesterSettingID'];
                    $semesterName = $sm['semesterName'];
                    $course = $db->getStudentSearchResult($regNumber, $semesterSettingID);
                    if (!empty($course)) {
                        $pdf->Cell(5, 6, '');
                        $pdf->setFont('Arial', 'B', 10);
                        $pdf->Cell(185, 6, "Exam Result for " . $semesterName, 1, '', 'C');
                        $pdf->Ln(6);
                        $pdf->Cell(5, 6, '');
                        $pdf->BasicTable($header);
                        $count = 0;
                        $i = 1;
                        $tunits = 0;
                        $tpoints = 0;



                        foreach ($course as $st) {
                            $count++;
                            $courseID = $st['courseID'];
                            $crstatus = $st['courseStatus'];

                            $coursec = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
                            if (!empty($coursec)) {
                                ?>
                                <?php
                                $i = 1;
                                foreach ($coursec as $c) {
                                    $courseCode = $c['courseCode'];
                                    $courseName = $c['courseName'];
                                    $units = $c['units'];
                                    $courseTypeID = $c['courseTypeID'];
                                }
                            } else {
                                $courseCode = "";
                                $courseName = "";
                                $units = "";
                                $courseTypeID = "";
                            }


                            if ($crstatus == 1)
                                $status = "Core";
                            else
                                $status = "Elective";

                            $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                            $sfe = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 2));
                            $sup = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 3));
                            $spc = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 4));
                            $prj = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 5));
                            $pt = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 6));
                            $tunits += $units;
                            $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                            $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                            $points = $gradePoint * $units;
                            $tpoints += $points;

                            $grade=$db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);

                            if (($grade == "D") or ($grade == "F") or ($grade == "E")) {
                                $countsupp = $countsupp + 1;

                            }
                            else if($grade == "I")
                            {
                                $countincomplete+=1;

                            }
                            else {
                                $countpass = $countpass + 1;

                            }

                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Cell(5, 6, '');
                            $pdf->Cell(30, 6, $courseCode, 1);
                            $pdf->Cell(110, 6, $courseName, 1);
                            $pdf->Cell(15, 6, $units, 1, '', 'C');
                            $pdf->Cell(15, 6, $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt), 1, '', 'C');
                            $pdf->Cell(15, 6, $status, 1, '', 'C');
                            $pdf->Ln();
                        }
                        $totalPoints += $tpoints;
                        $totalUnits += $tunits;
                    }
                    $pdf->Cell(5, 6, '');
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(100, 6, 'GPA', 0);
                    $pdf->Cell(80, 6, $db->getGPA($tpoints, $tunits), 0, '', 'R');
                    $pdf->Ln();
                }
                $pdf->Ln();
            }
            $totalPoints+=$tpoints;
            $totalUnits+=$tunits;
        }
            if($countsupp > 0 || $countincomplete > 0)
            {
                $gpaRemarks="Fail";
            }
            else
            {
                $gpa=$db->convert_gpa($db->getGPA($totalPoints,$totalUnits));
                $gpaRemarks=$db->getGPARemarks($regNumber,$gpa);
            }
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(20,4,'');$pdf->Cell(80,4,'OVERALL CGPA: '.$db->convert_gpa($db->getGPA($totalPoints,$totalUnits)));

            $pdf->Cell(70,4,'CLASSIFICATION:'.$gpaRemarks);
            $pdf->Ln(12);

            $pdf->Cell(5,4,'');$pdf->Cell(60,4,'.......................................');$pdf->Cell(60,4,'.......................................');$pdf->Cell(80,4,'...........................................');
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(60,4,'Examination Officer'); $pdf->Cell(60,4,'Date'); $pdf->Cell(80,4,'Director');
            $pdf->Ln(5);
            $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell(170,4,'END OF STATEMENT OF RESULTS','0','','C');
            $pdf->Ln(5);
            $pdf->Cell(5,4,'');$pdf->Cell(70,4,'1. The Statement will be valid only if it bears the College/University Seal');
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(70,4,'2. Points=Grade Points Multiplied by Number of Units');
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(70,4,'3. Key to the Grades and other Symbols for College Exam: SEE THE TABLE BELOW ');
            $pdf->Ln(4);
            $gradeclass=$db->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
            $pdf->Cell(5,4,'');$pdf->Cell(25,4,'Grade',1);
            foreach ($gradeclass as $grd) {
                $pdf->Cell(22, 4, $grd['gradeCode'], 1);
            }
            $pdf->Cell(22, 4, "I", 1);
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(25,4,'Marks Range',1);
            foreach ($gradeclass as $grd) {
                $pdf->Cell(22, 4, $grd['startMark'].'-'.$grd['endMark'], 1);
            }
            $pdf->Cell(22, 4, "NAN", 1);
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(25,4,'Grade Points',1);
            foreach ($gradeclass as $grd) {
                $pdf->Cell(22, 4, $grd['gradePoints'], 1);
            }
            $pdf->Cell(22, 4, "NAN", 1);
            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(25,4,'Remarks',1);
            foreach ($gradeclass as $grd) {
                $pdf->Cell(22, 4, $db->getData('remarks','remark','remarkID',$grd['remarkID']), 1);
            }
            $pdf->Cell(22, 4, "Incomplete", 1);
            $pdf->Ln(4);


            $pdf->Ln(4);
            $pdf->Cell(5,4,'');$pdf->Cell(70,4,'4. Key to the Classification Awards: SEE THE TABLE BELOW ');
            $pdf->Ln(4);
            $gpaclassess=$db->getRows("gpa",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
            $pdf->Cell(5,4,'');
            $pdf->Cell(30,4,'Classess',1);
            foreach ($gpaclassess as $gpa) {
                $pdf->Cell(30, 4, $gpa['gpaClass'], 1);
            }
            $pdf->Ln(4);
            $gpaclassess=$db->getRows("gpa",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
            $pdf->Cell(5,4,'');$pdf->Cell(30,4,'Comm.Points',1);
            foreach ($gpaclassess as $gpa) {
                $pdf->Cell(30, 4, $gpa['startPoint'].'-'.$gpa['endPoint'], 1);
            }
            $pdf->Ln(4);
            $gpaclassess=$db->getRows("gpa",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
            $pdf->Cell(5,4,'');$pdf->Cell(30,4,'Remarks',1);
            foreach ($gpaclassess as $gpa) {
                $pdf->Cell(30, 4, $db->getData('remarks','remark','remarkID',$gpa['remarkID']), 1);
            }
            $pdf->Ln(4);

        }


    $pdf->Output();
}
?>
