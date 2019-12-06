<div class="container">
<h1>Programme Levels Management</h1>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Programme Levels</button>
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
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Levels data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Level data has been edited Successfully</strong>.
</div>";
  }
  else
  {
    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
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
            $users = $db->getRows('programme_level',array('order_by'=>'status DESC'));
?>
<table  id="example" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Programme Level Name</th>
    <th>Programme Level Code</th>
    <th>Minimum Units</th>
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
                <td><?php echo $user['programmeLevel']; ?></td>
                <td><?php echo $user['programmeLevelCode']; ?></td>
                <td><?php echo $user['units']; ?></td>
                <td><?php echo $status; ?></td>
              <td>
                    <a href="index3.php?sp=edit_levels&id=<?php echo $user['programmeLevelID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
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
<form name="" method="post" action="action_programme_level.php">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Programme Level Name</label>
<input type="text" id="name" name="name" placeholder="Programme Level Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Programme Level Code</label>
<input type="text" id="code" name="code" placeholder="Programme Level Code" class="form-control" />
</div>

<div class="form-group">
<label for="email">Minimum Number of Units</label>
<input type="number" id="number" name="number" placeholder="Minimum Number of Units" class="form-control" />
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