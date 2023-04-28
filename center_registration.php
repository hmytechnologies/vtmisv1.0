<?php
$db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h3>Center Registration</h3>
        <br>
        <hr>
        <div class="row">
        </div>         <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <!--<button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Center</button>-->
                    <a href="index3.php?sp=add_new_center" class="btn btn-success">Register New Center</a>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">

                <?php
                if(!empty($_REQUEST['msg']))
                {
                    if($_REQUEST['msg']=="succ")
                    {
                        echo "<div class='alert alert-success fade in'><a href='index3.php?sp=center_reg' class='close' data-dismiss='alert'>&times;</a>
    <strong>Department data has been inserted successfully</strong>.
</div>";
                    }
                    else if($_REQUEST['msg']=="edited")
                    {
                        echo "<div class='alert alert-success fade in'><a href='index3.php?sp=center_reg' class='close' data-dismiss='alert'>&times;</a>
    <strong>Department data has been edited Successfully</strong>.
</div>";
                    }

                    else if($_REQUEST['msg']=="exist")
                    {
                        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=center_reg' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory,Department is already exist</strong>.
</div>";
                    }
                    else
                    {
                        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=center_reg' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
                    }
                }
                ?>


            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php

                $db = new DBHelper();
                $users = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                ?>
                <h3 class="text-info">List of Registered Centers</h3>
                <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Center Name</th>
                        <th>Center Code</th>
                        <th>Center Reg.Number</th>
                        <th>District Name</th>
                        <th>Owner</th>
                        <th>Reg.Status</th>
                        <th>Status</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;

                        if($user['centerStatus']==1)
                        {
                            $status="Active";
                        }
                        else
                        {
                            $status="Not Active";
                        }
                        $districtCode=$db->getData('ddx_shehia','districtCode','shehiaCode',$user['shehiaID']);

                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $user['centerName']; ?></td>
                            <td><?php echo $user['regNumber']; ?></td>
                            <td><?php echo $user['centerCode']; ?></td>
                            <td><?php echo $db->getData('ddx_district','districtName','districtCode',$districtCode);?></td>
                            <td><?php echo $db->getData('center_owner_type','typeName','ID',$user['ownershipTypeID']);?></td>
                            <td><?php echo $db->getData('center_registration_type','typeName','centerTypeID',$user['registrationTypeID']);?></td>
                            <td><?php echo $status;?></td>
                            <td>
                              

                              <a href="#edit_<?php echo $user['centerRegistrationID']; ?>" class="btn btn-success btn-sm" data-toggle="modal" >
                            
                              <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                    
                                    </span>
                                    <span><strong></strong></span>
                            </a>
                         </td>
                        </tr>



                        <div  id="edit_<?php echo $user['centerRegistrationID'];?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                            
                                <!-- Modal content-->
                                <div class="modal-content">

                                <form name="" id="" role="form" method="post" action="action_center_registration.php">
                                <!-- /php echo $user['centerRegistrationID'];$user['centerRegistrationID'];?> -->
                                
                                        <div class="modal-header">
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                                            <h4 class="modal-title" id="myModalLabel">Update Records</h4>
                                            <!-- update form  -->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="modal-body">


                                               
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="FirstName">Center Name</label>
                                                                        <input type="text" name="name"  class="form-control"  value="<?php echo $user['centerName'];?>" />
                                                                    </div>

                                                                </div>



                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="MiddleName">Center Short Code</label>
                                                                        <input type="text" name="code"   class="form-control" value="<?php echo $user['centerCode'];?>" />
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="LastName">Registration Number</label>
                                                                         <input type="text" name="regNumber"  class="form-control"  value="<?php echo $user['regNumber'];?>"/>
                                                                    </div>

                                                                </div>



                                                                <div class="col-lg-6">
                                                                   <div class="form-group">
                                                                    <label for="Physical Address">Registration Type</label>
                                                                            <select name="registrationTypeID" id="registrationTypeID" class="form-control" >

                                                                                <option value="<?php echo $user['registrationTypeID'];?>">
                                                                                
                                                                                
                                                                                <?php echo $db->getData("center_registration_type","typeName","centerTypeID",$user['registrationTypeID']);?>
                                                                                </option>
                                                                               <?php echo "<option>Select Registration Type</option>";?>
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

                                                                </div>
                                                            </div>





                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="Physical Address">Accreditation Type</label>
                                                                            <select name="accredidationTypeID" id="accredidationTypeID" class="form-control" >
                                                                                <option value="<?php echo $user['accredidationTypeID'];?>">
                                                                                <?php echo $db->getData("center_accreditation_type","typeName","ID",$user['accredidationTypeID']);?>
                                                                            
                                                                                </option>
                                                                                <?php echo "<option>Select Accreditation Type</option>";?>
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

                                                                    

                                                                </div>



                                                                


                                                           
                                                                <div class="col-lg-6">
                                                                    <label for="Physical Address">Ownership Type</label>
                                                                    <select name="ownershipTypeID" id="ownershipTypeID" class="form-control">
                                                                    <option value="<?php echo $user['ownershipTypeID'];?>">
                                                                                <?php echo $db->getData("center_owner_type","typeName","ID",$user['ownershipTypeID']);?>
                                                                            
                                                                                </option>
                                                                                <?php echo "<option>Select Ownership Type</option>";?>
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

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="Phone">Established Year</label>
                                                                        <select name="year" class="form-control">
                                                                        <?php  echo $user['establishedYear'];?>
                                                                            </option>
                                                                            <?php echo "<option>Select Ownership Type</option>";?>
                                                                            <?php
                                                                            $today=date('Y');
                                                                            for($x=$today;$x>=2000;$x--)
                                                                                echo $year= "<option value'$x'>$x</option>";
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            
                                                                
                                                            
                                                                <div class="col-lg-6">
                                                                <label for="Physical Address">Shehia</label>

                                                                <select name="shehiaID" id="shehiaID" class="form-control" >
                                                                <option value="<?php echo $user['shehiaID'];?>">
                                                                                <?php echo $db->getData("ddx_shehia","shehiaName","shehiaCode",$user['shehiaID']);?>
                                                                            
                                                                                </option>
                                                                                <?php echo "<option>Select Shehia</option>"?>
                                                                        <?php
                                                                        $cot=$db->getRows("ddx_shehia",array('order_by shehiaName ASC'));
                                                                        foreach($cot as $co)
                                                                        {
                                                                            $cotID=$co['shehiaCode'];
                                                                            $cotName=$co['shehiaName'];
                                                                            echo "<option value='$cotID'>$cotName</option>";
                                                                        }
                                                                        ?>

                                                                </select>

                                                                </div>


                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="Physical Address">Physical Address</label>
                                                                        <input type="text" name="physicalAddress"   class="form-control" value="<?php echo $user['physicalAddress'];?>" />
                                                                    </div>

                                                                </div>
                                                            
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="Phone">Postal Address</label>
                                                                         <input type="text" name="postalAddress"  class="form-control" value="<?php echo $user['postalAddress'];?>">
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                            <label for="Phone">Website</label>
                                                                             <input type="url" name="website"  class="form-control" value="<?php echo $user['centerWebsite'];?>">
                                                                    </div>

                                                                </div>
                                                            


                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="Phone">Phone Number</label>
                                                                         <input type="text" name="phoneNumber"  class="form-control" value="<?php echo $user['centerPhoneNumber'];?>">
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="MiddleName">Center Short Code</label>
                                                                        <input type="text" name="code"   class="form-control" value="<?php echo $user['centerRegistrationID'];?>"/>
                                                                    </div>

                                                                </div>
                                                           
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                            <label for="Phone">Contact Person</label>
                                                                            <input type="text" name="cperson"  class="form-control" value="<?php echo $user['contactPerson'];?>">
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                         <label for="Phone">Contact Phone Number</label>
                                                                          <input type="text" name="cphoneNumber"  class="form-control" value="<?php echo $user['contactPhone'];?>">
                                                                    </div>

                                                                </div>
                                                          



                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="Phone">Contact Email Address</label>
                                                                         <input type="text" name="cemail"  class="form-control" value="<?php echo $user['centerEmail'];?>">

                                                                </div>
                                                            </div>

                                                            <div class="row">


                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="Picture">Center Logo</label>
                                                                                <img id="image" src="img/" height="150px" width="150px;" />
                                                                                <input type='file' name="image" accept=".jpg" onchange="readURL(this);"  value="<?php echo $user['centerPicture'];?>"/>
                                                                            </div></div>
                                                                        <!-- Picture --><input type="hidden" name="id" value="<?php echo $user['centerRegistrationID'];?>"/>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <input type="hidden" name="action_type" value="edit"/>
                                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">

                                                        </div>
                                                    </div>
                                             </div>
                             
                                                    




                                               
                                             
                                    <!-- update form end -->
                                </div>


                                

                            </div>
                                                                    </form>
                        </div>
                    <?php } } ?>
                    </tbody>
                </table>
            </div></div>



        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form name="" method="post" action="action_department.php">
                        <div class="modal-header">
                           

                            <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="email">Department Name</label>
                                        <input type="text" id="name" name="name" placeholder="Department Name" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Department Code</label>
                                        <input type="text" id="code" name="code" placeholder="Code" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Department Email</label>
                                        <input type="email" id="email" name="email" placeholder="Email" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Department Coordinator</label>
                                        <input type="text" id="email" name="head_of_department" placeholder="HoD" class="form-control" />
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <input type="hidden" name="action_type" value="add"/>
                                    <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">

                                </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <!--End Department-->
</div>
</div>
</div>
