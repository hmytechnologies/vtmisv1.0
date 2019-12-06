<script>
$( document ).ready(function() {
    $('#myModal').on('hidden.bs.modal', function () {
          $(this).removeData('bs.modal');
    });
});
</script>
<?php
	/*if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}*/
	?>

<h4 class="text-info"><b>Candidate Transcript</h4>
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
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="$successMSG")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$successMSG."</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="$errMSG") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$errMSG."</strong>.
</div>";
  }
}
?> 
    
<?php
$db=new DBHelper();
if(isset($_GET['delete_id']))
	{
		// select image from db to delete
                $id=$_GET['delete_id'];
		$stmt_select = $db->runQuery('SELECT studentPic FROM student_picture WHERE studentPictureID =:sid');
		$stmt_select->execute(array(':sid'=>$id));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("student_images/".$imgRow['studentPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $db->runQuery('DELETE FROM student_picture WHERE studentPictureID =:sid');
		$stmt_delete->bindParam(':sid',$id);
		$stmt_delete->execute();
	}

?>
    
</div>
<div class="row">
	<?php
           
            if((isset($_POST['doSearch'])=="Search Student") ||(($_REQUEST['action']=="getRecords")))
            {
              $searchStudent=$_POST['search_student'];
              $searchStudent=$_REQUEST['search_student'];

               $student = $db->getRows('student',array('where'=>array('registration_number'=>$searchStudent),' order_by'=>' student_id ASC'));
               ?>
              
                <?php
                if(!empty($student))
                {
                	?>
                	<table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Student Name Code</th>
                        <th>Reg.Number</th>
                        <th>Gender</th>
                        <th>Level</th>
                        <th>Programme Name</th>
                        <th>Study Year</th>
                        <th>Student Status</th>
                        <th>Student Picture</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($student as $std)
                    { 
                      $count++;
                      $studentID=$std['student_id'];
                      $fname=$std['fname'];
                      $mname=$std['mname'];
                      $lname=$std['lname'];
                      $gender=$std['gender'];
                      $regNumber=$std['registration_number'];
                      $programmeID=$std['programme_id'];
                      $levelID=$std['programme_level_id'];
                      $statusID=$std['status_id'];
                      $name="$fname $mname $lname";


                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>";

                     $level= $db->getRows('programme_level',array('where'=>array('programme_level_id'=>$levelID),' order_by'=>' programme_level_code ASC'));
		                if(!empty($level))
		                {
		                	foreach ($level as $lvl) {
		                		$programme_level_code=$lvl['programme_level_code'];
		                		echo "$programme_level_code</td><td>";
		                	}
		                }

		                $programme= $db->getRows('programmes',array('where'=>array('programme_id'=>$programmeID),' order_by'=>' programme_name ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programme_code=$pro['programme_code'];
		                		echo "$programme_code</td><td>";
		                	}
		                }

                	
                	echo "1</td><td>";
                	$status= $db->getRows('status',array('where'=>array('status_id'=>$statusID),' order_by'=>'status_value ASC'));
		                if(!empty($status))
		                {
		                	foreach ($status as $st) {
		                		$status_value=$st['status_value'];
		                		echo "$status_value</td>";
		                	}
		                }
                	?>
                      <td>
                        <?php
                        
                        $stdPicture=$db->getRows('student_picture',array('where'=>array('studentID'=>$studentID),' order_by'=>'studentID ASC'));
                        if(!empty($stdPicture))
                        {
                            foreach($stdPicture as $pct)
                            {
                                $studentPic=$pct['studentPic'];
                                $id=$pct['studentPictureID'];
                                ?>
                          <img src="student_images/<?php echo $studentPic; ?>" class="img-rounded" width="120px" height="140px" /><br>
                          <span> 
				<a class="btn btn-danger" href="?sp=transcript&delete_id=<?php echo $id; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><span class="glyphicon glyphicon-remove-circle"></span> </a>|
                              <a href="printtranscript.php?action=getPDF&studentID=<?php echo $studentID;?>" target="_blank">Preview</a>
                          </span>
                          <?php
                            }
                        }
                        else {
                        ?>
                          <a data-toggle="modal" href="uploadimage.php?id=<?php echo $studentID;?>" data-target="#myModal" class="btn btn-link">Upload Image</a>
                        
				
				
                              <?php }?>  
                      </td>
<?php }?>
                	<!--<td><a href='index3.php?sp=studentregister&action=getDatails&studentID=<?php echo $studentID;?>'>Details</a></td>--></tr>
                	</tbody>
                	</table>

        
                            
                            
        
                            
                            
                            
<?php 

                	//End of List
           }
           else
           {
           	echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
           }
       }
    ?>
    </div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <strong>Loading...</strong>
        </div>
    </div>
</div>
