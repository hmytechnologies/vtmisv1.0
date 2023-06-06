<?php
                    
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



                        }