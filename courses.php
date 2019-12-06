<div class="container">
<div class="row"> 
    <h2>Course Management</h2>
<div class="col-md-12">
<div class="pull-right">
                  <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Setting</a>  <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Course</button>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Duplicate entry! A course with this code already exists.</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">
     <h3>List of Registered Course</h3>
     <br>
     <?php
          
            $db = new DBHelper();
            $users = $db->getRows('course',array('order_by'=>'courseName DESC'));
?>
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>No.</th>
    <th>Course Code</th>
    <th>Course Name</th>
    <th>Type</th>
    <th>Capacity</th>
    <th>Credits</th>
    <th>Hrs</th>
    <th>Department</th>
    <th>Status</th>
    <th>Edit</th>
    </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
  if($user['status']==1)
  {
    $status="Active";
  }
  else
  {
    $status="Not Active";
  }
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['courseCode']; ?></td>
                <td><?php echo $user['courseName']; ?></td>
                <td><?php echo $db->getData("course_type","courseType","courseTypeID",$user['courseTypeID']);?></td>
                <td><?php echo $user['capacity']; ?></td>
                <td><?php echo $user['units']; ?></td>
                <td><?php echo $user['numberOfHours']; ?></td>
                <td><?php echo $db->getData("departments","departmentName","departmentID",$user['departmentID']); ?></td>
                <td><?php echo $status;?></td>
                
              <td>
                    <a href="index3.php?sp=edit_course&id=<?php echo $user['courseID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<form name="" method="post" action="action_course.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Course Name</label>
<input type="text" id="name" name="name" placeholder="Course Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Code</label>
<input type="text" id="code" name="code" placeholder="Course Code" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Type</label>
<select name="courseTypeID"  class="form-control">
           <?php
           $type = $db->getRows('course_type',array('order_by'=>'courseType DESC'));
           if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
            $courseType=$t['courseType'];
            $courseTypeID=$t['courseTypeID'];
           ?>
           <option value="<?php echo $courseTypeID;?>"><?php echo $courseType;?></option>
           <?php }}?>
</select></div>

<div class="form-group">
<label for="email">Course Units</label>
<input type="number" id="units" name="units" step="any" placeholder="Course Units" class="form-control" />
</div>

<div class="form-group">
<label for="email">Number of Hours/Week</label>
<input type="number" id="nhrs" name="nhrs" placeholder="Number of Hours per Week;Eg 3" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Capacity</label>
<input type="number" id="capacity" name="capacity" placeholder="Course Capacity" class="form-control" />
</div>


<div class="form-group">
<label for="email">Department Name</label>
<select name="department_id"  class="form-control">
           <?php
           $department = $db->getRows('departments',array('order_by'=>'departmentName DESC'));
           if(!empty($department)){ $count = 0; foreach($department as $dept){ $count++;
            $department_name=$dept['departmentName'];
            $department_id=$dept['departmentID'];
           ?>
           <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
           <?php }}?>
</select>

</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
</form>
</div>
</div>
</div>
</div>