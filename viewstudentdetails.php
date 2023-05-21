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



        <h1>Student Information</h1>
        <hr>
        <h3>View Student Details</h3>
        <ul class="nav nav-tabs" id="myTab">

            <li class="active"><a data-toggle="tab" href="#student_details"><span style="font-size: 16px"><strong>Student Details</strong></span></a></li>
            <li><a data-toggle="tab" href="#student_documents"><span style="font-size: 16px"><strong>Student Documents</strong></span></a></li>
        </ul>
        <div class="tab-content">
            <?php
            $id=$db->my_simple_crypt($_REQUEST['id'],'d');
            $studentData = $db->getRows('student',array('where'=>array('studentID'=>$id),'order_by'=>'studentID ASC'));
            if(!empty($studentData)){
            ?>
            <div id="student_details" class="tab-pane fade in active">
                <?php
                foreach($studentData as $std)
                {
                    $studentID=$std['studentID'];
                    $fname=$std['firstName'];
                    $mname=$std['middleName'];
                    $lname=$std['lastName'];
                    $oname=$std['otherNames'];
                    $gender=$std['gender'];
                    $pobirth=$std['placeOfBirth'];
                    $mstatus=$std['maritalStatus'];
                    $citizenship=$std['citizenship'];
                    $dob=$std['dateOfBirth'];
                    $paddress=$std['physicalAddress'];
                    $pnumber=$std['phoneNumber'];
                    $email=$std['email'];
                    $nkin=$std['nextOfKinName'];
                    $nphone=$std['nextOfkinPhoneNumber'];
                    $naddress=$std['nextOfKinAddress'];
                    $nrelation=$std['relationship'];
                    $nemail=$std['nextOfKinEmail'];
                    $dstatus=$std['disabilityStatus'];
                    $empStatus=$std['employmentStatus'];
                    $sponsor=$std['sponsor'];
                    $studentPicture=$std['studentPicture'];
                    $hosteller=$std['hosteller'];
                    $religion=$std['religion'];
                    $shehiaID=$std['shehiaID'];

                    $districtID=$db->getData("ddx_shehia","districtCode","shehiaCode",$shehiaID);

                    $regionID=$db->getData("ddx_district","regionCode","districtCode",$districtID);

                    $registrationNumber=$std['registrationNumber'];
                    $admissionNumber=$std['admissionNumber'];
                    $academicYearID=$std['academicYearID'];
                    $formfournumber=$std['formFourIndexNumber'];
                    $rgStatus=$std['rgStatus'];

                    //student_center_programme
                    $student_prog=$db->getRows("student_programme",array("where"=>array("regNumber"=>$registrationNumber,'academicYearID'=>$academicYearID,'currentStatus'=>1)));
                    if(!empty($student_prog))
                    {
                        foreach($student_prog as $spg)
                        {
                            $centerRegistrationID=$spg['centerID'];
                            $programmeLevelID=$spg['programmeLevelID'];
                            $programmeID=$spg['programmeID'];
                        }
                    }
                    else
                    {
                        $centerRegistrationID="";
                        $programmeLevelID="";
                        $programmeID="";
                    }
                    ?>
                        <div class="well">
                            <hr>
                            <fieldset>
                                <legend>Personal Information</legend>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="FirstName">First Name</label>
                                                <input type="text" name="fname" value="<?php echo $fname;?>" class="form-control"  required readonly/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="MiddleName">Middle Name</label>
                                                <input type="text" name="mname" value="<?php echo $mname;?>"  class="form-control" readonly/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="LastName">Last Name</label>
                                                <input type="text" name="lname" value="<?php echo $lname;?>"  class="form-control"  required readonly/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="LastName">Other Names</label>
                                                <input type="text" name="oname" value="<?php echo $oname;?>"  class="form-control" readonly/>
                                            </div>
                                        </div>

                                        <!-- </div>
                                     </div>-->

                                        <!--<div class="row">
                                            <div class="col-lg-10">-->
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="gender">Gender</label>

                                                <select name="gender" class="form-control" readonly>
                                                    <?php
                                                    if($gender=="")
                                                    {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        if($gender=="M")
                                                            $genderI="Male";
                                                        else
                                                            $genderI="Female";
                                                        ?>
                                                        <option value="<?php echo $gender;?>" selected><?php echo $genderI;?></option>
                                                    <?php }?>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="Date of Birth">Date of Birth</label>
                                                <?php
                                                if(!empty($dob)) {
                                                    $dob2 = explode("-", $dob);
                                                    $year = $dob2[0];
                                                    $month = $dob2[1];
                                                    $date = $dob2[2];
                                                    if ($month == 00)
                                                        $month = 0;
                                                    else
                                                        $month = ltrim($dob2[1], 0);
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-lg-4 no-padding-right">
                                                        <select name="date" class="form-control" required readonly>
                                                            <?php
                                                            if(!empty($dob))
                                                            {
                                                                ?>
                                                                <option value="<?php echo $date;?>" selected><?php echo $date;?></option>
                                                                <?php
                                                            }else
                                                            {?>
                                                                <option value="">--Date--</option>
                                                                <?php
                                                            }
                                                            for($x=1;$x<=31;$x++)
                                                            {
                                                                echo "<option value='$x'>$x</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div><div class="col-lg-4 no-padding-right no-padding-left">
                                                        <select name="month" class="form-control" required readonly>
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
                                                            if(!empty($dob))
                                                            {
                                                                ?>
                                                                <option value="<?php echo $month;?>" selected><?php echo $arrmonth[$month];?></option>
                                                                <?php
                                                            }else
                                                            {?>
                                                                <option value="">--Month--</option>
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            for($i = 1; $i<=12; $i++)
                                                            {
                                                                echo "<option value='$i'>$arrmonth[$i]</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div><div class="col-lg-4 no-padding-left">
                                                        <select name="year" class="form-control" required readonly>
                                                            <?php
                                                            if(!empty($dob))
                                                            {
                                                                ?>
                                                                <option value="<?php echo $year;?>" selected><?php echo $year;?></option>
                                                                <?php
                                                            }else
                                                            {?>
                                                                <option value="">--Year--</option>
                                                                <?php
                                                            }
                                                            ?>

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
                                                <input type="text" name="placeOfBirth" value="<?php echo $pobirth;?>"  class="form-control" required readonly/>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="Geder">Marital Status</label>
                                                <select name="mstatus" class="form-control" required readonly>

                                                    <?php
                                                    if($mstatus=="") {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <option value="<?php echo $mstatus;?>" selected><?php echo $mstatus;?></option>
                                                    <?php }
                                                    ?>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Windowed">Windowed</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-lg-3">
                                                <label for="Physical Address">Region</label>
                                                <select name="regionID" id="regionID" class="form-control" required readonly>
                                                    <?php
                                                    if(!empty($regionID))
                                                    {
                                                        ?>
                                                        <option value="<?php echo $regionID;?>"><?php echo $db->getData("ddx_region","regionName","regionCode",$regionID); ?></option>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                    }

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

                                                <select name="districtID" id="districtID" class="form-control" required="" readonly>
                                                    <?php
                                                    if(!empty($districtID)) {
                                                        ?>
                                                        <option value="<?php echo $districtID;?>"><?php echo $db->getData("ddx_district","districtName","districtCode",$districtID);?></option>
                                                        <?php
                                                    }else
                                                    {
                                                        ?>
                                                        <option value="">--Select District--</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>

                                            <div class="col-lg-3">
                                                <label for="Physical Address">Shehia</label>

                                                <select name="shehiaID" id="shehiaID" class="form-control" required="" readonly>
                                                    <option value="<?php echo $shehiaID;?>"><?php echo $db->getData("ddx_shehia","shehiaName","shehiaCode",$shehiaID);?></option>
                                                    <option value="">--Select Shehia--</option>

                                                </select>

                                            </div>

                                            <div class="col-lg-3">
                                                <label for="Physical Address">Physical Address</label>
                                                <input type="text" name="address" value="<?php echo $paddress;?>"  class="form-control" required readonly/>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="Email">Email</label>
                                                <input type="text" name="appemail" value="<?php echo $email;?>"  class="form-control" readonly/>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="Phone">Phone Number</label>
                                                <input type="text" name="phoneNumber" value="<?php echo $pnumber;?>" class="form-control" required readonly>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="Geder">Hosteller</label>
                                                <select name="hosteller" id="hosteller" class="form-control" required="" readonly>
                                                    <?php
                                                    if(!empty($hosteller))
                                                    {
                                                        ?>
                                                        <option value="<?php echo $hosteller;?>" selected><?php echo $hosteller;?></option>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <option value="" selected>Select Here</option>
                                                        <?php
                                                    }
                                                    ?>

                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>

                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="Geder">Religion</label>
                                                <select name="religion" id="religion" class="form-control" required="" readonly>
                                                    <?php
                                                    if(!empty($religion))
                                                    {
                                                        ?>
                                                        <option value="<?php echo $religion;?>" selected><?php echo $religion;?></option>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <option value="" selected>Select Here</option>
                                                        <?php
                                                    }
                                                    ?>

                                                    <option value="Muslim">Muslim</option>
                                                    <option value="Christian">Christian</option>
                                                    <option value="Other Religion">Other Religon</option>
                                                    <option value="None">None</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-lg-3">
                                                <label for="Geder">Do you have any disability?</label>
                                                <select name="disability" id="disability" class="form-control" required readonly>
                                                    <?php
                                                    if($dstatus=="")
                                                    {
                                                        ?>
                                                        <option value="" selected>Select Here</option>
                                                        <option value="ndio">Yes</option>
                                                        <option value="hapana">No</option>
                                                        <?php
                                                    }
                                                    else if($dstatus=='No')
                                                    {
                                                        ?>
                                                        <option value="ndio">Yes</option>
                                                        <option value="hapana" selected>No</option>
                                                        <?php
                                                    }else
                                                    {
                                                        ?>
                                                        <option value="ndio" selected>Yes</option>
                                                        <option value="hapana">No</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            if($dstatus=="Yes")
                                            {
                                                $disabilityData=$db->getRows("disability",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                                                if(!empty($disabilityData)) {
                                                    foreach ($disabilityData as $sp) {
                                                        $disabilityName = $sp['disabilityName'];
                                                        $disabilityDescription = $sp['disabilityDescription'];
                                                    }
                                                }
                                                else
                                                {
                                                    $disabilityName = "";
                                                    $disabilityDescription = "";
                                                }
                                            }
                                            else
                                            {
                                                $disabilityName = "";
                                                $disabilityDescription = "";
                                            }
                                            ?>
                                            <div class="ndio"><div class="col-lg-3">
                                                    <label for="FirstName">Disability Name</label>
                                                    <input type="text" name="dname"  value="<?php echo $disabilityName;?>" class="form-control" readonly />
                                                </div>

                                                <div class="col-lg-6">
                                                    <label for="MiddleName">Disability Description</label>
                                                    <input type="text" name="ddescription" value="<?php echo $disabilityDescription;?>"  class="form-control" readonly />
                                                </div>

                                            </div></div>
                                    </div>

                                    <div class="col-lg-2">
                                        <!-- Picture -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="Picture">Student Picture</label>
                                                <img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />

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
                                                <select name="centerID" id="centerIDD"  class="form-control" required readonly>
                                                    <?php
                                                    if(!empty($centerRegistrationID))
                                                    {
                                                        ?><option value="<?php echo $centerRegistrationID;?>"selected><?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerRegistrationID);?></option>
                                                    <?php }
                                                    else {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                    }
                                                    $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                                                    if(!empty($center)){
                                                        $count = 0; foreach($center as $cnt){ $count++;
                                                            $centerRegistrationID=$cnt['centerRegistrationID'];
                                                            $centerName=$cnt['centerName'];
                                                            ?>
                                                            <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                                                        <?php }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="Physical Address">Trade Level</label>
                                                <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required readonly>
                                                    <?php
                                                    if(!empty($programmeLevelID))
                                                    {
                                                        ?><option value="<?php echo $programmeLevelID;?>"selected><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?></option>
                                                    <?php }
                                                    else {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                        <?php
                                                    }
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
                                                <select name="programmeID" id="programmeID"  class="form-control" required readonly>
                                                    <option value="<?php echo $programmeID;?>" selected><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?></option>
                                                    <option value="">Select Here</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="LastName">Academic Year</label>
                                                <select name="academicYearID" id="academicYearID"  class="form-control" required readonly>
                                                    <option value="<?php echo $academicYearID;?>"selected><?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></option>
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
                                                <?php
                                                if($rgStatus==1)
                                                {
                                                    ?>
                                                    <input type="text" name="regNumber" value="<?php echo $registrationNumber;?>"  class="form-control" readonly/>
                                                    <?php
                                                }
                                                else
                                                {?>
                                                    <input type="text" name="regNumber" value="<?php echo $registrationNumber;?>"  class="form-control" required readonly/>
                                                    <?php
                                                }?>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="LastName">Admission Number</label>
                                                <input type="text" name="admissionnumber" value="<?php echo $admissionNumber;?>"  class="form-control" readonly/>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="LastName">Form Four Index Number</label>
                                                <input type="text" name="formfournumber" value="<?php echo $formfournumber;?>"  class="form-control" readonly/>
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
                                                <label for="Geder">Are You Employed?</label>
                                                <select name="employed" id="employed" class="form-control" required readonly>
                                                    <?php
                                                    if($empStatus=="")
                                                    {
                                                        ?>
                                                        <option value="" selected>Select Here</option>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                        <?php
                                                    }
                                                    else if($empStatus=='no')
                                                    {
                                                        ?>
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                        <?php
                                                    }else
                                                    {
                                                        ?>
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            if($empStatus=="yes")
                                            {
                                                $sponsorData=$db->getRows("employmentstatus",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                                                if(!empty($sponsorData)) {
                                                    foreach ($sponsorData as $sp) {
                                                        $employer = $sp['employer'];
                                                        $placework = $sp['placeOfWork'];
                                                        $designation = $sp['designation'];
                                                    }
                                                }
                                                else
                                                {
                                                    $employer = "";
                                                    $placework = "";
                                                    $designation = "";
                                                }
                                            }
                                            else
                                            {
                                                $employer = "";
                                                $placework = "";
                                                $designation = "";

                                            }
                                            ?>
                                            <div class="yes"><div class="col-lg-3">
                                                    <label for="FirstName">Name of Employer</label>
                                                    <input type="text" name="employer" value="<?php echo $employer;?>" class="form-control" readonly />
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="MiddleName">Address/Place of Work</label>
                                                    <input type="text" name="placework" value="<?php echo $placework;?>"  class="form-control" readonly/>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="LastName">Designation</label>
                                                    <input type="text" name="designation" value="<?php echo $designation;?>"  class="form-control" readonly/>
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
                                                <input type="text" name="nextName" value="<?php echo $nkin;?>"  class="form-control" required readonly/>
                                            </div>

                                            <div class="col-lg-3">
                                                <label for="MiddleName">Address of Next of Kin </label>
                                                <input type="text" name="nextAddress" value="<?php echo $naddress;?>"  class="form-control" required readonly/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="LastName">Phone Number</label>
                                                <input type="text" name="nextPhoneNumber" value="<?php echo $nphone;?>"   class="form-control" required readonly/>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="Geder">Relationship</label>
                                                <input type="text" name="relationship" value="<?php echo $nrelation;?>" class="form-control"  required readonly/>
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
                                                <select name="sponsor" id="sponsor" class="form-control" required readonly>
                                                    <?php
                                                    $sponsor=$std['sponsor'];
                                                    if(empty($sponsor))
                                                    {
                                                        ?>
                                                        <option value="">Select Here</option>
                                                    <?php }
                                                    else {
                                                        ?>
                                                        <option value="<?php echo $sponsor;?>"><?php echo $sponsor; ?></option>
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    $stype = $db->getRows('sponsor_type',array('order_by'=>'sponsorCode ASC'));
                                                    if(!empty($stype)){

                                                        foreach($stype as $spl){
                                                            $sponsorTypeID=$spl['sponsorTypeID'];
                                                            $sponsorCode=$spl['sponsorCode'];
                                                            ?>
                                                            <option value="<?php echo $sponsorTypeID;?>"><?php echo $sponsorCode;?></option>
                                                        <?php }}?>
                                                </select>
                                            </div>
                                            <?php
                                            if($sponsor=="others")
                                            {
                                                $sponsorData=$db->getRows("sponsor",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                                                if(!empty($sponsorData)) {
                                                    foreach ($sponsorData as $sp) {
                                                        $sponsorname = $sp['sponsorName'];
                                                        $sponsoraddress = $sp['sponsorAddress'];
                                                        $sponsorphone = $sp['sponsorPhoneNumber'];
                                                        $sponsoremail=$sp['sponsoremail'];
                                                    }
                                                }
                                                else
                                                {
                                                    $sponsorname = "";
                                                    $sponsoraddress = "";
                                                    $sponsorphone = "";
                                                    $sponsoremail="";
                                                }
                                            }
                                            else
                                            {
                                                $sponsorname = "";
                                                $sponsoraddress = "";
                                                $sponsorphone = "";
                                                $sponsoremail="";
                                            }
                                            ?>
                                            <div class="others">
                                                <div class="col-lg-3">
                                                    <label for="FirstName">Sponsor's Full Name</label>
                                                    <input type="text" name="sponsorname" value="<?php echo $sponsorname;?>" class="form-control" readonly/>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label for="MiddleName">Address</label>
                                                    <input type="text" name="sponsoraddress" value="<?php echo $sponsoraddress;?>"  class="form-control" readonly/>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="MiddleName">Phone Number</label>
                                                    <input type="text" name="sponsorphonenumber" value="<?php echo $sponsorphone;?>"  class="form-control" readonly />
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="MiddleName">Email</label>
                                                    <input type="text" name="sponsoremail" value="<?php echo $sponsoremail;?>"  class="form-control" readonly />
                                                </div>
                                            </div>

                                        </div></div></div></fieldset>


                            <br />
                           <!-- <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-3">
                                    <input type="hidden" name="action_type" value="edit"/>
                                    <input type="hidden" name="studentID" value="<?php /*echo $studentID;*/?>">
                                    <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success form-control" />
                                </div>
                                <div class="col-lg-3">
                                    <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
                                </div>
                            </div>-->
                            <br />
                        </div>
                    </form>
                <?php }
                ?>
            </div>
            <div id="student_documents" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $document = $db->getRows("documents", array('where' => array('studentID' => $id), 'order_by studentID ASC'));
                        if (!empty($document)) {
                            ?>

                            <div class="col-lg-12">
                                <h3>List of Uploaded Documents</h3>
                                <table class="table table-striped" id="example" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Document Title</th>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count=0;
                                    foreach ($document as $doc) {
                                        $attachmentID = $doc['documentID'];
                                        $documentType = $doc['documentType'];
                                        $fileUrl = $doc['fileUrl'];
                                        $count++;
                                        echo "<tr><td>$count</td><td>$documentType</td>";
                                        ?>
                                        <td><a href="student_uploaded_document/<?php echo $fileUrl;?>" target="_blank"><i class="fa fa-download"></i>Download</a>
                                        </td>
                                        <td>
                                            <a href="action_upload_document.php?action_type=drop&did=<?php echo $db->my_simple_crypt($attachmentID,'e');?>&id=<?php echo $_REQUEST['id'];?>"
                                               class="glyphicon glyphicon-trash"
                                               onclick="return confirm('Are you sure you want to drop this document?');">Drop</a>
                                        </td>
                                        <?php
                                        echo "</tr>";


                                    }
                                    ?>
                                    </tbody>
                                </table>


                            </div>

                            <?php
                        } else {
                            ?>
                            <h3><span style="color: red;">No Document Found</span> </h3>
                        <?php } ?>
                        </fieldset>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>