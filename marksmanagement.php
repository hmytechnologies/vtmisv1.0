<?php
?>
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
<?php $db=new DBHelper();
?>
<div class="container">
  <div class="content">
      <h1>Results Management</h1>
      <hr>
      <h3>Manage results by course or by individual student</h3>
      <ul class="nav nav-tabs" id="myTab">
    
        <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Current Year</strong></span></a></li>
        <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>Previous Year</strong></span></a></li>
<!--        <li><a data-toggle="tab" href="#student_result"><span style="font-size: 16px"><strong>Student Results</strong></span></a></li>
-->
    </ul>

<div class="tab-content">
    <!-- Current Semester -->
<div id="currentdata" class="tab-pane fade in active">
 <?php 
$today=date("Y-m-d");

$academicYearID=$db->getCurrentAcademicYear();

//$courseprogramme = $db->getSemesterCourse($academicYearID,$_SESSION['main_role_session'],$_SESSION['department_session']);
$courseprogramme = $db->getMappingCourseList($academicYearID,$_SESSION['main_role_session'],$_SESSION['department_session']);
if(!empty($courseprogramme))
{
?>
<div class="row">

            <h3 class="box-title">Marks Management for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
    <hr>
          <!-- /.box-header -->
<table  id="exampleexample" class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
    <th>No.</th>
    <th>Subject Name</th>
    <th>Subject Code</th>
      <th>Level</th>
      <th>Trade</th>
 <th>No.of Students</th>
      <th>Exam List</th>
    <th>Post Results</th>
    <th>Bulk Post</th>
    <th>View Results</th>
    <th>Published</th>
     </tr>
  </thead>
  <tbody>    
<?php
$count = 0; foreach($courseprogramme as $std){ $count++;
$courseID=$std['courseID'];
$courseCode=$std['courseCode'];
$courseName=$std['courseName'];
$courseTypeID=$std['courseTypeID'];
$programmeLevelID=$std['programmeLevelID'];
$programmeID=$std['programmeID'];

$course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
if(!empty($course))
{
    foreach($course as $c)
    {
        $courseCode=$c['courseCode'];
        $courseName=$c['courseName'];
        $courseTypeID=$c['courseTypeID'];
    }
}



//$studentNumber=$db->getStudentCourseSum($courseID,$academicYearID,$programmeID,$programmeLevelID);

$studentNumber=$db->getStudentNumber($academicYearID,$programmeLevelID,$programmeID);



$checked=$db->checkStatus($courseID,$academicYearID,$programmeID,$programmeLevelID,'checked');
$published=$db->checkStatus($courseID,$academicYearID,$programmeID,$programmeLevelID,'status');

$boolExamStatus=$db->checkFinalExamResultStatus($courseID,$academicYearID,$programmeID,$programmeLevelID);



if($published==1)
    $statusPublished="<span class='text-success'>Yes</span>";
else
    $statusPublished="<span class='text-danger'>No</span>";

if($studentNumber==0)
{
    $addButton = '
	<div class="btn-group">
	     <i class="fa fa-plus" aria-hidden="true"></i>
	</div>';
    
    $excelButton = '
	<div class="btn-group">
        <i class="fa fa-file" aria-hidden="true"></i>
	</div>';
    
    $viewButton = '
	<div class="btn-group">
        <i class="fa fa-eye" aria-hidden="true"></i>
	</div>';
}
else
{
    if($published==1)
    {
        $addButton = '
    	<div class="btn-group">
    	     <i class="fa fa-plus" aria-hidden="true"></i>
    	</div>';
            
            $excelButton = '
    	<div class="btn-group">
            <i class="fa fa-file" aria-hidden="true"></i>
    	</div>';
            
            $viewButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&acadID='.$db->encrypt($academicYearID).'&lvlID='.$db->encrypt($programmeLevelID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
    }
    else
    {
        $addButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=add_score&cid='.$db->encrypt($courseID).'&acadID='.$db->encrypt($academicYearID).'&lvlID='.$db->encrypt($programmeLevelID).'&pid='.$db->encrypt($programmeID).'" class="glyphicon glyphicon-plus"></a>
    	</div>';
        
        $excelButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=import_score&cid='.$db->encrypt($courseID).'&acadID='.$db->encrypt($academicYearID).'&lvlID='.$db->encrypt($programmeLevelID).'&pid='.$db->encrypt($programmeID).'"class="glyphicon glyphicon-plus"></a>
    	</div>';
        
       if($boolExamStatus==true)
        {
            $viewButton = '
    	    <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&acadID='.$db->encrypt($academicYearID).'&lvlID='.$db->encrypt($programmeLevelID) .'&pid=' . $db->encrypt($programmeID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
       }
        else 
        {
            $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
        }
        
    }
}
?>

  <tr>
 <td><?php echo $count;?></td>
 <td><?php echo $courseName;?></td>
 <td><?php echo $courseCode;?></td>
      <td><?php echo $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID);?></td>
      <td><?php echo $db->getData('programmes', 'programmeName', 'programmeID', $programmeID); ?></td>
<td><?php echo $studentNumber;?></td>
      <td>Exam List</td>
 <td><?php echo $addButton;?></td>
<td><?php echo $excelButton;?></td>
      <td><?php echo $viewButton;?></td>
 <td><?php echo $statusPublished;?></td>
 </tr>
 
 <?php 
}
 ?>
  </tbody>
 </table></div>
 <?php 
}
else 
{
    echo "<h4 class='text-danger'>No Course Found</h4>";
}
 ?>
 </div>  


<!-- End of Current Semester -->

 <!-- Previous Semester -->
        <div id="previous" class="tab-pane fade">
            <h3>Previous Years</h3>
            <div class="row">
            <form name="" method="post" action="">
            <div class="col-md-12">
            <div class="row">
            <div class="col-lg-3">
                <label for="FirstName">Academic Year</label>
                <select name="academicYearID" class="form-control" required>
                    <?php
                    $adYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear DESC'));
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
                      <label for=""><br></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" />
              </div>
             </div>
             </div>
             </form>
             </div>
 <div class="row">
 <?php
 if(isset($_POST['doFind'])=="Find Records")
 {
     $academicYearID=$_POST['academicYearID'];

     $courseprogramme = $db->getSemesterCourse($semesterSettingID,$_SESSION['main_role_session'],$_SESSION['department_session']);
     if(!empty($courseprogramme))
     {
         $count = 0;
         ?>
        <div class="row">
        <div class="col-lg-12">
                <hr>
        </div>
        </div>
    <div class="row">
 	<div class="col-lg-12">
            <h3 class="box-title">Registered Course for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
          <!-- /.box-header -->
	<table id="example" class="table table-striped table-bordered table-condensed">
	<thead>
  	<tr>
    <th>No.</th>
    <th>Subject Name</th>
    <th>Subject Code</th>
        <th>Level</th>
    <th># Students</th>
    <th>Post</th>
    <th>View</th>
    <th>Published</th>
     </tr>
  </thead>
  <tbody>
  <?php
  foreach($courseprogramme as $std)
  {
            $count++;
            $courseID=$std['courseID'];
            $batchID=$std['batchID'];

     $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
     if(!empty($course))
     {
         foreach($course as $c)
         {
             $courseCode=$c['courseCode'];
             $courseName=$c['courseName'];
             $courseTypeID=$c['courseTypeID'];
         }
     }

     $instructor = $db->getRows('instructor_course',array('where'=>array('courseID'=>$courseID,'batchID'=>$batchID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseID ASC'));
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

     /*$studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);

     $checked=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);
     $published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);

     $boolExamStatus=$db->checkExamResultStatus($courseID,$semesterSettingID,$batchID);*/


     if($checked==1)
         $statusCheck="<span class='text-success'>Yes</span>";
         else
             $statusCheck="<span class='text-danger'>No</span>";

             if($published==1)
                 $statusPublished="<span class='text-success'>Yes</span>";
                 else
                     $statusPublished="<span class='text-danger'>No</span>";

                     if($studentNumber==0)
                     {
                         $addButton = '
                        <div class="btn-group">
                             <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>';

                         $excelButton = '
                        <div class="btn-group">
                            <i class="fa fa-file" aria-hidden="true"></i>
                        </div>';

                        $viewButton = '
                        <div class="btn-group">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </div>';
                     }
                     else
                     {
                         if($published==1)
                         {
                             $addButton = '
    	<div class="btn-group">
    	     <i class="fa fa-plus" aria-hidden="true"></i>
    	</div>';

                             $excelButton = '
    	<div class="btn-group">
            <i class="fa fa-file" aria-hidden="true"></i>
    	</div>';

                             $viewButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
                         }
                         else
                         {
                             $addButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=add_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-plus"></a>
    	</div>';

                             $excelButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=import_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'"><i class="glyphicon glyphicon-import"></i></a>
    	</div>';

                             if($boolExamStatus==true)
                             {
                                 $viewButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
                             }
                             else
                             {
                                 $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
                             }
                         }
                     }

 ?>

 <tr>
 <td><?php echo $count;?></td>
 <td><?php echo $courseName;?></td>
 <td><?php echo $courseCode;?></td>
 <td><?php echo $studentNumber;?></td>
 <td><?php echo $addButton;?></td>
<td><?php echo $viewButton;?></td>
 <td><?php echo $statusPublished;?></td>
 </tr>

  <?php }?>
   </tbody>
    </table></div></div>
<?php
 }
     else
     {
         ?>
         <h4 class="text-danger">No Course Found</h4>
         <?php
     }
 }
?>
</div>
			</div>

         <!-- End -->

<div id="student_result" class="tab-pane fade">
            <!-- Start -->
<div class="form-group">
<form name="" method="post" action="">
<h3>Search student to manage his/her results</h3>
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
<br>
<hr>
<div class="row">

	<?php
			$db=new DBhelper();
            if((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords"))
            {
              $searchStudent=$_POST['search_student'];
              $searchStudent=$_REQUEST['search_student'];

               $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
               ?>
                <?php
                if(!empty($studentID))
                {
                	?>
                	<div class="box box-solid box-primary">
                  <div class="box-header with-border text-center">
                    <h3 class="box-title">Personal Information</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                	<table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Student Name</th>
                        <th>Reg.No</th>
                        <th>Gender</th>
                        <th>Level</th>
                        <th>Programme Name</th>
                       <!--  <th>Programme Duration</th>
                        <th>Study Year</th>-->
                        <th>Study Mode</th>
                        <th>Status</th>
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
		                		echo "$programmeName</td>";
		                	}
		                }

		                //echo "$programmeDuration</td><td>";


		                /*$study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID,'studyYearStatus'=>1),' order_by'=>'studentID ASC'));
		                if(!empty($study_year))
		                {
		                    foreach ($study_year as $sy)
		                    {
		                        $studyYear=$sy['studyYear'];
		                    }
		                }
		                else
                        {
                            $studyYear="";
                        }
		                echo "<td>".$studyYear."</td>*/
		                echo "<td>";

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
                	</tbody>
                	</table>
			</div>
</div>
			<hr>


<div class="row">

<?php
$semester=$db->getSemester($regNumber);
if(!empty($semester))
{
    ?>
    <div class="col-md-9">
    <?php
    $totalPoints=0;
    $totalUnits=0;
    foreach($semester as $sm)
    {
        $semesterSettingID=$sm['semesterSettingID'];
        $semesterName=$sm['semesterName'];
        $course = $db->getStudentSearchResult($regNumber,$semesterSettingID);
        if(!empty($course))
        {
                    ?>

                    <div class="box box-solid box-primary">
                  <div class="box-header with-border text-center">
                    <h3 class="box-title">Exam Result for <?php echo $semesterName;?></h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive">
                    <table  id="" class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Status</th>
                        <th>Units</th>
                        <th>Total Marks</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php
                        $count = 0;
                        $i=1;
                        $tunits=0;
                        $tpoints=0;
                        foreach($course as $st)
                        {
                                $count++;
                                $courseID=$st['courseID'];
                                $crstatus=$st['courseStatus'];

                                $coursec= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                if(!empty($coursec))
                                {
                                    ?>
                                    <?php
                                    $i=1;
                                    foreach($coursec as $c)
                                    {
                                        $courseCode=$c['courseCode'];
                                        $courseName=$c['courseName'];
                                        $units=$c['units'];
                                        $courseTypeID=$c['courseTypeID'];
                                    }
                                }
                                else
                                {
                                    $courseCode="";
                                    $courseName="";
                                    $units="";
                                    $courseTypeID="";
                                }

                                    if($crstatus==1)
                                        $status="Core";
                                    else
                                        $status="Elective";
                                    /*$courseStatus = $db->getRows('programmemaping', array('where' => array('courseID' => $courseID), ' order_by' => ' courseStatusID ASC'));
                                    foreach ($courseStatus as $cs) {
                                        $courseStatus = $cs['courseStatusID'];
                                        if ($courseStatus == 1)
                                            $status = "Core";
                                        else
                                            $status = "Elective";
                                    }*/
                                    ?>

                                <tr>
                                <?php
                                echo"<td>$count</td><td>$courseCode</td><td>$courseName</td><td>$status</td><td>$units</td>";
                                $tunits+=$units;

                                $cwk=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,1));
                                $sfe=$db->decrypt($db->getFinalGrade($semesterSettingID,$courseID,$regNumber,2));
                                $sup=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,3));
                                $spc=$db->decrypt($db->getFinalGrade($semesterSettingID,$courseID,$regNumber,4));
                                $prj=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,5));
                                $pt=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,6));

                                $passCourseMark=$db->getExamCategoryMark(1,$regNumber,$studyYear);
                                $passFinalMark=$db->getExamCategoryMark(2,$regNumber,$studyYear);
                                $tmarks=$db->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
                                if(!empty($sup))
                                {
                                    $passMark=$db->getExamCategoryMark(3,$regNumber,$studyYear);
                                    if($tmarks>=$passMark)
                                        $grade="C";
                                    else
                                        $grade="D";
                                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                    $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                }
                                else if(!empty($pt))
                                {
                                    $passMark=$db->getExamCategoryMark(6,$regNumber,$studyYear);
                                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                    if($tmarks>=$passMark)
                                        $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                    else
                                        $grade="D";
                                    $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                }
                                else if(!empty($prj))
                                {
                                    $passMark=$db->getExamCategoryMark(5,$regNumber,$studyYear);
                                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                    if($tmarks>=$passMark)
                                        $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                    else
                                        $grade="D";
                                    $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                }
                                else if(empty($cwk)||empty($sfe))
                                {
                                    $grade="I";
                                    $gradePoint=0;
                                }
                                else if ($cwk < $passCourseMark)
                                {
                                        $grade = "I";
                                        $gradePoint = 0;
                                }
                                else if ($sfe < $passFinalMark)
                                {
                                        $grade = "E";
                                        $gradePoint = 0;
                                } else {
                                    $gradeID = $db->getMarksID($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                    $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                                    $grade = $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt);
                                }
                                $points=$gradePoint*$units;
                                $tpoints+=$points;


                                echo "<td>".$tmarks."</td>
                                <td>".$grade."</td>
                                <td>".$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";

                                ?>
                                <td>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $courseID;?>">
          	 						 <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        							<span><strong></strong></span>
          	 						</button>

                					<?php
                					$published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);
                					if($published==1)
                					{
                					    if($role_session==7) {
                                            ?>
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                    data-target="#message<?php echo $courseID; ?>">
                                                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                                <span><strong></strong></span>
                                            </button>
                                            <?php
                                        }
                					}
                					else
                					{
                					    ?>
                					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $courseID;?>">
          	 					 	<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        							<span><strong></strong></span>
          	 						</button>
                					    <?php
                					}
                					?>
                                </td>
                                </tr>



                <!-- Result Details -->
   <div id="message<?php echo $courseID;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details of <?php echo $courseCode."-".$courseName;?></h4>
      </div>
<form name="register" id="register" enctype="multipart/form-data" method="post" action="action_add_exmption.php">
<div class="modal-body">
<div class="row"  style="background-color:lightgray;">
<div class="col-lg-1"><strong>No</strong></div>
<div class="col-lg-3"><strong>Ass.Title</strong></div>
<div class="col-lg-2"><strong>Max.Marks</strong></div>
<div class="col-lg-2"><strong>Present</strong></div>
<div class="col-lg-2"><strong>Scored</strong></div>
<div class="col-lg-2"><strong>Weights</strong></div>
</div>
<div class="row">
<div class="col-lg-1">1</div>
<div class="col-lg-3">Course Work</div>
<div class="col-lg-2">40</div>
<div class="col-lg-2">
<?php
if($cwk>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $cwk;?></div>
<div class="col-lg-2"><?php echo $cwk;?></div>
</div>
<div class="row" style="background-color:lightgray;">
<div class="col-lg-1">2</div>
<div class="col-lg-3">Final Exam</div>
<div class="col-lg-2">60</div>
<div class="col-lg-2">
<?php
if($sfe>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $sfe;?></div>
<div class="col-lg-2"><?php echo $sfe;?></div>
</div>

<div class="row">
<div class="col-lg-1">3</div>
<div class="col-lg-3">Supplementary</div>
<div class="col-lg-2">100</div>
<div class="col-lg-2">
<?php
if($sup>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $sup;?></div>
<div class="col-lg-2"><?php echo $sup;?></div>
</div>

<div class="row" style="background-color:lightgray;">
<div class="col-lg-1">4</div>
<div class="col-lg-3">Special Exam</div>
<div class="col-lg-2">60</div>
<div class="col-lg-2">
<?php
if($spc>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $spc;?></div>
<div class="col-lg-2"><?php echo $spc;?></div>
</div>

<div class="row">
<div class="col-lg-1">5</div>
<div class="col-lg-3">Project</div>
<div class="col-lg-2">100</div>
<div class="col-lg-2">
<?php
if($pro>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $prj;?></div>
<div class="col-lg-2"><?php echo $prj;?></div>
</div>

<div class="row" style="background-color:lightgray;">
<div class="col-lg-1">6</div>
<div class="col-lg-3">Field Training</div>
<div class="col-lg-2">100</div>
<div class="col-lg-2">
<?php
if($pt>0)
    echo "Yes";
else
    echo "No";
?>
</div>
<div class="col-lg-2"><?php echo $pt;?></div>
<div class="col-lg-2"><?php echo $pt;?></div>
</div>

<div class="row">
<div class="col-lg-10">
<strong><span class="text-danger">Total Marks:</span></strong>
</div>
<div class="col-lg-2">
<strong><span class="text-danger"><?php echo $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);?></span></strong>
</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
 </form>
    </div>

  </div>
</div>
                <!-- End of Result Details -->
                        <?php
                        }
                        $totalPoints+=$tpoints;
                        $totalUnits+=$tunits;
                        ?>
                        <tr>
                            <td colspan="2" align="left" style="font-size: 20px;">
                                <strong><span class="text-danger">Total Credits:<?php echo $tunits;?></span></strong>
                            </td>
                            <td colspan="2" align="left" style="font-size: 20px;">
                                <strong><span class="text-danger">Total Points:<?php echo $tpoints;?></span></strong>
                            </td>
                            <td colspan="3" align="left" style="font-size: 20px;">
                                <strong><span class="text-danger">GPA:<?php echo $db->getGPA($tpoints, $tunits);?></span></strong>
                            </td>
                        </tr>
                 		</tbody>
                 		</table>
       					</div>
       					</div>



                		<?php
                        }
                        else
                        {
                        ?>
                        <h4 class="text-danger">No Result(s) found......</h4>
                        <?php
                        }
                   ?>

	<?php
    }
    ?>
    </div>
    <div class="col-md-3">
                   <div class="box box-solid box-primary">
                                  <div class="box-header with-border text-center">
                                    <h3 class="box-title">Perfomance</h3>
                                  </div>
                                  <!-- /.box-header -->
                                  <div class="box-body table-responsive">
                                  <table  id="" class="table table-striped table-bordered table-condensed">
                                  <thead>
                                  <tr><th>Total Credits: </th><td style="font-size: 18px;"><strong><span class="text-danger"><?php echo $totalUnits;?></span></strong></td></tr>
                                  <tr><th>Total Points</th><td style="font-size: 18px;"><strong><span class="text-danger"><?php echo $totalPoints;?></span></strong></td></tr>
                                  <tr><th>Overall GPA</th><td style="font-size: 18px;"><strong><span class="text-danger"><?php echo $db->convert_gpa($db->getGPA($totalPoints,$totalUnits));?></span></strong></td></tr>
                                  <tr><th>Remarks</th> <?php $gpa=$db->convert_gpa($db->getGPA($totalPoints,$totalUnits));?>
                                      <td style="font-size:18px;"><strong><span class="text-danger"><?php echo $db->getGPARemarks($regNumber,$gpa);?></span></strong></td></tr>
                                    </thead>
                                    <tbody>
                                    <tr>




                                    </tbody>
                                    </table>
                                  </div>
                                  </div>
                    </div>
    <?php
}
else
{
    echo "<h3 class='text-danger'>No Result Found</h3>";
}
?>
     </div>
	<?php
       }
       else
       {
           echo "<h3 class='text-danger'>No Student Found with Reg.Number: ".$searchStudent."</h3>";
       }
}
    ?>

</div>


            <!-- End -->


</div>

            </div>
            
</div></div>