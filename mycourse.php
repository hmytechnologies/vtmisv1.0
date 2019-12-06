<div class="container">
    <h1>My Courses</h1>
    <hr>
<div class="col-md-12">
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">List of Registered Course</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
     <?php
     $db = new DBHelper();
     $userID = $_SESSION['user_session'];
     $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
     if(!empty($studentID))
     {
         foreach($studentID as $std)
         {
             $regNumber=$std['registrationNumber'];
              $courseList = $db->getRows('student_course',array('where'=>array('regNumber'=>$regNumber),' order_by'=>'semesterSettingID ASC'));
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
                        <th>Semester Name</th>
                        <th>Lecturer</th>
                        
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; $total_credits=0;
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $regNumber=$list['regNumber'];
                      $courseID=$list['courseID'];
                      $semesterID=$list['semesterSettingID'];

                      
                      if($list['courseStatus']==1)
                        $status="Core";
                      else
                        $status="Option";

                      
                      //instructor
                        $courseprogramme = $db->getSemesterInstructorCourse($courseID, $semesterID);
                        if (!empty($courseprogramme)) {
                            foreach ($courseprogramme as $std) {
                                $batchID=$std['batchID'];
                                $instructor = $db->getRows('instructor_course', array('where' => array('courseID' => $courseID,'batchID'=>$batchID,'semesterSettingID' => $semesterID), 'order_by' => 'courseID ASC'));
                                if (!empty($instructor)) {
                                    foreach ($instructor as $i) {
                                        $instructorID = $i['instructorID'];
                                        $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                    }
                                } else {
                                    $instructorName = "Not assigned";
                                }
                            }
                        }
                        else
                        {
                            $instructorName = "Not assigned";
                        }
                        
                        
                     echo "<tr><td>$count</td>";

                     $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                    if(!empty($course))
                    {
                      foreach ($course as $c) {
                          $total_credits+=$c['units'];
                      }
                    }


                        /*if($courseStatusID==1)
                            $status="Core";
                        else
                            $status="Elective";*/

                        ?>
                        <td><?php echo $c['courseCode'];?></td>
                              <td><?php echo $c['courseName'];?></td>
                              <td><?php echo $c['units'];?></td>
                              <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                              <td><?php echo $status;?></td>
                              <td><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterID);?></td>
                             <td><?php echo $instructorName;?></td>
                              <?php    
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
                echo "<h4 class='text-danger'>No Course(s) Registered</h4>";
//<br>Please Please <a href='index3.php?sp=semister_registration'>Click Here</a> to make Course Semester Registration</h4>";
              }
         }
     }
              ?>
</div></div>
</div>

<!--<div class="col-md-3">
                   <div class="box box-solid box-primary">
                                  <div class="box-header with-border text-center">
                                    <h3 class="box-title">Perfomance</h3>
                                  </div>

                                  <div class="box-body">
                                  <table  id="" class="table table-striped table-bordered table-condensed">
                                  <thead>
                                  <tr>
                                    <th>Total Credits</th>
                                    <th>Total Points</th>
                                    <th>Overall GPA</th>
                                    <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                    
                                    </tr>
                                    </tbody>
                                    </table>
                                  </div>
                                  </div>
</div>
-->
</div>