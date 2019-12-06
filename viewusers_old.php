<?php
$db=new DBHelper();
    ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#userdata").DataTable({
            "dom": 'Blfrtip',
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

    <!--<script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#userdata").DataTable({
                "processing": true,
                "paging": true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "order": [[1, 'asc']]
            });
        });
    </script>-->

    <!-- end row -->

    <div class="row">
        <div class="col-md-9">
            <h2 class="text-info">List of All Users</h2>
        </div>
        <div class="col-md-3">
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
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been inserted successfully</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Username is already Exist!!!</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "block") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You blocked User</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "unblock") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You Unblock User</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "reset") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully You Reset Password!!!</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "edited") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=users' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been edited successfully</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="userdata"  class="table table-bordered table-responsive-xl table-hover display">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Department/School</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $users = $db->getRows('users', array('order by' => 'userID DESC'));
                if (!empty($users)) {
                $count = 0;
                foreach ($users

                as $user) {
                $count++;
                $userID = $user['userID'];
                $fname = $user['firstName'];
                $lname = $user['lastName'];
                $username = $user['username'];
                $phoneNumber = $user['phoneNumber'];
                $email = $user['email'];
                $status = $user['status'];
                $departmentID=$user['departmentID'];
                $name = "$fname $lname";

                $userRole = $db->getRows("userroles", array('where' => array('userID' => $userID)));
                $roleNames = array();
                if (!empty($userRole)) {
                    foreach ($userRole as $role) {
                        $roleID = $role['roleID'];
                        $roleName = $db->getData("roles", "roleName", "roleID", $roleID);
                        $roleNames[] = $roleName;
                    }
                } else {
                    $roleNames[] = "None";
                }



                if ($status == 1) {
                    $blockButton = '<a href="action_user.php?action_type=deactivate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-unlock"  title="Deactivate User" onclick="return confirm("Are you sure,you want to Deactivate this User?");"></a>';
                    $statusOutput = "<span style='color: green'>Active</span>";
                } else {
                    $blockButton = '<a href="action_user.php?action_type=activate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-lock" title="Activate User" onclick="return confirm("Are you sure, you want to Activate this User?");"></a>';
                    $statusOutput = "<span style='color: red'>Blocked</span>";
                }

                echo "<tr><td>$name</td><td>$phoneNumber</td><td>$email</td><td>
                ".$db->getData("departments","departmentCode","departmentID",$departmentID)."</td><td>$username</td><td>" . implode(",", $roleNames) . "</td><td>$statusOutput</td>";
                ?>
                <td>
                    <?php echo $blockButton; ?>
                    <button data-toggle="modal" data-target="#edit<?php echo $user['userID']; ?>"
                            class="btn btn-success fa fa-pencil" title="Edit User Information"></button>
                    <?php
                    if($roleID==2){
                        ?>
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    <?php
                    }
                    else{
                    ?>
                    <a href="index3.php?sp=assign_roles&id=<?php echo $db->my_simple_crypt($user['userID'], 'e'); ?>&roleID=<?php echo $db->my_simple_crypt($roleID);?>"
                       class="btn btn-success fa fa-plus">
                        <?php
                        }
                        ?>
                </td>
                <?php
                echo "</tr>";
                ?>

                <!--Edit-->
                <div class="modal fade" id="edit<?php echo $user['userID']; ?>" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Edit Records for <?php echo $name; ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                </button>

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
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="courseCode">First Name:</label>
                                                        <input type="text" id="fname" name="fname"
                                                               value="<?php echo $fname; ?>"
                                                               class="form-control" required="required"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="email">Last Name</label>
                                                        <input type="text" id="lname" name="lname"
                                                               value="<?php echo $lname; ?>"
                                                               class="form-control" required="required"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="text" id="email" name="email"
                                                               value="<?php echo $email; ?>"
                                                               class="form-control" required="required email"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="email">Confirm Email</label>
                                                        <input type="text" id="confemail" name="email"
                                                               value="<?php echo $email; ?>" class="form-control"
                                                               required="required email" onblur="confirmEmail()"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="email">Phone Number</label>
                                                        <input type="text" id="phoneNumber" name="phoneNumber"
                                                               value="<?php echo $phoneNumber; ?>" class="form-control"
                                                               required="required"/>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="email">Department Name</label>
                                                        <select name="departmentID" class="form-control" required>
                                                            <?php
                                                            if(!empty($departmentID))
                                                            {
                                                                ?>
                                                                <option value="<?php echo $departmentID;?>"><?php
                                                                    echo $db->getData("departments","departmentName","departmentID",$departmentID);
                                                                    ?></option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                <option value="">Select Here</option>
                                                                <?php
                                                            }
                                                            $departments = $db->getRows('departments',array('order_by'=>'departmentID ASC'));
                                                            if(!empty($departments)){
                                                                ?>

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
                                            <?php
                                            $statusRole = $db->getRows("userroles", array('where' => array('userID' => $userID,'status'=>1)));
                                            if (!empty($statusRole)) {
                                                foreach ($statusRole as $srole) {
                                                    $sroleID = $srole['roleID'];
                                                }
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="email">Main Role Name</label>
                                                        <select name="roleID" class="form-control" required>
                                                            <?php
                                                            if($sroleID==2){
                                                                ?>
                                                                <option value="<?php echo $mainRole; ?>" selected><?php
                                                                    echo $db->getData("roles", "roleName", "roleID", $sroleID);
                                                                    ?></option>
                                                                <?php
                                                            }
                                                            else {
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
                                                            }?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>



                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                                        </button>
                                        <input type="hidden" name="action_type" value="edituser"/>
                                        <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                                        <input type="submit" name="doSubmit" value="Save Records"
                                               class="btn btn-primary">

                                    </div>
                            </form>
                        </div>
                    </div>
                    <!--End edit-->
                    <?php
                    }
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
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
                                                <input type="text" id="email" name="email" placeholder="Email"
                                                       class="form-control" required="required email"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Confirm Email</label>
                                                <input type="text" id="confemail" name="email"
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
                                                <label for="email">Department Name</label>
                                                <select name="departmentID" class="form-control" required>
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
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">User Role</label>
                                                <select name="roleID" class="form-control" required>
                                                    <?php
                                                    $roles = $db->getRows('roles',array('order_by'=>'roleID ASC'));
                                                    if(!empty($roles)){
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                        $count = 0; foreach($roles as $role){ $count++;
                                                            $roleName=$role['roleName'];
                                                            $roleID=$role['roleID'];
                                                            if($roleID!=2){
                                                            ?>
                                                            <option value="<?php echo $roleID;?>"><?php echo $roleName;?></option>
                                                        <?php }}}?>
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