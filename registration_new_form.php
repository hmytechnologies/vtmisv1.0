<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">
<script type="text/javascript">
    $(document).ready(function(){
        $("#employed").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".yes").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".yes").hide();
                }
            });
        }).change();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#disability").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".ndio").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".ndio").hide();
                }
            });
        }).change();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#sponsor").change(function(){
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
    $(document).ready(function()
    {
        $("#programmeLevelID").change(function()
        {
            var programmeLevelID=$(this).val();
            var centerID=$("#centerIDD").val();
            var dataString = 'programmeLevelID='+programmeLevelID+'&centerID='+centerID;
            $.ajax
            ({
                type: "POST",
                url: "ajax_programme.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#programmeID").html(html);
                }
            });

        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#regionID").change(function () {
            var regionID = $(this).val();
            var dataString = 'regionID=' + regionID;
            $.ajax
            ({
                type: "POST",
                url: "ajax_district.php",
                data: dataString,
                cache: false,
                success: function (html) {
                    $("#districtID").html(html);

                }
            });

        });

    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $("#districtID").change(function () {
            var districtID = $(this).val();
            var dataString = 'districtID=' + districtID;
            $.ajax
            ({
                type: "POST",
                url: "ajax_shehia.php",
                data: dataString,
                cache: false,
                success: function (html) {
                    $("#shehiaID").html(html);

                }
            });

        });

    });
</script>


<?php
$db = new DBHelper();
?>

<div class="row">
    <div class="col-lg-12">
        <?php
        if(!empty($_REQUEST['msg']))
        {
            if($_REQUEST['msg']=="succ") {
                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Data Saved Successfully</strong>.
                    </div>";
            }
            else if($_REQUEST['msg']=="unsucc") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, We are not able to save your data</strong>.
                    </div>";
            }
            else if($_REQUEST['msg']=="error") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
            }
        }
        ?>
    </div>

</div>
<div class="container">
    <div class="content">

        <?php
                ?>
                <form action="action_register_new_student.php" enctype="multipart/form-data" method="post" name="register" id="register">

                    <div class="well">
                        <h2>Student Registration</h2>
                        <hr>
                        <fieldset>
                            <legend>Personal Information</legend>

                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="FirstName">First Name</label>
                                            <input type="text" name="fname"  class="form-control"  required />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="MiddleName">Middle Name</label>
                                            <input type="text" name="mname"   class="form-control" />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="LastName">Last Name</label>
                                            <input type="text" name="lname"  class="form-control"  required />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="gender">Gender</label>

                                            <select name="gender" class="form-control">

                                                <option value="">Select Here</option>

                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- </div>
                                 </div>-->

                                    <!--<div class="row">
                                        <div class="col-lg-10">-->
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <label for="Date of Birth">Date of Birth</label>

                                            <div class="row">
                                                <div class="col-lg-4 no-padding-right">
                                                    <select name="date" class="form-control" required>

                                                            <option value="">--Date--</option>
                                                            <?php
                                                        for($x=1;$x<=31;$x++)
                                                        {
                                                            echo "<option value='$x'>$x</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div><div class="col-lg-4 no-padding-right no-padding-left">
                                                    <select name="month" class="form-control" required>
                                                        <?php
                                                        $arrmonth=array();
                                                        $arrmonth[]="00";
                                                        $arrmonth[1] ="January";
                                                        $arrmonth[2] ="February";
                                                        $arrmonth[3] ="March";
                                                        $arrmonth[4] ="April";
                                                        $arrmonth[5] ="May";
                                                        $arrmonth[6] ="June";
                                                        $arrmonth[7] ="July";
                                                        $arrmonth[8] ="August";
                                                        $arrmonth[9] ="September";
                                                        $arrmonth[10] ="October";
                                                        $arrmonth[11] ="November";
                                                        $arrmonth[12] ="December";
                                                        ?>
                                                            <option value="">--Month--</option>

                                                        <?php
                                                        for($i = 1; $i<=12; $i++)
                                                        {
                                                            echo "<option value='$i'>$arrmonth[$i]</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div><div class="col-lg-4 no-padding-left">
                                                    <select name="year" class="form-control" required>

                                                            <option value="">--Year--</option>
                                                        <?php
                                                        $year=date('Y');
                                                        $year1=date('Y')-60;
                                                        for($x=$year;$x>=$year1;$x--)
                                                        {
                                                            echo "<option value='$x'>$x</option>";
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Physical Address">Place of Birth</label>
                                            <input type="text" name="placeOfBirth"  class="form-control" required />
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Geder">Marital Status</label>
                                            <select name="mstatus" class="form-control" required>


                                                    <option value="">Select Here</option>


                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Windowed">Windowed</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Physical Address">ZNZ/NIDA ID</label>
                                            <input type="text" name="nationalID" class="form-control">
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <label for="Physical Address">Region</label>
                                            <select name="regionID" id="regionID" class="form-control" required>

                                                    <option value="">Select Here</option>
                                                <?php
                                                $location=$db->getRows("ddx_region",array('order_by regionID ASC'));
                                                foreach($location as $sp)
                                                {
                                                    $regionID=$sp['regionCode'];
                                                    $regionName=$sp['regionName'];
                                                    echo "<option value='$regionID'>$regionName</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>


                                        <div class="col-lg-3">
                                            <label for="Physical Address">District</label>

                                            <select name="districtID" id="districtID" class="form-control" required="">

                                                    <option value="">--Select District--</option>

                                            </select>

                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Physical Address">Shehia</label>

                                            <select name="shehiaID" id="shehiaID" class="form-control" required="">

                                                <option value="">--Select Shehia--</option>

                                            </select>

                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Physical Address">Physical Address</label>
                                            <input type="text" name="address"   class="form-control" required />
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="Email">Email</label>
                                            <input type="text" name="email"   class="form-control" />
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="Phone">Phone Number</label>
                                            <input type="text" name="phoneNumber"  class="form-control" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="Geder">Hosteller</label>
                                            <select name="hosteller" id="hosteller" class="form-control" required="">


                                                    <option value="" selected>Select Here</option>


                                                <option value="1">Yes</option>
                                                <option value="0">No</option>

                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="Geder">Religion</label>
                                            <select name="religion" id="religion" class="form-control" required="">

                                                    <option value="" selected>Select Here</option>

                                                <option value="Muslim">Muslim</option>
                                                <option value="Christian">Christian</option>
                                                <option value="Other Religion">Other Religon</option>
                                                <option value="None">None</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">


                                        <div class="col-lg-3">
                                            <label for="Geder">Does student has any disability?</label>
                                            <select name="disability" id="disability" class="form-control" required>
                                                    <option value="" selected>Select Here</option>
                                                    <option value="ndio">Yes</option>
                                                    <option value="hapana">No</option>
                                            </select>
                                        </div>
                                        <div class="ndio"><div class="col-lg-3">
                                                <label for="FirstName">Disability Type</label>
                                                <input type="text" name="dname"   class="form-control" />
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="MiddleName">Disability Description</label>
                                                <input type="text" name="ddescription"   class="form-control" />
                                            </div>

                                        </div></div>
                                </div>

                                <div class="col-lg-2">
                                    <!-- Picture -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="Picture">Student Picture</label>
                                           <img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />
                                            <input type='file' name="photo" accept=".jpg" onchange="readURL(this);" />
                                        </div></div>
                                    <!-- Picture -->
                                </div>

                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Academic Information</legend>
                            <div class="row">
                                <div class="col-lg-10">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="Physical Address">Center Name</label>
                                            <select name="centerID" id="centerIDD"  class="form-control" required>
                                                <option value="">Select Here</option>
                                                <?php

                                                    $center = $db->getRows('center_registration',array('where'=>array('centerRegistrationID'=>$_SESSION['department_session']),'order_by'=>'centerName ASC'));
                                                if(!empty($center)){

                                                    $count = 0; foreach($center as $cnt){ $count++;
                                                        $centerRegistrationID=$cnt['centerRegistrationID'];
                                                        $centerName=$cnt['centerName'];
                                                        ?>
                                                        <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                                    <?php }}?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Physical Address">Trade Level</label>
                                            <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required>
                                               <option value="">Select Here</option>
                                                <?php
                                                $level = $db->getRows('programme_level',array('order_by'=>'programmeLevelCode ASC'));
                                                if(!empty($level)){

                                                    $count = 0; foreach($level as $lvl){ $count++;
                                                        $programmeLevelID=$lvl['programmeLevelID'];
                                                        $programmeLevel=$lvl['programmeLevel'];
                                                        ?>
                                                        <option value="<?php echo $programmeLevelID;?>"><?php echo $programmeLevel;?></option>
                                                    <?php }}?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Physical Address">Trade Name</label>
                                            <select name="programmeID" id="programmeID"  class="form-control" required>
                                                <option value="">Select Here</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                         <div class="col-lg-3">
                                                <label for="LastName">Academic Year</label>
                                                <select name="academicYearID" id="academicYearID"  class="form-control" required>
                                                    <?php
                                                    $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                                                    if(!empty($academicYear)){
                                                        $count = 0;
                                                        foreach($academicYear as $yr){ $count++;
                                                            $academicYearID=$yr['academicYearID'];
                                                            $academicYear=$yr['academicYear'];
                                                            ?>
                                                            <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                                        <?php }}?>
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="LastName">Registration Number</label>
                                                <input type="text" name="regNumber"  class="form-control" required/>
                                            </div>

                                        <div class="col-lg-3">
                                            <label for="LastName">Admission Number</label>
                                            <input type="text" name="admissionnumber"  class="form-control"/>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="LastName">Form Four Index Number</label>
                                            <input type="text" name="formfournumber"  class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Employment Information</legend>

                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="Geder">Employment Status</label>
                                            <select name="employed" id="employed" class="form-control" required>
                                                    <option value="" selected>Select Here</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="yes"><div class="col-lg-3">
                                                <label for="FirstName">Name of Employer</label>
                                                <input type="text" name="employer"  class="form-control" />
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="MiddleName">Address/Place of Work</label>
                                                <input type="text" name="placework"   class="form-control" />
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="LastName">Designation</label>
                                                <input type="text" name="designation"  class="form-control" />
                                            </div>
                                        </div></div></div></div></fieldset>
                        <hr>
                        <fieldset>
                            <legend>Emergency Information</legend>
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="FirstName">Name of Next of Kin</label>
                                            <input type="text" name="nextName"  class="form-control" required />
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="MiddleName">Address of Next of Kin </label>
                                            <input type="text" name="nextAddress"  class="form-control" required />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="LastName">Phone Number</label>
                                            <input type="text" name="nextPhoneNumber"   class="form-control" required />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="Geder">Relationship</label>
                                            <input type="text" name="relationship"  class="form-control"  required />
                                        </div>
                                    </div>

                                </div></div></fieldset>
                        <hr>
                        <fieldset>
                            <legend>Sponsorship Information</legend>
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label for="gender">Sponsor</label>
                                            <select name="sponsor" id="sponsor" class="form-control" required>
                                                    <option value="">Select Here</option>
                                                <option value="Self">Self Financed</option>
                                                <option value="ZHELB">ZHEB</option>
                                                <option value="others">Others</option>
                                            </select>
                                        </div>
                                        <div class="others">
                                            <div class="col-lg-3">
                                                <label for="FirstName">Sponsor's Full Name</label>
                                                <input type="text" name="sponsorname" class="form-control"/>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="MiddleName">Address</label>
                                                <input type="text" name="sponsoraddress" class="form-control" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="MiddleName">Phone Number</label>
                                                <input type="text" name="sponsorphonenumber"  class="form-control" />
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="MiddleName">Email</label>
                                                <input type="text" name="sponsoremail"   class="form-control" />
                                            </div>
                                        </div>

                                    </div></div></div></fieldset>

                        <br />
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                            </div>
                            <div class="col-lg-3">
                                <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
                            </div>
                        </div>
                        <br />
                    </div>
                </form>
    </div></div>