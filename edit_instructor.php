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
if(!empty($instructor)) {
    $x = 0;
    foreach ($instructor as $inst) {
        $x++;
        $gender = $inst['gender'];


        ?>
        <style>
            .no-padding-right {
                padding-right: 0;
            }

            .no-padding-left {
                padding-left: 0;
            }
        </style>
        <div class="row">
            <form action="action_instructor.php" enctype="multipart/form-data" method="post" name="register"
                  id="register">
                <div class="col-md-8">
                    <div class="modal-body">
                        <div class="well">
                            <fieldset>
                                <legend>Personal Information</legend>
                                <form name="" method="post" enctype="multipart/form-data"
                                      action="action_instructor.php">
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
                                        <?php
                                        $sal = $inst['salutation'];
                                        $fname = $inst['firstName'];
                                        $mname = $inst['middleName'];
                                        $lname = $inst['lastName'];
                                        $title=$inst['titleID'];
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="courseName">Salutation:</label>
                                                            <select name="salutation" class="form-control"
                                                                    required="required">
                                                                <?php
                                                                if ($sal == "") {
                                                                    ?>
                                                                    <option value=''>Select Salutation</option>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $sal; ?>"><?php echo $sal; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
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
                                                            <label for="courseName">Title:</label>
                                                            <select name="title" class="form-control"
                                                                    required="required">
                                                                <?php
                                                                if ($title == "") {
                                                                    ?>
                                                                    <option value=''>Select Title</option>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $title; ?>"><?php echo $title; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <option value="Professor">Professor</option>
                                                                <option value="Assoc.Professor">Assoc.Professor</option>
                                                                <option value="Seniro Lecturer">Senior Lecturer</option>
                                                                <option value="Lecturer">Lecturer</option>
                                                                <option value="Assist.Lecturer">Assist.Lecturer</option>
                                                                <option value="Tutorial Assistant">Tutorial Assistant</option>
                                                            </select>
                                                        </div>
                                                       <!-- <div class="form-group">
                                                            <label for="courseCode">First Name:</label>
                                                            <input type="text" id="fname" name="fname"
                                                                   value="<?php /*echo $fname; */?>" class="form-control"/>
                                                        </div>-->
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="courseCode">First Name:</label>
                                                            <input type="text" id="fname_id" name="fname"
                                                                   value="<?php echo $fname; ?>" class="form-control"/>
                                                        </div>
                                                       <!-- <div class="form-group">
                                                            <label for="email">Middle Name</label>
                                                            <input type="text" id="mname" name="mname"
                                                                   value="<?php /*echo $mname; */?>" class="form-control"/>
                                                        </div>-->
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Last Name</label>
                                                            <input type="text" id="lname" name="lname"
                                                                   value="<?php echo $lname; ?>" class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>

                                                            <select name="gender" class="form-control">
                                                                <?php
                                                                if ($gender == "") {
                                                                    ?>
                                                                    <option value="">Select Here</option>
                                                                    <?php
                                                                } else {
                                                                    if ($gender == "Male")
                                                                        $genderI = "Male";
                                                                    else
                                                                        $genderI = "Female";
                                                                    ?>
                                                                    <option value="<?php echo $gender; ?>"
                                                                            selected><?php echo $genderI; ?></option>
                                                                <?php } ?>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" id="email_id" name="email"
                                                                   value="<?php echo $inst['email']; ?>"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Phone Number</label>
                                                            <input type="text" id="phoneNumber" name="phoneNumber"
                                                                   value="<?php echo $inst['phoneNumber']; ?>"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Department Name</label>
                                                            <select name="departmentID" class="form-control">
                                                                <option value="<?php echo $inst['departmentID']; ?>"><?php echo $db->getData("departments", "departmentName", "departmentID", $inst['departmentID']); ?></option>
                                                                <?php
                                                                $department = $db->getRows('departments', array('order_by' => 'departmentName ASC'));
                                                                if (!empty($department)) {
                                                                    $count = 0;
                                                                    foreach ($department as $dept) {
                                                                        $count++;
                                                                        $department_name = $dept['departmentName'];
                                                                        $department_id = $dept['departmentID'];
                                                                        ?>
                                                                        <option value="<?php echo $department_id; ?>"><?php echo $department_name; ?></option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="Geder">Employment Status</label>
                                                        <select name="employmentStatus" class="form-control" required>
                                                            <option value="<?php echo $inst['employmentStatus']; ?>"><?php echo $inst['employmentStatus']; ?></option>
                                                            <option value="Full Time">Full Time</option>
                                                            <option value="Part Time">Part Time</option>
                                                        </select>

                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="Geder">Office Number</label>
                                                        <input type="text" id="officeNumber_id" name="officeNumber"
                                                               value="<?php echo $inst['officeNumber']; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="email">Work Status</label>
                                                            <?php if ($inst['instructorStatus'] == 1) {
                                                                ?>
                                                                <input type="radio" name="status" value="1"
                                                                       checked>Active <input type="radio" name="status"
                                                                                             value="0">Not Active
                                                            <?php } else { ?>
                                                                <input type="radio" name="status" value="1">Active
                                                                <input type="radio" name="status" value="0"
                                                                       checked>Not Active
                                                            <?php } ?>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- <div class="row">
                                                     <div class="col-lg-12">Do you want to allow this Instructor to Interact with System? <input type="radio" name="islogin" value="1" checked>Yes <input type="radio" name="islogin" value="0">No</div>
                                                 </div>-->
                                            </div>
                                            <div class="col-lg-3">
                                                <!-- Picture -->
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="Picture">Instructor Picture</label>
                                                        <img id="image"
                                                             src="student_images/<?php echo $inst['instructorImage']; ?>"
                                                             height="150px" width="150px;"/>
                                                        <input type='file' name="photo" accept=".jpg"
                                                               onchange="readURL(this);"/>
                                                    </div>
                                                </div>
                                                <!-- Picture -->
                                            </div>
                                        </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <input type="hidden" name="instructorID" value="<?php echo $inst['instructorID']; ?>">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success">
                            <button onclick="goBack()" class="btn btn-primary">Cancel</button>
                        </div>

                    </div>

            </form>
        </div>
    <?php }
}?>
