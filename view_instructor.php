<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#schemeID").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".others").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".others").hide();
            }
        });
    }).change();
});
</script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>  
<script type="text/javascript">
             $(document).ready(function()
              {
              $("#districtID").change(function()
              {
              var districtID=$(this).val();
              var dataString = 'districtID='+ districtID;
              $.ajax
              ({
              type: "POST",
              url: "ajax_shehia.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#shehiaID").html(html);
              } 
              });

              });

              });
        </script>
<script>
function goBack() {
    window.history.back();
}
</script>
<?php
$db = new DBHelper();
$instructorID=$db->my_simple_crypt($_REQUEST['id'],'d');
$instructor = $db->getRows('instructor',array('where'=>array('instructorID'=>$instructorID),'order_by'=>'instructorName ASC'));
if(!empty($instructor))
{
    $x=0;
    foreach ($instructor as $inst)
    {
        $x++;
        $gender=$inst['gender'];
     
    }
?>
<style>
.no-padding-right
{
    padding-right: 0;
}
.no-padding-left
{
    padding-left: 0;
}
</style>
<div class="row">
<div class="col-md-8">
<div class="modal-body">
                <div class="well">
                <fieldset>
                  <legend>Personal Information</legend>
<div class="modal-body">
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
<div class="row">
<div class="col-lg-8">
<div class="row">
<div class="col-lg-12">
<div class="form-group">
<label for="courseCode">Instructor Name:</label>
<input type="text" id="name" name="name" value="<?php echo $inst['instructorName']?>" readonly class="form-control"/>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-6">
<div class="form-group">
<label for="gender">Gender</label>
                           
                            <select name="gender" class="form-control" disabled>

                            	<option value="<?php echo $gender;?>" selected><?php echo $gender;?></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
</div>
</div>
<div class="col-lg-6">
<div class="form-group">
<label for="email">Email</label>
<input type="email" id="email" name="email" value="<?php echo $inst['email'];?>" readonly class="form-control" />
</div>
</div>
</div>

<div class="row">
 <div class="col-lg-6">
<div class="form-group">
<label for="email">Phone Number</label>
<input type="text" id="phone" name="phone" value="<?php echo $inst['phoneNumber'];?>" readonly class="form-control"/>
</div>
 </div>
 <div class="col-lg-6">
  <div class="form-group">
<label for="email">Department Name</label>
<select name="departmentID"  class="form-control" disabled>
<option value="<?php echo $inst['departmentID'];?>"><?php echo $db->getData("departments","departmentName","departmentID",$inst['departmentID']);?></option>
           <?php
           $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
           if(!empty($department)){
            $count = 0; foreach($department as $dept){ $count++;
            $department_name=$dept['departmentName'];
            $department_id=$dept['departmentID'];
           ?>
           <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
           <?php }}?>
</select>
</div> 

 </div>
 
  </div>
<div class="row">
<div class="col-lg-6">
                            <label for="Geder">Employment Status</label>
                           <select name="employmentStatus" class="form-control" required disabled>
                           <option value="<?php echo $inst['employmentStatus'];?>"><?php echo $inst['employmentStatus'];?></option>
                             <option value="">Select Here</option>
                             <option value="Full Time">Full Time</option>
                             <option value="Part Time">Part Time</option>
                           </select>
                        
 </div>
 <div class="col-lg-6">
 <label for="Geder">Office Number</label>
 <input type="text" id="officeNumber" readonly name="officeNumber" value="<?php echo $inst['officeNumber'];?>" class="form-control"/>
 </div>
</div>
<br>
<div class="row">
 <div class="col-lg-12">
 <div class="form-group">
<label for="email">Work Status</label>
<?php if($inst['instructorStatus']==1)
{?>
<input type="radio" name="status" value="1" checked disabled>Active <input type="radio" name="status" value="0" disabled>Not Active
<?php }else {?>
<input type="radio" name="status" value="1" disabled>Active <input type="radio" name="status" value="0" checked disabled>Not Active
<?php }?>
</div>

 </div>
</div>
 </div>
  <div class="col-lg-3">
                    <!-- Picture -->
			 <div class="row">
			  <div class="col-lg-12">
			   <label for="Picture">Instructor Picture</label>
				<img id="image" src="student_images/<?php echo $inst['instructorImage'];?>" height="150px" width="150px;" />
				
			  </div></div>
                    <!-- Picture -->
                    </div>
 </div>
                     </fieldset>
                </div>
</div>
<div class="row">
<div class="col-lg-3"></div>
<div class="col-lg-6">
<button onclick="goBack()" class="btn btn-primary">Go Back</button>
</div>

</div>

</div>
<?php }?>
