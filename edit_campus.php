<h1>Edit School Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('campus',array('where'=>array('campusID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<div class="row">
<div class="col-lg-6">
<form name="" method="post" action="action_campus.php">
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Campus Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['campusName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Campus Address</label>
<input type="text" id="code" name="code" value="<?php echo $userData['campusAddress'];?>"  class="form-control" />
</div>

<div class="form-group">
<label for="email">Account Number</label>
<input type="text" id="accnumber" name="accnumber" value="<?php echo $userData['accountNumber'];?>"  class="form-control" />
</div>

<div class="form-group">
<label for="email">Account Name</label>
<input type="text" id="accname" name="accname" value="<?php echo $userData['accountName'];?>"  class="form-control" />
</div>

<div class="form-group">
<label for="email">Bank Name</label>
<input type="text" id="bank" name="bank" value="<?php echo $userData['bankName'];?>"  class="form-control" />
</div>	

<div class="form-group">
<label for="email">Swift Code</label>
<input type="text" id="swiftcode" name="swiftcode" value="<?php echo $userData['swiftCode'];?>"  class="form-control" />
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