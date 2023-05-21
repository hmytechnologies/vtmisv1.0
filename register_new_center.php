<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">


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
        <form action="action_center_registration.php" enctype="multipart/form-data" method="post" name="register" id="register">

            <div class="well">
                <h2>Center Registration</h2>
                <hr>
                <fieldset>
                    <legend>Basic Information</legend>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="FirstName">Center Name</label>
                                    <input type="text" name="name"  class="form-control"  required />
                                </div>
                                <div class="col-lg-4">
                                    <label for="MiddleName">Center Short Code</label>
                                    <input type="text" name="code"   class="form-control" />
                                </div>

                                <div class="col-lg-4">
                                    <label for="LastName">Registration Number</label>
                                    <input type="text" name="regNumber"  class="form-control"  required />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="Physical Address">Registration Type</label>
                                    <select name="registrationTypeID" id="registrationTypeID" class="form-control" required>

                                        <option value="">Select Here</option>
                                        <?php

                                        $crt=$db->getRows("center_registration_type",array('order_by centerTypeID ASC'));
                                        foreach($crt as $cr)
                                        {
                                            $centerTypeID=$cr['centerTypeID'];
                                            $typeName=$cr['typeName'];
                                            echo "<option value='$centerTypeID'>$typeName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="Physical Address">Accreditation Type</label>
                                    <select name="accredidationTypeID" id="accredidationTypeID" class="form-control" required>
                                        <option value="">Select Here</option>
                                        <?php
                                        $cat=$db->getRows("center_accreditation_type",array('order_by ID ASC'));
                                        foreach($cat as $ca)
                                        {
                                            $ID=$ca['ID'];
                                            $typeName=$ca['typeName'];
                                            echo "<option value='$ID'>$typeName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="Physical Address">Ownership Type</label>
                                    <select name="ownershipTypeID" id="ownershipTypeID" class="form-control" required>
                                        <option value="">Select Here</option>
                                        <?php
                                        $cot=$db->getRows("center_owner_type",array('order_by ID ASC'));
                                        foreach($cot as $co)
                                        {
                                            $cotID=$co['ID'];
                                            $cotName=$co['typeName'];
                                            echo "<option value='$cotID'>$cotName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="Phone">Established Year</label>
                                    <select name="year" class="form-control" id="year">
                                        <option value="">Select Here</option>
                                        <?php
                                        $today=date('Y');
                                        for($x=$today;$x>=2000;$x--)
                                            echo "<option value'$x'>$x</option>";
                                        ?>
                                    </select>
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
                                    <input type="text" name="physicalAddress"   class="form-control" required />
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="Phone">Postal Address</label>
                                    <input type="text" name="postalAddress"  class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="Phone">Website</label>
                                    <input type="url" name="website"  class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="Email">Email</label>
                                    <input type="email" name="email"  class="form-control" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="Phone">Phone Number</label>
                                    <input type="text" name="phoneNumber"  class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-lg-3">
                                <label for="Phone">Contact Person</label>
                                <input type="text" name="cperson"  class="form-control" required>
                            </div>

                                <div class="col-lg-3">
                                    <label for="Phone">Contact Phone Number</label>
                                    <input type="text" name="cphoneNumber"  class="form-control" required>
                                </div>

                                <div class="col-lg-3">
                                    <label for="Phone">Contact Email Address</label>
                                    <input type="text" name="cemail"  class="form-control" required>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-2">
                            <!-- Picture -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="Picture">Center Logo</label>
                                    <img id="image" src="img/" height="150px" width="150px;" />
                                    <input type='file' name="image" accept=".jpg" onchange="readURL(this);" />
                                </div></div>
                            <!-- Picture -->
                        </div>

                    </div>
                </fieldset>
                <hr>

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