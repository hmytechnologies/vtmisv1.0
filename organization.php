<script type="text/javascript">
    $(document).ready(function () {
        $("#organization").DataTable();
    });
</script>
    <?php
    $db = new DBHelper();
    $users = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
    ?>
    <?php
    if(empty($users)) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Define Organization Structure
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
<div class="row">
        <div class="col-md-12">
            <br>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="deleted")
  {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been deleted Successfully</strong>.
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
<table  id="organization" class="table table-bordered table-responsive-xl table-hover display">
  <thead>
  <tr>
    <th>Name</th>
    <th>Code</th>
    <th>Address</th>
    <th>Email</th>
    <th>Phone Number</th>
    <th>Website</th>
    <th>Picture</th>
    <th>Edit</th>
  </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
 ?>
            <tr>
                <td><?php echo $user['organizationName']; ?></td>
                <td><?php echo $user['organizationCode']; ?></td>
                <td><?php echo $user['organizationAddress']; ?></td>
                <td><?php echo $user['organizationEmail'];?></td>
                <td><?php echo $user['organizationPhone'];?></td>
                <td><?php echo $user['organizationWebsite'];?></td>
                <td><img src="img/<?php echo $user['organizationPicture'];?>" width="150" height="100"></td>
              <td>
                    <a href="action_organization.php?action_type=delete_org&id=<?php echo $db->my_simple_crypt($user['organizationID'],'e'); ?>
                " class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Organization?');"></a>
                   
              </td>
            </tr>
            <?php } }else{ ?>
            
            <?php } ?>
</tbody>
 </table>
 </div></div>  



<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">

<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <form name="" method="post" action="action_organization.php" enctype="multipart/form-data">
<div class="row">
<div class="col-md-12">
<div class="modal-body">
<div class="form-group">
<label for="email">Organization Name</label>
<input type="text" id="name" name="name" placeholder="Organization Name" class="form-control" />
</div>
<div class="form-group">
<label for="email">Organization Code</label>
<input type="text" id="code" name="code" placeholder="Code" class="form-control" />
</div>

    <div class="form-group">
        <label for="email">Organization Reference Number</label>
        <input type="text" id="refnumber" name="refnumber" placeholder="Reference Number" class="form-control" />
    </div>

    <div class="form-group">
<label for="email">Organization Physical Address</label>
<input type="text" id="code" name="physicaladdress" placeholder="Physical Address" class="form-control" />
</div>
    <div class="form-group">
<label for="email">Organization Postal Address</label>
<input type="text" id="code" name="address" placeholder="Postal Address" class="form-control" />
</div>
    <div class="form-group">
<label for="email">Organization Email</label>
<input type="text" id="code" name="email" placeholder="Email" class="form-control" />
</div>
    <div class="form-group">
<label for="email">Organization Phone</label>
<input type="text" id="code" name="phone" placeholder="Phone" class="form-control" />
</div>
    <div class="form-group">
<label for="email">Organization Website</label>
<input type="text" id="code" name="website" placeholder="Website" class="form-control" />
</div>
    <div class="form-group">
<label for="email">Organization Picture</label>
<input type="file" id="image" name="image" />
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
</form>
</div>
</div>
</div>