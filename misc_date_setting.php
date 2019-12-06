<style type="text/css">
    .bs-example{
        margin: 10px;
    }
</style>
<?php $db=new DBHelper();?>
<div class="container">
    <div class="content">

        <div id="semestercourse">
            <h1>Exam Category Date Settings</h1>
            <hr>
            <div class="col-md-6">
                <h3>List of Registered Date Settings</h3>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Define New Semetser Setting</button>
                </div>
            </div>

            <br><br>
            <hr>
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date has been inserted successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="exist") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date already exist</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="date") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date data are not correct</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="deleted") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date Data has been delete successfully</strong>.
</div>";
                }
            }
            ?>
            <div class="row"><br></div>

            <div class="row">
                <?php
                $semester= $db->getRows('misc_date_setting',array('order_by=>startDate DESC'));

                if(!empty($semester))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Semester Name</th>
                            <th>Category Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach($semester as $sm)
                        {
                            $count++;
                            $id=$sm['miscDateID'];
                            $semisterID=$sm['semesterSettingID'];
                            $startDate=$sm['startDate'];
                            $endDate=$sm['endDate'];
                            $examCategoryID=$sm['examCategoryID'];

                            $today=date('Y-m-d');
                          if($today<=$endDate)
                                $status="<span class='label label-success'>Active</span>";
                            else
                                $status="<span class='label label-danger'>Not Active</span>";

                            ?>
                            <tr>
                                <td><?php echo $count;?></td>
                                <td><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semisterID);?></td>
                                <td><?php echo $db->getData("misc_category","categoryName","miscCategoryID",$examCategoryID);?></td>
                                <td><?php echo date('d-m-Y',strtotime($startDate));?></td>
                                <td><?php echo date('d-m-Y',strtotime($endDate));?></td>
                                <td><?php echo $status;?></td>
                                <td>
                                        <a href="action_misc_date_setting.php?action_type=delete&id=<?php echo $db->my_simple_crypt($id,'e'); ?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Setting?');"</a>
                          </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>

                    <?php
                }
                else
                {
                    ?>
                    <h4 class="text-danger">No Setting found......</h4>
                    <?php
                }
                ?>

                <?php

                ?>
            </div>
        </div>


    </div>
</div>
<!-- Modal for Semester Setting -->
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="" id="" role="form" method="post" action="action_misc_date_setting.php">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("#startDate").datepicker({
                                        dateFormat:"yy-mm-dd",
                                        changeMonth:true,
                                        changeYear:true,

                                        onSelect:function(dateText){
                                            $("#endDate").datepicker('option','minDate',dateText);
                                            $("#registrationDate").datepicker('option','minDate',dateText);
                                        }
                                    });
                                    $("#endDate").datepicker({
                                        dateFormat:"yy-mm-dd",
                                        changeMonth:true,
                                        changeYear:true,
                                        autoclose: true,

                                        onSelect:function(dateText){
                                            $("#registrationDate").datepicker('option','maxDate',dateText);
                                            $("#examStartDate").datepicker('option','minDate',dateText);
                                        }
                                    });


                                });
                            </script>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">

                                        <label for="FirstName">Semester Name</label>
                                        <select name="semisterID" id="semesterID" class="form-control" required>
                                            <?php
                                            $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                            if(!empty($semister)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($semister as $sm){ $count++;
                                                    $semister_name=$sm['semesterName'];
                                                    $semister_id=$sm['semesterSettingID'];
                                                    ?>
                                                    <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                                <?php }}

                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="email">Miscelaneous Category</label>
                                        <select name="examCategoryID" class="form-control" id="examCategoryID">
                                            <?php
                                            $exam_category=$db->getRows('misc_category',array('order_by'=>'categoryName ASC'));
                                            if(!empty($exam_category)){
                                                echo"<option value=''>Please Select Here</option>";
                                                foreach($exam_category as $prg)
                                                {
                                                    $examCategory=$prg['categoryName'];
                                                    $examCategoryID=$prg['miscCategoryID'];
                                                    echo "<option value='$examCategoryID'>$examCategory</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Start Date</label>
                                        <input type="text" name="startDate" class="form-control" id="startDate">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">End Date</label>
                                        <input type="text" name="endDate" class="form-control" id="endDate">
                                    </div></div></div>

                        </div>



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

