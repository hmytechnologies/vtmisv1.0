<div class="container">
    <div class="row">
        <h2 class="text-info">System Roles</h2>

    </div>

    <?php
    $db = new DBHelper();
    if(isset($_REQUEST['action'])=='edit')
    {
        $academicYearID=$_REQUEST['id'];
        $ayear = $db->getRows('academic_year',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>'academicYear DESC'));
        if(!empty($ayear))
        {
            foreach ($ayear as $ayear)
            {

                ?>
                <div class="row">
                    <h5 class="text-info">Edit Academic Year</h5>
                    <div class="col-md-3">
                        <div class="form-group">
                            <form name="" action="action_year.php" method="post">
                                <label for="academicYear">Academic Year</label>
                                <input type="text" id="academic_year" name="academic_year" value="<?php echo $ayear['academicYear'];?>" class="form-control" readonly/>
                        </div><span class="help-block" id="error"></span>

                        <div class="form-group">
                            <label for="email">Current Year?</label>
                            <?php if($ayear['status']==1){ ?>
                                <input type="radio" id="status" name="status" value="1" checked="checked" />Yes
                                <input type="radio" id="status" name="status" value="0" />No
                            <?php } else {?>
                                <input type="radio" id="status" name="status" value="1" />Yes
                                <input type="radio" id="status" name="status" value="0" checked="checked" />No
                            <?php }?>
                        </div></div>

                </div>

                <input type="hidden" name="action_type" value="edit"/>
                <input type="hidden" name="id" value="<?php echo $academicYearID;?>">
                <input type="submit" name="doEdit" value="Update Records" class="btn btn-success">
                </form>
                <?php
            }
        }
    }
    ?>

    <div class="row">
        <div class="col-md-12">
            <hr>
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="edited")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Academic Year data has been Updated successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="fail")
                {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Something Occur and Data not Updated</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php

            $roles = $db->getRows('roles',array('order_by'=>'roleName ASC'));
            ?>

            <table  id="example" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Role Name</th>
                    <th>Menu Item List</th>
                    <th>Description</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($roles)){ $count = 0; foreach($roles as $rl){ $count++;
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $rl['roleName'];?></td>
                        <td><?php echo $rl['menu_item']; ?></td>
                        <td><?php echo $rl['description']; ?></td>
                        <td>
                            
                            <!--<a href="index3.php?sp=academicyear&action=edit&id=<?php /*echo $ayear['academicYearID']; */?>" class="glyphicon glyphicon-edit"></a>-->

                        </td>
                    </tr>
                <?php } }?>
                </tbody>
            </table>
        </div></div>
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" id="" role="form" method="post" action="action_year.php">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="modal-body">
                        <div class="form-group">
                            <?php
                            $roles = $db->getRows('roles',array('order_by'=>'roleName ASC'));
                            if(!empty($roles))
                            {
                                foreach($roles as $rl)
                                {
                                    $roleID=$rl['roleID'];
                                    $roleName=$rl['roleName'];
                                }
                            }

                            ?>
                            <label for="academicYear">Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" value="<?php echo $year;?>/<?php echo $nextYear;?>" class="form-control" readonly />
                        </div><span class="help-block" id="error"></span>

                        <div class="form-group">
                            <label for="email">Current Year?</label>
                            <input type="radio" id="status" name="status" value="1" />Yes
                            <input type="radio" id="status" name="status" value="0" />No
                        </div>




                    </div></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                <!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
                </form>
            </div>
        </div>
    </div>
</div>