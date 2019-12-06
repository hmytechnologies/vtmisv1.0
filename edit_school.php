<h1>Edit School Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('schools',array('where'=>array('schoolID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<div class="row">
<div class="col-lg-6">
<form name="" method="post" action="action_school.php">
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">School Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['schoolName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">School Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['schoolCode'];?>"  class="form-control" />
</div>

<div class="form-group">
<label for="email">Campus Name</label>
<select name="campusID" class="form-control">
           <option value="<?php echo $db->getData('campus','campusID','campusID',$userData['campusID']);?>"><?php echo $db->getData('campus','campusName','campusID',$userData['campusID']);?></option>   
            <?php
           $campus = $db->getRows('campus',array('order_by'=>'campusID DESC'));
           if(!empty($campus)){ $count = 0; foreach($campus as $level){ $count++;
            $campusName=$level['campusName'];
            $campusID=$level['campusID'];
           ?>
           <option value="<?php echo $campusID;?>"><?php echo $campusName;?></option>
           <?php }}?>
</select>
</div>    

<div class="form-group">
<label for="email">Department Status</label>
<?php if($userData['status']==1)
{?>
<input type="radio" name="status" value="1" checked>Active <input type="radio" name="status" value="0">Not Active
<?php }else {?>
<input type="radio" name="status" value="1">Active <input type="radio" name="status" value="0" checked>Not Active
<?php }?>
</div>

</div>
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-3">
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
<input type="submit" name="doSubmit" value="Update Records" class="btn btn-success" tabindex="8">
</div></div>

</form>
</div>
</div>
</div>
</div>
<?php } ?>