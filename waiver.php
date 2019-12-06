<div class="container">
<div class="content">
<h3>Manage Student exemption by searching his/her information</h3>
<hr>
<div class="form-group">
<form name="" method="post" action="">
<div class="col-xs-12">
   
   <div class="col-xs-2"><label name="Search">Search Student By Reg.Number:</label></div>
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
                    <h3 class="box-title">Exam Result Information</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
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
                	</div>
                	</div>
                	<!--<div class="row"> 
						<div class="col-md-12">
						<div class="pull-right">
						                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Register New Course</button>
						            </div>   
						 </div>
						</div>-->
			<hr>
			
			
  <?php
  $debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>' regNumber ASC'));
  if(!empty($debit))
  {
      $count=0;
      $totalA=0;$totalD=0;$totalE=0;$totalP=0;$total=0;
      foreach($debit as $dbt)
      {
          $count++;
          $studentFeesID=$dbt['studentFeesID'];
          $amount=$dbt['amount'];
      }
  }
  
 
  
  $payment_setting = $db->getRows('payment_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'semesterSettingID   ASC'));
  if(!empty($payment_setting))
  {
      foreach($payment_setting as $ps)
      {
          $mAmount=$ps['minimumAmount'];
      }
  }
  
  if($mAmount>0)
      $sAmount=($mAmount/100)*$amount;
  else
      $sAmount=$totalAmount;
  
      $statusList = $db->getRows('student_exemption',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID),' order_by'=>' dateOfExemption DESC'));
      if(empty($statusList))
      {
      ?>
      <div class="row">
			<div class="col-lg-6"></div>
			<div class="col-lg-6">
			<h4 class="text-danger">Define New Exemption</h4>
			
			</div>
			<hr>
			</div>
			<form name="" method="post" action="action_add_exemption.php" enctype="multipart/form-data"> 
               <div class="row">
               
               <div class="col-lg-3">
                           <label for="FirstName">Semester Amount Required</label>
                            <input type='text' name="debit" value="<?php echo number_format($sAmount);?>" class="form-control" readonly/>
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
                           <label for="FirstName">Date of Registration</label>
                            <div class="input-group date form_date col-md-9" data-date="" data-date-format="yyyy MM dd" 
                            data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" name="statusDate" value="" id="pickyDate"> 
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                           <label for="FirstName">Attachment</label>
                            <input type='file' name="photo" accept=".pdf" />
                        </div>
                      </div>
                 <br>
                  <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
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
      }
?>
                	<?php
                	//List of Courses
                echo "<h4 class='text-info'>List of Registered Exemption</h4>";
                $statusList = $db->getRows('student_exemption',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' dateOfExemption DESC'));
                if(!empty($statusList))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed" id="example" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                      	<th>No</th>
                      	<th>Semester Name</th>
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
                      $studentExemptionID=$list['studentExemptionID'];
                      $semesterSettingID=$list['semesterSettingID'];
                      $statusDate=$list['dateOfExemption'];
                      $uploadedFile=$list['attachment'];
                      
                    
                     ?>			 <tr><td><?php echo $count;?></td>
                            	<td><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);?></td>
                            	<td><?php echo $statusDate;?></td>   
                            	
                            	<?php 
                            	if($uploadedFile=="")
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
                       			<td><a href="action_add_exemption.php?action_type=drop&id=<?php echo $studentExemptionID;?>&reg=<?php echo $db->my_simple_crypt($searchStudent,'e');?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
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
           			echo "<h4 class='text-danger'>No Exemption Found</h4>";
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
</div>
</div>         