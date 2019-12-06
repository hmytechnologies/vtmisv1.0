 <script type="text/javascript">
 $(document).ready(function () {
 $("#userdata").DataTable({
		"ajax": "api/userlist.php",
             "dom": 'Blfrtip',
             "scrollX":true,
             "buttons":[
                     {
                         extend:'excel',
                         title: 'List of all Users',
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     ,
                     {
                         extend: 'print',
                         title: 'List of all Users',
                         footer: false,
                         exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: 'List of all Users',
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         },
                         
                     }

                     ],
		"order": []
	});
 });
</script>
<div class="container">
<div class="row"> 
<h2 class="text-info">List of All Users</h2>
<div class="col-md-12">
    <div class="pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New User
            </button>
        </div>
    </div>
 </div>

<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="secc")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsecc")
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Username is already Exist!!!</strong>.
</div>";
  }else if($_REQUEST['msg']=="block")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You blocked User</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unblock")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You Unblock User</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="reset")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully You Reset Password!!!</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
      echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been edited successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="error")
  {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry,System error, please contact system administrator</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<table  id="userdata" class="display nowrap">
  <thead>
  <tr>
    <th>No.</th>
    <th>Full Name</th>
    <th>Username</th>
    <th>Phone Number</th>
    <th>Email</th>
      <th>Office</th>
    <th>Role</th>
    <th>Status</th>
	<th>Action</th>
     </tr>
  </thead>
 </table>
 </div>
 </div>
 </div>

 <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog">
         <div class="modal-content">
             <script type="text/javascript" src="js/jquery.min.js"></script>
             <script src="js/jquery-1.4.2.min.js"></script>
             <script type="text/javascript">
                 $(document).ready(function(){
                     $("#role").change(function(){
                         $(this).find("option:selected").each(function(){
                             var optionValue = $(this).attr("value");
                             if(optionValue==9){
                                 $(".9").not("." + optionValue).hide();
                                 $("." + optionValue).show();
                             } else if(optionValue==4) {
                                 $(".4").not("." + optionValue).hide();
                                 $("." + optionValue).show();
                             }else
                             {
                                 $(".9").hide();
                                 /*$(".4").hide();*/
                             }
                         });
                     }).change();
                 });
             </script>

             <script type="text/javascript">
                 $(document).ready(function(){
                     $("#office").change(function(){
                         $(this).find("option:selected").each(function(){
                             var optionValue = $(this).attr("value");
                             if(optionValue=='center'){
                                 $(".center").not("." + optionValue).hide();
                                 $("." + optionValue).show();
                             } else
                             {
                                 $(".center").hide();
                             }
                         });
                     }).change();
                 });
             </script>


             <script type="text/javascript">
                 $(document).ready(function()
                 {
                     $("#office").change(function()
                     {
                         var office=$(this).val();
                         var dataString = 'office='+office;
                         $.ajax
                         ({
                             type: "POST",
                             url: "ajax_user_roles.php",
                             data: dataString,
                             cache: false,
                             success: function(html)
                             {
                                 $("#role").html(html);
                             }
                         });

                     });

                 });
             </script>


             <div class="modal-header">
                 <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                             aria-hidden="true">&times;</span></button>


                 <script type="text/javascript">
                     function confirmEmail() {
                         var email = document.getElementById("email").value
                         var confemail = document.getElementById("confemail").value
                         if (email != confemail) {
                             alert('Email Not Matching!');
                         }
                     }
                 </script>

             </div>

             <form name="" method="post" action="action_user.php">
                 <div class="modal-body">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="row">
                                 <div class="col-lg-6">
                                     <div class="form-group">
                                         <label for="courseCode">First Name:</label>
                                         <input type="text" id="fname" name="fname" placeholder="First Name"
                                                class="form-control" required="required"/>
                                     </div>
                                 </div>
                                 <div class="col-lg-6">
                                     <div class="form-group">
                                         <label for="email">Last Name</label>
                                         <input type="text" id="lname" name="lname" placeholder="Last Name"
                                                class="form-control" required="required"/>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="form-group">
                                         <label for="email">Email</label>
                                         <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" id="email" name="email" placeholder="Email"
                                                class="form-control" required="required email"/>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="form-group">
                                         <label for="email">Confirm Email</label>
                                         <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$"  id="confemail" name="email"
                                                placeholder="Confirm Email" class="form-control"
                                                required="required email" onblur="confirmEmail()"/>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="form-group">
                                         <label for="email">Phone Number</label>
                                         <input type="text" id="phoneNumber" name="phoneNumber"
                                                placeholder="Phone Number" class="form-control"
                                                required="required"/>
                                     </div>
                                 </div>

                             </div>


                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="form-group">
                                         <label for="email">Office</label>
                                         <select name="officeID" id="office" class="form-control" required>
                                             <option value="">Select Here</option>
                                             <option value="headoffice">Head Office</option>
                                             <option value="center">Center</option>
                                         </select>
                                     </div>
                                 </div>
                             </div>

                             <div class="center">
                                 <div class="row">
                                     <div class="col-lg-12">
                                         <div class="form-group">
                                             <label for="LastName">Center Name</label>
                                             <select name="centerID" class="form-control" required>
                                                 <?php
                                                 $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                                                 if(!empty($center)){
                                                     echo"<option value=''>Please Select Here</option>";
                                                     $count = 0; foreach($center as $cept){ $count++;
                                                         $centerName=$cept['centerName'];
                                                         $centerID=$cept['centerRegistrationID'];
                                                         ?>
                                                         <option value="<?php echo $centerID;?>"><?php echo $centerName;?></option>
                                                     <?php }
                                                 }
                                                 ?>
                                             </select>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="row">
                                     <div class="col-lg-12">
                                         <div class="form-group">
                                             <label for="LastName">Department Name</label>
                                             <select name="departmentID" class="form-control" required>
                                                 <?php
                                                 $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
                                                 if(!empty($department)){
                                                     echo"<option value=''>Please Select Here</option>";
                                                     $count = 0; foreach($department as $dept){ $count++;
                                                         $departmentName=$dept['departmentName'];
                                                         $departmentID=$dept['departmentID'];
                                                         ?>
                                                         <option value="<?php echo $departmentID;?>"><?php echo $departmentName;?></option>
                                                     <?php }
                                                 }
                                                 ?>
                                             </select>
                                         </div>
                                     </div>
                                 </div>

                             </div>


                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="form-group">
                                         <label for="email">User Role</label>
                                         <select name="roleID" id="role" class="form-control" required>
                                         </select>
                                     </div>
                                 </div>
                             </div>


                         </div>
                     </div>
                 </div>

                 <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                     <input type="hidden" name="action_type" value="adduser"/>
                     <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary">

                 </div>
             </form>
         </div>
     </div>
 </div>




