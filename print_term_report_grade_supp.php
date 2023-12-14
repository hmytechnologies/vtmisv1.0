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
    $academicYearID = $_REQUEST['aid'];

    class PDF extends FPDF
    {
        function Banner($organizationName, $image)
        {
            $today = date('M d,Y');
            //Logo . 
            $this->setFont('Arial', 'B', 13);
            
            $this->Image($image, 135, 0, 40.98, 35.22);
            $this->Text(115, 40, strtoupper($organizationName));
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

            $this->Cell(100, 0, 'Printed Date ' . $today2 . ' Zanzibar', 0, 1, 'C');

        }

        function BasicTable($header)
        {
            $w = array(10, 35, 45);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
            //$this->Ln();

        }
    }

    $programmeID = $_REQUEST["pid"];
    $levelID = $_REQUEST["lid"];
    $academicYearID = $_REQUEST["aid"];
    $centerID = $_REQUEST["cid"];

    $centerName= $db->getData("center_registration", "centerName", "centerRegistrationID", $centerID);
    $levelName= $db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID);
    $academicYear= $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID);

    $pdf = new PDF();
    $pdf->AliasNbPages();
 
    $pdf->AddPage("L");
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
    $pdf->Text(10, 53, ' Supplement Exam Results - '.$db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID)." ". $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID));
    $pdf->Line(10,55,205,55);

    $header = array('No', 'Exam Number','Name');
    $pdf->Ln(10);

    //course details
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->Cell('10', 6, '');
    $pdf->Ln(6);

   /*  $trades = $db->getCenterTrade($centerID,$programmeLevelID,$academicYearID);
    if (!empty($trades)) {
        foreach ($trades as $pg) {
            $programmeID = $pg['programmeID'];
            $programmeName=$pg['programmeName'];
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(180, 6, "Trade Name:" . $programmeName, 0, 0, 'L');
            $pdf->Ln(6); */
            $student = $db->printCenterStudentExamNumber($centerID,$programmeLevelID, $programmeID, $academicYearID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                $course = $db->getCourseCredit($levelID, $programmeID);
                $wdth = 140/7;
                $codeCode = '';
                $course = $db->getCourseCredit($levelID,$programmeID);
                foreach ($course as $cs) {
                    $courseID = $cs['courseID'];
                        $code = $cs['courseCode'];
                        $courseTypeID = $cs['courseTypeID'];


                        $courseType = $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID);
                        $courseCategoryID = $cs['courseCategoryID'];


                        $courseCategory = $db->getData("course_category", "courseCategory", "courseCategoryID", $courseCategoryID);
                        $levelName= $db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID);


                        if ($courseCategory == 'Core Subjects' ) {


                            if ( $levelName =='Level I') {
                                # code...
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '11';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '13';
                                }
                                
                                else {
                                    $codeCode = $code . '12';
                                }
                            }
                            elseif( $levelName =='Level II'){
    
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '21';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '22';
                                }
                                
                                else {
                                    $codeCode = $code . '23';
                                }
    
                            }
                            
                            else {
                                # code...
    
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '31';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '33';
                                }
                                
                                else {
                                    $codeCode = $code . '32';
                                }
                            }
                            
    
    
                           
                        }
                        else {
                            if ( $levelName =='Level I') {
                                # code...
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '11';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '13';
                                }
                                
                                else {
                                    $codeCode = $code . '12';
                                }
                            }
                            elseif( $levelName =='Level II'){
    
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '21';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '22';
                                }
                                
                                else {
                                    $codeCode = $code . '23';
                                }
    
                            }
                            
                            else {
                                # code...
    
    
    
                                if ($courseType== 'Theory') { 
                                    $codeCode = $code . '31';
                                } 
                                elseif ($courseType== 'Field Training') {
                                    # code...
                                    $codeCode = $code . '33';
                                }
                                
                                else {
                                    $codeCode = $code . '32';
                                }
                            }
                            
    
                        }
                        $pdf->Cell(12.5, 6, $codeCode, 1, 0, 'C');
                }
                //$pdf->Ln();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(12.5, 6, "CSAVG", 1);
                $pdf->Cell(12.5, 6, "GSAVG", 1);
                $pdf->Cell(12.5, 6, "RMK", 1);
                $pdf->Ln();
                //$pdf->Cell(90, 6, "", 1);
                $pdf->SetFont('Arial', '', 8);
                /* foreach ($course as $cs) {
                    $pdf->Cell(10, 6, "Grade", 1);
                } */
                //$pdf->Cell(41, 6, "", 1);
               // $pdf->Ln();

                $count=0;
                foreach ($student as $st) {
                    $count++;
                    $studentID = $st['studentID'];
                    $fname = $st['firstName'];
                    $mname = $st['middleName'];
                    $lname = $st['lastName'];
                    $name = "$fname $lname";
                    $regNumber = $st['registrationNumber'];
                    $examNumber = $st['examNumber'];

                    $pdf->setFont('Arial', '', 10);
                    $pdf->Cell(10, 6, $count, 1);
                    $pdf->Cell(35, 6, $examNumber, 1, 0, 'C');
                    $pdf->Cell(45, 6, $name, 1, 0);
                   

                    //course marks
                    $course = $db->getCourseCredit($levelID, $programmeID);
                    $tunits = 0;
                    $tpoints = 0;
                    $countpass = 0;
                    $countsupp = 0;
                    $gstotal = 0;
                    $cstotal = 0;
                    $countgs = 0;
                    $countcs = 0;
                    $graderemarks = 0;
                    $totalPass =0;
                    $totalSupp =0;
                    foreach ($course as $cs) {
                        $courseID = $cs['courseID'];
                        $courseCategoryID = $cs['courseCategoryID'];
                        $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 1));
                        $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 2));
                        $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));
                        $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 5));

                        $exam_category_marks = $db->getTermCategorySetting();
                        if (!empty($exam_category_marks)) {
                            foreach ($exam_category_marks as $gd) {
                                $mMark = $gd['mMark'];
                                $pMark = $gd['passMark'];
                                $wMark = $gd['wMark'];
                            }
                        }

                        $term1m = ($term1Score / $mMark) * $wMark;
                        $term2m = ($term2Score / $mMark) * $wMark;
                        $finalm = ($finalScore / 100) * 50;

                        $totalMarks = round($term1m + $term2m + $finalm);



                /* if ($courseCategoryID == 1) {
                            $cstotal += $totalMarks;
                            $countcs++;
                        } else {
                            $gstotal += $totalMarks;
                            $countgs++;
                        } */
                if ($courseCategoryID == 1) {
                    if (!empty($suppScore)) {
                        $cstotal += $suppScore;
                        $countcs++;
                    } else {
                        $cstotal += $totalMarks;
                        $countcs++;
                    }
                } else {
                    $gstotal += $totalMarks;
                    $countgs++;
                }
                        if(!empty($suppScore))
                        {
                            if($suppScore>=40)
                                $grade="D";
                            else 
                                $grade="F";
                            $pdf->SetFillColor(169, 169, 169);
                            $pdf->Cell(12.5, 6, $grade,1,0,'L',1);
                        }
                        else 
                        {
                            $grade = $db->calculateTermGrade($totalMarks);
                            if($courseCategoryID==1)
                            {
                                if ($grade=="F") {
                                    $pdf->SetFillColor(220, 50, 50);
                                    $pdf->Cell(12.5, 6, $grade, 1, 0, 'L', 1);
                                }
                                else
                                    $pdf->Cell(12.5, 6, $grade, 1);
                            }
                            else
                            {
                                $pdf->Cell(12.5, 6, $grade, 1);
                            }
                        }

                if ($courseCategoryID == 1 && $grade == "F") {
                    $graderemarks++;
                }
                        
                    } 
                        $gsaverage = round(($gstotal / $countgs), 2);
                        $csaverage = round(($cstotal / $countcs), 2);


                        $gradecs = $db->calculateTermGrade($csaverage);
                        $gradegs = $db->calculateTermGrade($gsaverage);

                        if (($csaverage >= 40 && $graderemarks <= 0))
                        $gparemarks = "PASS";
                        else if ($csaverage >= 40 && $graderemarks > 0)
                        $gparemarks = "FAIL";
                        else
                        $gparemarks = "FAIL";

                        if ($gparemarks == "PASS")
                        $totalPass += 1;
                        else
                            $totalSupp += 1;

                    /* if ($csaverage >= 40)
                        $gparemarks = "Pass";
                    else
                        $gparemarks = "Supp"; */
                    //$pdf->Ln();

            $pdf->Cell(12.5, 6, $gradecs, 1);
            $pdf->Cell(12.5, 6, $gradegs, 1);
            if ($gparemarks == "FAIL") {
                $pdf->SetFillColor(220, 50, 50);
                $pdf->Cell(12.5, 6, $gparemarks, 1, 0, 'L', 1);
            } else {
                $pdf->Cell(12.5, 6, $gparemarks, 1);
            }
            //$pdf->Cell(15, 6, $gparemarks, 1);
            $pdf->Ln();
                    }
                }
                
  //      }
//}

    /* $pdf->Output($centerName."_".$levelName."_".$academicYear.".pdf", "D"); */
    $pdf->Output();
}
