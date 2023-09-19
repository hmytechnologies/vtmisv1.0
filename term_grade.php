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
            $w = array(10, 35, 60,10);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
            //$this->Ln();

        }
    }

    $programmeID = $_REQUEST["pid"];
    $levelID = $_REQUEST["lid"];
    $academicYearID = $_REQUEST["aid"];
    $centerID = $_REQUEST["cid"];
    $termID=$_REQUEST['termID'];
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
    $pdf->Text(10, 53, ''. $db->getData("exam_category", "examCategory", "examCategoryID", $termID)."  Results  -  " . $db->getData("programmes", "programmeName", "programmeID", $programmeID)."-".$db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID)." ". $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID));
    $pdf->Line(10,55,265,55);

    $header = array('No', 'Registration','Name','Sex');
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
            // $student = $db->getStudentTermList($centerProgrammeCourseID, $academicYearID, $levelID, $programmeID);
            // $student = $db->printCenterStudentExamNumber($centerID,$programmeLevelID, $programmeID, $academicYearID);
            $student = $db->getStudentTermList($centerID,$academicYearID,$programmeLevelID, $programmeID);
            if (!empty($student)) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->BasicTable($header);
                $course = $db->getCourseCredit($programmeLevelID, $programmeID);
                $wdth = 140/7;
                foreach ($course as $cs) {
                    // $pdf->Cell($wdth, 6, $cs['courseCode'], 1);
                    $pdf->Cell($wdth, 6, $cs['courseCode'], 1, 0, 'C');
                } 
                //$pdf->Ln();
                $pdf->SetFont('Arial', 'B', 9);
                /* $pdf->Cell(13, 6, "CSAVG", 1);
                $pdf->Cell(13, 6, "GSAVG", 1); */
                // $pdf->Cell(15, 6, "RMK", 1);
                $pdf->Ln();
                $pdf->Cell(115, 6, "", 1);
                $pdf->SetFont('Arial', '', 8);
                foreach ($course as $cs) {
                    // $pdf->Cell(10, 6, "Marks", 1);
                    // $pdf->Cell(10, 6, "Grade", 1);

                    $pdf->Cell($wdth, 6, "    Marks", 1);
                }
                // $pdf->Cell(15, 6, "", 1);
                $pdf->Ln();

                $count=0;$totalPass=0;$totalSupp=0;
                $npassmale=0;$nsuppmale=0;$npassfemale=0;$nsuppfemale=0; $mgender=0;$fgender=0;
             


                $gAmTotal = 0; 
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
                foreach ($student as $st) {
                    $count++;
                    $studentNumber++;
                    $studentID = $st['studentID'];  
                    $fname = $st['firstName'];
                    $mname = $st['middleName'];
                    $lname = $st['lastName'];
                    $name = "$fname $mname $lname";
                    $regNumber = $st['registrationNumber'];
                    // $examNumber = $st['examNumber'];
                    $gender=$st['gender'];

                    if($gender=="M") $mgender++;
                    else $fgender++;

                    $pdf->setFont('Arial', '', 8);
                    $pdf->Cell(10, 6, $count, 1);
                    $pdf->Cell(35, 6, $regNumber, 1, 0, 'C');
                    $pdf->Cell(60, 6, $name, 1, 0);
                    $pdf->Cell(10, 6, $gender, 1, 0);
                   

                    //course marks
                    $course = $db->getCourseCredit($levelID, $programmeID);
                

                    foreach ($course as $cs) {
                        $courseID = $cs['courseID'];
                        $courseCategoryID = $cs['courseCategoryID'];
                        // $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 1));
                        // $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 2));
                        // $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));
                        // $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 5));

                        $termScore = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, $termID));
                        
                        $exam_category_marks = $db->getTermCategorySetting();
                        if (!empty($exam_category_marks)) {
                            foreach ($exam_category_marks as $gd) {
                                $mMark = $gd['mMark'];
                                $pMark = $gd['passMark'];
                                $wMark = $gd['wMark'];
                            }
                        }

                        $term = ($termScore / $mMark) * $wMark;

                        // $term1m = ($termScore / $mMark) * $wMark;
                        // $term2m = ($termScore / $mMark) * $wMark;
                        /* if ($suppScore>=0) {
                            $finalm1=$suppScore;
                            $tMarks = round($finalm1);
                        }
                        else{ */
                            // $finalm1 = ($finalScore / 100) * 50;
                            // $tMarks = round($term1m + $term2m + $finalm1);
                        //}
                       // $totalMarks = round($term1m + $term2m + $finalm);
 /*
                        if ($tMarks>=35 && $tMarks<40 ) {
                            $addmarks=40-$tMarks;
                        }
                        else
                        {
                            $addmarks=0;
                            $finalScore = $finalScore;
                            $finalm2 = ($finalScore / $mMark) * $wMark;
                            $totalMarks = round($term1m + $term2m + $finalm); 
                        }*/

                            // $finalScore = $finalScore + $addmarks;
                            //$finalm = ($finalScore / $mMark) * $wMark;
                            // $finalm=$finalm1+$addmarks;
                            /* if($suppScore>=40){
                                $totalMarks=$suppScore;
                            }
                            else{ */
                                // $totalMarks = round($term1m + $term2m + $finalm);

                                $totalMark = round($term);



                            //}
                           /*  if($suppScore>=40)
                            {
                                $grade="D";
                            }
                            
                            else { */
                               
                               
                            //     $grade = $db->calculateTermGrade($termScore);
                            // //}
                            // if ($grade == "A" || $grade == "B" || $grade == "C"|| $grade == "D") {
                            //     $tpass++;
                            // } else {
                            //     $tfail++;
                            // }


                            //     if ($grade == "A")
                                
                            //         $gA++;
                            //     else if ($grade == "B")
                            //         $gB++;
                            //     else if ($grade == "C")
                            //         $gC++;
                            //     else if ($grade == "D")
                            //         $gD++;
                            //     else
                            //         $gF++;



//                         if ($courseCategoryID == 1) {
//                             $cstotal += $totalMark;
//                             $countcs++;
//                         } else {
//                             $gstotal += $totalMark;
//                             $countgs++;
//                         }

//                 if ($courseCategoryID == 1 && $grade == "F") {
//                     $graderemarks++;
//                 }

// /* 
//                 if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
//                     $tpass++;
//                 } else {
//                     $tfail++;
//                 } */
//                 if ($gender == 'M') {
//                     if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
//                         $tmpass++;
                
//                         if ($grade == "A") {
//                              $gAm ++; // Corrected here
//                             // $tmpass++;
//                         } else if ($grade == "B") {
//                             $gBm++;
//                         } else if ($grade == "C") {
//                             $gCm++;
//                         } else if ($grade == "D") {
//                             $gDm++;
//                         } else {
//                             $gFm++;
//                         }
//                     } else {
//                         $tmfail++;
//                         $gFm++;
//                     }
//                 } else {
//                     if ($grade == "A" || $grade == "B" || $grade == "C" || $grade == "D") {
//                         $tfpass++;
//                     } else {
//                         $tffail++;
//                     }
                
//                     if ($grade == "A")
//                         $gAf++;
//                     else if ($grade == "B")
//                         $gBf++;
//                     else if ($grade == "C")
//                         $gCf++;
//                     else if ($grade == "D")
//                         $gDf++;
//                     else
//                         $gFm++;
//                 }
                
                /*
                 if (($grade == "D") || ($grade == "F") || ($grade == "E") || $grade == "N" || $grade == "I" || $grade == "A0" || $grade == "A1" || $grade == "CC") {
                        $pdf->SetFillColor(169,169,169);
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1,0,'L',1);
                    }
                    else {
                        $pdf->Cell($wdth,6,$tmarks."-".$grade,1);
                    }
                */

                        // $pdf->Cell(10, 6, round($totalMark), 1);
                        $pdf->Cell($wdth, 6, round(    $totalMark), 1,0,'C');
                        if ($grade=="F") {
                            // $pdf->SetFillColor(169, 169, 169);
                            // $pdf->Cell(10, 6, $grade, 1,0,'L',1);
                        }else {
                            // $pdf->Cell(10, 6, $grade, 1);
                        }
                    }
            // $gsaverage = round(($gstotal / $countgs));
            // $csaverage = round(($cstotal / $countcs));

            // if ($csaverage >= 40 && $graderemarks <= 0)
            //     $gparemarks = "Pass";
            // else if($csaverage >= 40 && $graderemarks > 0)
            //     $gparemarks = "Supp";
            // else 
            //     $gparemarks="Supp";

            //     if($gparemarks=="Pass")
            //         $totalPass+=1;
            //     else 
            //         $totalSupp+=1;
                    //$pdf->Ln();

            /* $pdf->Cell(13, 6, $csaverage, 1);
            $pdf->Cell(13, 6, $gsaverage, 1); */
            // $pdf->Cell(15, 6, $gparemarks, 1);
            $pdf->Ln();

            // if($gender=="M")
            // {
            //     if($gparemarks=="Pass")
            //         $npassmale++;
            //     else 
            //         $nsuppmale++;
            // }
            // else 
            // {
            //     if ($gparemarks == "Pass")
            //         $npassfemale++;
            //     else
            //         $nsuppfemale++;
            // }

                    }
                }


                // $avgcwk=$tcwk/ $count;
                // $avgsfe=$tsfe/$count;
    //end report
    // $ppass = round(($totalPass / ($totalPass + $totalSupp)) * 100, 2);
    // $pfail = round(($totalSupp / ($totalPass + $totalSupp)) * 100, 2);

    
    // $ppass=round(($tpass/($tpass + $tfail))*100,2);
    // $pfail = round(($tfail / ($tpass  + $tfail)) * 100, 2);
    // $pA = round(($gA / ($gA+$gB+$gC+$gD+$gF)) * 100, 2);
    // $pB = round(($gB / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    // $pC = round(($gC / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    // $pD = round(($gD / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    // $pF = round(($gF / ($gA + $gB + $gC + $gD + $gF)) * 100, 2);
    // $sumGrades = $gA + $gB + $gC + $gD + $gF;


    // $tpass=$npassmale+$npassfemale;
    // $tfail=$nsuppfemale+$nsuppmale;


    // $present = $db->getStudentExamStatusProgramme($academicYearID,3, 1);
    // $absent = $db->getStudentExamStatusProgramme($academicYearID,3, 0);

    // $pgender=(($mgender+$fgender)/$studentNumber)*100;

    //end percent
    // $pdf->Ln(10);
    // $pdf->SetFont('Arial','B',14);
    // $pdf->Cell(50,6,"Overall Summary");
    //  $pdf->Cell(12, 6, $gAm, 1);
    //  $pdf->Cell(12, 6, $gAmTotal, 1);

    // $pdf->Ln(6);
    // $pdf->SetFont('Arial', '', 12);
    // $pdf->Cell(25, 6, 'Grade', 1);
    // $pdf->Cell(24, 6, 'A', 1,0,'C');

    // $pdf->Cell(24, 6, "B", 1, 0, 'C');
    // $pdf->Cell(24, 6, "C", 1, 0, 'C');
    // $pdf->Cell(24, 6, "D", 1, 0, 'C');
    // $pdf->Cell(24, 6, "F", 1, 0, 'C');
    // $pdf->Cell(24, 6, "Pass", 1, 0, 'C');
    // $pdf->Cell(24, 6, "Fail", 1, 0, 'C');
    // $pdf->Ln(6);
    // $pdf->Cell(25, 6, "Gender", 1);
    // $pdf->Cell(12, 6, 'M', 1);

    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Cell(12, 6, "M", 1, 0, 'C');
    // $pdf->Cell(12, 6, "F", 1, 0, 'C');
    // $pdf->Ln(6);
    // $pdf->Cell(25, 6, "SubTotal", 1);
    // $pdf->Cell(12, 6, $gAm, 1);

    // $pdf->Cell(12, 6, $gAf, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gBm, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gBf, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gCm, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gCf, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gDm, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gDf, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gFm, 1, 0, 'C');
    // $pdf->Cell(12, 6, $gFf, 1, 0, 'C');
    // $pdf->Cell(12, 6, $tmpass, 1, 0, 'C');
    // $pdf->Cell(12, 6, $tfpass, 1, 0, 'C');
    // $pdf->Cell(12, 6, $tmfail, 1, 0, 'C');
    // $pdf->Cell(12, 6, $tffail, 1, 0, 'C');
    // $pdf->Ln(6);
    // $pdf->Cell(25, 6, "Total(%)", 1);
    // $pdf->SetFont('Arial', '', 12);
    // $pdf->Cell(24, 6, $gA . "(" . $pA . "%)", 1);
    // $pdf->Cell(24, 6, $gB . "(" . $pB . "%)", 1, 0, 'C');
    // $pdf->Cell(24, 6, $gC . "(" . $pC . "%)", 1, 0, 'C');
    // $pdf->Cell(24, 6, $gD . "(" . $pD . "%)", 1, 0, 'C');
    // $pdf->Cell(24, 6, $gF . "(" . $pF . "%)", 1, 0, 'C');
    // $pdf->Cell(24, 6, $tpass."(".$ppass."%)", 1, 0, 'C');
    // $pdf->Cell(24, 6, $tfail."(". $pfail."%)", 1, 0, 'C');

  



    
   

//}

  ob_start();

 
            $pdf->Output();
            
            
            ob_end_flush();
}
?>