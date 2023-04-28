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
                            <td><?php echo $user['centerCode']; ?></td>
                            <td><?php echo $user['regNumber']; ?></td>
                            <td><?php echo $db->getData('ddx_district','districtName','districtCode',$districtCode);?></td>
                            <td><?php echo $db->getData('center_owner_type','typeName','ID',$user['ownershipTypeID']);?></td>
                            <td><?php echo $db->getData('center_registration_type','typeName','centerTypeID',$user['registrationTypeID']);?></td>
                            <td><?php echo $status;?></td>
                            <?php

                            $editButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=edit_center_reg&id='.$db->my_simple_crypt($user['centerRegistrationID'],'e').'" title="Edit Center Info" class="btn btn-success fa fa-edit"></a>
	</div>';

                            $viewButton = '
	                    <div class="btn-group">
	                        <a href="index3.php?sp=view_cente_info&id='.$db->my_simple_crypt($user['centerRegistrationID'], 'e').'" title="View Center Information" class="btn btn-success fa fa-eye"></a>
                        </div>';

                            $button=" $viewButton $editButton";
                            ?>
                            <td>
                                <?php
                                echo $button;
                                ?>
                                <!--<a href="index3.php?sp=view_center_reg&id=<?php /*echo $db->my_simple_crypt($user['centerRegistrationID'],'e'); */?>" class="glyphicon glyphicon-eye-open" title="View Center Details"></a>|<a href="index3.php?sp=edit_center_reg&id=<?php /*echo $db->my_simple_crypt($user['centerRegistrationID'],'e'); */?>" class="glyphicon glyphicon-edit" title="Edit Center Details">--></a>
                            </td>
                        </tr>
                    <?php } } ?>
                    </tbody>
                </table>
            </div></div>



        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form name="" method="post" action="action_department.php">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

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
