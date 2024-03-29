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
<script type="text/javascript">
    function confirmEmail() {
        var email = document.getElementById("email").value;
        var confemail = document.getElementById("confemail").value;
        if (email !== confemail) {
            alert('Email Not Matching!');
        }
    }
</script>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<h1>Edit User Data</h1>
<?php
$db = new DBHelper();
$userID=$db->my_simple_crypt($_REQUEST['id'],'d');
$userData = $db->getRows('users',array('where'=>array('userID'=>$userID)));
if(!empty($userData)) {
    foreach($userData as $user) {

        $statusRole = $db->getRows("userroles", array('where' => array('userID' => $userID, 'status' => 1)));
        if (!empty($statusRole)) {
            foreach ($statusRole as $srole) {
                $main_role = $srole['roleID'];
            }
        }
        $officeID=$db->getData('roles','officeID','roleID',$main_role);
        ?>

        <form name="" method="post" enctype="multipart/form-data" action="action_user.php">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="courseCode">First Name:</label>
                                        <input type="text" id="fname" name="fname" placeholder="First Name"
                                               value="<?php echo $user['firstName']; ?>" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Middle Name</label>
                                        <input type="text" id="mname" name="mname" placeholder="Middle Name"
                                               value="<?php echo $user['middleName']; ?>" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Last Name</label>
                                        <input type="text" id="lname" name="lname" placeholder="Last Name"
                                               value="<?php echo $user['lastName']; ?>" class="form-control"
                                               required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" name="email" placeholder="Email"
                                               value="<?php echo $user['email']; ?>" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Confirm Email</label>
                                        <input type="text" id="confemail" name="email"
                                               value="<?php echo $user['email']; ?>" class="form-control"
                                               required="required email" onblur="confirmEmail()"/>
                                    </div>
                                </div>

                                <div class="col-lg-4">

                                    <div class="form-group">
                                        <label for="email">Phone Number</label>
                                        <input type="text" id="phone" name="phoneNumber"
                                               value="<?php echo $user['phoneNumber']; ?>" placeholder="Phone Number"
                                               class="form-control" required="required"/>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">User Roles</label>
                                        <?php
                                        $statusRole = $db->getRows("userroles", array('where' => array('userID' => $userID, 'status' => 1)));
                                        if (!empty($statusRole)) {
                                            foreach ($statusRole as $srole) {
                                                $sroleID = $srole['roleID'];
                                            }
                                        }
                                        ?>
                                        <select name="roleID" class="form-control" required>
                                            <?php
                                            if ($sroleID == 2) {
                                                ?>
                                                <option value="<?php echo $sroleID; ?>" selected><?php
                                                    echo $db->getData("roles", "roleName", "roleID", $sroleID);
                                                    ?></option>
                                                <?php
                                            } else {
                                                if (!empty($sroleID)) {
                                                    ?>
                                                    <option value="<?php echo $sroleID; ?>"><?php
                                                        echo $db->getData("roles", "roleName", "roleID", $sroleID);
                                                        ?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="">Select Here</option>
                                                    <?php
                                                }
                                                $roles_s = $db->getRows('roles', array('order_by' => 'roleID ASC'));
                                                if (!empty($roles_s)) {
                                                    foreach ($roles_s as $rl) {
                                                        $roleNames = $rl['roleName'];
                                                        $roleIDs = $rl['roleID'];
                                                        if ($roleIDs != 2) {
                                                            ?>
                                                            <option value="<?php echo $roleIDs; ?>"><?php echo $roleNames; ?></option>
                                                        <?php }
                                                    }
                                                }
                                            } ?>
                                        </select>

                                    </div>
                                </div>

                                <?php
                                if ($officeID == 2) {

                                    $centerID = $db->getData('instructor','centerID','userID',$userID);
                                    $departmentID = $db->getData('instructor','departmentID','userID',$userID);




                                    ?>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="email">Center Name</label>
                                            <select name="centerID" class="form-control">
                                                <?php
                                                if(!empty($centerID)) {
                                                    ?>
                                                    <option value="<?php echo $centerID; ?>"><?php
                                                        echo $db->getData("center_registration", "centerName", "centerRegistrationID", $centerID);
                                                        ?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="">Select Here</option>
                                                    <?php
                                                }
                                                $center = $db->getRows('center_registration', array('order_by' => 'centerRegistrationID ASC'));
                                                if (!empty($center)) {
                                                    foreach ($center as $cent) {
                                                        $count++;
                                                        $centerName = $cent['centerName'];
                                                        $centerID = $cent['centerRegistrationID'];
                                                        ?>
                                                        <option value="<?php echo $centerID; ?>"><?php echo $centerName; ?></option>
                                                    <?php }
                                                } ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="email">Department Name</label>
                                            <select name="departmentID" class="form-control">
                                                <?php

                                                if(!empty($departmentID))
                                                {
                                                    ?>
                                                    <option value="<?php echo $departmentID; ?>"><?php
                                                        echo $db->getData("departments", "departmentName", "departmentID", $departmentID);
                                                        ?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="">Select Here</option>
                                                    <?php
                                                }
                                                $departments = $db->getRows('departments', array('order_by' => 'departmentID ASC'));
                                                if (!empty($departments)) {
                                                    ?>

                                                    <?php
                                                    $count = 0;
                                                    foreach ($departments as $level) {
                                                        $count++;
                                                        $department_Name = $level['departmentName'];
                                                        $department_ID = $level['departmentID'];
                                                        ?>
                                                        <option value="<?php echo $department_ID; ?>"><?php echo $department_Name; ?></option>
                                                    <?php }
                                                } ?>
                                            </select>

                                        </div>
                                    </div>
                                    <?php
                                }

                                    ?>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <!-- Picture -->
                    <div class="row">
    <div class="col-lg-12">
        <label for="photo">Profile Picture</label>
           <?php
     // Check if the userImage key exists in $userData array and if it has a valid value
        if (isset($userData['userImage']) && !empty($userData['userImage'])) {
            $imagePath = "student_images/" . $userData['userImage'];
        } else {
            // Set a default image path here if needed
            $imagePath = "default_profile_image.jpg";
        }
        ?>
        <img id="image" src="<?php echo $imagePath; ?>" height="150px" width="150px"/>
        <input type="file" name="photo" id="photo" accept=".jpg" onchange="readURL(this);"/>
    </div>

    
</div>


                    <!-- Picture -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="edituser"/>
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                    <input type="hidden" name="officeID" value="<?php echo $officeID;?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                </div>
                <div class="col-lg-3">
                    <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
                </div>
            </div>
        </form>
    <?php }
}?>