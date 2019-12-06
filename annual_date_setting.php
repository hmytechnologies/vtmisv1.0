

<div class="row">
            <div class="col-lg-12">
                <h2>Annual Date Settings</h2>

                <form name="" id="" role="form" method="post" action="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-lg-6">
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

                            <div class="col-lg-6">
                                <label for="MiddleName"></label>
                                <input type="submit" name="doFilter" value="Filter Records" class="btn btn-primary form-control">
                            </div>

                        </div>
                    </div>
                </form>

                <hr>
                <?php
                    if(isset($_POST['doFilter'])=="Filter Records")
                    {
                        $gradingYearID=$_POST['gradingYearID'];
                ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="text-danger">Define Annual Setting for Academic Year <?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$gradingYearID);?></h4>
                                <hr>
                            </div>
                        </div>
                    <form name="" id="" role="form" method="post" action="action_semester_setting.php">
                    <div class="row">
                    <div class="col-md-6">
                            <script type="text/javascript">
                                /*$(document).ready(function () {
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
                                    });*/

                                   /* $('#registrationDate').datepicker({
                                        dateFormat: 'yy-mm-dd',
                                        changeMonth:true,
                                        changeYear:true,
                                        autoclose: true
                                    });

                                    $('#examStartDate').datepicker({
                                        dateFormat: 'yy-mm-dd',
                                        changeMonth:true,
                                        changeYear:true,
                                        autoclose: true
/!*
                                        onSelect:function(dateText){
                                            $("#examEndDate").datepicker('option','minDate',dateText);
                                        }*!/
                                    });

                                    $('#examEndDate').datepicker({
                                        dateFormat: 'yy-mm-dd',
                                        changeMonth:true,
                                        changeYear:true,
                                        autoclose: true/!*,

                                        onSelect:function(dateText){
                                            $("#finalexamdate").datepicker('option','minDate',dateText);
                                        }*!/
                                    });

                                    $('#finalexamdate').datepicker({
                                        dateFormat: 'yy-mm-dd',
                                        changeMonth:true,
                                        changeYear:true,
                                        autoclose: true
                                    });
                                });*/
                            </script>

                        <?php
                        $gradYear=$db->getRows("grading_year",array('where'=>array('gradingYearID'=>$gradingYearID)));
                        if(!empty($gradYear))
                        {
                            foreach($gradYear as $yr)
                            {
                                $numberOfTerms=$yr['numberOfTerms'];
                            }
                        }
                        for($x=1;$x<=$numberOfTerms;$x++) {
                            ?>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#startDate<?php echo $x;?>").datepicker({
                                    dateFormat:"yy-mm-dd",
                                    changeMonth:true,
                                    changeYear:true
                                });
                                $("#endDate<?php echo $x;?>").datepicker({
                                    dateFormat:"yy-mm-dd",
                                    changeMonth:true,
                                    changeYear:true,
                                    autoclose: true
                                });
                                </script>
                            <span style="font-size: 18px;font-weight: bold">Term<?php echo $x;?></span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Start Date</label>
                                        <input type="text"  name="startDate<?php echo $x;?>" class="form-control" id="startDate<?php echo $x;?>">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">End Date</label>
                                        <input type="text" name="endDate<?php echo $x;?>" class="form-control" id="endDate<?php echo $x;?>">
                                    </div></div>
                            </div>

                            <span style="font-size: 18px;font-weight: bold">Vacation/Mapunziko<?php echo $x;?></span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Start Date</label>
                                        <input type="text" name="vstartDate<?php echo $x;?>" class="form-control" id="vstartDate<?php echo $x;?>">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">End Date</label>
                                        <input type="text" name="vendDate<?php echo $x;?>" class="form-control" id="vendDate<?php echo $x;?>">
                                    </div></div>
                            </div>

                            <?php
                        }
                        ?>
                            <!--<div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Start Date</label>
                                        <input type="text" name="startDate" class="form-control" id="startDate">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">End Date</label>
                                        <input type="text" name="endDate" class="form-control" id="endDate">
                                    </div></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Exam Start Date</label>
                                        <input type="text" name="startExamDate" class="form-control" id="examStartDate">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">Exam End Date</label>
                                        <input type="text" name="endExamDate" class="form-control" id="examEndDate">
                                    </div></div></div>-->
                    <hr>
                        <span style="font-size: 18px;font-weight: bold">Final Exam</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">E/D of Course Confirmation</label>
                                        <input type="text" name="endDateRegistration" class="form-control" id="registrationDate">
                                    </div></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="FirstName">E/D of Final Exam Marks Uploading</label>
                                        <input type="text" name="endDateFinalExam" class="form-control"  id="finalexamdate">
                                    </div>
                                </div>

                            </div>

                        <span style="font-size: 18px;font-weight: bold">Mapunziko ya Mwisho Mwaka</span>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="FirstName">E/D of Course Confirmation</label>
                                    <input type="text" name="endDateRegistration" class="form-control" id="registrationDate">
                                </div></div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="FirstName">E/D of Final Exam Marks Uploading</label>
                                    <input type="text" name="endDateFinalExam" class="form-control"  id="finalexamdate">
                                </div>
                            </div>

                        </div>


                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="email">Current Year?</label>
                                    <input type="radio" id="status" name="status" value="1" />Yes
                                    <input type="radio" id="status" name="status" value="0" />No
                                </div></div>
                        </div>


                <div class="row">
                    <div class="col-lg-4">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                    </div>
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-default form-control" data-dismiss="modal">Cancel</button>
                    </div></div>
            </form>
                <?php
                }
                ?>
            </div>
        </div>
