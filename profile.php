<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
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

<script>
    function goBack() {
        window.history.back();
    }
</script>

<h1>User Information</h1>
<hr>
<?php
$db = new DBHelper();
if(!empty($_REQUEST['msg']))
{
    if($_REQUEST['msg']=="edited")
    {
        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
        <strong>User data has been edited successfully</strong>.
        </div>";
    }
}
?>
<?php

$userData = $db->getRows('users',array('where'=>array('userID'=>$db->my_simple_crypt($_GET['id'],'d')),'return_type'=>'single'));
if(!empty($userData)){
    ?>

    <form name="" method="post" enctype="multipart/form-data" action="action_edit_profile.php">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="courseCode">First Name:</label>
                                    <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo $userData['firstName'];?>" disabled class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">Middle Name</label>
                                    <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo $userData['middleName'];?>" disabled class="form-control" required="required" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">Last Name</label>
                                    <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo $userData['lastName'];?>" disabled class="form-control" required="required" />
                                </div></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">User Name</label>
                                    <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo $userData['username'];?>" disabled class="form-control" required="required" />
                                </div></div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" name="email" placeholder="Email" value="<?php echo $userData['email'];?>" class="form-control" required/>
                                </div>
                            </div>
                            <div class="col-lg-4">

                                <div class="form-group">
                                    <label for="email">Phone Number</label>
                                    <input type="text" id="phone" name="phone" value="<?php echo $userData['phoneNumber'];?>" placeholder="Phone Number" class="form-control" required="required" />
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">User Roles</label>
                                    <select name="roleID" id="role" class="form-control" required disabled>
                                        <?php
                                        $userRole=$db->getRows("userroles",array('where'=>array('userID'=>$userData['userID']),'order_by roleID ASC'));
                                        if(!empty($userRole))
                                        {
                                            $count=0;
                                            foreach($userRole as $role)
                                            {
                                                $roleID=$role['roleID'];
                                                $roleName=$db->getData("roles","roleName", "roleID",$roleID);
                                            }
                                        }
                                        ?>
                                        <option value="<?php echo $roleID;?>" selected>
                                            <?php echo $db->getData("roles","roleName","roleID",$roleID);?>
                                        </option>
                                        <option value="">Select Here</option>
                                        <?php
                                        $roles = $db->getRows('roles',array('order_by roleID ASC'));
                                        if(!empty($roles)){ $count = 0; foreach($roles as $role){ $count++;
                                            $roleName=$role['roleName'];
                                            $roleID=$role['roleID'];
                                            ?>
                                            <option value="<?php echo $roleID;?>"><?php echo $roleName;?></option>
                                        <?php }}?>
                                    </select>

                                </div>
                            </div>
                           <!-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">Department Name</label>
                                    <select name="schoolID" class="form-control" disabled>
                                        <option value="<?php /*echo $db->getData("departments","departmentID","departmentID",$userData['departmentID']);*/?>" selected>
                                            <?php /*echo $db->getData("departments","departmentName","departmentID",$userData['departmentID']);*/?>
                                        </option>
                                        <?php
/*                                        $departments = $db->getRows('departments',array('order_by'=>'departmentID ASC'));
                                        if(!empty($departments)){ $count = 0; foreach($departments as $level){ $count++;
                                            $departmentName=$level['departmentName'];
                                            $departmentID=$level['departmentID'];
                                            */?>
                                            <option value="<?php /*echo $departmentID;*/?>"><?php /*echo $departmentName;*/?></option>
                                        <?php /*}}
                                        */?>
                                    </select>

                                </div>
                            </div>-->

                        </div>
                    </div>
                </div></div>
            <div class="col-lg-2">
                <!-- Picture -->
                <div class="row">
                    <div class="col-lg-10">
                        <label for="Picture">Profile Picture</label>
                        <img id="image" src="student_images/<?php echo $userData['userImage'];?>" height="150px" width="150px;" />
                        <input type='file' name="photo" accept=".jpg" onchange="readURL(this);" />
                    </div></div>
                <!-- Picture -->
            </div></div>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="edit"/>
                <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
            </div>
            <div class="col-lg-3">
                <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
            </div>
        </div>
    </form>
<?php } ?>