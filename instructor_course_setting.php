<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<?php $db=new DBHelper();?>
<div class="container">
    <div class="row">
    <div class="col-md-8">
    <h1>Instructor Course Allocation</h1>
    </div>
   <!-- <div class="col-md-4">
    <div class="pull-right">
            <a href="index3.php?sp=semester_setting" class="btn btn-warning">Back to Main Setting</a>
        </div>
    </div>--></div>
    <hr>

  <div class="content">

    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#workload"><strong>Course Allocation</strong></a></li>
        <li><a data-toggle="tab" href="#instructor_workload"><strong>View Instructor Workload</strong></a></li>
    </ul>
    <div class="tab-content">
        <div id="workload"  class="tab-pane fade in active">
            <h3>Course Allocation</h3>
            <hr>
       <!-- Start -->
       <div class="row">
            
            <form name="" method="post" action="">
            			<?php 
            			if( $_SESSION['main_role_session']==4)
            			{
            			    ?>
                            <div class="col-lg-3">
                                <label for="MiddleName">Center Name</label>
                                <select name="departmentID" class="form-control" required>
                                    <?php
                                    $department = $db->getRows('departments',array('where'=>array('departmentID'=>$_SESSION['department_session']),'order_by'=>'departmentName ASC'));
                                    if(!empty($department)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($department as $dept){ $count++;
                                            $department_name=$dept['departmentName'];
                                            $department_id=$dept['departmentID'];
                                            ?>
                                            <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
                                        <?php }}

                                    ?>
                                </select>
                            </div>
                            <?php
            			}
            			else 
            			{
            			?>
                         <div class="col-lg-3">
                           <label for="MiddleName">Center Name</label>
                           <select name="departmentID" class="form-control" required>
                             <?php
                                   $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
                                   if(!empty($department)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($department as $dept){ $count++;
                                    $department_name=$dept['departmentName'];
                                    $department_id=$dept['departmentID'];
                                   ?>
                                   <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
                                   <?php }}

                              ?>
                           </select>
                        </div>
                        <?php 
            			}
            			?>
                        <div class="col-lg-3">
                           <label for="FirstName">Academic Year</label>
                            <select name="academicYearID" class="form-control" required>
                                <?php
                                $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
                                if(!empty($adYear)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($adYear as $year){ $count++;
                                        $academic_year=$year['academicYear'];
                                        $academic_year_id=$year['academicYearID'];
                                        ?>
                                        <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                                    <?php }
                                }
                                ?>
                            </select>
                        </div>

                    
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                   </form>
        </div>
        <br><br>
        <?php
            if(isset($_POST['doFind'])=="Find Records")
            {
              $departmentID=$_POST['departmentID'];
              if($role_session==4)
                  $departmentID=$_SESSION['department_session'];
              else 
                  $departmentID=$departmentID;
              
              $semisterID=$_POST['semisterID'];
             ?>
            <div class="row">
          <div class="box box-solid box-primary">
          <div class="box-header with-border text-center">
            <h3 class="box-title">Instructor Workload</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <h4 class="text-danger">Instructor Workload for <?php echo $db->getData("departments","departmentName","departmentID",$departmentID);?> - <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semisterID);?></h4>
                    <table  id="" class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Type</th>
                        <th>Credits</th>
                        <th>Hours</th>
<!--                        <th>Course Status</th>
-->                        <th>Batch</th>
                        <th>Instructor</th>
                         </tr>
                         </thead>
                      <tbody>
 <?php 
 $progStudy= $db->getProgrammeStudyYear($departmentID,$semisterID);
 if(!empty($progStudy))
 {
     foreach($progStudy as $ps)
     {
         $programmeID=$ps['programmeID'];
         $studyYear=$ps['studyYear'];
         if($studyYear==1)
             $studyYearT="First Year";
         else if($studyYear==2)
             $studyYearT="Second Year";
         else if($studyYear==3)
             $studyYearT="Third Year";
         echo "<tr><td colspan='8' align='center'><h4>".$db->getData("programmes","programmeName","programmeID",$programmeID)."-".$studyYearT."</h4></td></tr>";
         
         $courseProgramme=$db->getCourseAllocationProgramme($programmeID,$studyYear,$semisterID);
         if(!empty($courseProgramme))
         {
             $count=0;
             foreach($courseProgramme as $cp)
             {
                 $count++;
/*                 $courseProgrammeID=$cp['courseProgrammeID'];*/
                 $courseID=$cp['courseID'];
                 $batchID=$cp['batchID'];
                 $courseStatus=$cp['courseStatus'];

                 if($courseStatus==1)
                     $status="Core";
                 else 
                     $status="Elective";
                 
                 $courseValue=$db->getRows("course",array('where'=>array('courseID'=>$courseID)));
                 foreach($courseValue as $cv)
                 {
                     $courseCode=$cv['courseCode'];
                     $courseName=$cv['courseName'];
                     $units=$cv['units'];
                     $courseTypeID=$cv['courseTypeID'];
                     $nhours=$cv['numberOfHours'];
                 }
                 $instructor = $db->getRows('instructor_course',array('where'=>array('courseID'=>$courseID,'batchID'=>$batchID,'semesterSettingID'=>$semisterID),'order_by'=>'courseID ASC'));
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
                     $instructorName="Not Assigned";
                 }
                 
                 ?>
                            <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $courseCode;?></td>
                            <td><?php echo $courseName;?></td>
                            <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                            <td><?php echo $units;?></td>
                            <td><?php echo $nhours;?></td>
<!--                            <td><?php /*echo $status;*/?></td>
-->                            <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
                            <td><?php echo $instructorName;?></td>
                            </tr>
                            <?php 
             }
         }
     }
 }
 ?>                     
                      </tbody>
                      </table>
 </div>
 </div>           
  </div>
  <?php }
  ?>      
       <!-- End -->     
        </div>
        
        <div id="instructor_workload" class="tab-pane fade">
            <h3>Instructor Workload</h3>
            <hr>
       <!-- Start -->
       <div class="row">
            
            <form name="" method="post" action="">
            			<?php
                        if($_SESSION['main_role_session']==4)
                        {
                            ?>
                            <div class="col-lg-3">
                                <label for="MiddleName">Department Name</label>
                                <select name="departmentID" class="form-control" required>
                                    <?php
                                    $department = $db->getRows('departments',array('where'=>array('departmentID'=>$_SESSION['department_session']),'order_by'=>'departmentName ASC'));
                                    if(!empty($department)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($department as $dept){ $count++;
                                            $department_name=$dept['departmentName'];
                                            $department_id=$dept['departmentID'];
                                            ?>
                                            <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
                                        <?php }}

                                    ?>
                                </select>
                            </div>
                            <?php
                        }
            			else 
            			{
            			?>
                         <div class="col-lg-3">
                           <label for="MiddleName">Department Name</label>
                           <select name="departmentID" class="form-control" required>
                             <?php 
                            
                             
                                   $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
                                   if(!empty($department)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($department as $dept){ $count++;
                                    $department_name=$dept['departmentName'];
                                    $department_id=$dept['departmentID'];
                                   ?>
                                   <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
                                   <?php }}

                              ?>
                           </select>
                        </div>
                        <?php }
                        ?>
                        <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semisterID" id="semesterID" class="form-control" required>
                              <?php
                                 $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
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
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                   </form>
        </div>
        <br><br>
        <?php
            if(isset($_POST['doFind'])=="Find Records")
            {
              $departmentID=$_POST['departmentID'];
              if($role_session==4)
                  $departmentID=$_SESSION['department_session'];
              else
                  $departmentID=$departmentID;
              $semisterID=$_POST['semisterID'];
             ?>
            <div class="row">
          <div class="box box-solid box-primary">
          <div class="box-header with-border text-center">
            <h3 class="box-title">Instructor Workload</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <h4 class="text-danger">Instructor Workload for <?php echo $db->getData("departments","departmentName","departmentID",$departmentID);?> - <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semisterID);?></h4>
                    <table  id="" class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Type</th>
                        <th>Credits</th>
                        <th>Hours</th>
<!--                        <th>Course Status</th>
-->                        <th>Batch</th>
                        <th>#Students</th>
                         </tr>
                         </thead>
                      <tbody>
 <?php 
 $progStudy= $db->getInstructorCourseProgramme($departmentID,$semisterID);
 if(!empty($progStudy))
 {
     foreach($progStudy as $ps)
     {
         $instructorID=$ps['instructorID'];
         echo "<tr><td colspan='8' align='center'><h4>".$db->getData("instructor","instructorName","instructorID",$instructorID)."</h4></td></tr>";
         
         $courseProgramme=$db->getIsntructorCourseProgramme($instructorID,$semisterID);
         if(!empty($courseProgramme))
         {
             $count=0;$tnhours=0;
             foreach($courseProgramme as $cp)
             {
                 $count++;
                 $courseID=$cp['courseID'];
                 $batchID=$cp['batchID'];
                 
                 $courseValue=$db->getRows("course",array('where'=>array('courseID'=>$courseID)));
                 
                 foreach($courseValue as $cv)
                 {
                     $courseCode=$cv['courseCode'];
                     $courseName=$cv['courseName'];
                     $units=$cv['units'];
                     $courseTypeID=$cv['courseTypeID'];
                     $nhours=$cv['numberOfHours'];
                     $tnhours+=$nhours;
                     
                     $studentNumber=$db->getStudentCourseSum($courseID,$semisterID,$batchID);
                 }
                 ?>
                            <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $courseCode;?></td>
                            <td><?php echo $courseName;?></td>
                            <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                            <td><?php echo $units;?></td>
                            <td><?php echo $nhours;?></td>
<!--                            <td><?php /*echo $status;*/?></td>
-->                            <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
                            <td>
                            <?php echo $studentNumber;?>
                            </td>
                            </tr>
                            <?php
             }
         }
         ?>
         </tbody>
         <tr><th colspan="5" style="font-size:16px;">Total Number of Hours</th><th style="font-size:16px;"><?php echo $tnhours;?></th></tr>
         
         <?php
     }
 }
 ?>                     
                      
                      </table>
 </div>
 </div>           
  </div>
  <?php }
  ?>      
       <!-- End -->     
        </div>
       
    </div>
    </div>
</div>