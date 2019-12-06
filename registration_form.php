<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
?>
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
    $("#sponsorID").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".4").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".4").hide();
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
<div id="" class="">

    <?php
    $studentData=$db->getRows('student',array('where'=>array('userID'=>$_SESSION['user_session']),'order_by'=>'studentID ASC'));
    if(!empty($studentData)){
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
            $dstatus=$std['disabilityStatus'];
            $hosteller=$std['hosteller'];
            $districtID=$std['districtID'];
            $religion=$std['religion'];
            $empStatus=$std['employmentStatus'];
            $sponsor=$std['sponsor'];
            $studentPicture=$std['studentPicture'];


            ?>
            <form action="action_edit_student_profile.php" enctype="multipart/form-data" method="post" name="register" id="register">



                <div class="well">
                    <fieldset>
                        <legend>Personal Information</legend>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="FirstName">First Name</label>
                                        <input type="text" name="fname" value="<?php echo $fname;?>" class="form-control"  readonly required />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="MiddleName">Middle Name</label>
                                        <input type="text" name="mname" value="<?php echo $mname;?>"  class="form-control" readonly/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="LastName">Last Name</label>
                                        <input type="text" name="lname" value="<?php echo $lname;?>"  class="form-control" readonly required />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="gender">Gender2</label>

                                        <select name="gender" class="form-control" disabled>
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
                                </div>

                            </div>
                        </div>


                        <hr>
                        <h5 class="text-danger">Please Complete bellow information in order to continue using system</h5>
                        <hr>

                        <div class="row">
                            <div class="col-lg-10">
                                <!--     <div class="row">
                    <div class="col-lg-6">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" value="<?php /*echo $fname;*/?>" class="form-control"  readonly required />
                        </div>
                        <div class="col-lg-6">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname" value="<?php /*echo $mname;*/?>"  class="form-control" readonly/>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname" value="<?php /*echo $lname;*/?>"  class="form-control" readonly required />
                        </div>

                        <div class="col-lg-6">
                            <label for="LastName">Other Names</label>
                            <input type="text" name="oname" value="<?php /*echo $oname;*/?>"  class="form-control" />
                        </div>

                    </div>-->
                                <!--<div class="row">
                    <div class="col-lg-6">
                            <label for="gender">Gender</label>

                            <select name="gender" class="form-control" disabled>
                            <?php
                                /*                            if($gender=="")
                                                            {
                                                            */?>
                                <option value="">Select Here</option>
                            <?php
                                /*                            }
                                                            else
                                                            {
                                                                if($gender=="M")
                                                                    $genderI="Male";
                                                                else
                                                                    $genderI="Female";
                                                            */?>
                            	<option value="<?php /*echo $gender;*/?>" selected><?php /*echo $genderI;*/?></option>
                       <?php /*}*/?>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                     <div class="col-lg-6">
                            <label for="Geder">Marital Status</label>
                           <select name="mstatus" class="form-control" required>

                             <?php
                                /*                             if($mstatus=="")
                                                             {
                                                             */?><option value="">Select Here</option>
                             <?php /*}
                             else
                             {
                             */?>
                             <option value="<?php /*echo $mstatus;*/?>" selected><?php /*echo $mstatus;*/?></option>
                             <?php /*}*/?>
                             <option value="Single">Single</option>
                             <option value="Married">Married</option>
                             <option value="Windowed">Windowed</option>
                             <option value="Divorced">Divorced</option>
                           </select>
                        </div>
                    </div>-->
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="Date of Birth">Date of Birth</label>
                                        <?php

                                        ?>
                                        <div class="row">
                                            <div class="col-lg-4 no-padding-right">
                                                <select name="date" class="form-control" required>
                                                    <?php
                                                    if(!empty($dob))
                                                    {
                                                        $dob2=explode("-",$dob);
                                                        $year=$dob2[0];
                                                        $month=$dob2[1];
                                                        $date=$dob2[2];
                                                        if($month==00)
                                                            $month=0;
                                                        else
                                                            $month=ltrim($dob2[1],0);
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
                                                    if(!empty($dob))
                                                    {
                                                        $dob2=explode("-",$dob);
                                                        $year=$dob2[0];
                                                        $month=$dob2[1];
                                                        $date=$dob2[2];
                                                        if($month==00)
                                                            $month=0;
                                                        else
                                                            $month=ltrim($dob2[1],0);
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
                                                <select name="year" class="form-control" required>
                                                    <?php
                                                    if(!empty($dob))
                                                    {
                                                        $dob2=explode("-",$dob);
                                                        $year=$dob2[0];
                                                        $month=$dob2[1];
                                                        $date=$dob2[2];
                                                        if($month==00)
                                                            $month=0;
                                                        else
                                                            $month=ltrim($dob2[1],0);
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
                                                    $year=date('Y')-14;
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
                                        <input type="text" name="placeOfBirth" value="<?php echo $pobirth;?>"  class="form-control" required />
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="Geder">Marital Status</label>
                                        <select name="mstatus" class="form-control" required>

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
                                        <label for="Physical Address">Nationality</label>

                                        <select name="citizenship" class="form-control" required>
                                            <?php
                                            if(!empty($citizenship)) {
                                                ?>
                                                <option value="<?php echo $citizenship;?>" selected><?php echo $db->getData("country","countryName","countryID",$citizenship);?></option>
                                                <?php
                                            }else {
                                                ?>
                                                <option value="">Select Nationality</option>
                                                <?php
                                            }
                                            $country=$db->getRows("country",array('order_by countryID ASC'));
                                            foreach($country as $ct)
                                            {
                                                $countryID=$ct['countryID'];
                                                $countryName=$ct['countryName'];
                                                echo "<option value='$countryID'>$countryName</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <div class="col-lg-3">
                                        <label for="Physical Address">Region</label>
                                        <select name="regionID" id="regionID" class="form-control" required>
                                            <?php
                                            $regionID=$db->getData("district","regionID","districtID",$districtID);
                                            if(!empty($regionID))
                                            {
                                                ?>
                                                <option value="<?php echo $regionID;?>"><?php echo $db->getData("region","regionName","regionID",$regionID); ?></option>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <option value="">Select Here</option>
                                                <?php
                                            }
                                            $location=$db->getRows("region",array('order_by regionID ASC'));
                                            foreach($location as $sp)
                                            {
                                                $regionID=$sp['regionID'];
                                                $regionName=$sp['regionName'];
                                                echo "<option value='$regionID'>$regionName</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="col-lg-3">
                                        <label for="Physical Address">District</label>

                                        <select name="districtID" id="districtID" class="form-control" required="">
                                            <?php
                                            if(!empty($districtID)) {
                                                ?>
                                                <option value="<?php echo $districtID;?>"><?php echo $db->getData("district","districtName","districtID",$districtID);?></option>
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
                                        <label for="Physical Address">Physical Address</label>
                                        <input type="text" name="address" value="<?php echo $paddress;?>"  class="form-control" required />
                                    </div>



                                </div>



                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="Email">Email</label>
                                        <input type="text" name="appemail" value="<?php echo $email;?>"  class="form-control" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="Phone">Phone Number</label>
                                        <input type="text" name="phoneNumber" value="<?php echo $pnumber;?>" class="form-control" required>
                                    </div>
                                <div class="col-lg-3">
                                    <label for="Geder">Hosteller</label>
                                    <select name="hosteller" class="form-control" required>
                                        <?php
                                        if($hosteller=="")
                                        {
                                            ?>
                                            <option value="" selected>Select Here</option>
                                            <option value="ndio">Yes</option>
                                            <option value="hapana">No</option>
                                            <?php
                                        }
                                        else if($hosteller==1)
                                        {
                                            ?>
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                            <?php
                                        }else
                                        {
                                            ?>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="Geder">Religion</label>
                                    <select name="religion" id="religion" class="form-control" required="">
                                        <?php
                                        if(!empty($religion))
                                        {
                                            ?>
                                            <option value="<?php echo $religion;?>"><?php echo $db->getData("religion","religionName","religionID",$religion);?></option>

                                        <?php }
                                        else {
                                            ?>
                                            <option value="">Select Here</option>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        $religiontype = $db->getRows('religion',array('order_by'=>'religionID ASC'));
                                        if(!empty($religiontype)){

                                            foreach($religiontype as $rlg){
                                                $religionID=$rlg['religionID'];
                                                $religionName=$rlg['religionName'];
                                                ?>
                                                <option value="<?php echo $religionID;?>"><?php echo $religionName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                                </div>


                                <div class="row">


                                    <div class="col-lg-3">
                                        <label for="Geder">Do you have any disability?</label>
                                        <select name="disability" id="disability" class="form-control" required>
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
                                            <input type="text" name="dname"  value="<?php echo $disabilityName;?>" class="form-control" />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="MiddleName">Disability Description</label>
                                            <input type="text" name="ddescription" value="<?php echo $disabilityDescription;?>"  class="form-control" />
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
                    <fieldset>
                        <legend>Employment Information</legend>

                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="Geder">Are You Employed?</label>
                                        <select name="employed" id="employed" class="form-control" required>
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
                                        foreach($sponsorData as $sp)
                                        {
                                            $employer=$sp['employer'];
                                            $placework=$sp['placeOfWork'];
                                            $designation=$sp['designation'];
                                        }
                                    }
                                    else
                                    {
                                        $employer="";
                                        $placework="";
                                        $designation="";
                                    }
                                    ?>
                                    <div class="yes"><div class="col-lg-3">
                                            <label for="FirstName">Name of Employer</label>
                                            <input type="text" name="employer" value="<?php echo $employer;?>" class="form-control" />
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="MiddleName">Address/Place of Work</label>
                                            <input type="text" name="placework" value="<?php echo $placework;?>"  class="form-control" />
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="LastName">Designation</label>
                                            <input type="text" name="designation" value="<?php echo $designation;?>"  class="form-control" />
                                        </div>
                                    </div></div></div></div></fieldset>

                    <fieldset>
                        <legend>Emergency Information</legend>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="FirstName">Name of Next of Kin</label>
                                        <input type="text" name="nextName" value="<?php echo $nkin;?>" class="form-control" required />
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="MiddleName">Address of Next of Kin </label>
                                        <input type="text" name="nextAddress" value="<?php echo $naddress;?>" class="form-control" required />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="LastName">Phone Number</label>
                                        <input type="text" name="nextPhoneNumber" value="<?php echo $nphone;?>"  class="form-control" required />
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="Geder">Relationship</label>
                                        <input type="text" name="relationship"  class="form-control" value="<?php echo $nrelation;?>" required />
                                    </div>
                                </div></div></div></fieldset>

                    <fieldset>
                        <legend>Sponsorship Information</legend>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="gender">Sponsor</label>
                                        <select name="sponsor" id="sponsorID" class="form-control" required>
                                            <?php
                                            if($sponsor=="")
                                            {
                                                ?>
                                                <option value="">Select Here</option>
                                            <?php }
                                            else {
                                                ?>
                                                <option value="<?php echo $sponsor;?>"><?php echo $db->getData("sponsor_type","sponsorCode","sponsorTypeID",$sponsor); ?></option>
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
                                    if($sponsor=="4")
                                    {
                                        $sponsorData=$db->getRows("sponsor",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                                        foreach($sponsorData as $sp)
                                        {
                                            $sponsorname=$sp['sponsorName'];
                                            $sponsoraddress=$sp['sponsorAddress'];
                                            $sponsorphone=$sp['sponsorPhoneNumber'];
                                        }
                                    }
                                    else
                                    {
                                        $sponsorname="";
                                        $sponsoraddress="";
                                        $sponsorphone="";
                                    }
                                    ?>
                                    <div class="4">
                                        <div class="col-lg-3">
                                            <label for="FirstName">Sponsor's Full Name</label>
                                            <input type="text" name="sponsorname" value="<?php echo $sponsorname;?>" class="form-control"/>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="MiddleName">Address</label>
                                            <input type="text" name="sponsoraddress" value="<?php echo $sponsoraddress;?>"  class="form-control"/>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="MiddleName">Phone Number</label>
                                            <input type="text" name="sponsorphonenumber" value="<?php echo $sponsorphone;?>"  class="form-control"/>
                                        </div>
                                    </div>

                                </div></div></div></fieldset>


                    <br />
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="hidden" name="studentID" value="<?php echo $studentID;?>">
                            <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <button onclick="goBack()" class="btn btn-danger form-control">Cancel</button>
                        </div>
                    </div>
                    <br />
                </div>
            </form>
        <?php }
    }
    else
    {
        echo "UserID".$_SESSION['user_session'];

    }?>
</div>