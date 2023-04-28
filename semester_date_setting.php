<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
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
                    <div class="row"><br></div>

                    <!-- view data of setting  -->
        
        <div class="row">
                <?php
                        
                        $db = new DBHelper();
                        $users = $db->getRows('semester_setting',array('order_by'=>'academicYearID ASC'));
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
                            if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
                            if($user['semesterStatus']==1)
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
                          <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$user['academicYearID']);?></td>

                          <td><?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$user['gradingYearID']);?></td>
                          <td><?php echo $user['t1startDate']; ?></td>
                          <td><?php echo $user['t1endDate']; ?></td>
                          <td><?php echo $user['vt1startDate']; ?></td>
                          <td><?php echo $user['vt1endDate']; ?></td>

                          <td><?php echo $status;?></td>
                          <td>
                              <!-- <button id="" type="button" class="btn btn-success" data-toggle="message" data-target="#data-message2">
                                  <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                  <span><strong></strong></span>
                              </button> -->
                              <!-- <button id=" type="button" class="btn btn-success" data-toggle="modal" data-target="#message1">
                                  <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                    
                                    </span>
                                    <span><strong></strong></span>
                              </button> -->

                              <a href="#edit_<?php echo $user['semesterSettingID']; ?>" class="btn btn-success btn-sm" data-toggle="modal" >
                            
                              <span class="glyphicon glyphicon-edit" aria-hidden="true">
                                    
                                    </span>
                                    <span><strong></strong></span>
                            </a>
                     </td>
                          </tr>
                         
                        </tbody>


                            <!-- view data of setting  -->
                        <!--view data-->

                        <!--view data-->
                        <div  id="edit_<?php echo $user['semesterSettingID'];?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                            
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <form name="" id="" role="form" method="post" action="action_semester_setting.php">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Update Records</h4>
                                            <!-- update form  -->
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
                                                        <label for="FirstName">Academic Year</label>
                                                        <select name="academicID" class="form-control" >

                                                            <option value="<?php echo $user['academicYearID']; ?>">
                                                            <?php echo $db->getData("academic_year","academicYear","academicYearID",$user['academicYearID']);?>
                                                        </option>
                                                           <option>Please choose academic year</option>
                                                            <?php
                                                            $type = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
                                                            if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
                                                                $academicYear=$t['academicYear'];
                                                                $academicYearID=$t['academicYearID'];
                                                            ?>
                                                            
                                                            <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>


                                                            <?php }}?>
                                                              
                                                        </select>




                                                                                                                    
                                                    </div>


                                                    <div class="form-group">
                                                            <label for="MiddleName">Grading Year</label>
                                                            <select name="gradingYearID" id="gradingYearID" class="form-control" >

                                                            <option value="<?php echo $user['gradingYearID']; ?>">
                                                                <?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$user['gradingYearID']);?>
                                                            </option>

                                                            <option>Please choose Grading year</option>
                                                            <?php
                                                            $type = $db->getRows('grading_year',array('order_by'=>'gradingYearName DESC'));
                                                            if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
                                                                $gradingYearName=$t['gradingYearName'];
                                                                $gradingYearID=$t['gradingYearID'];
                                                            ?>
                                                            <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYearName;?></option>


                                                            <?php }}?>
                                                            </select>
                                                        </div>




                                                        <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                            <label for="FirstName">Term I Start Date</label>
                                                                <input type="text"  name="t1startDate" class="form-control" value="<?php echo $user['t1startDate']; ?>" id="start_date">
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term I End Date</label>
                                                            <input type="text"  name="t1endDate" class="form-control" value="<?php echo $user['t1endDate']; ?>" id="end_date">
                                                        </div>
                                                     </div></div>


                                                     <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                            <label for="FirstName">Term I Vacation Start Date</label>
                                                                <input type="text" name="vt1startDate" class="form-control" id="startDatevt2"value="<?php echo $user['vt1startDate']; ?>" >
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term I Vacation End Date</label>
                                                            <input type="text"  name="vt1endDate" class="form-control" id="endDatevt2" value="<?php echo $user['vt1endDate']; ?>" >
                                                        </div>
                                                     </div></div>

                                                     
                                                    

                                                    
                                                            
                                                    

                                                        <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term 2 Start Date</label>
                                                            <input type="text" name="t2startDate" class="form-control" id="startDatet2" value="<?php echo $user['t2startDate']; ?>">
                                                        </div></div>



                                                       
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term 2 End Date</label>
                                                            <input type="text" name="t2endDate" class="form-control" id="endDatet2" value="<?php echo $user['t2endDate']; ?>">
                                                        </div></div></div>


                                                        
                                                        <div class="row">
                                                
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term 2 Vacation Start Date</label>
                                                            <input type="text" name="vt2startDate" class="form-control" id="startDatevt2" value="<?php echo $user['vt2startDate']; ?>">
                                                        </div></div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Term 2 Vacation End Date</label>
                                                            <input type="text" name="vt2endDate" class="form-control" id="endDatevt2" value="<?php echo $user['vt2endDate']; ?>">
                                                        </div></div></div>

                                                    <div class="row">
                                                
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="FirstName">Final Exam Start Date</label>
                                                            <input type="text" name="finalStartDate" class="form-control" id="examStartDate" value="<?php echo $user['examStartDate']; ?>">
                                                        </div></div>

                                                        <div class="col-lg-6">
                                                    
                                                        <div class="form-group">
                                                            <label for="FirstName">Final Exam End Date</label>
                                                            <input type="text" name="finalEndDate" class="form-control"  id="examEndDate" value="<?php echo $user['examEndDate']; ?>">
                                                        </div>
                                                        </div></div>


                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="FirstName">Vacation Start Date</label>
                                                                    <input type="text" name="vfinalStartDate" class="form-control" id="examStartDatev" value="<?php echo $user['vestartDate']; ?>">
                                                                </div></div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="FirstName">Vacation End Date</label>
                                                                    <input type="text" name="vfinalEndDate" class="form-control"  id="examEndDatev" value="<?php echo $user['veendDate']; ?>">
                                                                </div>
                                                            </div>

                                                        </div>

                                                   

                                                </div>

                                                



                                               
                                                <div class="form-group">
                                                    <label for="email">Current Semester?</label>
                                                        <input type="radio" id="status" name="status" value="1"/>Yes
                                                        <input type="radio" id="status" name="status" value="0" checked/>No
                                                        <input type="hidden" name="id" value="<?php echo $user['semesterSettingID'];?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="hidden" name="id" value="<?php echo $user['semesterSettingID'];?>"/>
                                            <input type="hidden" name="semisterID" value="<?php echo $_GET['semesterSettingID'];?>">
                                            <input type="hidden" name="academicYearID" value="<?php echo $user['academicYearID'];?>">
                                            <input type="hidden" name="batchID" value="">
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                        </div>
                                    </div>
                                        
                                    </form>
                                    <!-- update form end -->
                                </div>
                                

                            </div>
                        </div>
                        <?php } }?>          
                        <!--view data-->

                        <!--view data-->
                        <div id="#data-message2" class="modal fade" role="dialog">
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
                                                            <option value="2"></option>
                                                            FieldAdmin                        </select>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">Start Date</label>
                                                        <input type="text" name="startDate" class="form-control" value="" id="start_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">End Date</label>
                                                        <input type="text" name="endDate" class="form-control" value="" id="end_date">
                                                    </div></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam Start Date</label>
                                                                <input type="text" name="startExamDate" class="form-control" value="" id="examStart_Date">
                                                            </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam End Date</label>
                                                                <input type="text" name="endExamDate" class="form-control" value="" id="examEnd_Date">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">E/D of Course Confirmation</label>
                                                        <input type="text" name="endDateRegistration" class="form-control" value="" id="registration_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">E/D of Final Exam Marks Uploading</label>
                                                                <input type="text" name="endDateFinalExam" class="form-control" value="" id="final_exam_date">
                                                            </div>
                                                        </div>

                                                        </div>



                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Current Semester?</label>
                                                                                                            <input type="radio" id="status" name="status" value="1"/>Yes
                                                        <input type="radio" id="status" name="status" value="0" checked/>No
                                                                                                    </div>

                                            </div></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="hidden" name="id" value="2">
                                            <input type="hidden" name="semisterID" value="">
                                            <input type="hidden" name="academicYearID" value="17">
                                            <input type="hidden" name="batchID" value="">
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                                            
                        <!--view data-->

                        <!--view data-->
                        <div id="message3" class="modal fade" role="dialog">
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
                                                            <option value="3"></option>
                                                                                                                    </select>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">Start Date</label>
                                                        <input type="text" name="startDate" class="form-control" value="" id="start_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">End Date</label>
                                                        <input type="text" name="endDate" class="form-control" value="" id="end_date">
                                                    </div></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam Start Date</label>
                                                                <input type="text" name="startExamDate" class="form-control" value="" id="examStart_Date">
                                                            </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">Exam End Date</label>
                                                                <input type="text" name="endExamDate" class="form-control" value="" id="examEnd_Date">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label for="FirstName">E/D of Course Confirmation</label>
                                                        <input type="text" name="endDateRegistration" class="form-control" value="" id="registration_date">
                                                    </div></div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="FirstName">E/D of Final Exam Marks Uploading</label>
                                                                <input type="text" name="endDateFinalExam" class="form-control" value="" id="final_exam_date">
                                                            </div>
                                                        </div>

                                                        </div>



                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Current Semester?</label>
                                                                                                            <input type="radio" id="status" name="status" value="1"/>Yes
                                                        <input type="radio" id="status" name="status" value="0" checked/>No
                                                                                                    </div>

                                            </div></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="hidden" name="id" value="3">
                                            <input type="hidden" name="semisterID" value="">
                                            <input type="hidden" name="academicYearID" value="18">
                                            <input type="hidden" name="batchID" value="">
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                                            </tbody>
                    </table>
                    
                                       
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
                                   <label for="FirstName">Academic Year</label>
                                 <select name="academicID" class="form-control" >

                                 <option value="<?php echo $user['academicYearID']; ?>">
                                     <?php echo $db->getData("academic_year","academicYear","academicYearID",$user['academicYearID']);?>
                                          </option>
                                       <option>Please choose academic year</option>
                                                            <?php
                                                            $type = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
                                                            if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
                                                                $academicYear=$t['academicYear'];
                                                                $academicYearID=$t['academicYearID'];
                                                            ?>
                                                            
                                                            <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>


                                                            <?php }}?>
                                                              
                                                        </select>




                                                                                                                    
                                                    </div>
        </div>

                            <div class="col-lg-6">
                            <div class="form-group">
                                                            <label for="MiddleName">Grading Year</label>
                                                            <select name="gradingYearID" id="gradingYearID" class="form-control" >

                                                           
                                                            <option>Please choose Grading year</option>
                                                            <?php
                                                            $type = $db->getRows('grading_year',array('order_by'=>'gradingYearName DESC'));
                                                            if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
                                                                $gradingYearName=$t['gradingYearName'];
                                                                $gradingYearID=$t['gradingYearID'];
                                                            ?>
                                                            <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYearName;?></option>


                                                            <?php }}?>
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
