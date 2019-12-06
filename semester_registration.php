              <?php
              $db = new DBHelper();
               $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
               if(!empty($studentID))
               {
                   $count = 0;
                   foreach($studentID as $std)
                   {
                       $count++;
                       $studentID=$std['studentID'];
                       $fname=$std['firstName'];
                       $mname=$std['middleName'];
                       $lname=$std['lastName'];
                       $gender=$std['gender'];
                       $regNumber=$std['registrationNumber'];
                       $programmeID=$std['programmeID'];
                       $statusID=$std['statusID'];
                       $batchID=$std['batchID'];
                   }
               }
               ?>
<?php 
            $today=date("Y-m-d");
            $sm=$db->readSemesterSetting($today);
            foreach ($sm as $s) 
            {
                $semisterID=$s['semesterID'];
                $academicYearID=$s['academicYearID'];
                $semesterName=$s['semesterName'];
                $semesterSettingID=$s['semesterSettingID'];
            }
            
            $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'studentID ASC'));
            if(!empty($study_year))
            {
                foreach ($study_year as $sy) {
                    $studyYear=$sy['studyYear'];
                }
            }
?>
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-8">
          
              <?php
                //$courseList = $db->getRows('courseprogramme',array('where'=>array('programmeID'=>$programmeID,'academicYearID'=>$academicYearID,'semesterID'=>$semisterID,'studyYear'=>1),' order_by'=>' courseID ASC'));

              $courseList = $db->filterRecords($programmeID,$semisterID,$batchID,$studyYear,$regNumber);
                if(!empty($courseList))
                {
                  ?>
                  <!-- general form elements -->
              <div class="box box-primary">
            <div class="box-header with-border">
            
              <h3 class="box-title">List of Confirmation Course for <?php echo $semesterName;?>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" name="" method="post" action="action_student_confirmation.php">
              <div class="box-body">
                  <table class="table table-striped table-bordered table-condensed" id="example2">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th></th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Units</th>
                         <th>Course Type</th>
                        <th>Course Status</th>
                       
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $courseID=$list['courseID'];
                      
                              $courseStatus = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseStatusID ASC'));
                                foreach($courseStatus as $cs)
                                {
                                  $courseStatus=$cs['courseStatusID'];
                                    if($courseStatus==1)
                                   $status="Core";
                                    else
                                   $status="Option";
                                }
                                

                     echo "<tr><td>$count</td>";
                    
                        ?>
                            <td>
                            <?php if ($courseStatus==1){
                            ?>
                            <input class="checkbox1" type="checkbox" name="courseID[<?php echo $courseID;?>]" value="<?php echo $courseID;?>" checked="checked" readonly="readonly">
                            <?php }
                              else
                              {
                                ?>
                                <input class="checkbox1" type="checkbox" name="courseID[<?php echo $courseID;?>]" value="<?php echo $courseID;?>">
                                <?php 
                              }

                               $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                if(!empty($course))
                                {
                                  foreach ($course as $c) {
                                  ?>
                                  </td>
                                 <td><?php echo $c['courseCode'];?></td>
                                  <td><?php echo $c['courseName'];?></td>
                                  <td><?php echo $c['units'];?></td>
                                  <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                                  <td><?php echo $status;?></td>

                                  <?php
                                  }
                                }

                            ?>

                            
                              <?php    
                            echo "</tr>";
                }
                  ?>
                  
                  </tbody>
                  </table>
                      </div>

              <div class="box-footer" >
                          <input type="hidden" name="action_type" value="add"/>
                         <input type="hidden" name="courseStatus" value="<?php echo $courseStatus;?>">
                        <input type="hidden" name="number_student" value="<?php echo $count;?>">
                        <input type="hidden" name="semisterID" value="<?php echo $semesterSettingID;?>">
                        <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                        <input type="hidden" name="studentID" value="<?php echo $studentID;?>">
                <input type="submit" class="btn btn-primary pull-right" value="Confirm">
              </div>
            </form>
          </div>
                  <?php
              }
              ?>
          

              <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">List of Registered Course for <?php echo $semesterName;?> 
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
              <?php
              $courseList = $db->getRows('student_course',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID),' order_by'=>' academicYearID ASC'));
                if(!empty($courseList))
                {
                  ?>
                  <table class="table table-striped table-bordered table-condensed" id="example">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Units</th>
                        <th>Course Type</th>
                        <th>Course Status</th>
                        <th>Lecturer</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; $total_credits=0;
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $studentCourseID=$list['studentCourseID'];
                      $studentID=$list['studentID'];
                      $courseID=$list['courseID'];
                      $academicYearID=$list['academicYearID'];
                      $semisterID=$list['semisterID'];
                      $total_credits+=$c['units'];
                      if($list['courseStatus']==1)
                        $status="Core";
                      else
                        $status="Option";
                      
                     echo "<tr><td>$count</td>";
                     //instructor
                     $courseprogramme = $db->getSemesterInstructorCourse($courseID,$semesterSettingID);
                     if(!empty($courseprogramme))
                     {
                         foreach($courseprogramme as $std)
                         {
                             $courseProgrammeID=$std['courseProgrammeID'];
                             $instructor = $db->getRows('instructor_course',array('where'=>array('courseProgrammeID'=>$courseProgrammeID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseProgrammeID ASC'));
                             if(!empty($instructor))
                             {
                                 foreach($instructor as $i)
                                 {
                                     $instructorID=$i['instructorID'];
                                     $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
                                 }
                             }
                             else
                             {
                                 $instructorName="Not assigned";
                             }
                         }
                     }
                    $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                    if(!empty($course))
                    {
                      foreach ($course as $c) {
                      }
                    }

                        ?>
                        	<td><?php echo $c['courseCode'];?></td>
                              <td><?php echo $c['courseName'];?></td>
                              <td><?php echo $c['units'];?></td>
                              <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                              <td><?php echo $status;?></td>
                              <td><?php echo $instructorName;?></td>
                              <?php 
                              if($status=="Core")
                              {
                              ?>
                              <td>No</td>
                              <?php 
                              }else {?>
                              <td><a href="action_student_confirmation.php?action_type=drop&id=<?php echo $studentCourseID;?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                              <?php    
                              }
                            echo "</tr>";
                }
                  ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                  <th colspan=3>Total Number of Credits:
                  </th><th colspan=5><?php echo $total_credits;?></th>
                  </tr>
                  </tfoot>
                  </table>
                  <?php
              }
              else
              {
                echo "<h4 class='text-danger'>No Course Registered for the Academic Year</h4>";
              }

               
              ?>
              </div>
              <!-- /.box-body -->

              
            </form>
          </div>

      </div>

      <div class="col-md-4">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Examination Information for <?php echo $semesterName;?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
              <?php 
              $exam_number=$db->getRows("exam_number",array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID)));
              if(!empty($exam_number))
              {
                 foreach($exam_number as $enumber)
                 {
                     $exam_number=$enumber['examNumber'];
                     ?>
                     <h4 class="text-danger">Your Exam Number is: <?php echo $exam_number;?></h4>
                     <?php
                 }
              }
              else 
              {
              ?>
                <h4 class="text-danger">Your Semester Registration is Incomplete, You can only complete semester registration 
                if you have cleared your semester outstanding balance.<br><br>Please <a href='complete_semester_registration.php?action_type=register&sid=<?php echo $db->encrypt($semesterSettingID);?>&regno=<?php echo $db->my_simple_crypt($regNumber,'e');?>'>Click Here</a> to Complete Semester Registration</h4>
              <?php 
              }
              ?>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          </div> 


      </div>
      </section>
      <?php 
   
                ?>