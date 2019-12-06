<h4 class="text-info"><b>Register Student Course By Searching Student</h4>
<div class="form-group">
<form name="" method="post" action="">
<div class="col-xs-12">
   <label class="col-xs-3 control-label"> Enter Student Reg.Number:</label>
	<div class="col-xs-4">
		<input type="text" name="search_student" id="search_text" class="form-control">
	</div>
	<div class="col-xs-4"><input  type="submit" class="btn btn-success" name="doSearch" value="Search Student"/>
	</div>
	</div>
	</form>
</div>
<br><br>
<div class="row">
	<?php
			$db=new DBhelper();
            if((isset($_POST['doSearch'])=="Search Student") ||(($_REQUEST['action']=="getRecords")))
            {
              $searchStudent=$_POST['search_student'];
              $searchStudent=$_REQUEST['search_student'];

               $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
               ?>
              
                <?php
                if(!empty($studentID))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Student Name Code</th>
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

                     
                      $today=date("Y-m-d");
                      $sm=$db->readSemesterSetting($today);
                      foreach ($sm as $s)
                      {
                          $semisterID=$s['semesterID'];
                          $academicYearID=$s['academicYearID'];
                          $semesterName=$s['semesterName'];
                          $semesterSettingID=$s['semesterSettingID'];
                      }
                      
                      
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
		                
		                
		                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
		                if(!empty($study_year))
		                {
		                    foreach ($study_year as $sy) 
		                    {
		                        $studyYear=$sy['studyYear'];
		                    }
		                }
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
		                
                    }
                	?>
                	<!--<td><a href='index3.php?sp=studentregister&action=getDatails&studentID=<?php echo $studentID;?>'>Details</a></td>--></tr>
                	</tbody>
                	</table>
                	<!--<div class="row"> 
						<div class="col-md-12">
						<div class="pull-right">
						                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Register New Course</button>
						            </div>   
						 </div>
						</div>-->
			<hr>
			
			<div class="row">
			<div class="col-lg-6"></div>
			<div class="col-lg-6">
			<h4 class="text-primary">Add New Course</h4>
			</div>
			</div>
			<form name="" method="post" action="action_student_register.php"> 
               <div class="row">
                   <div class="col-lg-3">
                            <label for="MiddleName">Course Name</label>
                            
                            <select name="courseID" class="form-control chosen-select" required>
                            
                              <?php
                               $course = $db->getRows('course',array('order_by'=>'courseName ASC'));
                               if(!empty($course)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($course as $c){ $count++;
                                $course_name=$c['courseName'];
                                $course_code=$c['courseCode'];
                                $course_id=$c['courseID'];
                               ?>
                               <option value="<?php echo $course_id;?>"><?php echo $course_code."-".$course_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                      <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semesterID" class="form-control" required="">
                              <?php
                                 $semister = $db->getRows('semester_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'semesterName ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semesterName'];
                                  $semister_id=$sm['semesterSettingID'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                       
                        <div class="col-lg-3">
                           <label for="FirstName">Course Status</label>
                            <select name="courseStatusID" class="form-control" required="">
                              <?php
                                 $course_status = $db->getRows('coursestatus',array('order_by'=>'courseStatus ASC'));
                                 if(!empty($course_status)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($course_status as $cstatus){ $count++;
                                  $courseStatus=$cstatus['courseStatus'];
                                  $courseStatusID=$cstatus['courseStatusID'];
                                 ?>
                                 <option value="<?php echo $courseStatusID;?>"><?php echo $courseStatus;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                      </div>
                 <br>
                  <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                            <input type="hidden" name="searchStudent" value="<?php echo $searchStudent;?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                        </div>
                        <!--<div class="col-lg-3">
                            <input type="submit" value="Cancel" class="btn btn-primary form-control" />
                        </div>-->
                    </div>
                </form>

<div class="row">
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course Data has been delete successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="exist") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course already Registered</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsucc") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Something wrong happening, contact System Administrator</strong>.
</div>";
  }
}
?> 
</div>

                	<?php
                	//List of Courses
                echo "<h4 class='text-info'>List of Registered Courses</h4>";
                $courseList = $db->getRows('student_course',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' semesterSettingID ASC'));
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
                        <th>Course Type</th>
                          <th>Course Status</th>
                        <th>Semister Name</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $studentID=$list['studentID'];
                      $studentCourseID=$list['studentCourseID'];
                      $courseID=$list['courseID'];
                      $academicYearID=$list['academicYearID'];
                      $semesterSettingID=$list['semesterSettingID'];
                      $courseStatus=$list['courseStatus'];

                      if($courseStatus==1)
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
                            	 <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                            	<td><?php echo $status;?></td>
                            	<?php
		                $semister= $db->getRows('semester_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),' order_by'=>' semesterName ASC'));
		                if(!empty($semister))
		                {
		                	foreach ($semister as $sm) {
		                		$semister_name=$sm['semesterName'];
		                		echo "<td>$semister_name</td>";
		                	}
		                }
		               ?> 
		                
                       <td><a href="action_student_register.php?action_type=drop&id=<?php echo $studentCourseID;?>&regNumber=<?php echo $searchStudent;?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                       </tr>

		            <?php 
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

                	//End of List
           }
           else
           {
           	echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
           }
       }
    ?>
    </div>
