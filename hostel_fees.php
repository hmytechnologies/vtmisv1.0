<div class="container">
    <div class="row">
        <h2>Fees Type Available</h2>
        <div class="col-md-12">
            <div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Fees Type</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=feestype' class='close' data-dismiss='alert'>&times;</a>
    <strong>Fees Type data has been inserted successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="edited")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=feestype' class='close' data-dismiss='alert'>&times;</a>
    <strong>Fees Type data has been edited Successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=='unsucc')
                {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=feestype' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Fees Type Name is already Exists</strong>.
</div>";
                }
                else {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=feestype' class='close' data-dismiss='alert'>&times;</a>
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
            $users = $db->getRows('feestype',array('order_by'=>'feesTypeID ASC'));
            ?>
            <table  id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Fees Type</th>
                    <th>Fees Category</th>
                    <th>Fees Type Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($users)){ $countf = 0; foreach($users as $user){ $countf++;
                    if($user['feesTypeStatus']==1)
                    {
                        $status="Active";
                    }
                    else
                    {
                        $status="Not Active";
                    }

                    ?>
                    <tr>
                        <td><?php echo $countf; ?></td>
                        <td><?php echo $user['feesType']; ?></td>
                        <td><?php echo $db->getData('fees','fees','feesID',$user['feesID']);?></td>
                        <td><?php echo $user['feesTypeDesc']; ?></td>
                        <td><?php echo $status;?></td>

                        <td>
                            <button data-toggle="modal" data-target="#<?php echo $user['feesTypeID'];?>"
                                    class="btn btn-success fa fa-pencil"></button>
                        </td>
                    </tr>
                    <!-- Modal form for editting -->
                    <div class="modal fade" id="<?php echo $user['feesTypeID'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <script>
                                        $(function() {
                                            $("#idForme").validate({
                                                rules: {
                                                    feesCategoryID:"required",
                                                    name:"required",
                                                    code:"required",
                                                },
                                                messages: {
                                                }
                                            });
                                        });

                                    </script>
                                </div>
                                <form name="idForme" id="idForme" method="post" action="action_feestype.php">
                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email">Fee Category:</label>
                                                    <select name="feesCategoryID" id="feesCategoryID" class="form-control" required>
                                                        <option value="<?php echo $db->getData('fees','feesID','feesID',$user['feesID']);?>"><?php echo $db->getData('fees','fees','feesID',$user['feesID']);?></option>
                                                        <?php
                                                        $fees = $db->getRows('fees',array('order_by'=>'feesID ASC'));
                                                        if(!empty($fees)){
                                                            $count = 0; foreach($fees as $fee){ $count++;
                                                                $fees=$fee['fees'];
                                                                $feesID=$fee['feesID'];
                                                                ?>
                                                                <option value="<?php echo $feesID;?>"><?php echo $fees;?></option>
                                                            <?php }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email">Fees Type: </label>
                                                    <input type="text" id="name" name="name" value="<?php echo $user['feesType'];?>"class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email">Fees Type Description: </label>
                                                    <input type="text" id="code" name="code" value="<?php echo $user['feesTypeDesc'];?>"class="form-control" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email">Fees Type Status: </label>
                                                    <?php
                                                    if($user['feesTypeStatus']==1)
                                                    {
                                                        ?>
                                                        <input type="radio" name="feesTypeStatus" value="1" checked>Active<input type="radio" name="feesTypeStatus" value="0">Not Active
                                                    <?php } else if($user['feesTypeStatus']==0){?>
                                                        <input type="radio" name="feesTypeStatus" value="1" >Active<input type="radio" name="feesTypeStatus" value="0"checked>Not Active
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <br />
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                            <input type="hidden" name="id" value="<?php echo $user['feesTypeID'];?>">
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary" tabindex="8">
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- End of Modal form -->
                <?php } }else{ ?>
                    <tr><td colspan="4">No Fees(s) found......</td></tr>

                <?php } ?>
                </tbody>
            </table>
        </div></div>
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" method="post" action="action_feestype.php">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Fees Category</label>
                            <select name="feesCategoryID" id="feesCategoryID" class="form-control" required>
                                <?php
                                $fees = $db->getRows('fees',array('order_by'=>'feesID ASC'));
                                if(!empty($fees)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($fees as $fee){ $count++;
                                        $fees=$fee['fees'];
                                        $feesID=$fee['feesID'];
                                        ?>
                                        <option value="<?php echo $feesID;?>"><?php echo $fees;?></option>
                                    <?php }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Fees Type</label>
                            <input type="text" id="name" name="name" placeholder="Fees Type Name" class="form-control" required="" />
                        </div>

                        <div class="form-group">
                            <label for="email">Fees Type Description</label>
                            <input type="text" id="code" name="code" placeholder="Fees Type Description" class="form-control" required="" />
                        </div>




                    </div>





                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>