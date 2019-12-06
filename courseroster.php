    	<?php
      require_once("DB.php");
      $db = new DBHelper();
      $studentID=$_SESSION['user_session'];


      //Confirmation of Courses
          $courseID = $db->getRows('programmemaping',array('where'=>array('programmeID'=>$programmeID,'studyYear'=>$studyYear,'semesterID'=>$semisterID),' order_by'=>' courseID ASC'));
    
                if(!empty($courseID))
                {
                    ?>
                    <form name="" method="post" action="action_semister_course.php">
                    <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th><input type="checkbox" id="selecctall"/></th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Course Status</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($courseID as $cID)
                    { 
                      $count++;
                      $courseID=$cID['courseID'];
                      echo "<input type='hidden' name='courseID$count' value='$courseID'>";
                      $courseStatus=$cID['courseStatusID'];
                      if($courseStatus==1)
                        $status="Core";
                      else
                        $status="Option";
                       $course = $db->getRows('course',array('where'=>array('course_id'=>$courseID),' order_by'=>' course_name ASC'));
                        if(!empty($course))
                        {
                          $i=0;
                          foreach($course as $c)
                          {
                            $i++;
                            ?>
                            <tr>
                            <td><?php echo $count;?></td>
                            <td><input class="checkbox1" type="checkbox" name="courseID[<?php echo $courseID;?>]" value="<?php echo $courseID;?>"></td>
                            <td><?php echo $c['course_code'];?></td>
                            <td><?php echo $c['course_name'];?></td>
                            <td><?php echo $c['units'];?></td>
                            <td><?php echo $status;?></td>
                            </tr>
                            <?php
                          }
                        }
                    }
                    ?>
                    </tbody>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                         <input type="hidden" name="action_type" value="add"/>
                         <input type="hidden" name="courseStatusID" value="<?php echo $courseStatus;?>">
                        <input type="hidden" name="number_student" value="<?php echo $count;?>">
                        <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                        <input type="hidden" name="semisterID" value="<?php echo $semisterID;?>">
                        <input type="hidden" name="studyYear" value="<?php echo $studyYear;?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="reset" value="Cancel" class="btn btn-primary form-control" />
                        </div>
                    </div>
                    
                    </form>
                    <?php 
                }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Course(s) found......</h4>
                        <?php 
                    } 
                   ?>
                   
                 <?php
        //}

      //End of Confirmation

                	//List of Courses
                	echo "<h4 class='text-info'>List of Registered Courses</h4>";

                	$courseList = $db->getRows('student_course',array('where'=>array('student_id'=>$studentID),' order_by'=>' academic_year_id ASC'));
                if(!empty($courseList))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed" id="example" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                      	<th>No</th>
                      	<th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Units</th>
                        <th>Course Status</th>
                        <th>Semister</th>
                        <th>Academic Year</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $studentID=$list['student_id'];
                      $courseID=$list['course_id'];
                      $academicYearID=$list['academic_year_id'];
                      $semisterID=$list['semister_id'];
                      $courseStatus=$list['course_status'];

                      if($courseStatus==1)
                        $status="Core";
                      else
                        $status="Option";
                      
                     echo "<tr><td>$count</td>";

                     $course= $db->getRows('course',array('where'=>array('course_id'=>$courseID),' order_by'=>' course_name ASC'));
		                if(!empty($course))
		                {
		                	foreach ($course as $c) {
		                	}
		                }

		                		?>
		                		<td><?php echo $c['course_code'];?></td>
                            	<td><?php echo $c['course_name'];?></td>
                            	<td><?php echo $c['units'];?></td>
                            	<td><?php echo $status;?></td>
                            	<?php
		                //	}
		                //}

		                $semister= $db->getRows('semister',array('where'=>array('semister_id'=>$semisterID),' order_by'=>' semister_name ASC'));
		                if(!empty($semister))
		                {
		                	foreach ($semister as $sm) {
		                		$semister_name=$sm['semister_name'];
		                		echo "<td>$semister_name</td>";
		                	}
		                }

                	
                	
                			$adYear = $db->getRows('academic_year',array('where'=>array('academic_year_id'=>$academicYearID),'order_by'=>'academic_year ASC'));
                               if(!empty($adYear))
                               { 
                               foreach($adYear as $year)
                               {
                                $academic_year=$year['academic_year'];
                               ?>
                               <td><?php echo $academic_year;?></td>
                               <?php 
                           		}
                       			}
                       			echo "<td><a href=''>Drop</td></tr>";

		            }
                	?>
                	
                	</tbody>
                	</table>
                	<?php
           		}
           		else
           		{
           			echo "<h4 class='text-danger'>No Course Registered for that Student</h4>";
           		}