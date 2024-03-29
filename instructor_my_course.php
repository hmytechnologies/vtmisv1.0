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
// $academicYearID=$db->getCurrentAcademicYear();
$userId = $_SESSION['user_session'];
    $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

    if(!empty($instructor))
     {
        foreach($instructor as $i)
      {
             $instructorID=$i['instructorID'];
             $centerID=$i['centerID'];
             $departmentID=$i['departmentID'];
            $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
        }
    }
    $center_program_course = $db->getRows('center_programme_course',array('where'=>array('staffID'=>$instructorID),'order_by'=>'centerProgrammeCourseID ASC'));

    if(!empty($center_program_course))
     {
        foreach($center_program_course as $ii)
      {
        $programmeLevelID=$ii['programmeLevelID'];
       
        $programmeID=$ii['programmeID'];
        $academicYearID=$ii['academicYearID'];
        }
        


        $semester=$db->getRows("semester_setting",array('where'=>array('academicYearID'=>$academicYearID),'order_by semesterName ASC'));
        if(!empty($semester))
        {
        foreach($semester as $sm)
        {
            
            $semesterSettingID=$sm['semesterSettingID'];
        }
   
        $courseprogramme = $db->getInstructorAcademicCourse($academicYearID,$instructorID);

    }
}
   
  
     


if(!empty($courseprogramme))
{
?>
<hr>
 <div class="col-md-12">
 <div class="box box-solid box-primary">
     <div class="box-header with-border text-center">
         <h3 class="box-title">List of Assigned Courses for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
     </div>
          <div class="box-body">
<table  id="" class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
    <th>No.</th>
      <th>Class Number</th>
    <th>Subject Name</th>
    <th>Subject Code</th>
    <th>Subject Type</th>
      <th>Level Name</th>
      <th>Trade Name</th>
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
            $courseID = $std['courseID'];
            $classNumber = $std['classNumber'];
            $programmeID = $std['programmeID'];
            $programmeLevelID = $std['programmeLevelID'];
            // $lv = $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID);

     $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
     if(!empty($course))
     {
         foreach($course as $c)
         {
             $courseCode=$c['courseCode'];
             $courseid = $c['courseID'];
             $courseName=$c['courseName'];
             $courseTypeID=$c['courseTypeID'];
             $units=$c['units'];
             $nhours=$c['numberOfHours'];
            //  $courseOutline=$c['courseOutline'];
             $totalHours+=$nhours;
         }
         
     }
     $userId = $_SESSION['user_session'];
     $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

     if(!empty($instructor))
      {
         foreach($instructor as $i)
       {
              $instructorID=$i['instructorID'];
              $centerID=$i['centerID'];
              $departmentID=$i['departmentID'];
             $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
         }
     }
     

      $studentNumber=$db->getStudentCourseSum($centerID,$academicYearID,$programmeLevelID,$programmeID);
     if($studentNumber>0)
     {

    
        $viewButton = '
	   <div class="btn-group">
	         <a href="index3.php?sp=student_list&id='.$db->encrypt($courseid).'&programmeID='.$db->encrypt($programmeID).'&centerID='.$db->encrypt($centerID).'&year='.$db->encrypt($academicYearID).'&level='.$db->encrypt($programmeLevelID).'&instID='.$db->encrypt($instructorID).'&sid='.$db->encrypt($semesterSettingID).'"class="glyphicon glyphicon-eye-open"></a>
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
     <td><?php echo $classNumber;?></td>
 <td><?php echo $courseName;?></td>
 <td><?php echo $courseCode;?></td>
 <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
     <td><?php echo $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID); ?></td>
     <td><?php echo $db->getData('programmes','programmeName','programmeID',$programmeID); ?></td>
     <td><?php echo $studentNumber;?></td>
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
    <th colspan="5" style="font-size:16px;" >Total Number of Hours per Week</th><th style="font-size:16px;"><?php echo $totalHours;?></th>

    </tr>
 </table>
 </div>
 </div></div>
  <?php 
}
else 
{
    ?>
    <h4 class="text-danger">No Subject Found<?php  echo $center_program_course; 
          
        //   echo   $lv;
    
    ?></h4>
    <?php 
}
 ?> 
 </div>  

</div>

<!-- End of Current Semester -->

 <!-- Previous Semester -->       
        <div id="previous" class="tab-pane fade in active ">
            
            <div class="row">
            <form name="" method="post" action="">
            <div class="col-md-12">
            <div class="row">
                <div class="col-lg-4">
                    <label for="FirstName">Academic Year</label>
                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                        <?php
                        // $academic_year = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                        $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
      $academicYearID=$_POST['academicYearID'];
     $semester=$db->getRows("semester_setting",array('where'=>array('academicYearID'=>$academicYearID),'order_by semesterName ASC'));
     if(!empty($semester))
     {
     foreach($semester as $sm)
     {
        //  $semesterSettingID=$sm[''];
        $academicYearID=$sm['academicYearID'];
        //  $semesterName=$sm['semesterName'];
         $semesterSettingID=$sm['semesterSettingID'];
     }

     $userId = $_SESSION['user_session'];
     $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

     if(!empty($instructor))
      {
         foreach($instructor as $i)
       {
              $instructorID=$i['instructorID'];
              $centerID=$i['centerID'];
              $departmentID=$i['departmentID'];
 $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
         }
     }
     
 
    //  $courseprogramme = $db->getInstructorCours($departmentID,$semesterSettingID, $academicYearID, $instructorID);
     $courseprogramme = $db->getInstructorAcademicCourse($academicYearID,$instructorID);
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
             <h3 class="box-title">List of Assigned Courses for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
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
    <!-- <th>Slot Name</th> -->
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
            // $batchID=$std['batchID'];
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
    //  }getStudentCourseSum($centerID, $academicYearID, $programmeLevelID, $progID)

    $userId = $_SESSION['user_session'];
    $instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userId),'order_by'=>'instructorID ASC'));

    if(!empty($instructor))
     {
        foreach($instructor as $i)
      {
             $instructorID=$i['instructorID'];
             $centerID=$i['centerID'];
             $departmentID=$i['departmentID'];
            $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
        }
    }
    $center_program_course = $db->getRows('center_programme_course',array('where'=>array('staffID'=>$instructorID),'order_by'=>'centerProgrammeCourseID ASC'));
    if(!empty($center_program_course))
    {
       foreach($center_program_course as $ii)
     {
       $programmeLevelID=$ii['programmeLevelID'];
       $programmeID=$ii['programmeID'];
       $academicYearID=$ii['academicYearID'];
       }
   }
   
     $studentNumber=$db->getStudentCourseSum( $centerID, $academicYearID, $programmeLevelID,$programmeID);
     
     if($studentNumber>0)
     {
         $viewButton = '
	   <div class="btn-group">
   
       <a href="index3.php?sp=student_list&id='.$db->encrypt($courseid).'&programmeID='.$db->encrypt($programmeID).'&centerID='.$db->encrypt($centerID).'&year='.$db->encrypt($academicYearID).'&level='.$db->encrypt($programmeLevelID).'&instID='.$db->encrypt($instructorID).'&sid='.$db->encrypt($semesterSettingID).'"class="glyphicon glyphicon-eye-open"></a>
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