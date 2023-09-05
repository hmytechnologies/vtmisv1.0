<script type="text/javascript">
    $(document).ready(function () {
        $('#instructor').dataTable(
            {
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend:'excel',
                        footer:false,
                        exportOptions:{
                            columns:[0,1,2,3]
                        }
                    },
                    ,
                    {
                        extend: 'print',

                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',

                        footer: true,
                        /* exportOptions: {
                             columns: [0, 1, 2, 3,5,6]
                         }*/
                        orientation: 'landscape',
                    }

                ]
            });
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

<?php $db=new DBHelper();?>
<div class="container">
    <div class="content">
        <!--<div class="row">
          <h3>Instructor Registration</h3>
        <hr>
        </div>-->
        <div class="row">
            <div class="row">
                <div class="col-md-8">
                    <div class="pull-left">
                        <h3>List of Instructors in ,
                            <?php

                            // echo  $_SESSION['user_session'] ;
                            if($_SESSION['main_role_session']==4)
                            {

                                $departmentID = $db->getData("instructor","departmentID","userID",$_SESSION['user_session']);
                                echo $db->getData("departments","departmentName","departmentID",$departmentID);
                            }
                            else if($_SESSION['main_role_session']==9)

                            {

                                $centerID = $db->getData("instructor","centerID","userID",$_SESSION['user_session']);
                                echo $db->getData("center_registration","centerName","centerRegistrationID",$centerID);
                            }
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <!--<div class="pull-right">
                                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Register an Instructor</button>
                                </div>
                     </div>-->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>

                        <?php
                        if(!empty($err))  {
                            echo "<div class=\"msg\">";
                            foreach ($err as $e) {
                                echo "* $e <br>";
                            }
                            echo "</div>";
                        }

                        ?>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $db = new DBHelper();
                        if($_SESSION['role_session']==4)
                        {
                            $departmentID = $db->getData("instructor","departmentID","userID",$_SESSION['user_session']);
                          $roleID = $db->getData("userroles","roleID","userID",$_SESSION['user_session']);
                            $instructor=$db->getInstructorList($roleID, $departmentID);
                        }
                        else if($_SESSION['role_session']==9)
                        {
                            $instructor=$db->getInstructorList($_SESSION['role_session'], $departmentID);
                        }
                        //$instructor = $db->getRows('instructor',array('order_by'=>'employmentStatus DESC'));
                        ?>
                        <table  id="instructor" class="display">
                            <thead>
                            <tr>

                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Title</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Office No.</th>
                                <th>Employment Status</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($instructor)){ $count = 0; foreach($instructor as $inst){ $count++;
                                $name=$inst['instructorName'];
                                if($inst['instructorStatus']==1)
                                    $status="Active";
                                else
                                    $status="Not Active";

                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $name;?></td>
                                    <td><?php echo $inst['gender'];?></td>
                                    <td><?php echo $db->getData("instructor_title", "title","titleID",$inst['titleID']); ?></td>
                                    <td><?php echo $inst['phoneNumber']; ?></td>
                                    <td><?php echo $inst['email']; ?></td>
                                    <td><?php echo $db->getData("departments", "departmentName","departmentID",$inst['departmentID']); ?></td>
                                    <td><?php echo $inst['officeNumber'];?></td>
                                    <td><?php echo $db->getData("instructor_emp", "empType","empID",$inst['employmentStatusID']); ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                 <a href="index3.php?sp=view_instructor&id=<?php echo $db->my_simple_crypt($inst['instructorID'],'e');?>" class="glyphicon glyphicon-eye-open"></a></td><td>
                                        <a href="index3.php?sp=edit_instructor&id=<?php echo $db->my_simple_crypt($inst['instructorID'],'e');?>" class="glyphicon glyphicon-edit"></a>

                                    </td>

                                </tr>
                            <?php } }?>
                            </tbody>
                        </table>
                    </div></div>
            </div>



            <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                        </div>
                        <form name="" method="post" enctype="multipart/form-data" action="action_instructor.php">
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
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="courseName">Salutation:</label>
                                                    <select name="salutation" class="form-control" required="required" >
                                                        <option value=''>Select Salutation</option>
                                                        <option value=Mr>Mr</option>
                                                        <option value=Ms>Ms</option>
                                                        <option value=Miss>Miss</option>
                                                        <option value=Dr>Dr</option>
                                                        <option value=Prof>Prof</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="courseCode">First Name:</label>
                                                    <input type="text" id="fname" name="fname" placeholder="First Name" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Middle Name</label>
                                                    <input type="text" id="mname" name="mname" placeholder="Middle Name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Last Name</label>
                                                    <input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="">Select Here</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" placeholder="Email" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
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
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email">Phone Number</label>
                                            <input type="text" id="phone" name="phone" placeholder="Phone Number" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email">Department Name</label>
                                            <select name="departmentID"  class="form-control">
                                                <?php
                                                $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
                                                if(!empty($department)){
                                                    echo "<option value=''>Select Here</option>";
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
                                        <select name="employmentStatus" class="form-control" required>
                                            <option value="">Select Here</option>
                                            <option value="Full Time">Full Time</option>
                                            <option value="Part Time">Part Time</option>
                                        </select>

                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Geder">Office Number</label>
                                        <input type="text" id="officeNumber" name="officeNumber" placeholder="Office Number" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">Do you want to allow this Instructor to Interact with System? <input type="radio" name="islogin" value="1" checked>Yes <input type="radio" name="islogin" value="0">No</div>
                                </div>

                                <div class="row">
                                    <br />

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
                                    <input type="hidden" name="action_type" value="add"/>
                                    <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>