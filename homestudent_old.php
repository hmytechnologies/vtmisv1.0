              <?php
               require_once("DB.php");
              $db = new DBHelper();
              $userID = $_SESSION['user_session'];
               $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
               ?>
              
                <?php
                if(!empty($studentID))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Name</th>
                        <th>Reg.Number</th>
                        <th>Gender</th>
                        <th>Level</th>
                        <th>Programme Name</th>
                        <th>Programme Duration</th>
                        <th>Study Year</th>
                        <th>Study Mode</th>
                        <th>Student Status</th>
                      </tr>
                      </thead>
                      <tbody>
                    <?php 
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
                      $name="$fname $mname $lname";


                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>";
                     $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
                     $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
		                if(!empty($level))
		                {
		                	foreach ($level as $lvl) {
		                		$programme_level_code=$lvl['programmeLevelCode'];
		                		echo "$programme_level_code</td><td>";
		                	}
		                }

		                $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		$programmeDuration=$pro['programmeDuration'];
		                		echo "$programmeName</td><td>";
		                	}
		                }

                	
                	echo "$programmeDuration</td><td>";
                	
                	
                	$studyYear= $db->getRows('student_study_year',array('where'=>array('studentID'=>$studentID,'studyYearStatus'=>1),' order_by'=>'studentID ASC'));
                	if(!empty($studyYear))
                	{
                	    foreach ($studyYear as $sy) {
                	       $studyYear=$sy['studyYear'];
                	       $semesterStudyYear=$sy['semesterSettingID'];
                	    }
                	}
                	if($studyYear==0)
                	    $studyYear+=1;
                    else if(!empty($studyYear)&&($semesterSettingID==$semesterStudyYear))
                	    $studyYear=$studyYear;     
                	echo $studyYear."</td><td>";
                	
                	echo $db->getData("batch","batchName","batchID",$batchID)."</td><td>";
                	$status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
		                if(!empty($status))
		                {
		                	foreach ($status as $st) {
		                		$status_value=$st['statusValue'];
		                		echo "$status_value</td>";
		                	}
		                }
                  
                	?>
                	</tr>
                	</tbody>
                	</table>
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
?>
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-7">
              <?php
                //$courseList = $db->getRows('courseprogramme',array('where'=>array('programmeID'=>$programmeID,'academicYearID'=>$academicYearID,'semesterID'=>$semisterID,'studyYear'=>1),' order_by'=>' courseID ASC'));

              $courseList = $db->filterRecords($programmeID,$semesterSettingID,$batchID,$studyYear,$studentID);
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
                        <input type="hidden" name="studyYear" value="<?php echo $studyYear;?>">
                        <input type="hidden" name="semisterID" value="<?php echo $semesterSettingID;?>">
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
              $courseList = $db->getRows('student_course',array('where'=>array('studentID'=>$studentID,'semesterSettingID'=>$semesterSettingID),' order_by'=>' academicYearID ASC'));
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
                        <th>Course Status</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; $total_credits=0;
                    foreach($courseList as $list)
                    { 
                      $count++;
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
                              <td><?php echo $status;?></td>
                              <td><a href=''>Drop</a></td>
                              <?php    
                            echo "</tr>";
                }
                  ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                  <th colspan=3>Total Number of Credits:
                  </th><th colspan=3><?php echo $total_credits;?></th>
                  </tr>
                  </tfoot>
                  </table>
                  <?php
              }
              else
              {
                echo "<h4 class='text-danger'>No Course Registered for ".$semesterName."<br>Please Please <a href='index3.php?sp=semester_registration'>Click Here to make Course Semester Registration</h4>";
              }

               }
              }
              ?>
              </div>
              <!-- /.box-body -->

              
            </form>
          </div>

      </div>

      <div class="col-md-5">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Performance Summary</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
                <!--Course List-->
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