
<h1><b>Search Student</h1>
<hr>
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
            <h3 class="box-title">Student Profile</h3>
        </div>
            <div class="box-body">
                	<table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Student Name Code</th>
                        <th>Reg.Number</th>
                        <th>Gender</th>
                        <th>Level</th>
                        <th>Programme Name</th>
                        <th>Student Status</th>
                          <th>Edit</th>
                          <th>View</th>
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
                      $statusID=$std['statusID'];
                      $academicYearID=$std['academicYearID'];
                      $name="$fname $mname $lname";

                      
                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";

                    $student_programme=$db->getRows("student_programme",array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID)));
                    if(!empty($student_programme)) {
                        foreach ($student_programme as $sp) {
                            $programmeID = $sp['programmeID'];
                            $programmeLevelID = $sp['programmeLevelID'];
                        }
                    }


                     $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
		                if(!empty($level))
		                {
		                	foreach ($level as $lvl) {
		                		$programme_level_code=$lvl['programmeLevelCode'];

		                	}
		                }

		               $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		$programmeDuration=$pro['programmeDuration'];

		                	}
		                }

		                
		                $status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
		                if(!empty($status))
		                {
		                    foreach ($status as $st) {
		                        $status_value=$st['statusValue'];

		                    }
		                }

                        echo "<td>$programme_level_code</td>";echo "<td>$programmeName</td>"; echo "<td>$status_value</td>";
		                
                    }

                    $editButton = '
                    <div class="btn-group">
                         <a href="index3.php?sp=edit_student&id='.$db->my_simple_crypt($studentID,'e').'" class="glyphicon glyphicon-edit"></a>
                    </div>';
                                    $viewButton = '
                    <div class="btn-group">
                         <a href="index3.php?sp=view_student_profile&id='.$db->my_simple_crypt($studentID,'e').'" class="glyphicon glyphicon-eye-open"></a>
                    </div>';

                	?>
                	<td>
                        <?php
                        echo $editButton;
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $viewButton;
                        ?>
                    </td>

                	</tr>
                	</tbody>
                    </table></div>
                	<?php
           
       }
  }
    ?>
    </div>
