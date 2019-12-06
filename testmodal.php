<div class="row"> 
<div class="col-md-12">
<div class="pull-left">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New User</button>
            </div>   
 </div>
</div>
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<form name="" method="post" action="">
<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="modal-body">
 
<div class="form-group">
<label for="courseName">Salutation:</label>
 <select name="salutation" class="form-control" required="required">
               <option value=''>Select Salutation</option>
                       <option value=Mr>Mr</option>
                       <option value=Mrs>Ms</option>
                       <option value=Miss>Miss</option>
                       <option value=Dr>Dr</option>
                       <option value=Prof>Prof</option>
                 </select>
</div>
 
<div class="form-group">
<label for="courseCode">First Name:</label>
<input type="text" id="fname" name="fname" placeholder="First Name" class="form-control" />
</div>
 
<div class="form-group">
<label for="email">Middle Name</label>
<input type="text" id="mname" name="mname" placeholder="Middle Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Last Name</label>
<input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control" />
<!--<select name="lname"  class="form-control">
           <?php //echo dropdownvalue("course_category","categoryName","courseCategoryID");?>
           </select>-->
</div>

<div class="form-group">
<label for="email">Address</label>
<input type="text" id="address" name="address" placeholder="Address" class="form-control" />
</div>

<div class="form-group">
<label for="email">Phone Number</label>
<input type="text" id="phone" name="phone" placeholder="Phone Number" class="form-control" />
</div>

<div class="form-group">
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Email" class="form-control" />
</div>
 
<div class="form-group">
<label for="email">User Previledge</label>
<select name="user_privilege"  class="form-control">
           <?php echo dropdownvalue("user_privilege","privilege_name","user_privilege_id");?>
           </select>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
</form>
</div>
</div>
</div>
</div>