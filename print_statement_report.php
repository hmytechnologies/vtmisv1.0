<?php
session_start();


if($_REQUEST['action']=="getPDF") {
    if (isset($_POST['level'])) {
         
        include 'DB.php';
        $db=new DBHelper();
        require('fpdf.php');
        $reg = $_POST['regNumber'];
        $studentPicture = $db->getRows('student',array('where'=>array('registrationNumber'=>$reg),' order_by'=>' studentID ASC'));
       if(!empty($studentPicture))
        {
            foreach ($studentPicture as $picture) {
                # code...
                $img="student_images/".$picture['studentPicture'];
    
            }
    
        }
         $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
    
        
    
          $studenDetails = $db->getRows('student_programme',array('where'=>array('regNumber'=>$reg)));
         if(!empty($studenDetails))
          {
    
            foreach ($studenDetails as $pro) {
                # code...
               $studentprogrammeID = $pro['programmeID'];
                //  $studentprogrammeID;
               $programmeID =$db->getRows('programmes',array('where'=>array('programmeID'=>$studentprogrammeID)));
               foreach ($programmeID as $proID) {
                   # code...
                    $proName =$proID['programmeName'];
               }
               
            }
            
             
              
             
          }   

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
        $phone="+XXX";
        $email="hmy@hmytechnologies.com";
        $website="http://www.hnytechnologies.com";
        $postal="P.O.BOX XXX Zanzibar-Tanzania";
    }
         $selectedLevels = [];
         $selectedLevels = $_POST['level'];
         $countLevel = count($selectedLevels);
         $regNum = $_POST['regNumber'];

      
         $student = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNum),' order_by'=>' studentID ASC'));
         foreach($student as $std) {
            $count++;
            $studentID = $std['studentID'];
            $fname = str_replace("&#039;","'",$std['firstName']);
            $mname = $std['middleName'];
            $mname=str_replace("&#039;","'",$mname);
            $lname = str_replace("&#039;","'",$std['lastName']);
            $gender = $std['gender'];
            $regNumber = $std['registrationNumber'];
            // $programmeID = $std['programmeID'];
            $statusID = $std['statusID'];
            //$admissionYearID = $std['academicYearID'];
            $Dob = $std['dateOfBirth'];
            $name = "$fname $mname $lname";            
            
        }

         
 
         //echo $"count";
         

         class PDF extends FPDF
         {
             function Banner($name,$image,$phone,$email,$website,$postal,$proName,$stupicture)
             {
                $today=date('M d,Y');
                $studentREg= $_REQUEST['regNo'];
                //Logo .
                
                $this->setFont('Arial', '', 16);
                //$this->Text(45,15,strtoupper($name));
                //Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
               $this->Image($image,130,9,30,30);
               $this->setFont('Arial', 'B', 25);
               $this-> Cell(0,67,$name,0,0,'C');
            //    $this-> Cell(0,69,,0,0,'C');
                $this->setFont('Arial', 'B', 14);
                 $this->Text(115,52,'Academic Progress Report');
                 ///passport size student
                 $this->Image($stupicture,220,41,30,25);
                //$this->Image(file,x,y,w,h,type,link);OCCUPATION: AUTO ELECTRIC
                 $this->setFont('Arial','B',10);
                 $this->SetFont('Times', '');
                 $this->Text(120,58,'(Invalid without National Certificate)');
    
                 $this->setFont('Arial', 'B', 8);
                 $this->Text(105,64,'                 OCCUPATION:  '.$proName.'');
             }
             function SetCol($col)
             {
                 // Set position at a given column
                 $this->col = $col;
                 $x = 10 + $col * 65;
                 $this->SetLeftMargin($x);
                 $this->SetX($x);
             }
     
            
             function BasicTable($header)
             {
                 $w = array(10, 35, 60,10);
                 for ($i = 0; $i < count($header); $i++)
                     $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 0);
                 //$this->Ln();
     
             }
         }
     


        // echo  $student ;

   

    $pdf = new PDF("L",'mm','A4');
    $pdf->AliasNbPages();
 
    $pdf->AddPage();
    // $pdf->setFont('Arial', '', 8);
   // $today = date('M d,Y');
    //Logo .
    $pdf->setFont('Arial', 'B', 15);
    $pdf->Banner($organizationName, $organizationPicture, $phone, $email, $website, $postal,$proName,$img);
    $pdf->Ln(57);
            $pdf->setFont('Arial', 'B', 8);
            $pdf->Cell(5, 6, '');
            $pdf->Cell(350, 6, '                     
                        REG.NO:      ' .  $regNum . '                       NAME:     ' .                      $name . '                 
                             DATE OF BIRTH:     ' . $db->getData('student', 'dateOfBirth', 'registrationNumber', $regNum));
            $pdf->Ln(6);  
            
        //    echo  $db->countLevel($regNum, $selectedLevels);
     $data =0;
           
            foreach ( $selectedLevels as $levelID) {
                # code...
                $data++;
               
        
                 $students = $db->getLevel($regNum, $levelID);

                 
                 
        
                 foreach ($students as $level) {
                    # code...
                    
                   $selectedLevelsID =  $level['programmeLevelID'];
                   $programLevel= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=> $selectedLevelsID ),'order_by'=>'programmeLevelID ASC'));
                   foreach ($programLevel as $levelName ) {
                    # code...
      
                    $levelName =  $levelName['programmeLevel'];

                   $YearID= $db->getRows('student_programme',array('where'=>array('programmeLevelID'=> $selectedLevelsID,'regNumber'=> $regNum ),'order_by'=>'academicYearID ASC'));
                   foreach ($YearID as $year ) {
                    //   # code...
                    //   $countlevel++;
                      $academicYearID =  $year['academicYearID'];
                      
                     $programmeID=$year['programmeID'];
        
                      $Yearname= $db->getRows('academic_year',array('where'=>array('academicYearID'=>  $academicYearID),'order_by'=>'academicYearID ASC'));
                      foreach ($Yearname as $yearname ) {
                         # code...
         
                         $academicYearName =  $yearname['academicYear'];
         
                      }
                    //   echo $academicYearName;
                   }
                    }
                 }
                
              
               // echo $academicYearName;
           // echo $countlevel;
          
             //echo $selectedLevelsID ;
             $countInner= (int) 0; 
             if(  $countLevel == 1){
                # code...
                //$selectedLevelsID ;
                $pdf->Ln(1);  
                $pdf->SetX(($pdf->GetPageWidth() - 210) / 2);
                // $pdf->cell(width,height,text,border,endline,align);
                $pdf->Cell(210, 7,  $levelName .'  '.$academicYearName , 1, 1, 'C');
    
                $pdf->SetFont('Arial', '', 10);
                
                $pdf->SetX(($pdf->GetPageWidth() - 210) / 2);
                    
                    $pdf->Cell(20, 7, 'Code', 1, 0, 'C');
                    $pdf->Cell(140, 7, 'Subjects', 1, 0, 'C');
                    $pdf->Cell(50, 7, 'Average Performance', 1, 1, 'C');
    
    
    
                $pdf->SetX(($pdf->GetPageWidth() - 210) / 2); 
                    $pdf->SetFont('Arial', '',8);            
                    $pdf->Cell(20, 7, '', 1, 0, 'C');
                    $pdf->Cell(140, 7, 'CORE  SUBJECT:  ', 1, 0, 'C');
                    $pdf->Cell(50, 7, ' ', 1, 1, 'C');
               // echo $academicYearID;
                 
                    $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
    
                    foreach ($examNumber as $number) {
                        # code...
                       
                       $exam_nr =  $number['examNumber'];
                       $programmeID =  $number['programmeID'];
                       $regN =  $number['regNumber'];
                    }

                   $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);

                   foreach ($Std as $cs) {
                    # code...
                  

                    
                    $examScore =  $cs['examScore'];
                   $courseCode =  $cs['courseCode'];
                  $courseID =  $cs['courseID'];  
                 $courseName =  $cs['courseName'];  
                   $courseTypeID=  $cs['courseCategoryID']; 
                   $courseCategory=  $cs['courseCategory'];

                     $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                        $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                        $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                        $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                        $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                

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
                        $grade = $db->calculateTermGrade($totalMarks);

                         
                        if($courseCategory=='Core Subjects'){
                            $pdf->SetX(($pdf->GetPageWidth() - 210) / 2);
                           $pdf->Cell(20, 7, $courseCode, 1, 0, 'C');
                           $pdf->Cell(140, 7, $courseName, 1, 0, 'L');
                           $pdf->Cell(50, 7, $grade, 1, 1, 'C');
                       }

                       
                         
                }


                $pdf->SetX(($pdf->GetPageWidth() - 210) / 2); 
                $pdf->SetFont('Arial', '',8);                   
                    $pdf->Cell(20, 7, '', 1, 0, 'C');
                    $pdf->Cell(140, 7, 'GENERAL  SUBJECT:  ', 1, 0, 'C');
                    $pdf->Cell(50, 7, ' ', 1, 1, 'C');
               // echo $academicYearID;
                 
                    $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
    
                    foreach ($examNumber as $number) {
                        # code...
                       
                       $exam_nr =  $number['examNumber'];
                       $programmeID =  $number['programmeID'];
                       $regN =  $number['regNumber'];
                    }

                   $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);

                   foreach ($Std as $cs) {
                    # code...
                  

                    
                    $examScore =  $cs['examScore'];
                   $courseCode =  $cs['courseCode'];
                  $courseID =  $cs['courseID'];  
                 $courseName =  $cs['courseName'];  
                   $courseTypeID=  $cs['courseCategoryID']; 
                   $courseCategory=  $cs['courseCategory'];

                     $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                        $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                        $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                        $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                        $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                

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
                        $grade = $db->calculateTermGrade($totalMarks);

                         
                        if($courseCategory=='General Subjects'){
                            $pdf->SetX(($pdf->GetPageWidth() - 210) / 2);
                           $pdf->Cell(20, 7, $courseCode, 1, 0, 'C');
                           $pdf->Cell(140, 7, $courseName, 1, 0, 'L');
                           $pdf->Cell(50, 7, $grade, 1, 1, 'C');
                       }

                       
                         
                }
                $pdf->Ln(5);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(100,6,"                                                CORE SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Cell(100,6,"                                                                                            GENERAL SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Ln(8);
    $pdf->Cell(100,6,"                                                          ...................................");
    $pdf->Cell(100,6,"                                                                                                                              ...................................");
    
    $pdf->Ln(6);
    $pdf->Cell(100,6,"                                                          EXECUTIVE DIRECTOR");
    $pdf->Cell(100,6,"                               OFFICIAL STAMP");
    $pdf->Cell(100,6,"  CENTER MANAGER");



    $pdf->Ln(7);
    $pdf->Cell(100,6,"                                                            Grading and Interpretation:  A=100-80 Competent,    B=79-60 Competent,    C=59-50 Competent,    D=49-40 Competent,    F=39-0 Not competent");
    
                
    
                
                
                
             }

            if($countLevel == 2)
            {
                // echo $academicYearName;

                $pageWidth = 297; // A4 width in mm
                $pageHeight = 78.3; // A4 height in mm
                $marginLeft = 10; // Left margin in mm
                $marginRight = 10; // Right margin in mm

                if ($data == 1) {
                   
                    //  echo $data;
                     //echo $academicYearName;
                    // echo $academicYearName;
                    // echo $countlevel;
                    
                    //echo $selectedLevelsID ;
                    

                    // Calculate the coordinates of the dividing line
                    $divideX = $marginLeft + ($pageWidth - $marginLeft - $marginRight) / 2;
                    //echo $dx=($divideX - $marginLeft )/ 3;
                    // Draw the dividing line
                    $pdf->Rect($divideX, 73, 2, $pageHeight);
                    $pdf->SetXY($marginLeft, 73); // Adjust the Y position as needed for centering
                    $pdf->Cell($divideX - $marginLeft, 10,  $levelName .'  '.$academicYearName, 1, 0, 'C');
                    // Left half
                    $pdf->SetXY($marginLeft, 83); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 7, 'Code', 1, 0, 'C');
                    $pdf->Cell(59.24, 7, 'Subjects', 1, 0, 'C');
                    $pdf->Cell(59.24, 7, 'Average Performance', 1, 1, 'C');
                    $pdf->Ln();

                    $pdf->SetXY($marginLeft,90); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 6, '', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, 'Core Subject', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, '', 1, 1, 'C');

                    //echo $dx=($divideX - $marginLeft )/ 3;

                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
    
                     foreach ($examNumber as $number) {
                         # code...
                        
                        $exam_nr =  $number['examNumber'];
                        $programmeID =  $number['programmeID'];
                        $regN =  $number['regNumber'];
                     }

                     $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                     foreach ($Std as $cs) 
                    {
                        # code...
                      
    
                        
                        $examScore =  $cs['examScore'];
                       $courseCode =  $cs['courseCode'];
                      $courseID =  $cs['courseID'];  
                     $courseName =  $cs['courseName'];  
                       $courseTypeID=  $cs['courseCategoryID']; 
                       $courseCategory=  $cs['courseCategory'];
    
                         $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                            $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                            $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                            $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                            $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                    
    
                                 $exam_category_marks = $db->getTermCategorySetting();
                          if (!empty($exam_category_marks))
                           {
                               foreach ($exam_category_marks as $gd)
                                {
                                   $mMark = $gd['mMark'];
                                   $pMark = $gd['passMark'];
                                  $wMark = $gd['wMark'];
                                }
                            }

                            $term1m = ($term1Score / $mMark) * $wMark;
                         $term2m = ($term2Score / $mMark) * $wMark;
 
                          $finalm1 = ($finalScore / 100) * 50;
                         $tMarks = round($term1m + $term2m + $finalm1);
 
                          if ($tMarks>=35 && $tMarks<40 ) 
                          {
                             $addmarks=40-$tMarks;
                          }
                         else
                         {
                              $addmarks=0;
                             
                          }

                          $finalScore = $finalScore + $addmarks;
                         $finalm=$finalm1+$addmarks;
                         $totalMarks = round($term1m + $term2m + $finalm);
                         $grade = $db->calculateTermGrade($totalMarks);
 
                          
                         if($courseCategory=='Core Subjects'){
                             
                            $pdf->Cell(20, 7, $courseCode, 1, 0, 'C');
                            $pdf->Cell(59.24, 7, $courseName, 1, 0, 'L');
                            $pdf->Cell(59.24, 7, $grade, 1, 1, 'C');

                            // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                            // $pdf->Cell(20, 8, '', 1, 0, 'C');
                            // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                            // $pdf->Cell(59, 8, '', 1, 1, 'C');
       
                            
                        }
                        
 
                    }

                    $pdf->SetXY($marginLeft, 117.3); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 6, '', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, 'General Subject', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, ' ', 1, 1, 'C');
                    foreach ($examNumber as $number) {
                        # code...
                       
                       $exam_nr =  $number['examNumber'];
                       $programmeID =  $number['programmeID'];
                       $regN =  $number['regNumber'];
                    }
    
                   $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
    
                   foreach ($Std as $cs) 
                   {$pdf->SetFont('Arial', 'B', 8); // Set font to Arial, bold, size 10

                    $examScore =  $cs['examScore'];
                    $courseCode =  $cs['courseCode'];
                    $courseID =  $cs['courseID'];  
                    $courseName =  $cs['courseName'];  
                    $courseTypeID=  $cs['courseCategoryID']; 
                    $courseCategory=  $cs['courseCategory'];

                    $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                    $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                    $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                    $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                    $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));


                    $exam_category_marks = $db->getTermCategorySetting();
                    if (!empty($exam_category_marks)) 
                    {
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
                     $grade = $db->calculateTermGrade($totalMarks);
                     

                     if($courseCategory=='General Subjects')
                     {
                        
                        $pdf->Cell(20, 7, $courseCode, 1, 0, 'C');
                        $pdf->Cell(59.24, 7, $courseName, 1, 0, 'L');
                        $pdf->Cell(59.24, 7, $grade, 1, 1, 'C');
 
                        // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                        // $pdf->Cell(20, 8, '', 1, 0, 'C');
                        // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                        // $pdf->Cell(59, 8, '', 1, 1, 'C');
   
                        
                    }


                   }
 


                }
                else
                {
                    // echo $data;
                    //echo $academicYearName;

                    // $pdf->SetXY($divideX + 2, 99); // Adjust the Y position as needed for centering
                    // $pdf->Cell(20, 6, '', 1, 0, 'C');
                    // $pdf->Cell(58.25,6, 'General Subjects', 1, 0, 'C');
                    // $pdf->Cell(58.25, 6, '', 1, 1, 'C');
                    $pdf->SetFont('Arial', 'B', 8); // Set font to Arial, bold, size 10
                    $pdf->SetXY($divideX + 2, 73.1); // Adjust the Y position as needed for centering
                    $pdf->Cell($divideX - $marginLeft, 10,  $levelName .'  '.$academicYearName, 1, 0, 'C');
                    // Left half
                    $pdf->SetFont('Arial', 'B', 8); // Set font to Arial, bold, size 10
                    $pdf->SetXY($divideX + 2, 83); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 7, 'Code', 1, 0, 'C');
                    $pdf->Cell(59.24, 7, 'Subjects', 1, 0, 'C');
                    $pdf->Cell(59.24, 7, 'Average Performance', 1, 1, 'C');
                    $pdf->Ln();
                    $pdf->SetFont('Arial', 'B', 8); // Set font to Arial, bold, size 10
                    $pdf->SetXY($divideX + 2, 90); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 6, '', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, 'Core Subject', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, '', 1, 1, 'C');

                    //echo $dx=($divideX - $marginLeft )/ 3;

                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                            // Set the width of each column in the table
                  $columnWidths = array(20, 59.24, 59.24);
                     foreach ($examNumber as $number) {
                         # code...
                        
                        $exam_nr =  $number['examNumber'];
                        $programmeID =  $number['programmeID'];
                        $regN =  $number['regNumber'];
                     }

                     $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                     foreach ($Std as $cs) 
                    {
                        # code...
                      
    
                        
                        $examScore =  $cs['examScore'];
                       $courseCode =  $cs['courseCode'];
                      $courseID =  $cs['courseID'];  
                     $courseName =  $cs['courseName'];  
                       $courseTypeID=  $cs['courseCategoryID']; 
                       $courseCategory=  $cs['courseCategory'];
    
                         $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                            $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                            $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                            $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                            $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                    
    
                                 $exam_category_marks = $db->getTermCategorySetting();
                          if (!empty($exam_category_marks))
                           {
                               foreach ($exam_category_marks as $gd)
                                {
                                   $mMark = $gd['mMark'];
                                   $pMark = $gd['passMark'];
                                  $wMark = $gd['wMark'];
                                }
                            }

                            $term1m = ($term1Score / $mMark) * $wMark;
                         $term2m = ($term2Score / $mMark) * $wMark;
 
                          $finalm1 = ($finalScore / 100) * 50;
                         $tMarks = round($term1m + $term2m + $finalm1);
 
                          if ($tMarks>=35 && $tMarks<40 ) 
                          {
                             $addmarks=40-$tMarks;
                          }
                         else
                         {
                              $addmarks=0;
                             
                          }

                          $finalScore = $finalScore + $addmarks;
                         $finalm=$finalm1+$addmarks;
                         $totalMarks = round($term1m + $term2m + $finalm);
                         $grade = $db->calculateTermGrade($totalMarks);
 
                          
                         if($courseCategory=='Core Subjects'){
                            $pdf->SetX(150.5); // Adjust the X position according to your desired location
                            $pdf->Cell($columnWidths[0], 7, $courseCode, 1, 0, 'L');
                            $pdf->Cell($columnWidths[1], 7, $courseName, 1, 0, 'L');
                            $pdf->Cell($columnWidths[2], 7, $grade, 1, 1, 'C');

                            // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                            // $pdf->Cell(20, 8, '', 1, 0, 'C');
                            // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                            // $pdf->Cell(59, 8, '', 1, 1, 'C');
       
                            
                        }
                        
 
                    }
                   
                    $pdf->SetXY($divideX + 2, 117); // Adjust the Y position as needed for centering
                    $pdf->Cell(20, 6, '', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, 'General Subject', 1, 0, 'C');
                    $pdf->Cell(59.24, 6, ' ', 1, 1, 'C');
                    foreach ($examNumber as $number) {
                        # code...
                       
                       $exam_nr =  $number['examNumber'];
                       $programmeID =  $number['programmeID'];
                       $regN =  $number['regNumber'];
                    }
    
                   $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
    
                   foreach ($Std as $cs) 
                   {

                    $examScore =  $cs['examScore'];
                    $courseCode =  $cs['courseCode'];
                    $courseID =  $cs['courseID'];  
                    $courseName =  $cs['courseName'];  
                    $courseTypeID=  $cs['courseCategoryID']; 
                    $courseCategory=  $cs['courseCategory'];

                    $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                    $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                    $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                    $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                    $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));


                    $exam_category_marks = $db->getTermCategorySetting();
                    if (!empty($exam_category_marks)) 
                    {
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
                     $grade = $db->calculateTermGrade($totalMarks);
                     
                    
                     if($courseCategory=='General Subjects')
                     {
                        
                        $pdf->SetX(150.5);
                        $pdf->Cell(20, 7.05, $courseCode, 1, 0, 'L');
                        $pdf->Cell(59.24, 7.05, $courseName, 1, 0, 'L');
                        $pdf->Cell(59.24, 7.05, $grade, 1, 1, 'C');
 
                        // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                        // $pdf->Cell(20, 8, '', 1, 0, 'C');
                        // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                        // $pdf->Cell(59, 8, '', 1, 1, 'C');
   
                        
                    }


                   }
              }



              $pdf->Ln(5);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(100,6,"                                                CORE SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Cell(100,6,"                                                                                            GENERAL SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Ln(8);
    $pdf->Cell(100,6,"                                                          ...................................");
    $pdf->Cell(100,6,"                                                                                                                              ...................................");
    
    $pdf->Ln(6);
    $pdf->Cell(100,6,"                                                          EXECUTIVE DIRECTOR");
    $pdf->Cell(100,6,"                               OFFICIAL STAMP");
    $pdf->Cell(100,6,"  CENTER MANAGER");



    $pdf->Ln(7);
    $pdf->Cell(100,6,"                                             Grading and Interpretation:  A = 100 - 80 Competent,    B = 79 - 60 Competent,    C = 59 - 50 Competent,    D = 49 - 40 Competent,    F= 39 - 0 Not competent ");
    
                
               
         }

 
                        
                                    


                 
                     
                


                   
                     
            



                
                
             
             else if( $countLevel == 3){
                
                
              
                $pageWidth = 297; // A4 width in mm
                $pageHeight = 80; // A4 height in mm
                $marginLeft = 10; // Left margin in mm
                $marginRight = 10; // Right margin in mm
                
                // Calculate the coordinates of the dividing lines
                $divideX1 = $marginLeft + ($pageWidth - $marginLeft - $marginRight) / 3;
                $divideX2 = $marginLeft + 2 * ($pageWidth - $marginLeft - $marginRight) / 3;
                
                // Draw the dividing lines
                $pdf->Rect($divideX1, 73, 2, $pageHeight);
                $pdf->Rect($divideX2, 73, 2, $pageHeight);
                

                if ($data == 1) {
                    // Left section
                    $pdf->SetXY($marginLeft, 73); // Adjust the Y position as needed for centering
                    $pdf->Cell($divideX1 - $marginLeft, 10, $levelName .'  '.$academicYearName, 1, 0, 'C');
                  // echo  $f = $divideX1 - $marginLeft;

                    // Left half
                    $pdf->SetXY(10, 83); // Adjust the Y position as needed for centering
                    $pdf->Cell(14, 7, 'Code', 1, 0, 'C');
                    $pdf->Cell(44.7, 7, 'Subjects', 1, 0, 'C');
                    $pdf->Cell(33.4, 7, 'Average Performance', 1, 1, 'C');

                    $pdf->SetXY(10, 90); // Adjust the Y position as needed for centering
                    $pdf->Cell(14, 7, '', 1, 0, 'C');
                    $pdf->Cell(44.7, 7, 'Core Subjects', 1, 0, 'C');
                    $pdf->Cell(33.4, 7, '', 1, 1, 'C');


                    $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                            // Set the width of each column in the table
                  $columnWidths = array(20, 59.24, 59.24);
                     foreach ($examNumber as $number) {
                         # code...
                        
                        $exam_nr =  $number['examNumber'];
                        $programmeID =  $number['programmeID'];
                        $regN =  $number['regNumber'];
                     }

                     $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                     foreach ($Std as $cs) 
                    {
                        # code...
                      
    
                        
                        $examScore =  $cs['examScore'];
                       $courseCode =  $cs['courseCode'];
                      $courseID =  $cs['courseID'];  
                     $courseName =  $cs['courseName'];  
                       $courseTypeID=  $cs['courseCategoryID']; 
                       $courseCategory=  $cs['courseCategory'];
    
                         $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                            $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                            $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                            $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                            $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                    
    
                                 $exam_category_marks = $db->getTermCategorySetting();
                          if (!empty($exam_category_marks))
                           {
                               foreach ($exam_category_marks as $gd)
                                {
                                   $mMark = $gd['mMark'];
                                   $pMark = $gd['passMark'];
                                  $wMark = $gd['wMark'];
                                }
                            }

                            $term1m = ($term1Score / $mMark) * $wMark;
                         $term2m = ($term2Score / $mMark) * $wMark;
 
                          $finalm1 = ($finalScore / 100) * 50;
                         $tMarks = round($term1m + $term2m + $finalm1);
 
                          if ($tMarks>=35 && $tMarks<40 ) 
                          {
                             $addmarks=40-$tMarks;
                          }
                         else
                         {
                              $addmarks=0;
                             
                          }

                          $finalScore = $finalScore + $addmarks;
                         $finalm=$finalm1+$addmarks;
                         $totalMarks = round($term1m + $term2m + $finalm);
                         $grade = $db->calculateTermGrade($totalMarks);
 
                          
                         if($courseCategory=='Core Subjects'){
                            $pdf->SetX(10); // Adjust the X position according to your desired location
                            $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                            $pdf->Cell(44.7, 7, $courseName, 1, 0, 'L');
                            $pdf->Cell(33.4, 7, $grade, 1, 1, 'C');

                            // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                            // $pdf->Cell(20, 8, '', 1, 0, 'C');
                            // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                            // $pdf->Cell(59, 8, '', 1, 1, 'C');
       
                            
                        }
                    }


                    // Left half
                    

                    $pdf->SetXY(10, 118); // Adjust the Y position as needed for centering
                    $pdf->Cell(14, 7, '', 1, 0, 'C');
                    $pdf->Cell(44.7, 7, 'General Subjects', 1, 0, 'C');
                    $pdf->Cell(33.4, 7, '', 1, 1, 'C');


                    $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                            // Set the width of each column in the table
                  $columnWidths = array(20, 59.24, 59.24);
                     foreach ($examNumber as $number) {
                         # code...
                        
                        $exam_nr =  $number['examNumber'];
                        $programmeID =  $number['programmeID'];
                        $regN =  $number['regNumber'];
                     }

                     $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                     foreach ($Std as $cs) 
                    {
                        # code...
                      
    
                        
                        $examScore =  $cs['examScore'];
                       $courseCode =  $cs['courseCode'];
                      $courseID =  $cs['courseID'];  
                     $courseName =  $cs['courseName'];  
                       $courseTypeID=  $cs['courseCategoryID']; 
                       $courseCategory=  $cs['courseCategory'];
    
                         $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                            $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                            $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                            $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                            $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                    
    
                                 $exam_category_marks = $db->getTermCategorySetting();
                          if (!empty($exam_category_marks))
                           {
                               foreach ($exam_category_marks as $gd)
                                {
                                   $mMark = $gd['mMark'];
                                   $pMark = $gd['passMark'];
                                  $wMark = $gd['wMark'];
                                }
                            }

                            $term1m = ($term1Score / $mMark) * $wMark;
                         $term2m = ($term2Score / $mMark) * $wMark;
 
                          $finalm1 = ($finalScore / 100) * 50;
                         $tMarks = round($term1m + $term2m + $finalm1);
 
                          if ($tMarks>=35 && $tMarks<40 ) 
                          {
                             $addmarks=40-$tMarks;
                          }
                         else
                         {
                              $addmarks=0;
                             
                          }

                          $finalScore = $finalScore + $addmarks;
                         $finalm=$finalm1+$addmarks;
                         $totalMarks = round($term1m + $term2m + $finalm);
                         $grade = $db->calculateTermGrade($totalMarks);
 
                          
                         if($courseCategory=='General Subjects'){
                            $pdf->SetX(10); // Adjust the X position according to your desired location
                            $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                            $pdf->Cell(44.7, 7, $courseName, 1, 0, 'L');
                            $pdf->Cell(33.4, 7, $grade, 1, 1, 'C');

                            // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                            // $pdf->Cell(20, 8, '', 1, 0, 'C');
                            // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                            // $pdf->Cell(59, 8, '', 1, 1, 'C');
       
                            
                        }
                    }



                }else if($data == 2){

                    // Middle section
                    $pdf->SetDrawColor(0, 0, 0);
                    $pdf->SetXY($divideX1 + 2, 73); // Adjust the Y position as needed for centering
                    $pdf->Cell($divideX2 - $divideX1 - 2, 10,  $levelName .'  '.$academicYearName, 1, 0, 'C');

                     // Left half
                     $pdf->SetXY(104.5, 83); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, 'Code', 1, 0, 'C');
                     $pdf->Cell(44.7, 7, 'Subjects', 1, 0, 'C');
                     $pdf->Cell(31.4, 7, 'Average Performance', 1, 1, 'C');
 
                     $pdf->SetXY(104.5, 90); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, '', 1, 0, 'C');
                     $pdf->Cell(44.7, 7, 'Core Subjects', 1, 0, 'C');
                     $pdf->Cell(31.4, 7, '', 1, 1, 'C');
 
 
                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                             // Set the width of each column in the table
                   $columnWidths = array(20, 59.24, 59.24);
                      foreach ($examNumber as $number) {
                          # code...
                         
                         $exam_nr =  $number['examNumber'];
                         $programmeID =  $number['programmeID'];
                         $regN =  $number['regNumber'];
                      }
 
                      $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                      foreach ($Std as $cs) 
                     {
                         # code...
                       
     
                         
                         $examScore =  $cs['examScore'];
                        $courseCode =  $cs['courseCode'];
                       $courseID =  $cs['courseID'];  
                      $courseName =  $cs['courseName'];  
                        $courseTypeID=  $cs['courseCategoryID']; 
                        $courseCategory=  $cs['courseCategory'];
     
                          $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                             $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                             $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                             $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                             $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                     
     
                                  $exam_category_marks = $db->getTermCategorySetting();
                           if (!empty($exam_category_marks))
                            {
                                foreach ($exam_category_marks as $gd)
                                 {
                                    $mMark = $gd['mMark'];
                                    $pMark = $gd['passMark'];
                                   $wMark = $gd['wMark'];
                                 }
                             }
 
                             $term1m = ($term1Score / $mMark) * $wMark;
                          $term2m = ($term2Score / $mMark) * $wMark;
  
                           $finalm1 = ($finalScore / 100) * 50;
                          $tMarks = round($term1m + $term2m + $finalm1);
  
                           if ($tMarks>=35 && $tMarks<40 ) 
                           {
                              $addmarks=40-$tMarks;
                           }
                          else
                          {
                               $addmarks=0;
                              
                           }
 
                           $finalScore = $finalScore + $addmarks;
                          $finalm=$finalm1+$addmarks;
                          $totalMarks = round($term1m + $term2m + $finalm);
                          $grade = $db->calculateTermGrade($totalMarks);
  
                           
                          if($courseCategory=='Core Subjects'){
                             $pdf->SetX(104.5); // Adjust the X position according to your desired location
                             $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                             $pdf->Cell(44.7, 7, $courseName, 1, 0, 'L');
                             $pdf->Cell(31.4, 7, $grade, 1, 1, 'C');
 
                             // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                             // $pdf->Cell(20, 8, '', 1, 0, 'C');
                             // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                             // $pdf->Cell(59, 8, '', 1, 1, 'C');
        
                             
                         }
                     }
 
 
                     // Left half
                     
                   
                     $pdf->SetXY(104.5, 118); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, '', 1, 0, 'C');
                     $pdf->Cell(44.7, 7, 'General Subjects', 1, 0, 'C');
                     $pdf->Cell(31.4, 7, '', 1, 1, 'C');
 
 
                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                             // Set the width of each column in the table
                   $columnWidths = array(20, 59.24, 59.24);
                      foreach ($examNumber as $number) {
                          # code...
                         
                         $exam_nr =  $number['examNumber'];
                         $programmeID =  $number['programmeID'];
                         $regN =  $number['regNumber'];
                      }
 
                      $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                      foreach ($Std as $cs) 
                     {
                         # code...
                       
     
                         
                         $examScore =  $cs['examScore'];
                        $courseCode =  $cs['courseCode'];
                       $courseID =  $cs['courseID'];  
                      $courseName =  $cs['courseName'];  
                        $courseTypeID=  $cs['courseCategoryID']; 
                        $courseCategory=  $cs['courseCategory'];
     
                          $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                             $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                             $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                             $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                             $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                     
     
                                  $exam_category_marks = $db->getTermCategorySetting();
                           if (!empty($exam_category_marks))
                            {
                                foreach ($exam_category_marks as $gd)
                                 {
                                    $mMark = $gd['mMark'];
                                    $pMark = $gd['passMark'];
                                   $wMark = $gd['wMark'];
                                 }
                             }
 
                             $term1m = ($term1Score / $mMark) * $wMark;
                          $term2m = ($term2Score / $mMark) * $wMark;
  
                           $finalm1 = ($finalScore / 100) * 50;
                          $tMarks = round($term1m + $term2m + $finalm1);
  
                           if ($tMarks>=35 && $tMarks<40 ) 
                           {
                              $addmarks=40-$tMarks;
                           }
                          else
                          {
                               $addmarks=0;
                              
                           }
 
                           $finalScore = $finalScore + $addmarks;
                          $finalm=$finalm1+$addmarks;
                          $totalMarks = round($term1m + $term2m + $finalm);
                          $grade = $db->calculateTermGrade($totalMarks);
  
                          
                          if($courseCategory=='General Subjects'){
                            $pdf->SetFont('Arial', 'B', 8); 
                             $pdf->SetX(104.5); // Adjust the X position according to your desired location
                             $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                             $pdf->Cell(44.7, 7, $courseName, 1, 0, 'L');
                             $pdf->Cell(31.4, 7, $grade, 1, 1, 'C');
 
                             // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                             // $pdf->Cell(20, 8, '', 1, 0, 'C');
                             // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                             // $pdf->Cell(59, 8, '', 1, 1, 'C');
        
                             
                         }
                     }
 
 
                

                }
                
                
                else{

                    // Right section
                    $pdf->SetXY($divideX2 + 2, 73); // Adjust the Y position as needed for centering
                    $pdf->Cell($pageWidth - $marginRight - $divideX2 - 2, 10,  $levelName .'  '.$academicYearName, 1, 0, 'C');



                     // Left half
                        $pdf->SetFont('Arial', 'B', 8); 
                     $pdf->SetXY(196.5, 83); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, 'Code', 1, 0, 'C');
                     $pdf->Cell(44.9, 7, 'Subjects', 1, 0, 'C');
                     $pdf->Cell(31.6, 7, 'Average Performance', 1, 1, 'C');
 
                     $pdf->SetXY(196.5, 90); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, '', 1, 0, 'C');
                     $pdf->Cell(44.9, 7, 'Core Subjects', 1, 0, 'C');
                     $pdf->Cell(31.6, 7, '', 1, 1, 'C');
                     
 
                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                             // Set the width of each column in the table
                   $columnWidths = array(20, 59.24, 59.24);
                      foreach ($examNumber as $number) {
                          # code...
                         
                         $exam_nr =  $number['examNumber'];
                         $programmeID =  $number['programmeID'];
                         $regN =  $number['regNumber'];
                      }
 
                      $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                      foreach ($Std as $cs) 
                     {
                         # code...
                       
     
                         
                         $examScore =  $cs['examScore'];
                        $courseCode =  $cs['courseCode'];
                       $courseID =  $cs['courseID'];  
                      $courseName =  $cs['courseName'];  
                        $courseTypeID=  $cs['courseCategoryID']; 
                        $courseCategory=  $cs['courseCategory'];
     
                          $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                             $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                             $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                             $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                             $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                     
     
                                  $exam_category_marks = $db->getTermCategorySetting();
                           if (!empty($exam_category_marks))
                            {
                                foreach ($exam_category_marks as $gd)
                                 {
                                    $mMark = $gd['mMark'];
                                    $pMark = $gd['passMark'];
                                   $wMark = $gd['wMark'];
                                 }
                             }
 
                             $term1m = ($term1Score / $mMark) * $wMark;
                          $term2m = ($term2Score / $mMark) * $wMark;
  
                           $finalm1 = ($finalScore / 100) * 50;
                          $tMarks = round($term1m + $term2m + $finalm1);
  
                           if ($tMarks>=35 && $tMarks<40 ) 
                           {
                              $addmarks=40-$tMarks;
                           }
                          else
                          {
                               $addmarks=0;
                              
                           }
 
                           $finalScore = $finalScore + $addmarks;
                          $finalm=$finalm1+$addmarks;
                          $totalMarks = round($term1m + $term2m + $finalm);
                          $grade = $db->calculateTermGrade($totalMarks);
  
                           
                          if($courseCategory=='Core Subjects'){
                            $pdf->SetX(196.5); // Adjust the X position according to your desired location
                            $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                             $pdf->Cell(44.9, 7, $courseName, 1, 0, 'L');
                             $pdf->Cell(31.6, 7, $grade, 1, 1, 'C');
 
                             // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                             // $pdf->Cell(20, 8, '', 1, 0, 'C');
                             // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                             // $pdf->Cell(59, 8, '', 1, 1, 'C');
        
                             
                      }
                     }
 
 
                     // Left half
                     
 
                     $pdf->SetXY(196.5, 118); // Adjust the Y position as needed for centering
                     $pdf->Cell(14, 7, '', 1, 0, 'C');
                     $pdf->Cell(44.9, 7, 'General Subjects', 1, 0, 'C');
                     $pdf->Cell(31.6, 7, '', 1, 1, 'C');
 
 
                     $examNumber= $db->getRows('exam_number',array('where'=>array('regNumber'=> $regNum,'academicYearID'=> $academicYearID),'order_by'=>'regNumber ASC'));
                             // Set the width of each column in the table
                   $columnWidths = array(20, 59.24, 59.24);
                      foreach ($examNumber as $number) {
                          # code...
                         
                         $exam_nr =  $number['examNumber'];
                         $programmeID =  $number['programmeID'];
                         $regN =  $number['regNumber'];
                      }
 
                      $Std = $db->getcourseStudent($regN, $exam_nr ,$academicYearID);
                      foreach ($Std as $cs) 
                     {
                         # code...
                       
     
                         
                         $examScore =  $cs['examScore'];
                        $courseCode =  $cs['courseCode'];
                       $courseID =  $cs['courseID'];  
                      $courseName =  $cs['courseName'];  
                        $courseTypeID=  $cs['courseCategoryID']; 
                        $courseCategory=  $cs['courseCategory'];
     
                          $courseGrade = $db->getCourseCreditstudent($selectedLevelsID, $programmeID);
                             $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 1));
                             $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regN, 2));
                             $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 3));
                             $suppScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID,   $exam_nr , 5));
                                     
     
                                  $exam_category_marks = $db->getTermCategorySetting();
                           if (!empty($exam_category_marks))
                            {
                                foreach ($exam_category_marks as $gd)
                                 {
                                    $mMark = $gd['mMark'];
                                    $pMark = $gd['passMark'];
                                   $wMark = $gd['wMark'];
                                 }
                             }
 
                             $term1m = ($term1Score / $mMark) * $wMark;
                          $term2m = ($term2Score / $mMark) * $wMark;
  
                           $finalm1 = ($finalScore / 100) * 50;
                          $tMarks = round($term1m + $term2m + $finalm1);
  
                           if ($tMarks>=35 && $tMarks<40 ) 
                           {
                              $addmarks=40-$tMarks;
                           }
                          else
                          {
                               $addmarks=0;
                              
                           }
 
                           $finalScore = $finalScore + $addmarks;
                          $finalm=$finalm1+$addmarks;
                          $totalMarks = round($term1m + $term2m + $finalm);
                          $grade = $db->calculateTermGrade($totalMarks);
  
                           
                          if($courseCategory=='General Subjects'){
                             $pdf->SetX(196.5); // Adjust the X position according to your desired location
                             $pdf->Cell(14, 7, $courseCode, 1, 0, 'L');
                             $pdf->Cell(44.9, 7, $courseName, 1, 0, 'L');
                             $pdf->Cell(31.6, 7, $grade, 1, 1, 'C');
 
                             // $pdf->SetXY($marginLeft, 93); // Adjust the Y position as needed for centering
                             // $pdf->Cell(20, 8, '', 1, 0, 'C');
                             // $pdf->Cell(59, 8, 'Core Subject', 1, 0, 'C');
                             // $pdf->Cell(59, 8, '', 1, 1, 'C');
        
                             
                         }
                     }

                     $pdf->Ln(5);
                     $pdf->SetFont('Arial', 'B', 8); // Set font to Arial, bold, size 10
    $pdf->Cell(100,6,"                                                CORE SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Cell(100,6,"                                                                                            GENERAL SUBJECT - AVERAGE PERFORMANCE");
    $pdf->Ln(8);
    $pdf->Cell(100,6,"                                                          ...................................");
    $pdf->Cell(100,6,"                                                                                                                              ...................................");
    
    $pdf->Ln(6);
    $pdf->Cell(100,6,"                                                          EXECUTIVE DIRECTOR");
    $pdf->Cell(100,6,"                               OFFICIAL STAMP");
    $pdf->Cell(100,6,"  CENTER MANAGER");



    $pdf->Ln(7);
    $pdf->Cell(100,6,"                                                      Grading and Interpretation:  A = 100 - 80 Competent,    B = 79 - 60 Competent,    C = 59 - 50 Competent,    D = 49 - 40 Competent,    F= 39 - 0 Not competent ");
    
                

                }
                
                
                
             }
                
                                      
                                    
             
            
        
                
        
             
   
       

            }
    
    $pdf->Output();
}
else{
    header("Location:index3.php?sp=student_academic_reports&msg=unsucc");
}

}