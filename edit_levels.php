<h1>Edit Programme Levels Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<form name="" method="post" action="action_programme_level.php">
<div class="row">
<div class="col-md-6">

<div class="form-group">
<label for="email">Programme Level Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['programmeLevel'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Programme Level Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['programmeLevelCode'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Minimum Number of Units</label>
<input type="number" id="number" name="number" value="<?php echo $userData['units'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Programme Level Status</label>
<?php if($userData['status']==1)
{?>
<input type="radio" name="status" value="1" checked>Active <input type="radio" name="status" value="0">Not Active
<?php }else {?>
<input type="radio" name="status" value="1">Active <input type="radio" name="status" value="0" checked>Not Active
<?php }?>
</div>
</div>
</div>

<div class="row">

<div class="col-lg-3">
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
<input type="submit" name="doSubmit" value="Update Records" class="btn btn-success form-control" tabindex="8">
</div>
    <div class="col-lg-3">
        <a href="index3.php?sp=programmes" class="btn btn-danger form-control">Cancel</a>
    </div>
</div>
</form>
</div>
</div>

<?php }?>