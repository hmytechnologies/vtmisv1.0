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
            $this->setFont('Arial', 'B', 18);
            
            $this->Image($image, 135, 0, 40.98, 35.22);
            $this->Text(105, 40, strtoupper($organizationName));
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
            $w = array(10, 35, 40,10);
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
    $pdf->Text(10, 45, strtoupper($db->getData("center_registration", "centerName", "centerRegistrationID", $centerID)));
    //Arial bold 15
    $pdf->Ln(35);
    $pdf->setFont('Arial', '', 14);
    $pdf->Text(10, 53, ' Supplement Exam Results - '. $db->getData("programmes", "programmeName", "programmeID", $programmeID)."-".$db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID)." ". $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID)."-Second Round");
    $pdf->Line(10,55,280,55);

    $header = array('No', 'Exam Number','Name','Sex');
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
            $studentNumber=0;
            $student = $db->printCenterStudentExamNumber($centerID,$programmeLevelID, $programmeID, $academicYearID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                $course = $db->getCourseCredit($levelID, $programmeID);
                $wdth = 140/7;
                $codeCode = '';
                foreach ($course as $cs) {

                    $courseID = $cs['courseID'];
                    $code = $cs['courseCode'];
                    $courseTypeID = $cs['courseTypeID'];

                    
                    $courseType = $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID);
                    $courseCategoryID = $cs['courseCategoryID'];


                    $courseCategory = $db->getData("course_category", "courseCategory", "courseCategoryID", $courseCategoryID);



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
                
                    // Debugging
                   // echo "codeCode: $codeCode<br>";
                
                    // Display the codeCode using $pdf->Cell if needed
                    $pdf->Cell($wdth, 6, $codeCode, 1);
                }
                //$pdf->Ln();
                $pdf->SetFont('Arial', 'B', 9);
                /* $pdf->Cell(13, 6, "CSAVG", 1);
                $pdf->Cell(13, 6, "GSAVG", 1); */
                $pdf->Cell(12, 6, "RMK", 1);
                $pdf->Ln();
                $pdf->Cell(95, 6, "", 1);
                $pdf->SetFont('Arial', '', 8);
                foreach ($course as $cs) {
                    $pdf->Cell(10, 6, "Marks", 1);
                    $pdf->Cell(10, 6, "Grade", 1);
                }
                $pdf->Cell(12, 6, "Remark", 1);
                $pdf->Ln();

                $count=0;$totalPass=0;$totalSupp=0;
                $npassmale=0;$nsuppmale=0;$npassfemale=0;$nsuppfemale=0; $mgender=0;$fgender=0;
                foreach ($student as $st) {
                    $count++;
                    $studentNumber++;
                    $studentID = $st['studentID'];  
                    $fname = $st['firstName'];
                    $mname = $st['middleName'];
                    $lname = $st['lastName'];
                    $name = "$fname $mname $lname";
                    $regNumber = $st['registrationNumber'];
                    $examNumber = $st['examNumber'];
                    $gender=$st['gender'];

                    if($gender=="M") $mgender++;
                    else $fgender++;

                    $pdf->setFont('Arial', '', 7);
                    $pdf->Cell(10, 6, $count, 1);
                    $pdf->Cell(35, 6, $examNumber, 1, 0, 'C');
                    $pdf->Cell(40, 6, $name, 1, 0);
                    $pdf->Cell(10, 6, $gender, 1, 0);
                   

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


                    $gA = 0;
                    $gB = 0;
                    $gC = 0;
                    $gD = 0;
                    $gF = 0;
                    $tpass = 0;
                    $tfail = 0;

                   // $count = 0;
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

                    $graderemarks=0;

                    $finalScore=0;

                    $present=0;
                    $absent=0;
                    $testpresent=0;
                    $testabsent=0;

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
                    
                        $finalm1 = ($finalScore / 100) * 50;
                        $tMarks = round($term1m + $term2m + $finalm1);

                        if ($tMarks>=35 && $tMarks<40 ) {
                            $addmarks=40-$tMarks;
                        }
                        else
                        {
                            $addmarks=0;
                        }

                            $finalScore = $finalScore + $addmarks;
                            $finalm=$finalm1+$addmarks;
                            $totalMarks = round($term1m + $term2m + $finalm);
                            if(!empty($suppScore))
                            {
                                if($suppScore>=40)
                                    $grade="D";
                                else 
                                    $grade="F";
                            }
                            else 
                            {
                                $grade = $db->calculateTermGrade($totalMarks);
                            }

                        if ($courseCategoryID == 1) {
                            if(!empty($suppScore))
                            {
                                $cstotal += $suppScore;
                                $countcs++;
                            }
                            else 
                            {
                                $cstotal += $totalMarks;
                                $countcs++;
                            }
                            
                        } else {
                            //if(!empty($suppScore))
                            //{
                            //    $gstotal += $suppScore;
                            //    $countgs++;
                            //}
                            //else
                            //{
                                $gstotal += $totalMarks;
                                $countgs++;
                            //}
                        }

                if ($courseCategoryID == 1 && $grade == "F") {
                    $graderemarks++;
                }

/* 
                if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
                    $tpass++;
                } else {
                    $tfail++;
                } */

               

                /*
                 if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I" || $grade == "A0" || $grade == "A1" || $grade == "CC") {
                        $pdf->SetFillColor(169,169,169);
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1,0,'L',1);
                    }
                    else {
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1);
                    }
                */
                        if(!empty($suppScore))
                        {
                            $pdf->SetFillColor(169, 169, 169);
                            $pdf->Cell(10, 6, round($suppScore), 1,0,'L',1);
                            if($suppScore>=40)
                                $grade="D";
                            else 
                                $grade="F";
                            $pdf->Cell(10, 6, $grade,1, 0, 'L', 1);
                        }
                        else 
                        {
                            if ($courseCategoryID == 1) {
                                if ($grade == "F") {
                                    $pdf->SetFillColor(220, 50, 50);
                                    $pdf->Cell(10, 6, round($totalMarks), 1,0,'L',1);
                                    $pdf->Cell(10, 6, $grade, 1, 0, 'L', 1);
                                } 
                                else{
                                    $pdf->Cell(10, 6, round($totalMarks), 1);
                                    $pdf->Cell(10, 6, $grade, 1);
                                }
                            } else {
                                $pdf->Cell(10, 6, round($totalMarks), 1);
                                $pdf->Cell(10, 6, $grade, 1);
                            }
                            
                        }

                if ($gender == 'M') {
                    if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
                        $tmpass++;
                    } else {
                        $tmfail++;
                    }
                } else {
                    if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
                        $tfpass++;
                    } else {
                        $tffail++;
                    }
                }

                       /*  if ($grade=="F") {
                            $pdf->SetFillColor(169, 169, 169);
                            $pdf->Cell(10, 6, $grade, 1,0,'L',1);
                        }else {
                            $pdf->Cell(10, 6, $grade, 1);
                        } */
                    }
            $gsaverage = round(($gstotal / $countgs));
            $csaverage = round(($cstotal / $countcs));

            if ($csaverage >= 40 && $graderemarks <= 0)
                $gparemarks = "PASS";
            else if($csaverage >= 40 && $graderemarks > 0)
                $gparemarks = "FAIL";
            else 
                $gparemarks="FAIL";

                if($gparemarks=="PASS")
                    $totalPass+=1;
                else 
                    $totalSupp+=1;
                    //$pdf->Ln();

            /* $pdf->Cell(13, 6, $csaverage, 1);
            $pdf->Cell(13, 6, $gsaverage, 1); */
            if ($gparemarks=="FAIL") {
                $pdf->SetFillColor(220, 50, 50);
                $pdf->Cell(12, 6, $gparemarks, 1,0,'L',1);
            }
            else {
                $pdf->Cell(12, 6, $gparemarks, 1);
            }
            $pdf->Ln();

            if($gender=="M")
            {
                if($gparemarks=="PASS")
                    $npassmale++;
                else 
                    $nsuppmale++;
            }
            else 
            {
                if ($gparemarks == "PASS")
                    $npassfemale++;
                else
                    $nsuppfemale++;
            }

                    }
                }


    //end report
    $ppass = round(($totalPass / ($totalPass + $totalSupp)) * 100, 2);
    $pfail = round(($totalSupp / ($totalPass + $totalSupp)) * 100, 2);

    $tpass=$npassmale+$npassfemale;
    $tfail=$nsuppfemale+$nsuppmale;


    $present = $db->getStudentExamStatusProgramme($academicYearID,3, 1);
    $absent = $db->getStudentExamStatusProgramme($academicYearID,3, 0);

    $pgender=(($mgender+$fgender)/$studentNumber)*100;

    //end percent
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);


    $pdf->Cell(50, 6, "Overall Summary");

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 6, 'Grade', 1);
    $pdf->Cell(46, 6, 'Student Number', 1, 0, 'C');
    $pdf->Cell(46, 6, "Pass", 1, 0, 'C');
    $pdf->Cell(46, 6, "Fail", 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 6, "Gender", 1);
    $pdf->Cell(23, 6, 'M', 1);
    $pdf->Cell(23, 6, "F", 1, 0, 'C');
    $pdf->Cell(23, 6, "M", 1, 0, 'C');
    $pdf->Cell(23, 6, "F", 1, 0, 'C');
    $pdf->Cell(23, 6, "M", 1, 0, 'C');
    $pdf->Cell(23, 6, "F", 1, 0, 'C');

    $pdf->Ln(6);
    $pdf->Cell(40, 6, "SubTotal", 1);
    $pdf->Cell(23, 6, $mgender, 1);
    $pdf->Cell(23, 6, $fgender, 1, 0, 'C');
    $pdf->Cell(23, 6, $npassmale, 1, 0, 'C');
    $pdf->Cell(23, 6, $npassfemale, 1, 0, 'C');
    $pdf->Cell(23, 6, $nsuppmale, 1, 0, 'C');
    $pdf->Cell(23, 6, $nsuppfemale, 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 6, "Total(%)", 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(46, 6, $studentNumber . "(" . $pgender . "%)", 1,0,'C');
    $pdf->Cell(46, 6, $tpass . "(" . $ppass . "%)", 1, 0, 'C');
    $pdf->Cell(46, 6, $tfail . "(" . $pfail . "%)", 1, 0, 'C');

    /* $pdf->Cell(50, 6, "Overall Summary");

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(25, 6, 'Grade', 1);
    $pdf->Cell(24, 6, "Pass", 1, 0, 'C');
    $pdf->Cell(24, 6, "Supp", 1, 0, 'C');
    $pdf->Ln(6);
    
    $pdf->Cell(25, 6, "Total", 1);
    $pdf->Cell(24, 6, $totalPass, 1, 0, 'C');
    $pdf->Cell(24, 6, $totalSupp, 1, 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(25, 6, "Percentage", 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(24, 6, $ppass."%", 1, 0, 'C');
    $pdf->Cell(24, 6, $pfail."%", 1, 0, 'C'); */
                
  //      }
//}

    /* $pdf->Output($centerName."_".$levelName."_".$academicYear.".pdf", "D"); */
    $pdf->Output();
}
