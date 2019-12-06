<div class="container">
    <div class="row">
        <h2>Course Management</h2>
        <div class="col-md-12">
            <!--<div class="pull-right">
                <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Setting</a>  <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Course</button>
            </div>-->
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
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been inserted successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="edited")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been edited Successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=='unsucc')
                {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Duplicate entry! A course with this code already exists.</strong>.
</div>";
                }
                else {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=course' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>List of Registered Course</h3>
            <br>
            <?php

            $db = new DBHelper();
            $users = $db->getRows('course',array('where'=>array('departmentID'=>$_SESSION['department_session']),'order_by'=>'courseName DESC'));
            ?>
            <table  id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Credits</th>
                    <th>Hrs</th>
                    <th>Prerequiste</th>
                    <th>Status</th>
                    <th>Down.Outline</th>
                    <th>Up.Outline</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
                    if($user['status']==1)
                    {
                        $status="Active";
                    }
                    else
                    {
                        $status="Not Active";
                    }
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $user['courseCode']; ?></td>
                        <td><?php echo $user['courseName']; ?></td>
                        <td><?php echo $db->getData("course_type","courseType","courseTypeID",$user['courseTypeID']);?></td>
                        <td><?php echo $user['capacity']; ?></td>
                        <td><?php echo $user['units']; ?></td>
                        <td><?php echo $user['numberOfHours']; ?></td>
                        <td><?php echo $db->getData("course","courseCode","courseID",$user['coursePrerequiste']);?></td>
                        <td><?php echo $status;?></td>
                        <td>
                            <?php
                            if(!empty($user['courseOutline'])) {
                                ?>
                                <a href="course_outline/<?php echo $user['courseOutline'];?>" class="glyphicon glyphicon-download-alt" target="_blank"></a>
                                <?php
                            }
                            else
                            {
                                ?>
                                No
                                <?php
                            }
                            ?>
                        </td>

                        <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $user['courseID'];?>">
          	  <span class="fa fa-upload" aria-hidden="true" title="Upload Course Outline">
                            <!--<a href="index3.php?sp=edit_course&id=<?php /*echo $user['courseID'];*/?>" class="fa fa-upload" title="Upload Course Outline"></a>-->
                        </td>
                    </tr>
                    <div id="message<?php echo $user['courseID'];?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    <h4 class="modal-title">Upload Course Outline for <?php echo $user['courseCode']."-".$user['courseName']; ?></h4>
                                </div>
                                <form name="register" id="register" method="post" enctype="multipart/form-data" action="action_upload_course_outline.php">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">File Upload:</label>
                                            <input type="file" name="user_image" accept="application/pdf">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <input type="hidden" name="courseID" value="<?php echo $user['courseID'];?>">
                                        <input type="hidden" name="action_type" value="add"/>
                                        <input type="submit" name="doUpdate" value="Save Records" class="btn btn-success">
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                <?php } }?>
                </tbody>
            </table>
        </div></div>
</div>