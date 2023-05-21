<?php
require_once 'DB.php';
$db= new DBHelper();
$staffID=$db->my_simple_crypt($_REQUEST['id'],'d');

$userData = $db->getRows('xsms_teacher',array('where'=>array('teacherCode'=>$staffID),'return_type'=>'single'));


// $userData = $db->getRows('users',array('where'=>array('userID'=>$userID)));


?>
<script>
    function showContractinfo(sel)
    {
        var recruitmentType = sel.options[sel.selectedIndex].value;
        $("#contract1").html( "" );
        if (recruitmentType.length > 0 ) {
            $.ajax({
                type: "POST",
                url: "fetch_contract_info.php",
                data: "recruitmentType="+recruitmentType,
                cache: false,
                beforeSend: function () {
                    $('#contract1').html('<img src="loader.gif" alt="" width="24" height="24">');
                },
                success: function(html) {
                    $("#contract1").html( html );
                }
            });
        }
    }

    function readURL(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(120)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showDistrict(sel)
    {
        var region_id = sel.options[sel.selectedIndex].value;
        $("#output1").html( "" );
        if (region_id.length > 0 ) {

            $.ajax({
                type: "POST",
                url: "ajax_district.php",
                data: "regionID="+region_id,
                cache: false,
                beforeSend: function () {
                    $('#output1').html('<img src="loader.gif" alt="" width="24" height="24">');
                },
                success: function(html) {
                    $("#output1").html( html );
                }
            });
        }
    }

    function showShehia(sel)
    {
        var shehia_id = sel.options[sel.selectedIndex].value;
        $("#output2").html( "" );
        if (shehia_id.length > 0 ) {

            $.ajax({
                type: "POST",
                url: "ajax_shehia.php",
                data: "districtID="+shehia_id,
                cache: false,
                beforeSend: function () {
                    $('#output2').html('<img src="loader.gif" alt="" width="24" height="24">');
                },
                success: function(html) {
                    $("#output2").html( html );
                }
            });
        }
    }

    function showSpecialization(sel)
    {
        var specialize_id = sel.options[sel.selectedIndex].value;
        $("#specializeOut").html( "" );
        if (specialize_id == 1)
        {
            document.getElementById("specializeOut").disabled = false;
            document.getElementById("subjectComb").disabled = false;
            $.ajax({
                type: "POST",
                url: "fetch_specialize.php",
                data: "specialize_id="+specialize_id,
                cache: false,
                beforeSend: function () {
                    $('#specializeOut').html('<img src="loader.gif" alt="" width="24" height="24">');
                },
                success: function(html) {
                    $("#specializeOut").html( html );
                }
            });
        }
        else
        {
            if( specialize_id == 2 )
            {
                document.getElementById("specializeOut").disabled = true;
                document.getElementById("subjectComb").disabled = true;
                document.getElementById("specializeOut").required = false;
                document.getElementById("subjectComb").required = false;
            }
            else
            {
                document.getElementById("specializeOut").disabled = false;
                document.getElementById("subjectComb").disabled = false;
            }
        }
    }
</script>

<div class="container-fluid">

    <!-- end row -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card mb-3">
                <div class="well">
                    <h2>Register Staff</h2>
                    <hr>

                    <form method="post" action="action_teacher.php" enctype="multipart/form-data" data-parsley-validate novalidate>
                        <div>
                            <h4>Personal Information</h4>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example2">First Name<span class="text-danger">*</span></label>
                                            <input class="form-control" placeholder="First Name" name="fname" type="text" value="<?php echo $userData['firstName'];?>"  required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" placeholder="Middle Name" name="mname" type="text" value="<?php echo $userData['middleName'];?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name<span class="text-danger">*</span></label>
                                            <input class="form-control" placeholder="Last Name" name="lname" type="text" required value="<?php echo $userData['lastName'];?>"  />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Date of Birth<span class="text-danger">*</span></label>
                                        <input class="form-control" name="dob" type="date" required  value="<?php echo $userData['dateOfBirth'];?>"/>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example2">Gender<span class="text-danger">*</span></label>
                                            <select class="form-control" name="gender" required>
                                                <option value="<?php echo $userData['sex'];?>"><?php echo $userData['sex'];?></option>
                                                <option value="">select marital status</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Marital Status<span class="text-danger">*</span></label>
                                                <select name="maritalStatus" class="form-control" required>
                                                    <option value="<?php echo $userData['meritalStatus'];?>"><?php echo $userData['meritalStatus'];?></option>
                                                    <option value="">select marital status</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married"> Married</option>
                                                    <option value="Divorced">Divorced</option>
                                                    <option value="Widow">Widow</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Nationality<span class="text-danger">*</span></label>

                                        <select name="nationality" class="form-control" required="required">

                                        <option value="<?php echo $userData['nationalityCode']; ?>">
                                       
                                            <?php echo $db->getData("country","countryName","countryID",$userData['nationalityCode']);?>

                                        </option>

                                        <option value="">Select Here ...</option>
                                            <?php
                                            $country=$db->getRows('country',array('order_by'=>'countryCode ASC'));
                                            if($country){
                                                foreach ($country as $country){
                                                    $count++;?>
                                                    
                                                    <option value="<?php echo $country['countryID']?>"><?php echo $country['countryName']?></option>
                                                <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" placeholder="eg 0677905299" name="phone" type="text" value="<?php echo $userData['phoneNumber'];?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" placeholder="eg someone@hmytechnologies.com" name="email" value="<?php echo $userData['staffEmail'];?>" type="email" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="col-lg-12">
                                    <label for="Picture">Staff Picture</label>
                                    <img src="#" height="150px" width="150px;" id="image"/>
                                    <br/><br/>
                                    <input type="file" name="photo" onchange="readURL(this);" value="<?php echo $userData['photo'];?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-10">
                        <div>
                            <h4>Address</h4>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Physical Address<span class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Physical Address" name="address" type="text" value="<?php echo $userData['physicalAddress'];?>" required="required" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">Region<span class="text-danger">*</span></label>
                                    <select name="regionId" class="form-control" id="m" onChange="showDistrict(this);"  required="required">
                                        
                                        <option value="">Select Here ...</option>
                                        <?php
                                        $reg=$db->getRows('ddx_region',array('order_by'=>'regionCode ASC'));
                                        if($reg){
                                            foreach ($reg as $reg){
                                                $count++;?>
                                                <option value="<?php echo $reg['regionCode']?>"><?php echo $reg['regionName']?></option>
                                            <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">District<span class="text-danger">*</span></label>
                                    <select name="districtId" class="form-control" required="required" onChange="showShehia(this);" id="output1">
                                    
                                    <option value="">Select Here ...</option>
                                        <?php
                                        $reg=$db->getRows('ddx_district',array('order_by'=>'districtCode ASC'));
                                        if($reg){
                                            foreach ($reg as $reg){
                                                $count++;?>
                                                <option value="<?php echo $reg['districtCode']?>"><?php echo $reg['districtName']?></option>
                                            <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">Shehia<span class="text-danger">*</span></label>
                                    <select name="shehiaId" class="form-control" id="output2" required="required" value="<?php echo $userData['firstName'];?>">
                                    <option value="<?php echo $userData['shehiaID']; ?>">
                                       
                                       <?php echo $db->getData("ddx_shehia","shehiaName","shehiaCode",$userData['shehiaID']);?>

                                   </option>
                                    <option value="">Select Here ...</option>
                                        <?php
                                        $reg=$db->getRows('ddx_shehia',array('order_by'=>'shehiaName ASC'));
                                        if($reg){
                                            foreach ($reg as $reg){
                                                $count++;?>
                                                <option value="<?php echo $reg['shehiaCode']?>"><?php echo $reg['shehiaName']?></option>
                                            <?php } } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Education Level</h4>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">Type of Staff<span class="text-danger">*</span></label>
                                    <select name="staffTypeId" class="form-control" id="staffType" required="required" onChange="showSpecialization(this);">
                                        
                                        <option value="">Select Here ...</option>
                                        <option value="1">Teaching</option>
                                        <option value="2">Administration</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">Specialization</label>
                                    <select name="specialize_id" class="form-control" id="specializeOut" required="required">

                                   
                                    <option value="">Select Here ...</option>
                                        <?php
                                        $subjectRow=$db->getRows('xsms_specialization',array('order_by'=>'specializationCode ASC'));
                                        if(!empty($subjectRow))
                                        {
                                            foreach ( $subjectRow as $subjectRow )
                                            { ?>
                                                <option value="<?php echo $subjectRow['specializationCode'];?>">
                                                    <?php echo $subjectRow['specializationName'];?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subjectComb">Subject Combination</label>
                                    <select name="subjectCombId[]" class="form-control chosen-select" multiple="multiple" id="subjectComb" required="required">
                                  
                                        <option value="">Select Here ...</option>
                                        <?php
                                        $subjectRow=$db->getRows('course',array('order_by'=>'courseID ASC'));
                                        if(!empty($subjectRow))
                                        {
                                            foreach ( $subjectRow as $subjectRow )
                                            { ?>
                                                <option value="<?php echo $subjectRow['courseID'];?>">
                                                    <?php echo $subjectRow['courseName'];?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="m">Education Level<span class="text-danger">*</span></label>
                                    <select name="staffLevelId" class="form-control" required="required">
                                    <option value="<?php echo $userData['nationalityCode']; ?>">
                                       
                                       <?php echo $db->getData("country","countryName","countryID",$userData['nationalityCode']);?>

                                   </option>
                                        <option value="">Select Here ...</option>
                                        <?php
                                        $staffLevel=$db->getRows('xsms_staff_level',array('order_by'=>'staffLevelName ASC'));
                                        if (!empty($staffLevel))
                                        {
                                            foreach ($staffLevel as $staffLevels)
                                            { ?>
                                                <option value="<?php echo $staffLevels['staffLevelCode'];?>">
                                                    <?php echo $staffLevels['staffLevelName'];?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="m">Award<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="award" required="required" ></input>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="m">Institution<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="institution" required="required"></input>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="m">Graduation Year<span class="text-danger">*</span></label>
                                    <select name="yearId" class="form-control" required="required">
                                    <option value="<?php echo $userData['nationalityCode']; ?>">
                                       
                                       <?php echo $db->getData("country","countryName","countryID",$userData['nationalityCode']);?>

                                   </option>
                                   <option value=""></option>
                                        <?php
                                        $current_year = date('Y');
                                        for ($i=0; $i < 50 ; $i++) {
                                            echo '<option>'.$current_year--.'</option>';
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Employment Information</h4>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PF NO<span class="text-danger">*</span></label>
                                    <input type="text" name="pfNo" required placeholder="PF NO" class="form-control" value="<?php echo $userData['pfNo'];?>"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Employment NO<span class="text-danger">*</span></label>
                                    <input type="text" name="employmentNo" required  placeholder="Employment NO" class="form-control" value="<?php echo $userData['employmentNO'];?>"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Employment date<span class="text-danger">*</span></label><br>
                                    <input class="form-control" name="employmentDate" type="date" required value="<?php echo $userData['employmentDate'];?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Confirmation date<span class="text-danger">*</span></label>
                                    <input class="form-control" name="confirmationDate" type="date" required value="<?php echo $userData['confirmationDate'];?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Recruitment type<span class="text-danger">*</span></label>
                                    <select name="recruitmentTypeCode" class="form-control" id="recruitmentTypeCode" onChange="showContractinfo(this);" required>


                                    <option value="<?php echo $userData['recruitmentTypeCode']; ?>">
                                       
                                       <?php echo $db->getData("xsms_recruitment_type","recruitmentTypeName","recruitmentTypeCode",$userData['recruitmentTypeCode']);?>

                                   </option>
                                        <option value="">Select here...</option>
                                        <?php
                                        $recruitmentTypes= $db->getRows('xsms_recruitment_type',array('order_by'=>'recruitmentTypeCode ASC'));
                                        if(!empty($recruitmentTypes)){ $count = 0; foreach($recruitmentTypes as $recruitmentType){ $count++;
                                            $recruitmentTypeName=$recruitmentType['recruitmentTypeName'];
                                            $recruitmentTypeCode=$recruitmentType['recruitmentTypeCode'];
                                            ?>
                                            <option value="<?php echo $recruitmentTypeCode;?>"><?php echo $recruitmentTypeName?></option>
                                        <?php }}?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Current Postion<span class="text-danger">*</span></label>
                                    <select name="positionCode" class="form-control">


                                    <option value="<?php echo $userData['positionCode']; ?>">
                                       
                                       <?php echo $db->getData("xsms_position","positionName","positionCode",$userData['positionCode']);?>

                                   </option>

                                        <option value="">Select here...</option>
                                        <?php
                                        $position= $db->getRows('xsms_position',array('order_by'=>'positionCode ASC'));
                                        if(!empty($position)){ $count = 0; foreach($position as $position){ $count++;
                                            $positionName=$position['positionName'];
                                            $positionCode=$position['positionCode'];
                                            ?>
                                            <option value="<?php echo $positionCode;?>"><?php echo $positionName?></option>
                                        <?php }}?>
                                    </select>
                                </div>
                            </div>
                            <div id="contract1" class="col-md-6">

                            </div>

                        </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Center Name<span class="text-danger">*</span></label>
                                        <select name="positionCode" class="form-control">

                                           
                                            <option value="">Select here...</option>
                                            <?php
                                            $center_registration= $db->getRows('center_registration',array('order_by'=>'centerRegistrationID ASC'));
                                            if(!empty($center_registration)){ $count = 0; foreach($center_registration as $center){ $count++;
                                                $centerName=$center['centerName'];
                                                $centerID=$center['centerRegistrationID'];
                                                ?>
                                                <option value="<?php echo $centerID;?>"><?php echo $centerName;?></option>
                                            <?php }}?>

                                            <option value="200">Head Office</option>
                                            <option value="201">Head Office Pemba</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div>
                            <h4>Next of Kin (In case of emergency)</h4>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" name="nextOfKinName" required placeholder="Full Name" class="form-control" value="<?php echo $userData['nextOfKin'];?>"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telephone No<span class="text-danger">*</span></label>
                                    <input type="text" name="nextOfKinTelephone" required placeholder="eg 0777111111" class="form-control" value="<?php echo $userData['nextOfKinTelephone'];?>"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address<span class="text-danger">*</span></label>
                                    <input type="text" name="nextOfKinAddress" required placeholder="Physical Address" class="form-control" value="<?php echo $userData['nextOfKinAddress'];?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <input name="staffID" type="hidden" value="<?php echo $userData['teacherCode'];?>">

                                <input name="schoolID" type="hidden" value="<?php echo $id_school;?>">
                                <!-- <input type="hidden" name="action_type" value="addBasicInfo"/> -->
                                <input type="submit" name="DoEdit" value="Update" class="btn btn-success form-control" />
                            </div>
                            <div class="col-md-4">
                                <a href="index3.php?sp=view_teachers">
                                    <span class="btn btn-primary form-control">Cancel</span>
                                </a>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end card-->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- END container-fluid -->