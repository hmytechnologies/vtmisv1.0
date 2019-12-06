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
 <script type="text/javascript">
 $(document).ready(function () {
 $("#studentdata").DataTable({
             "dom": 'Blfrtip',
             "scrollX":true,
             "paging":true,
             "buttons":[
                     {
                         extend:'excel',
                         title: 'List of all Register',
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     ,
                     {
                         extend: 'print',
                         title: 'List of all Register',
                         footer: false,
                         exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: 'List of all Register',
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         },
                         
                     }

                     ],
		"order": []
	});
 });
</script>

<?php $db=new DBHelper();
$instructorID=$db->getData("instructor","instructorID","userID",$_SESSION['user_session']);
?>
<div class="container">
<h1>My Course<hr></h1>
  <div class="content">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Current Year</strong></span></a></li>
        <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>Previous Year</strong></span></a></li>
    </ul>
    <div class="tab-content">
    <!-- Current Semester -->
<div id="currentdata" class="tab-pane fade in active">

 <div class="row">
 <?php

$today=date("Y-m-d");
$sm=$db->readSemesterSetting($today);
foreach ($sm as $s) {
    $semisterID=$s['semesterID'];
    $academicYearID=$s['academicYearID'];
    $semesterName=$s['semesterName'];
    $semesterSettingID=$s['semesterSettingID'];
}
$courseprogramme = $db->getInstructorSemesterCourse($semesterSettingID,$instructorID);
if(!empty($courseprogramme))
{
?>
<hr>
 <div class="col-md-12">
 <div class="box box-solid box-primary">
     <div class="box-header with-border text-center">
         <h3 class="box-title">List of Assigned Courses for <?php echo $semesterName;?></h3>
     </div>
         <!-- /.box-header -->
          <div class="box-body">
<table  id="" class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
    <th>No.</th>
    <th>Subject Name</th>
    <th>Subject Code</th>
    <th>Subject Type</th>
    <th>No.of Students</th>
      <th>Course Ouline</th>
    <th>View</th>
     </tr>
  </thead>
  <tbody>
  <?php 
  $totalHours=0;
  $count=0;
  foreach($courseprogramme as $std)
  {
            $count++;
            $courseID=$std['courseID'];
            $batchID=$std['batchID'];
            // $courseProgrammeID=$std['courseProgrammeID'];
     
     $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
     if(!empty($course))
     {
         foreach($course as $c)
         {
             $courseCode=$c['courseCode'];
             $courseName=$c['courseName'];
             $courseTypeID=$c['courseTypeID'];
             $units=$c['units'];
             $nhours=$c['numberOfHours'];
             $courseOutline=$c['courseOutline'];
             $totalHours+=$nhours;
         }
     }
     
     $studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);
     if($studentNumber>0)
     {
        $viewButton = '
	   <div class="btn-group">
	         <a href="index3.php?sp=student_list&id='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'"class="glyphicon glyphicon-eye-open"></a>
	   </div>';
     }
     else 
     {
         $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
     }
 ?>

 <tr>
 <td><?php echo $count;?></td>
 <td><?php echo $courseName;?></td>
 <td><?php echo $courseCode;?></td>
 <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
  <td><?php echo $units;?></td>
  <td><?php echo $nhours;?></td>
 <td><?php echo $studentNumber;?></td>
 <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
     <td><?php
         if(!empty($courseOutline)) {
             ?>
             <a href="course_outline/<?php echo $courseOutline;?>" class="glyphicon glyphicon-download-alt" target="_blank"></a>
             <?php
         }
         else
         {
             ?>
             Not Uploaded
             <?php
         }
         ?></td>
 <td><?php echo $viewButton;?></td>
 </tr>
 
  <?php }?>
   </tbody>
<tr>
    <th colspan="5" style="font-size:16px;">Total Number of Hours per Week</th><th style="font-size:16px;"><?php echo $totalHours;?></th>

    </tr>
 </table>
 </div>
 </div></div>
  <?php 
}
else 
{
    ?>
    <h4 class="text-danger">No Subject Found</h4>
    <?php 
}
 ?> 
 </div>  

</div>

<!-- End of Current Semester -->

 <!-- Previous Semester -->       
        <div id="previous" class="tab-pane fade">
            
            <div class="row">
            <form name="" method="post" action="">
            <div class="col-md-12">
            <div class="row">
                <div class="col-lg-4">
                    <label for="FirstName">Academic Year</label>
                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                        <?php
                        $academic_year = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                        if(!empty($academic_year)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($academic_year as $sm){ $count++;
                                $academicYear=$sm['academicYear'];
                                $academicYearID=$sm['academicYearID'];
                                ?>
                                <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
              <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" />
              </div>
             </div>
             </div>
             </form>
             </div>
 <?php 
 if(isset($_POST['doFind'])=="Find Records")
 {
     $semesterID=$_POST['semisterID'];
     $semester=$db->getRows("semester_setting",array('where'=>array('semesterSettingID'=>$semesterID),'order_by semesterName ASC'));
     if(!empty($semester))
     {
     foreach($semester as $sm)
     {
         $semisterID=$sm['semesterID'];
         $academicYearID=$sm['academicYearID'];
         $semesterName=$sm['semesterName'];
         $semesterSettingID=$sm['semesterSettingID'];
     }
     
 
     $courseprogramme = $db->getInstructorSemesterCourse($semesterSettingID,$instructorID);
     if(!empty($courseprogramme))
     {
         $count = 0; 
         ?>
         <div class="row"> 
        <div class="col-md-12">
        <hr>
        </div>
        </div>
         <div class="row">
 		<div class="col-md-12">  
 		 <div class="box box-solid box-primary">

             <div class="box-header with-border text-center">
                 <h3 class="box-title">List of Assigned Courses for <?php echo $semesterName;?></h3>
             </div>

          <!-- /.box-header -->
          <div class="box-body"> 
		<table id="" class="table table-striped table-bordered table-condensed">
	  <thead>
  	<tr>
    <th>No.</th>
    <th>Course Name</th>
    <th>Course Code</th>
    <th>Course Type</th>
    <th>Credits</th>
    <th>Hours</th>
    <th>No. of Students</th>
    <th>Slot Name</th>
    <th>View</th>
     </tr>
  </thead>
   <tbody>
  <?php
  $totalHours=0;
  foreach($courseprogramme as $std)
  {
            $count++;
            $courseID=$std['courseID'];
            $batchID=$std['batchID'];
            // $courseProgrammeID=$std['courseProgrammeID'];
     
     $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
     if(!empty($course))
     {
         foreach($course as $c)
         {
             $courseCode=$c['courseCode'];
             $courseName=$c['courseName'];
             $courseTypeID=$c['courseTypeID'];
             $units=$c['units'];
             $nhours=$c['numberOfHours'];
             $totalHours+=$nhours;
         }
     }
     
    //  $instructor = $db->getRows('instructor_course',array('where'=>array('courseProgrammeID'=>$courseProgrammeID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseProgrammeID ASC'));
    //  if(!empty($instructor))
    //  {
    //      foreach($instructor as $i)
    //      {
    //          $instructorID=$i['instructorID'];
    //          $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
    //      }
    //  }
    //  else
    //  {
    //      $instructorName="Not assigned";
    //  }
     
     $studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);
     
     if($studentNumber>0)
     {
         $viewButton = '
	   <div class="btn-group">
	         <a href="index3.php?sp=student_list&id='.$db->encrypt($courseID).'&sid='.$db->encrypt($semisterID).'&bid='.$db->encrypt($batchID).'"class="glyphicon glyphicon-eye-open" title="View Students"></a>
	   </div>';
     }
     else
     {
         $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
     }
     
 ?>

 <tr>
 <td><?php echo $count;?></td>
 <td><?php echo $courseName;?></td>
 <td><?php echo $courseCode;?></td>
 <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
  <td><?php echo $units;?></td>
  <td><?php echo $nhours;?></td>
 <td><?php echo $studentNumber;?></td>
 <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
 <td><?php echo $viewButton;?></td>
 </tr>
 
  <?php }?>

   </tbody>
    <tr>
    <th colspan="5" style="font-size:16px;">Total Number of Hours per Week</th><th style="font-size:16px;"><?php echo $totalHours;?></th>

    </tr>
 </table>
 </div></div> </div></div> 
<?php 
 }
     else
     {
         ?>
         <h4 class="text-danger">No Course Found</h4>
         <?php 
     }
 }
 }
?>
			</div>
       
         <!-- End -->

            </div>
            
</div></div>