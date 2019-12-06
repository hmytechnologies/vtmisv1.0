 <?php 
//include("DB.php");
$db=new DBHelper();
 /*function getGrade($semisterID,$academicYearID,$courseID,$studentID,$examCategoryID)
 {
    $score=$db->getRows('exam_result',array('where'=>array('student_id'=>$studentID,'academic_year_id'=>$academicYearID,'semister_id'=>$semisterID,'course_id'=>$courseID,'exam_category_id'=>$examCategoryID),' order_by'=>'student_id ASC'));
    if(!empty($score))
    {
      foreach ($score as $sc) {
        $examScore=$sc['exam_score'];
        return $examScore;
        # code...
      }
    }
    else
    {
      $examScore="NIL";
      return $examScore;
    }
 }*/
                            //{
                            $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,$examCategoryID);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $examScore=$exm['exam_score'];
                            }
                          }
                          else
                            $examScore="";
                          return $examScore;
                        //}
                         $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,2);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $sfe=$exm['exam_score'];
                              //echo "<td>".$cw['exam_score']."</td>";
                            }
                          }
                          else
                            $sfe="";
                         $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,3);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $sup=$exm['exam_score'];
                              //echo "<td>".$cw['exam_score']."</td>";
                            }
                          }
                          else
                            $sup="";
                          $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,4);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $spc=$exm['exam_score'];
                              //echo "<td>".$cw['exam_score']."</td>";
                            }
                          }
                          else
                            $spc="";

                          $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,5);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $prj=$exm['exam_score'];
                              //echo "<td>".$cw['exam_score']."</td>";
                            }
                          }
                          else
                            $prj="";

                          $exmScore=$db->getExamScore($semisterID,$academicYearID,$courseID,$studentID,6);
                            if(!empty($exmScore)){
                            foreach ($exmScore as $exm) {
                              # code...
                              $pt=$exm['exam_score'];
                              //echo "<td>".$cw['exam_score']."</td>";
                            }
                          }
                          else
                            $pt="";
                          // } 
                  


                         if(!empty($sup))
                          {
                          $tmarks=$sup;
                          if($tmarks>=50)
                          {
                            $grade="C";
                            $remarks="Pass";
                          }else
                          {
                            $grade="D";
                            $remarks="Fail";
                          }
                        }
                        else if(!empty($spc)){
                          $tmarks=$spc+$cwk;
                          if($tmarks>=80)
                                      {
                                      $grade="A";
                                      $remarks="PASS";
                                      }else if($tmarks>=65)
                                      {
                                        $grade="B";
                                        $remarks="PASS";
                                      }else if($tmarks>=50)
                                      {
                                        $grade="C";
                                        $remarks="PASS";
                                      }else if($tmarks>=40)
                                      {
                                        $grade="D";
                                        $remarks="POOR";
                                      }else{
                                        $grade="F";
                                        $remarks="FAIL";
                                      }

                        }
                        else if(!empty($pt))
                        {
                          $tmarks=$pt;
                          if($tmarks>=80)
                                      {
                                      $grade="A";
                                      $remarks="PASS";
                                      }else if($tmarks>=65)
                                      {
                                        $grade="B";
                                        $remarks="PASS";
                                      }else if($tmarks>=50)
                                      {
                                        $grade="C";
                                        $remarks="PASS";
                                      }else if($tmarks>=40)
                                      {
                                        $grade="D";
                                        $remarks="POOR";
                                      }else{
                                        $grade="F";
                                        $remarks="FAIL";
                                      }
                        }
                        else if(!empty($prj))
                        {
                          $tmarks=$prj;
                          if($tmarks>=80)
                                      {
                                      $grade="A";
                                      $remarks="PASS";
                                      }else if($tmarks>=65)
                                      {
                                        $grade="B";
                                        $remarks="PASS";
                                      }else if($tmarks>=50)
                                      {
                                        $grade="C";
                                        $remarks="PASS";
                                      }else if($tmarks>=40)
                                      {
                                        $grade="D";
                                        $remarks="POOR";
                                      }else{
                                        $grade="F";
                                        $remarks="FAIL";
                                      }
                        }
                        else {
                        if(empty($cwk))
                        {
                          $tmarks=$sfe;
                          $grade="I";
                          $remarks="Incomplete";
                        }
                        else if(empty($sfe))
                        {
                          $tmarks=$cwk;
                          $grade="I";
                          $remarks="Incomplete";
                        }
                        else
                        {
                          $tmarks=$cwk+$sfe;
                          if($tmarks>=80)
                                      {
                                      $grade="A";
                                      $remarks="PASS";
                                      }else if($tmarks>=65)
                                      {
                                        $grade="B";
                                        $remarks="PASS";
                                      }else if($tmarks>=50)
                                      {
                                        $grade="C";
                                        $remarks="PASS";
                                      }else if($tmarks>=40)
                                      {
                                        $grade="D";
                                        $remarks="POOR";
                                      }else{
                                        $grade="F";
                                        $remarks="FAIL";
                                      }
                        }
                      }
                                      
                                      

                         /*if($grade=="A")
                          $points=$units*5;
                          else if($grade=="B+")
                          $points=$units*4;
                          else if($grade=="B")
                          $points=$units*3;
                          else if($grade=="C")
                          $points=$units*2;
                          else if($grade=="D")
                          $points=$units*1;
                          else if($grade=="E")
                          $points=$units*0.5;
                          else
                          $points=0;
                          $tpoints=$tpoints+$points;
                          $tunits=$tunits+$units;
                          $gpa=round($tpoints/$tunits,2); */
                            ?>