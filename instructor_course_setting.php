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
    </div>-->
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
            			if( $_SESSION['main_role_session']==7)
            			{
            			    ?>
                            <div class="col-lg-3">
                                <label for="MiddleName">Center Name</label>
                                <select name="centerID" id="centerID"  class="form-control" required>
                                    <option value="">Select Here</option>
                                    <?php

                                    $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                                    if(!empty($center)){

                                        $count = 0; foreach($center as $cnt){ $count++;
                                            $centerRegistrationID=$cnt['centerRegistrationID'];
                                            $centerName=$cnt['centerName'];
                                            ?>
                                            <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                        <?php }}?>
                                </select>
                            </div>
                            <?php
            			}
            			else 
            			{
            			?>
                         <div class="col-lg-3">
                           <label for="MiddleName">Center Name</label>
                             <select name="centerID" id="centerIDD"  class="form-control" required>
                                 <option value="">Select Here</option>
                                 <?php

                                 $center = $db->getRows('center_registration',array('where'=>array('centerRegistrationID'=>$_SESSION['department_session']),'order_by'=>'centerName ASC'));
                                 if(!empty($center)){

                                     $count = 0; foreach($center as $cnt){ $count++;
                                         $centerRegistrationID=$cnt['centerRegistrationID'];
                                         $centerName=$cnt['centerName'];
                                         ?>
                                         <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                     <?php }}?>
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

              if($_SESSION['main_role_session']==7)
                  $centerID=$_POST['centerID'];
              else
                  $centerID=$_SESSION['department_session'];

              $academicYearID=$_POST['academicYearID'];
              ?>
            <div class="row">
          <div class="box box-solid box-primary">
          <div class="box-header with-border text-center">
            <h3 class="box-title">Instructor Workload</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <h4 class="text-danger">Instructor Workload for <?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerID);?> - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h4>
                    <table  id="" class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                          <th>No.</th>
                          <th>Class Number</th>
                          <th>Subject Code</th>
                          <th>Subject Name</th>
                          <th>Programme Level</th>
                          <th>Instructor Name</th>
                      </tr>
                         </thead>
                      <tbody>
 <?php
 $data = $db->getCourseInstructor($centerID, $academicYearID);
 $count = 0;
      foreach($data as $dt)
      {
                      $count++;
                      $courseID=$dt['courseID'];
                      $instructorID=$dt['staffID'];
                        $classNumber=$dt['classNumber'];
                        $programmeLevelID=$dt['programmeLevelID'];
                        $centerProgrammeCourseID=$dt['centerProgrammeCourseID'];
                        $centerID=$dt['centerID'];

                      $courseValue=$db->getRows("course",array('where'=>array('courseID'=>$courseID)));
                      foreach($courseValue as $cv)
                      {
                           $courseCode=$cv['courseCode'];
                           $courseName=$cv['courseName'];
                           $ctype=$cv['courseTypeID'];
                      }
                            ?>
 <tr>
     <td><?php echo $count;?></td>
     <td><?php echo $classNumber;?></td>
     <td><?php echo $courseCode;?></td>
     <td><?php echo $courseName;?></td>
     <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?></td>
     <td>
         <?php echo $db->getData("instructor", "instructorName", "instructorID", $dt['staffID']);?>
     </td>
 </tr>
 <?php
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
                if( $_SESSION['main_role_session']== 7 )
                {
                    ?>
                    <div class="col-lg-3">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerID" id="centerID"  class="form-control" required>
                            <option value="">Select Here</option>
                            <?php

                            $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                            if(!empty($center)){

                                $count = 0; foreach($center as $cnt){ $count++;
                                    $centerRegistrationID=$cnt['centerRegistrationID'];
                                    $centerName=$cnt['centerName'];
                                    ?>
                                    <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                <?php }}?>
                        </select>
                    </div>
                    <?php
                }
                else
                {
                    ?>
                    <div class="col-lg-3">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerID" id="centerIDD"  class="form-control" required>
                            <option value="">Select Here</option>
                            <?php

                            $center = $db->getRows('center_registration',array('where'=>array('centerRegistrationID'=>$_SESSION['department_session']),'order_by'=>'centerName ASC'));
                            if(!empty($center)){

                                $count = 0; foreach($center as $cnt){ $count++;
                                    $centerRegistrationID=$cnt['centerRegistrationID'];
                                    $centerName=$cnt['centerName'];
                                    ?>
                                    <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                <?php }}?>
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
                if($_SESSION['main_role_session']==7)
                    $centerID=$_POST['centerID'];
                else
                    $centerID=$_SESSION['department_session'];

                $academicYearID=$_POST['academicYearID'];
                ?>
                <div class="row">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">Instructor Workload</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h4 class="text-danger">Instructor Workload for <?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerID);?> - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h4>
                    <table  id="" class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                          <th>No</th>
                          <th>Class Number</th>
                          <th>Subject Name</th>
                          <th>Subject Code</th>
                          <th>Subject Type</th>
                          <th>Level Name</th>
                          <th>Trade Name</th>
                          <th># Students</th>
                         </tr>
                         </thead>
                      <tbody>
 <?php 
 $progStudy= $db->getInstructorCourseProgramme($centerID,$academicYearID);
 if(!empty($progStudy))
 {
     foreach($progStudy as $ps)
     {
         $instructorID=$ps['staffID'];
         echo "<tr><td colspan='8' align='center'><h4>".$db->getData("instructor","instructorName","instructorID",$instructorID)."</h4></td></tr>";
         
         $courseProgramme=$db->getIsntructorCourseProgramme($instructorID,$academicYearID);
         if(!empty($courseProgramme))
         {
             $count=0;
             foreach($courseProgramme as $cp)
             {
                 $count++;
                 $courseID=$cp['courseID'];
                 $classNumber=$cp['classNumber'];
                 $programmeLevelID=$cp['programmeLevelID'];
                 $programmeID=$cp['programmeID'];

                 $courseValue=$db->getRows("course",array('where'=>array('courseID'=>$courseID)));
                 foreach($courseValue as $cv)
                 {
                     $courseCode=$cv['courseCode'];
                     $courseName=$cv['courseName'];
                     $units=$cv['units'];
                     $courseTypeID=$cv['courseTypeID'];

                     $studentNumber=$db->getStudentCourseSum($centerID,$academicYearID,$programmeLevelID,$programmeID);
                 }
                 ?>
                            <tr>
                                <td><?php echo $count;?></td>
                                <td><?php echo $classNumber;?></td>
                                <td><?php echo $courseName;?></td>
                                <td><?php echo $courseCode;?></td>
                                <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                                <td><?php echo $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID); ?></td>
                                <td><?php echo $db->getData('programmes','programmeName','programmeID',$programmeID); ?></td>
                                <td><?php echo $studentNumber;?></td>
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