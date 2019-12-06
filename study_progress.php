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
        var titleheader = $('#titleheader').text();
        $('#firsttab').dataTable(
            {
                responsive:true,
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend:'excel',
                        title:titleheader,
                        footer:false,
                        exportOptions:{
                            columns:[0,1,2,3,4,5]
                        }
                    },
                    ,
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5]
                         }
                    }

                ]
            });
    });
</script>


<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#thirdtab').dataTable(
            {
                responsive:true,
                scrollX:true,
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend:'excel',
                        title:titleheader,
                        footer:false,
                        exportOptions:{
                            columns:[0,2,3,4,5,6,7,8]
                        }
                    },
                    ,
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        },
                        exportOptions:{
                            columns:[0,2,3,4,5,6,7,8,9]
                        }

                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0,2,3,5,6,7,8]
                        }
                    }

                ]
            });
    });
</script>


<?php $db=new DBHelper();
?>
<div class="container">
  <div class="content">
      <h1>Student Management</h1>
      <hr>
    <ul class="nav nav-tabs" id="myTab">
        <!--<li><a data-toggle="tab" href="#list_student"><span style="font-size: 16px"><strong>List of Students by Programmes</strong></span></a></li>-->
        <!--<li class="active"><a data-toggle="tab" href="#list_continue_student"><span style="font-size: 16px"><strong>List of Students By Study Year</strong></span></a></li>-->
        <li class="active"><a data-toggle="tab" href="#status"><span style="font-size: 16px"><strong>Update Student Status</strong></span></a></li>
<!--        <li><a data-toggle="tab" href="#transfer"><span style="font-size: 16px"><strong>Transfer Student</strong></span></a></li>
-->        <li><a data-toggle="tab" href="#view_remarks"><span style="font-size: 16px"><strong>Remarks Report</strong></span></a></li>
    </ul>

<div class="tab-content">
 <!-- Previous Semester -->
        <div id="status" class="tab-pane fade in active">
        <!-- Start -->
            <h3>Update Student Status</h3>
            <hr>
<div class="form-group">
<form name="" method="post" action="">
<div class="col-xs-12">
   
   <div class="col-xs-2"><label name="Search">Search Student by Reg.Number:</label></div>
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
            if((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords"))
            {
                if(isset($_POST['doSearch'])=="Search Student") {
                    $searchStudent = $_POST['search_student'];
                }
                else
                {
                    $searchStudent=$_REQUEST['search_student'];
                }

               $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
               ?>
              
                <?php
                if(!empty($studentID))
                {
                	?>
                	<div class="box box-solid box-primary">
                  <div class="box-header with-border text-center">
                    <h3 class="box-title">Student Information</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                	<table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Student Name Code</th>
                        <th>Reg.Number</th>
                        <th>Gender</th>

                       <th>Admission Year</th>
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
                      $admissionYearID=$std['academicYearID'];
                      $statusID=$std['statusID'];
                      $name="$fname $mname $lname";

                      $academicYear=$db->getData('academic_year','academicYear','academicYearID',$admissionYearID);

                     
                      $today=date("Y-m-d");

                        $status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
                        if(!empty($status))
                        {
                            foreach ($status as $st) {
                                $status_value=$st['statusValue'];
                            }
                        }
                      
                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>$academicYear</td><td>$status_value</td>";
		                
		                

		                
                    }
                	?>
                	</tbody>
                	</table>
                	</div>

                        <div class="box-body">
                            <table class="table table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Center Name</th>
                                    <th>Level</th>
                                    <th>Trade Name</th>
                                    <th>Current Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $student_prog=$db->getRows("student_programme",array("where"=>array("regNumber"=>$regNumber)));
                                if(!empty($student_prog))
                                {
                                    foreach($student_prog as $spg) {
                                        $centerRegistrationID = $spg['centerID'];
                                        $programmeLevelID = $spg['programmeLevelID'];
                                        $programmeID = $spg['programmeID'];
                                        $academicYearID=$spg['academicYearID'];
                                        $currentStatus=$spg['currentStatus'];

                                        if($currentStatus==1)
                                            $cStatus="<span class='text-info'>Yes</span>";
                                        else
                                            $cStatus="<span class='text-danger'>No</span>";

                                        echo "<tr><td>".$db->getData('academic_year','academicYear','academicYearID',$academicYearID)."</td><td>".$db->getData('center_registration','centerName','centerRegistrationID',$centerRegistrationID)."</td><td>".$db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID)."</td><td>".$db->getData('programmes','programmeName','programmeID',$programmeID)."</td><td>$cStatus</td>";

                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

			<hr>
                    <?php
                   /* if($statusID == 2 || $statusID == 3 || $statusID == 7)
                    {

                    }
                    else {*/
                        ?>
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <h4 class="text-primary">Define New Status</h4>
                            </div>
                        </div>
                        <form name="" method="post" action="action_change_status.php" enctype="multipart/form-data">

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("#status_date").datepicker({
                                        dateFormat: "yy-mm-dd",
                                        changeMonth: true,
                                        changeYear: true,
                                    });
                                });
                            </script>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="MiddleName">Status Name</label>
                                    <select name="statusID" class="form-control" required>
                                        <?php
                                        $status = $db->getRows('status', array('order_by' => 'statusValue ASC'));
                                        if (!empty($status)) {
                                            echo "<option value=''>Please Select Here</option>";
                                            $count = 0;
                                            foreach ($status as $c) {
                                                $count++;
                                                $statusValue = $c['statusValue'];
                                                $statusID = $c['statusID'];
                                                ?>
                                                <option value="<?php echo $statusID; ?>"><?php echo $statusValue; ?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="FirstName">Academic Year</label>
                                    <select name="academicYearID" id="academicYearID"  class="form-control" required>
                                        <?php
                                        $academicYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                        if(!empty($academicYear)){
                                            $count = 0;
                                            foreach($academicYear as $yr){ $count++;
                                                $academicYearID=$yr['academicYearID'];
                                                $academicYear=$yr['academicYear'];
                                                ?>
                                                <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                            <?php }}?>
                                    </select>

                                </div>


                                <div class="col-lg-3">
                                    <label for="FirstName">Date of Remarks</label>
                                    <input type="text" name="statusDate" class="form-control" id="status_date">
                                </div>

                                <div class="col-lg-3">
                                    <label for="FirstName">Attachment</label>
                                    <input type='file' name="photo" accept=".pdf"/>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-9"></div>
                                <div class="col-lg-3">
                                    <input type="hidden" name="action_type" value="add"/>
                                    <input type="hidden" name="regNumber" value="<?php echo $regNumber; ?>">
                                    <input type="submit" name="doSubmit" value="Save Records"
                                           class="btn btn-success form-control"/>
                                </div>
                            </div>
                        </form>

<div class="row">
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been delete successfully</strong>.
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
                echo "<h4 class='text-info'>List of Registered Remarks</h4>";
                $statusList = $db->getRows('student_status',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' statusDate DESC'));
                if(!empty($statusList))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed" id="" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                      	<th>No</th>
                      	<th>Status Name</th>
                        <th>Academic Year</th>
                        <th>Status Date</th>
                        <th>Attachment</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($statusList as $list)
                    { 
                      $count++;
                      $statusID=$list['statusID'];
                      $studentStatusID=$list['studentStatusID'];
                      $academicYearID2=$list['academicYearID'];
                      $statusDate=$list['statusDate'];
                      $uploadedFile=$list['uploadedFile'];
                      
                    
                     ?>			 <tr><td><?php echo $count;?></td>
		                		<td><?php echo $db->getData("status","statusValue","statusID",$statusID);?></td>
                            	<td><?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID2);;?></td>
                            	<td><?php echo $statusDate;?></td>   
                            	
                            	<?php 
                            	if(empty($uploadedFile))
                                {
                                 ?>
                                 <td>-</td>
                                 <?php 
                                }
                                else
                                {
                                ?>
                                <td><a href="uploaded_file/<?php echo $uploadedFile;?>" class="glyphicon glyphicon-download-alt" target="_blank"></a></td>
                                <?php
                                }
                                ?>
                       			<td><a href="action_change_status.php?action_type=drop&id=<?php echo $studentStatusID;?>&reg=<?php echo $db->my_simple_crypt($searchStudent,'e');?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
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
           			echo "<h4 class='text-danger'>No Remarks Found Student</h4>";
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
        
        <!-- End -->  
		</div>
    <!--start-->
<div id="view_remarks" class="tab-pane fade">
            <!-- Start -->
            
<h3>View Student Remarks</h3>
            <hr>
           <div class="row">
            <form name="" method="post" action="">
                        <div class="col-lg-3">

                            <label for="MiddleName">Academic Year</label>
                            <select name="academicYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>


                <div class="col-lg-3">
                    <label for="MiddleName">Status Name</label>
                    <select name="statusID" class="form-control" required>
                        <?php
                        $status = $db->getRows('status', array('order_by' => 'statusValue ASC'));
                        if (!empty($status)) {
                            echo "<option value=''>Please Select Here</option>";
                            $count = 0;
                            foreach ($status as $c) {
                                $count++;
                                $statusValue = $c['statusValue'];
                                $statusID = $c['statusID'];
                                ?>
                                <option value="<?php echo $statusID; ?>"><?php echo $statusValue; ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
                        
                        
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doViewList" value="View List" class="btn btn-primary form-control" /></div>
               </form>    
        </div>      

            
        <?php
        if(isset($_POST['doViewList'])=="View List")
        {
                $academicYearID=$_POST['academicYearID'];
                $statusID=$_POST['statusID'];
                $studentStatus=$db->getStudentYearStatus($academicYearID,$statusID);
               if(!empty($studentStatus))
               {
        ?>
    <h4><span class="text-danger" id="titleheader">
                List of <?php echo $db->getData("status","statusValue","statusID",$statusID);?>
            Students  in <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
    <hr>
    <table  id="thirdtab" class="display nowrap">
        <thead>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Reg.Number</th>
            <th>Center Name</th>
            <th>Trade Level</th>
            <th>Trade Name</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 0;
        foreach($studentStatus as $st)
        {
            $count++;
            $studentID=$st['studentID'];
            $fname=$st['firstName'];
            $mname=$st['middleName'];
            $lname=$st['lastName'];
            $programmeID=$st['programmeID'];
            $semesterSettingID=$st['semesterSettingID'];
            $name="$fname $mname $lname";
            $regNumber=$st['registrationNumber'];


            $student_prog=$db->getRows("student_programme",array("where"=>array("regNumber"=>$regNumber,'currentStatus'=>1)));
            if(!empty($student_prog))
            {
                foreach($student_prog as $spg)
                {
                    $centerRegistrationID=$spg['centerID'];
                    $programmeLevelID=$spg['programmeLevelID'];
                    $programmeID=$spg['programmeID'];
                }
            }
            else
            {
                $centerRegistrationID="";
                $programmeLevelID="";
                $programmeID="";
            }


            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $name;?></td>
                <td><?php echo $st['gender']; ?></td>
                <td><?php echo $st['registrationNumber']; ?></td>
                <td><?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerRegistrationID); ?></td>
                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID); ?></td>
                <td><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID); ?></td>

            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
        <?php
               }
               else {
                   ?>
                   <h4><span class="text-danger">No Student(s) found......</span></h4>
                   <?php
               }

        }
        ?>

        
    
              
            <!-- End -->           
</div>

            </div>
      <!--end-->
            
</div></div>