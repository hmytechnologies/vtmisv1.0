<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">

        <div id="semestercourse">
            <h1>Annual Date Settings</h1>
            <hr>
            <div class="col-md-6">
                <h3>List of Registered Dates</h3>
            </div>
            <div class="col-md-6">
            <div class="pull-right">
                <a href="index3.php?sp=semester_setting" class="btn btn-warning">Back to Main Setting</a>

                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Define New Annual Setting</button>
                        </div>
             </div>

             <br><br>
             <hr>
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=semester_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been inserted successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="exist") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=semester_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data already exist</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="date") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=semester_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date data are not correct</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="deleted") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=semester_date_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been delete successfully</strong>.
</div>";
                }
            }
            ?>
        <div class="row"><br></div>
        
        <div class="row">
            <?php
               $semester= $db->getRows('semester_setting',array('order_by=>semesterStatus ASC'));
    
                if(!empty($semester))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Academic Year</th>
                          <th>Grading Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                          <th>Vacation Start Date</th>
                          <th>Vacation End Date</th>
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
                        $semesterSettingID=$sm['semesterSettingID'];
                        $academicYearID=$sm['academicYearID'];
                        $gradingYearID=$sm['gradingYearID'];
                        $semesterStatus=$sm['semesterStatus'];

                      if($semesterStatus==1)
                        $status="Active";
                      else
                        $status="Not Active";


                       $academicYear = $db->getRows('academic_year',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYear ASC'));
                       foreach($academicYear as $acy)
                       {
                           $academicYear=$acy['academicYear'];
                       }
                          ?>
                          <tr>
                          <td><?php echo $count;?></td>
                          <td><?php echo $academicYear;?></td>
                              <td><?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$gradingYearID);?></td>
                          <td><?php echo date('d-m-Y',strtotime($sm['t1startDate']));?></td>
                          <td><?php echo date('d-m-Y',strtotime($sm['examEndDate']));?></td>
                          <td><?php echo date('d-m-Y',strtotime($sm['vestartDate']));?></td>
                              <td><?php echo date('d-m-Y',strtotime($sm['veendDate']));?></td>

                          <td><?php echo $status;?></td>
                          <td>
                              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#data-message<?php echo $semesterSettingID;?>">
                                  <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                  <span><strong></strong></span>
                              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $semesterSettingID;?>">
                                  <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                  <span><strong></strong></span>
                            </td>
                          </tr>
                        <!--view data-->

                        <!--view data-->
                        <div id="message<?php echo $semesterSettingID;?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <form name="" id="" role="form" method="post" action="action_semester_setting.php">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Update Record</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="modal-body">


                                                    <script type="text/javascript">
                                                        $(document).ready(function () {
                                                            $("#start_date").datepicker({
                                                                dateFormat:"yy-mm-dd",
                                                                changeMonth:true,
                                                                changeYear:true,

                                                                onSelect:function(dateText){
                                                                    $("#end_date").datepicker('option','minDate',dateText);
                                                                    $("#registration_date").datepicker('option','minDate',dateText);
                                                                }
                                                            });
                                                            $("#end_date").datepicker({
                                                                dateFormat:"yy-mm-dd",
                                                                changeMonth:true,
                                                                changeYear:true,
                                                                autoclose: true,

                                                                onSelect:function(dateText){
                                                                    $("#registration_date").datepicker('option','maxDate',dateText);
                                                                    $("#examStart_Date").datepicker('option','minDate',dateText);
                                                                }
                                                            });

                                                            $('#registration_date').datepicker({
                                                                dateFormat: 'yy-mm-dd',
                                                                changeMonth:true,
                                                                changeYear:true,
                                                                autoclose: true
                                                            });

                                                            $('#examStart_Date').datepicker({
                                                                dateFormat: 'yy-mm-dd',
                                                                changeMonth:true,
                                                                changeYear:true,
                                                                autoclose: true,

                                                                onSelect:function(dateText){
                                                                    $("#examEnd_Date").datepicker('option','minDate',dateText);
                                                                }
                                                            });

                                                            $('#examEnd_Date').datepicker({
                                                                dateFormat: 'yy-mm-dd',
                                                                changeMonth:true,
                                                                changeYear:true,
                                                                autoclose: true
                                                            });
                                                        });
                                                    </script>

                                                    <div class="form-group">
                                                        <label for="FirstName">Semester Name</label>
                                                        <select name="semisterID" class="form-control" required>
                                                            <option value="<?php echo $semesterSettingID;?>"><?php echo $semesterName; ?></option>
                                                            <?php
                                                            $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                                            if(!empty($semister)){
                                                                $count = 0; foreach($semister as $sm){ $count++;
                                                                    $semister_name=$sm['semesterName'];
                                                                    $semister_id=$sm['semesterSettingID'];
                                                                    ?>
                                                                    <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">Start Date</label>
                                                        <input type="text" name="startDate" class="form-control" value="<?php  echo $startDate;?>" id="start_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">End Date</label>
                                                        <input type="text" name="endDate" class="form-control" value="<?php  echo $endDate;?>" id="end_date">
                                                    </div></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam Start Date</label>
                                                                <input type="text" name="startExamDate" class="form-control" value="<?php echo $examStartDate;?>" id="examStart_Date">
                                                            </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam End Date</label>
                                                                <input type="text" name="endExamDate" class="form-control" value="<?php echo $examEndDate;?>" id="examEnd_Date">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">E/D of Course Confirmation</label>
                                                        <input type="text" name="endDateRegistration" class="form-control" value="<?php  echo $endDateRegistration;?>" id="registration_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">E/D of Final Exam Marks Uploading</label>
                                                                <input type="text" name="endDateFinalExam" class="form-control" value="<?php  echo $endDatefinalExam;?>" id="final_exam_date">
                                                            </div>
                                                        </div>

                                                        </div>



                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Current Semester?</label>
                                                    <?php
                                                    if($semesterStatus==1) {
                                                        ?>
                                                        <input type="radio" id="status" name="status" value="1"
                                                               checked/>Yes
                                                        <input type="radio" id="status" name="status" value="0"/>No
                                                        <?php
                                                    }else
                                                    {
                                                        ?>
                                                        <input type="radio" id="status" name="status" value="1"/>Yes
                                                        <input type="radio" id="status" name="status" value="0" checked/>No
                                                    <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="hidden" name="id" value="<?php echo $semesterSettingID;?>">
                                            <input type="hidden" name="semisterID" value="<?php echo $semisterID;?>">
                                            <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                            <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
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
                        <h4 class="text-danger">No Date Setting found......</h4>
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
<form name="" id="" role="form" method="post" action="action_semester_setting.php">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Define new Annual Setting</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

    <script type="text/javascript">
        $(document).ready(function () {
            $("#startDatet1").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,

                onSelect:function(dateText){
                    $("#endDatet1").datepicker('option','minDate',dateText);
                }
            });
            $("#endDatet1").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#startDatevt1").datepicker('option','minDate',dateText);
                }
            });


            $("#startDatevt1").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,

                onSelect:function(dateText){
                    $("#endDatevt1").datepicker('option','minDate',dateText);
                    /*$("#registrationDate").datepicker('option','minDate',dateText);*/
                }
            });
            $("#endDatevt1").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#startDatet2").datepicker('option','minDate',dateText);
                    /*$("#examStartDate").datepicker('option','minDate',dateText);*/
                }
            });

            $("#startDatet2").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#endDatet2").datepicker('option','minDate',dateText);
                    /*$("#examStartDate").datepicker('option','minDate',dateText);*/
                }
            });

            $("#endDatet2").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#startDatevt2").datepicker('option','minDate',dateText);
                }
            });

            $("#startDatevt2").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,

                onSelect:function(dateText){
                    $("#endDatevt2").datepicker('option','minDate',dateText);
                }
            });
            $("#endDatevt2").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#examStartDate").datepicker('option','minDate',dateText);
                }
            });



            $('#examStartDate').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#examEndDate").datepicker('option','minDate',dateText);
                }
            });

            $('#examEndDate').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#examStartDatev").datepicker('option','minDate',dateText);
                }
            });

            $('#examStartDatev').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true,
                autoclose: true,
                onSelect:function(dateText){
                    $("#examEndDatev").datepicker('option','minDate',dateText);
                }
            });


            $('#examEndDatev').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true,
                autoclose: true
            });
        });
    </script>


    <div class="row">

        <div class="col-lg-6">
            <div class="form-group">
                <label for="MiddleName">Academic Year</label>
                <select name="academicYearID" class="form-control" required>
                    <?php
                    $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
                    /*$adYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear DESC'));*/
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

        <div class="col-lg-6">
            <div class="form-group">
                <label for="MiddleName">Choose Grading Year</label>
                <select name="gradingYearID" id="gradingYearID" class="form-control" required>
                    <?php
                    $academicYear = $db->getRows('grading_year',array('where'=>array('status'=>1),'order_by'=>'Status ASC'));
                    if(!empty($academicYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($academicYear as $sm){ $count++;
                            $gradingYearID=$sm['gradingYearID'];
                            $gradingYearName=$sm['gradingYearName'];
                            ?>
                            <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYearName;?></option>
                        <?php }
                    }
                    ?>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
<div class="form-group">
<label for="FirstName">Term I Start Date</label>
    <input type="text" name="t1startDate" class="form-control" id="startDatet1">
</div></div>
            <div class="col-lg-6">
<div class="form-group">
 <label for="FirstName">Term I End Date</label>
    <input type="text" name="t1endDate" class="form-control" id="endDatet1">
</div></div></div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term I Vacation Start Date</label>
                <input type="text" name="vt1startDate" class="form-control" id="startDatevt1">
            </div></div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term I Vacation End Date</label>
                <input type="text" name="vt1endDate" class="form-control" id="endDatevt1">
            </div></div></div>



    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term 2 Start Date</label>
                <input type="text" name="t2startDate" class="form-control" id="startDatet2">
            </div></div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term 2 End Date</label>
                <input type="text" name="t2endDate" class="form-control" id="endDatet2">
            </div></div></div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term 2 Vacation Start Date</label>
                <input type="text" name="vt2startDate" class="form-control" id="startDatevt2">
            </div></div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Term 2 Vacation End Date</label>
                <input type="text" name="vt2endDate" class="form-control" id="endDatevt2">
            </div></div></div>


    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Final Exam Start Date</label>
                <input type="text" name="finalStartDate" class="form-control" id="examStartDate">
            </div></div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Final Exam End Date</label>
                <input type="text" name="finalEndDate" class="form-control"  id="examEndDate">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Vacation Start Date</label>
                <input type="text" name="vfinalStartDate" class="form-control" id="examStartDatev">
            </div></div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="FirstName">Vacation End Date</label>
                <input type="text" name="vfinalEndDate" class="form-control"  id="examEndDatev">
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-lg-8">
<div class="form-group">
<label for="email">Is Current Year?</label>
<input type="radio" id="status" name="status" value="1" />Yes
<input type="radio" id="status" name="status" value="0" />No
</div></div>
    </div>

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
    
