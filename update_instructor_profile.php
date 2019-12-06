<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
$db = new DBHelper();
$userID=$_SESSION['user_session'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'edit') {
        if (!empty($_POST['instructorID'])) {
            $instructorID = $_POST['instructorID'];
            $officeNumber = $_POST['officeNumber'];
            $fname = trim($_POST['fname']);
            $lname = trim($_POST["lname"]);
            $name = "$fname $lname";
            $userData = array(
                'salutation' => $_POST['salutation'],
                'titleID' => $_POST['titleID'],
                'gender' => $_POST['gender'],
                'phoneNumber' => $_POST['phone'],
                'employmentStatusID' => $_POST['employmentStatus'],
                'officeNumber' => $officeNumber,
                'departmentID'=>$_POST['departmentID'],
                'instructorStatus' => 1,
                'status'=>1
            );

            $userID = $db->getData("instructor", "userID", "instructorID", $instructorID);
            $condition = array('instructorID' => $instructorID);
            $update = $db->update("instructor", $userData, $condition);
            //upload image
            $imgFile = $_FILES['photo']['name'];
            $tmp_dir = $_FILES['photo']['tmp_name'];
            $imgSize = $_FILES['photo']['size'];
            if (!empty($imgFile)) {
                $upload_dir = 'student_images/'; // upload directory

                $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                // valid image extensions
                $valid_extensions = array('png', 'jpg', 'jpeg'); // valid extensions

                // rename uploading image
                $userpic = rand(1000, 1000000) . "." . $imgExt;

                // allow valid image file formats
                if (in_array($imgExt, $valid_extensions)) {
                    // Check file size '5MB'
                    if ($imgSize < 5000000) {
                        move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                        $pictureData = array(
                            'instructorImage' => $userpic
                        );
                        $condition = array('instructorID' => $_POST['instructorID']);
                        $update = $db->update("instructor", $pictureData, $condition);

                        $userImage = array(
                            'userImage' => $userpic
                        );
                        $condition_user = array('userID' => $userID);
                        $update = $db->update("users", $userImage, $condition_user);

                    } else {
                        $errMSG = "Sorry, your image file is too large.";
                        $boolStaus = false;
                    }
                } else {
                    $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                    $boolStaus = false;
                }
            }
            header("Location:index3.php");
        }
    }
}


$instructor = $db->getRows('instructor',array('where'=>array('userID'=>$userID),'order by'=>'firstName ASC'));
if(!empty($instructor)) {
    $x = 0;
    foreach ($instructor as $inst) {
        $x++;
        $gender = $inst['gender'];
        $sal = $inst['salutation'];
        $fname = $inst['firstName'];
        $lname = $inst['lastName'];
        $titleID = $inst['titleID'];
        $empID=$inst['employmentStatusID'];


        ?>
            <form action="" enctype="multipart/form-data" method="post" name="register"
                  id="register">
                <div class="col-md-10">
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
                                                            <select name="titleID" class="form-control"
                                                                    required="required">
                                                                <?php
                                                                if ($titleID == "") {
                                                                    ?>
                                                                    <option value=''>Select Title</option>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $titleID; ?>"><?php
                                                                        echo $db->getData("instructor_title","title","titleID",$titleID); ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                $title = $db->getRows('instructor_title', array('order_by' => 'titleID ASC'));
                                                                if (!empty($title)) {
                                                                    $count = 0;
                                                                    foreach($title as $ttl) {
                                                                        $titleName = $ttl['title'];
                                                                        $titleID = $ttl['titleID'];
                                                                        ?>
                                                                        <option value="<?php echo $titleID; ?>"><?php echo $titleName; ?></option>
                                                                    <?php }
                                                                } ?>
                                                                

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
                                                            <input type="text" id="fname" name="fname"
                                                                   value="<?php echo $fname; ?>" class="form-control" readonly/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Last Name</label>
                                                            <input type="text" id="lname" name="lname"
                                                                   value="<?php echo $lname; ?>" class="form-control" readonly/>
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
                                                            <input type="email" id="email" name="email"
                                                                   value="<?php echo $inst['email']; ?>"
                                                                   class="form-control" readonly/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="email">Phone Number</label>
                                                            <input type="text" id="phone" name="phone"
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
                                                            <?php
                                                            if ($empID == "") {
                                                                ?>
                                                                <option value=''>Select Title</option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $empID; ?>"><?php
                                                                    echo $db->getData("instructor_emp","empType","empID",$empID); ?>
                                                                </option>
                                                                <?php
                                                            }
                                                            $empStatus = $db->getRows('instructor_emp', array('order_by' => 'empID ASC'));
                                                            if (!empty($empStatus)) {
                                                                foreach($empStatus as $emp) {
                                                                    $empName = $emp['empType'];
                                                                    $empID = $emp['empID'];
                                                                    ?>
                                                                    <option value="<?php echo $empID; ?>"><?php echo $empName; ?></option>
                                                                <?php }
                                                            } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="Geder">Office Number</label>
                                                        <input type="text" id="officeNumber" name="officeNumber"
                                                               value="<?php echo $inst['officeNumber']; ?>"
                                                               class="form-control"/>
                                                    </div>
                                                </div>
                                                <br>
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

                                        <div class="row">
                                            <div class="col-lg-6"></div>
                                            <div class="col-lg-6">
                                                <input type="hidden" name="instructorID" value="<?php echo $inst['instructorID']; ?>">
                                                <input type="hidden" name="action_type" value="edit"/>
                                                <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success">
                                                <button onclick="goBack()" class="btn btn-primary">Cancel</button>
                                            </div>

                                        </div>
                            </fieldset>
                        </div>


            </form>
    <?php
    }
}
    ?>
