<div class="container">
<h1>Department Management</h1>
  <br>
<hr>
<div class="row">
<div class="col-md-12">
<div class="pull-right">
    <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Settings</a> <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Department</button>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=departments' class='close' data-dismiss='alert'>&times;</a>
    <strong>Department data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=departments' class='close' data-dismiss='alert'>&times;</a>
    <strong>Department data has been edited Successfully</strong>.
</div>";
  }

  else if($_REQUEST['msg']=="exist")
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=departments' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory,Department is already exist</strong>.
</div>";
  }
  else
  {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=departments' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<?php
          
            $db = new DBHelper();
            $users = $db->getRows('departments',array('order_by'=>'status DESC'));
?>
<h3 class="text-info">List of Registered Departments</h3>
<table  id="example" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Department Name</th>
    <th>Department Code</th>
    <th>Department Address</th>
    <th>Head of Department</th>
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
                <td><?php echo $user['departmentName']; ?></td>
                <td><?php echo $user['departmentCode']; ?></td>
                <td><?php echo $user['departmentAddress']; ?></td>
                <td><?php echo $user['headOfDepartment'] ?></td>
                <td><?php echo $status;?></td>
              <td>
                    <a href="index3.php?sp=edit_department&id=<?php echo $user['departmentID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } } ?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<form name="" method="post" action="action_department.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Department Name</label>
<input type="text" id="name" name="name" placeholder="Department Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Department Code</label>
<input type="text" id="code" name="code" placeholder="Code" class="form-control" />
</div>

<div class="form-group">
<label for="email">Department Email</label>
<input type="email" id="email" name="email" placeholder="Email" class="form-control" />
</div>
 
<div class="form-group">
<label for="email">Head of Department</label>
<input type="text" id="email" name="head_of_department" placeholder="HoD" class="form-control" />
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