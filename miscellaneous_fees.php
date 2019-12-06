<div class="container">
    <div class="row">
        <h2>Miscellaneous Fees</h2>
        <!--<div class="col-md-12">
            <div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Fees Type</button>
            </div>
        </div>-->
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=other_fees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Fees Type data has been inserted successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="edited")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=other_fees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Fees Type data has been edited Successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=='unsucc')
                {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=other_fees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Fees Type Name is already Exists</strong>.
</div>";
                }
                else {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=other_fees' class='close' data-dismiss='alert'>&times;</a>
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
            $users = $db->getRows('feestype',array('where'=>array('feesID'=>3),'order_by'=>'feesTypeID ASC'));
            ?>
            <table  id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Fees Type</th>
                    <th>Amount</th>
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
                        <td>
                        <?php
                            $other_fees = $db->getRows('other_fees',array('where'=>array('feesTypeID'=>$user['feesTypeID']),'order_by'=>'feesTypeID ASC'));
                            if(!empty($other_fees))
                            {
                                foreach($other_fees as $of)
                                {
                                    $amount=number_format($of['amount'],2);
                                }
                            }
                            else
                            {
                                $amount="NA";
                            }
                            echo $amount;
                            ?>
                        </td>
                        <td><?php echo $status;?></td>
                        <td>
                            <button data-toggle="modal" data-target="#<?php echo $user['feesTypeID'];?>" class="btn btn-success fa fa-pencil"></button>
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
                                <form name="idForme" id="idForme" method="post" action="action_add_miscellaneous_fees.php">
                                    <div class="modal-body">
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
                                                    <label for="email">Amount</label>
                                                    <input type="text" id="amount" name="amount" value="<?php echo filter_var($amount,FILTER_SANITIZE_NUMBER_INT);?>" class="form-control" required="" />
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
                <?php } }
                ?>
                </tbody>
            </table>
        </div></div>
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" method="post" action="action_add_miscellaneous_fees.php">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Fees Type ID</label>
                            <select name="feeTypeID" id="feeTypeID" class="form-control" required>
                                <?php
                                $fees = $db->getRows('feestype',array('where'=>array('feesID'=>3,'feesTypeStatus'=>1),'order_by'=>'feesTypeID ASC'));
                                if(!empty($fees)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($fees as $fee){ $count++;
                                        $feesTypeID=$fee['feesTypeID'];
                                        $feesType=$fee['feesType'];
                                        ?>
                                        <option value="<?php echo $feesTypeID;?>"><?php echo $feesType;?></option>
                                    <?php }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Amount</label>
                            <input type="text" id="amount" name="amount" placeholder="Eg. 20000" class="form-control" required="" />
                        </div>


                        <div class="form-group">
                            <label for="MiddleName">Academic Year</label>
                            <select name="admissionYearID" class="form-control" required="">
                                <?php
                                $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                if(!empty($adYear)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($adYear as $year){ $count++;
                                        $academic_year=$year['academicYear'];
                                        $academic_year_id=$year['academicYearID'];
                                        ?>
                                        <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                                    <?php }}
                                ?>
                            </select>
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