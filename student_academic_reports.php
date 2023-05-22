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

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php $db=new DBHelper();
?>
<div class="container">
  <div class="content">
    <h1>Student Academic Reports</h1>
    <!-- Changing  -->
      <hr>
    <ul class="nav nav-tabs" id="myTab">
    
        <li class="active"><a data-toggle="tab" href="#semester_report"><span style="font-size: 16px"><strong>Progress Report</strong></span></a></li>
<!--        <li><a data-toggle="tab" href="#progress_report"><span style="font-size: 16px"><strong>Progress Report</strong></span></a></li>
-->        <li><a data-toggle="tab" href="#transcripts"><span style="font-size: 16px"><strong>Transcripts</strong></span></a></li>
        
    </ul>

<div class="tab-content">
    <!-- Current Semester -->
<div id="semester_report" class="tab-pane fade in active">
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
    if(((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")))
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
                            <!-- <th>Study Year</th> -->
                            <!-- <th>Study Mode</th> -->
                            <th>Status</th>
                            <th>Picture</th>
                            <th>Action</th>
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
                            // $programmeID=$std['programmeID'];
                            $statusID=$std['statusID'];
                            
                            $studentPicture=$std['studentPicture'];
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
                         $student_program = $db->getRows('student_programme',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
                         if (!empty($student_program)) {
                            foreach($student_program as $pro)
                            {

                                $studentProgrammeID=$pro['studentProgrammeID'];
                                $programmeID=$pro['programmeID'];
                                $programmeLevel=$pro['programmeLevelID'];
                                $centerID=$pro['centerID'];
                               

                            } 
                           
                         }  
                         
                         


                            echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";
                          
                            $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevel),' order_by'=>' programmeLevelCode ASC'));
                            if(!empty($level))
                            {
                                foreach ($level as $lvl) {
                                    $programme_level_code=$lvl['programmeLevelCode'];
                                    echo "<td>$programme_level_code</td>";
                                }
                            }

                            $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
                            if(!empty($programme))
                            {
                                foreach ($programme as $pro) {
                                    $programmeName=$pro['programmeName'];
                                    $programmeDuration=$pro['programmeDuration'];
                                    echo "<td>$programmeName</td>";
                                }
                            }



                            $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'studentID ASC'));
                            if(!empty($study_year))
                            {
                                foreach ($study_year as $sy)
                                {
                                    $studyYear=$sy['studyYear'];
                                   
                                }
                            }
                            // echo"<td $studyYear </td>";

                          
                            $status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
                            if(!empty($status))
                            {
                                foreach ($status as $st) {
                                    $status_value=$st['statusValue'];
                                    echo "<td>$status_value</td>";
                                }
                            }

                        }
                        ?>
                        <td>
                        <?php
                        if(!empty($studentPicture))
                        {
                        ?>
                            <img id="image" src="student_images/<?php echo $studentPicture; ?>" height="150px" width="150px;"/>
                        </td>
                        <?php
                        }else
                        {
                            
                            ?>
                            
                            <form method="post" name="upload" action="action_upload_new_picture.php" enctype="multipart/form-data">
                                <img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />
                                <input type='file' name="student_image" accept=".jpg" onchange="readURL(this);" />
                                <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                                <input type="hidden" name="action_type" value="add"/>
                                <input  type="submit" class="btn btn-success" name="btnSave" value="Upload Picture"/>
                              </form>
                             
                                       
                            <?php
                        }
                            ?>
                        <td>

                                <?php
                                if(!empty($studentPicture))
                                {     
                                    ?>

                                    <div class="col-lg-6">
                                
                                    <select name="examcategory[]" class="form-control chosen-select" multiple required>
                                    <?php
                                        $student = $db->getRows('exam_category',array('order_by'=>'examCategoryID ASC'));
                                        if(!empty($student)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($student as $studentlevel){ $count++;
                                                $examCategoryID=$studentlevel['examCategoryID'];
                                                //  $centerID=$studentlevel['centerID'];
                                                $regNumber=$studentlevel['regNumber'];
                                                $term=$db->getData('exam_category','examCategory','examCategoryID',$examCategoryID);
                                        ?>
                                                <option value="<?php echo $examCategoryID;?>"><?php echo $term;?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                </select>
                                       
                                    
                            

                                   
                                    
                                </div>
                                    
                                    <div class="col-lg-6">
                                
                                           
                                        
                                        <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Preview</button>
                                        <!-- <a href="print_statement_report.php?action=getPDF&regNo=<?php echo $regNumber; ?>&cateID=<?php echo $examCategoryID; ?>">click</a> -->

                                    </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    No Preview
                                    <?php
                                }
                                ?>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
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
    <div id="add_new_atype_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Preview Course Result</h4>
                </div>
                <div class="modal-body">
                    
                
                    <embed src="print_statement_report.php?action=getPDF&regNo=<?php echo $regNumber;?>&categoryID=<?php echo $examCategoryID;?>" frameborder="0" width="100%" height="600px">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
 </div>  
<!-- End of Current Semester -->



<div id="transcripts" class="tab-pane fade">
            <!-- Start -->
            
    <div class="form-group">
<form name="" method="post" action="">
<h3>Search student to preview his/her Transcript</h3>
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
            if(((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")))
            {
              $searchStudent=$_POST['search_student'];
              $searchStudent=$_REQUEST['search_student'];

              $studentID = $db->transcriptList($searchStudent);
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
                        <!-- <th>Study Mode</th> -->
                         <!-- <th>CGPA</th>-->
                        <th>Status</th>
                        <th>Picture</th>
                        <th>Preview</th>
                          <th>More</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($studentID as $std)
                    { 
                      $count++;
                      $fname=$std['firstName'];
                      $mname=$std['middleName'];
                      $lname=$std['lastName'];
                      $gender=$std['gender'];
                      $regNumber=$std['registrationNumber'];
                    // $programmeID=$std['programmeID'];


                     
/*                      $gpa=$std['gpa'];*/
                      $name="$fname $mname $lname";

                      $student_program = $db->getRows('student_programme',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
                         if (!empty($student_program)) {
                            foreach($student_program as $pro)
                            {

                                $studentProgrammeID=$pro['studentProgrammeID'];
                                $programmeID=$pro['programmeID'];
                                $programmeLevel=$pro['programmeLevelID'];
                                $centerID=$pro['centerID'];
                               

                            } 
                           
                         }  


                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";

                    //  $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
                     $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
		                if(!empty($level))
		                {
		                	foreach ($level as $lvl) {
		                		$programme_level_code=$lvl['programmeLevelCode'];
		                		echo "<td>$programme_level_code</td>";
		                	}
		                }

		                $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		$programmeDuration=$pro['programmeDuration'];
		                		echo "<td>$programmeName</td>";
		                	}
		                }
		              
		                
                    }
                	?>
                    <td>
                    <?php
                    if(!empty($studentPicture)) {
                        ?>
                        <form method="post" name="upload" action="action_upload_new_picture.php" enctype="multipart/form-data">
                            <img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />
                            <input type='file' name="student_image" accept=".jpg" onchange="readURL(this);" />
                            <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                            <input type="hidden" name="action_type" value="add"/>
                            <input  type="submit" class="btn btn-success" name="btnSave" value="Upload Picture"/>
                        </form>
                        <?php
                    }else {
                        ?>
                        <form method="post" name="upload" action="action_upload_new_picture.php" enctype="multipart/form-data">
                        <img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />
                        <input type='file' name="student_image" accept=".jpg" onchange="readURL(this);" />
                            <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                            <input type="hidden" name="action_type" value="add"/>
                            <input  type="submit" class="btn btn-success" name="btnSave" value="Upload Picture"/>
                        </form>
                        <?php
                    }
                        ?>
                    </td>
                    <td>
                       <?php
                        if(!empty($studentPicture))
                        {
                            ?>
                            <div class="col-lg-12">
                                <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#view_transcript"><i class="fa fa-download"></i>Print Preview</button>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            No Preview
                            <?php
                        }
                        ?>

                    </td>
                    <td>
                        <a href="index3.php?sp=transcript_details&regNo=<?php echo $db->my_simple_crypt($regNumber,'e');?>" class="btn btn-primary glyphicon glyphicon-fast-forward" role="button"></a>
                    </td>
                	</tr>
                	</tbody>
                	</table>
			</div>
</div>
			<hr>
	<?php
       }
       else 
       {
           echo "<h3 class='text-danger'>Student with Reg.Number: ".$searchStudent." are not in graduate list</h3>";
       }
}
    ?>
   
</div>
    <div id="view_transcript" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Preview Transcript</h4>
                </div>
                <div class="modal-body">
                    <embed src="print_transcript_report.php?action=getPDF&regNo=<?php echo $regNumber;?>" frameborder="0" width="100%" height="600px">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <strong>Loading...</strong>
            </div>
        </div>
    </div>-->

            <!-- End -->           
  </div>
            
</div></div>
