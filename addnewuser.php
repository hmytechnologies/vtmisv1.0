<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script> 
<link href="css/validation.css" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function(){
    $("#roleID").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".4").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".4").hide();
            }
        });
    }).change();
});
</script>
<script>
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#image')
        .attr('src', e.target.result)
        .width(150)
        .height(150);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
<?php 
$db=new DBHelper();
?>
<div class="container">
<h2>Add New User</h2>
<hr>
<form name="" method="post" enctype="multipart/form-data" action="action_user.php">
<div class="row">
<div class="col-lg-8">

<div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="courseCode">First Name:</label>
<input type="text" id="fname" name="fname" placeholder="First Name" class="form-control" required="required" />
</div>
</div>
<div class="col-lg-4">
<div class="form-group">
<label for="email">Middle Name</label>
<input type="text" id="mname" name="mname" placeholder="Middle Name" class="form-control" required="required" />
</div>
</div>
    <div class="col-lg-4">
<div class="form-group">
<label for="email">Last Name</label>
<input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control" required="required" />
</div></div></div>
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Email" class="form-control" required="required email" />
</div>
</div>
    <div class="col-lg-4">
        
<div class="form-group">
<label for="email">Phone Number</label>
<input type="text" id="phone" name="phone" placeholder="Phone Number" class="form-control" required="required" />
</div>
    </div>
    
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
<label for="email">User Roles</label>
<select name="roleID" id="roleID" class="form-control" required="">
    <option value="">Select Here</option>
   <?php
           //$roles = $db->getRows('roles',array('where'=>array('roleName'!='Student'),'order_by roleID ASC'));
           $roles=$db->getRoleName();
           if(!empty($roles)){ $count = 0; foreach($roles as $role){ $count++;
            $roleName=$role['roleName'];
            $roleID=$role['roleID'];
           ?>
           <option value="<?php echo $roleID;?>"><?php echo $roleName;?></option>
           <?php }}?>
</select>

</div>
    </div>
    
<div class="col-lg-4">
<div class="4">
<div class="row" id="roleID">
<div class="form-group">
<label for="email">Department Name</label>
<select name="schoolID" class="form-control">
            <?php
           $departments = $db->getRows('departments',array('order_by'=>'departmentID ASC'));
           if(!empty($departments)){
               ?>
               <option value="">Select Here</option>
               <?php 
               $count = 0; foreach($departments as $level){ $count++;
            $departmentName=$level['departmentName'];
            $departmentID=$level['departmentID'];
           ?>
           <option value="<?php echo $departmentID;?>"><?php echo $departmentName;?></option>
           <?php }}?>
</select>
</div>
</div>
</div>
</div>
</div>

</div>

</div></div>

<div class="col-lg-2">
                    <!-- Picture -->
			 <div class="row">
			  <div class="col-lg-12">
			   <label for="Picture">Instructor Picture</label>
				<img id="image" src="#" height="150px" width="150px;" />
				<input type='file' name="photo" accept=".jpg" onchange="readURL(this);" />
			  </div></div>
                    <!-- Picture -->
</div> 
</div>
                
<div class="row">
<div class="col-lg-3"></div>
<div class="col-lg-3">
        <input type="hidden" name="action_type" value="add"/>
        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
</div>
<div class="col-lg-3">
    <input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">
</div>
</div>
</form>
</div>