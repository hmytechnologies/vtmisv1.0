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
$instructorID=$db->getData("instructor","instructorID","userID",$_SESSION['user_session']);
?>
<div class="container">
  <div class="content">
  <h1>Exam Results</h1>
      <hr>
    <ul class="nav nav-tabs" id="myTab">
    
        <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Current Year</strong></span></a></li>
        <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>Previous Year</strong></span></a></li>
<!--        <li><a data-toggle="tab" href="#publish"><span style="font-size: 16px"><strong>Publish Course Work</strong></span></a></li>
-->
    </ul>

<div class="tab-content">
    <!-- Current Semester -->
<div id="currentdata" class="tab-pane fade in active">
 <script type="text/javascript">
 $(document).ready(function () {
 $("#studentdata").DataTable({
	"ajax": "api/marksmanagement.php",
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
<div class="row">
 <div class="col-md-12">     
 <div class="box box-solid box-primary">
          <div class="box-header with-border text-center">
            <h3 class="box-title">Registered Course for <?php echo $semesterName;?></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
<table  id="" class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
    <th>No.</th>
    <th>Subject Name</th>
    <th>Subject Code</th>
    <th>#Students</th>
    <th>Post</th>
    <th>Bulk Post</th>
    <th>View</th>
    <th>Published</th>
     </tr>
  </thead>
  <tbody>    
<?php
$count = 0; foreach($courseprogramme as $std){ $count++;
$courseID=$std['courseID'];
// $courseProgrammeID=$std['courseProgrammeID'];
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

// $instructor = $db->getRows('instructor_course',array('where'=>array('courseProgrammeID'=>$courseProgrammeID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseProgrammeID ASC'));
// if(!empty($instructor))
// {
//     foreach($instructor as $i)
//     {
//         $instructorID=$i['instructorID'];
//         $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
//     }
// }
// else
// {
//     $instructorName="Not assigned";
// }

$studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);

$checked=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);
$published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);

$boolExamStatus=$db->checkExamResultStatus($courseID,$semesterSettingID,$batchID);


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
 <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
 <td><?php echo $addButton;?></td>
 <td><?php echo $excelButton;?></td>
 <td><?php echo $viewButton;?></td>
 <td><?php echo $statusPublished;?></td>
 </tr>
 
 <?php 
}
 ?>
  </tbody>
 </table></div></div>
 </div></div>
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
            <h3>Previous Semester</h3>
            <div class="row">
            <form name="" method="post" action="">
            <div class="col-md-12">
            <div class="row">
            <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semisterID" id="semisterID" class="form-control">
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
        <div class="col-lg-12">
                <hr>
        </div>
        </div>
    <div class="row">
 	<div class="col-lg-12"> 
 	 <div class="box box-solid box-primary">
          <div class="box-header with-border text-center">
            <h3 class="box-title">Registered Course for <?php echo $semesterName;?></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">  
	<table id="data" class="table table-striped table-bordered table-condensed">
	<thead>
  	<tr>
    <th>No.</th>
    <th>Course Name</th>
    <th>Course Code</th>
    <th>#Students</th>
    <th>Slot Name</th>
    <th>Post</th>
    <th>Bulk Post</th>
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
            // $courseProgrammeID=$std['courseProgrammeID'];
     
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
     
     $checked=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);
     $published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);
     
     $boolExamStatus=$db->checkExamResultStatus($courseID,$semesterSettingID,$batchID);
     
     
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
 <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
 <td><?php echo $addButton;?></td>
 <td><?php echo $excelButton;?></td>
 <td><?php echo $viewButton;?></td>
 <td><?php echo $statusPublished;?></td>
 </tr>
 
  <?php }?>
   </tbody>
 </table></div></div>
 </div></div>  
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
			</div>
       
         <!-- End -->
    <!--start publish result-->
<!--    <div id="publish" class="tab-pane fade">
        <div class="row">
                <?php
/*                $today=date("Y-m-d");
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
                    */?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Publish Course Work for Registered Course for <?php /*echo $semesterName;*/?></h3>
                                </div>

                                <div class="box-body">
                                    <form name="register2" id="register2" method="post" action="action_publish_course_work.php">
                                    <table  id="" class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                            <th>Course Name</th>
                                            <th>Course Code</th>
                                            <th>#Students</th>
                                            <th>Batch</th>
                                            <th>View</th>
                                            <th>Published</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
/*                                        $count = 0; foreach($courseprogramme as $std){ $count++;
                                            $courseID=$std['courseID'];
                                            $courseProgrammeID=$std['courseProgrammeID'];
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

                                            $studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);

                                            $checked=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);
                                            $published=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);

                                            $boolExamStatus=$db->checkExamResultStatus($courseID,$semesterSettingID,$batchID);


                                            if($published==1)
                                                $statusPublished="<span class='text-success'>Yes</span>";
                                            else
                                                $statusPublished="<span class='text-danger'>No</span>";

                                            if($studentNumber==0)
                                            {

                                                $viewButton = '
                                                <div class="btn-group">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </div>';
                                            }
                                            else {
                                                if ($boolExamStatus == true) {
                                                    $viewButton = '
                                                   <div class="btn-group">
                                                         <a href="index3.php?sp=view_course_work&cid=' . $db->encrypt($courseID) . '&sid=' . $db->encrypt($semesterSettingID) . '&bid=' . $db->encrypt($batchID) . '" class="glyphicon glyphicon-eye-open"></a>
                                                   </div>';
                                                } else {
                                                    $viewButton = '
                                                <div class="btn-group">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </div>';
                                                }

                                            }
                                            */?>

                                            <tr>
                                                <td><?php /*echo $count;*/?></td>
                                                <?php
/*                                                if($boolExamStatus==false)
                                                {
                                                    */?>
                                                    <td>NA</td>
                                                    <?php
/*                                                }
                                                else
                                                {
                                                    */?>
                                                    <td><input type='checkbox' class='checkbox_class' name='id[]' value='<?php /*echo $courseID;*/?>'></td>
                                                    <?php
/*                                                }*/?>
                                                <td><?php /*echo $courseName;*/?></td>
                                                <td><?php /*echo $courseCode;*/?></td>
                                                <td><?php /*echo $studentNumber;*/?></td>
                                                <td><?php /*echo $db->getData("batch","batchName","batchID",$batchID);*/?></td>
                                                <td><?php /*echo $viewButton;*/?></td>
                                                <td><?php /*echo $statusPublished;*/?></td>
                                            </tr>

                                            <?php
/*                                        }
                                        */?>
                                        </tbody>
                                    </table>
                                        <div class="row">
                                            <div class="col-lg-6"></div>
                                            <input type="hidden" name="number_applicants" value="<?php /*echo $count;*/?>">
                                            <input type="hidden" name="semesterID" value="<?php /*echo $semesterSettingID;*/?>">
                                            <input type="hidden" name="batchID" value="<?php /*echo $batchID;*/?>">
                                            <div class="col-lg-3">
                                                <input type="hidden" name="action_type" value="add"/>
                                                <input type="submit" name="doAdmit" value="Publish" class="btn btn-success form-control">
                                            </div>
                                            <div class="col-lg-3">
                                                <input type="hidden" name="action_type" value="edit"/>
                                                <input type="submit" name="doReject" value="Unpublish" class="btn btn-danger form-control">
                                            </div>
                                        </div>
                                    </form>
                                </div></div>
                        </div></div>
                    <?php
/*                }
                else
                {
                    echo "<h4 class='text-danger'>No Course Found</h4>";
                }
                */?>
            </form>
        </div>
    </div>
--><!--end-->

            </div>
            
</div></div>