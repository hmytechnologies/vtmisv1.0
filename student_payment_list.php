<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });


</script>
<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Student Payment Management</h1>
        <hr>
        <h3>Manage payment by programme or by individual student</h3>
        <ul class="nav nav-tabs" id="myTab">

            <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Programme Payments</strong></span></a></li>
            <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>Student Payments Info</strong></span></a></li>
        </ul>

        <div class="tab-content">
            <!-- Current Semester -->
            <div id="currentdata" class="tab-pane fade in active">

                <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
                <script type="text/javascript">
                    $(document).ready(function()
                    {
                        $("#programID").change(function()
                        {
                            var id=$(this).val();
                            var dataString = 'id='+ id;
                            $.ajax
                            ({
                                type: "POST",
                                url: "ajax_studyear.php",
                                data: dataString,
                                cache: false,
                                success: function(html)
                                {
                                    $("#studyYear").html(html);
                                }
                            });

                        });

                    });
                </script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#selecctall').click(function(event) {  //on click
                            if(this.checked) { // check select status
                                $('.checkbox1').each(function() { //loop through each checkbox
                                    this.checked = true;  //select all checkboxes with class "checkbox1"
                                });
                            }else{
                                $('.checkbox1').each(function() { //loop through each checkbox
                                    this.checked = false; //deselect all checkboxes with class "checkbox1"
                                });
                            }
                        });

                    });
                </script>

                <style type="text/css">
                    .bs-example{
                        margin: 10px;
                    }
                </style>
                <div class="row">
                </div>
                <hr>
                <h3>View/Assign Course In a Semester</h3>
                <div class="row">
                    <form name="" method="post" action="">
                        <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
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
                        <div class="col-lg-4">
                            <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control" required>

                                <?php
                                $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                                if(!empty($programmes)){
                                    echo"<option value=''>Please Select Here</option>";
                                    echo "<option value='all'>All Programmes</option>";
                                    $count = 0; foreach($programmes as $prog){ $count++;
                                        $programme_name=$prog['programmeName'];
                                        $programme_id=$prog['programmeID'];
                                        ?>
                                        <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                                    <?php }}
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for=""></label>
                            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                    </form>
                </div>
                <div class="row"><br></div>


                <?php
                if(!empty($_REQUEST['msg']))
                {
                    if($_REQUEST['msg']=="succ")
                    {
                        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Finance fees data has been inserted successfully</strong>.
            </div>";
                    }
                    else if($_REQUEST['msg']=="deleted") {
                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Finance fees data has been delete successfully</strong>.
            </div>";
                    }
                }
                ?>
                <div class="row">
                    <?php
                    if(isset($_POST['doFind'])=="Find Records") {
                        $academicYearID = $_POST['admissionYearID'];
                        $programmeID = $_POST['programmeID'];
                        $student = $db->getStudentPaymentList($programmeID,$academicYearID);
                        if (!empty($student)) {
                            ?>
                            <h4><span class="text-danger" id="titleheader">
                List of Students for <?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?>
                                    <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
                </span></h4>
                            <hr>
                    <form name="register" id="register" method="post" action="action_update_student_fees.php">
                    <table  id="example" class="display nowrap">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Full Name</th>
                                <th>Reg.Number</th>
                                <th>Prog.Code</th>
                                <th>Study Year</th>
                                <th>Study Mode</th>
                                <th>Student Status</th>
                                <th>Sponsor</th>
                                <th>Req.Fees</th>
                                <th>Actual Fee</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            foreach ($student as $st) {
                                $count++;
                                $regNumber=$st['registrationNumber'];
                                $studyYear=$st['studyYear'];
                                $student_list = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                                foreach($student_list as $lst) {
                                    $fname = $lst['firstName'];
                                    $mname = $lst['middleName'];
                                    $lname = $lst['lastName'];
                                    $sponsor=$lst['sponsor'];
                                    $programmeID=$lst['programmeID'];
                                    $name = "$fname $mname $lname";

                                    $progFees=$db->getAllFees($programmeID);
                                    $paidOnce=$db->getOnceFees($programmeID);
                                    if($studyYear==1)
                                        $requiredFees=$progFees;
                                    else
                                        $requiredFees=$progFees-$paidOnce;


                                    $actualFee=$db->getStudentFees($regNumber,$academicYearID,$studyYear);
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><input type='checkbox' class='checkbox_class' name='regNumber[]' value='<?php echo $regNumber;?>'></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $st['registrationNumber']; ?></td>
                                        <td><?php echo $db->getData("programmes","programmeCode","programmeID",$programmeID); ?></td>
                                        <td><?php echo $studyYear;?></td>
                                        <td><?php echo $db->getData("batch", "batchName", "batchID", $lst['batchID']); ?></td>
                                        <td><?php echo $db->getData("status", "statusValue", "statusID", $lst['statusID']); ?></td>
                                        <td><?php echo $db->getData("sponsor_type", "sponsorCode", "sponsorTypeID", $sponsor);?></td>
                                        <td><?php echo number_format($requiredFees,2);?></td>
                                        <td><?php echo number_format($actualFee,2);?></td>
                                        <td>
                                            <a href="index3.php?sp=view_student_payment&id=<?php echo $db->my_simple_crypt($regNumber, 'e'); ?>"
                                               class="glyphicon glyphicon-eye-open">
                                            </a></td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>

                            <?php
                        } else {
                            ?>
                            <h4><span class="text-danger">No Student(s) found......</span></h4>
                            <?php
                        }
                        ?>
                        </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <input type="hidden" name="number_applicants" value="<?php echo $count;?>">
                         <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                             <input type="text" hidden name="academicYearID" value="<?php echo $academicYearID;?>">
                             <input type="submit" name="doAdmit" value="Update Fees" class="btn btn-success form-control">
                            </div>
                        </div>
                        <?php
                    }
        ?>
                </div>
            </div>





            <!-- End of Current Semester -->

            <!-- Previous Semester -->
            <div id="previous" class="tab-pane fade">
                <h4 class="text-info"><b>Register Student Course By Searching Student</h4>
                <div class="form-group">
                    <form name="" method="post" action="">
                        <div class="col-xs-12">
                            <label class="col-xs-3 control-label"> Enter Student Reg.Number:</label>
                            <div class="col-xs-4">
                                <input type="text" name="search_student" id="search_text" class="form-control">
                            </div>
                            <div class="col-xs-4"><input  type="submit" class="btn btn-success" name="doSearch" value="Search Student"/>
                            </div>
                        </div>
                    </form>
                </div>
                <br><br>
                <div class="row">
                    <?php
                    /*$db=new DBhelper();
                    if((isset($_POST['doSearch'])=="Search Student") || isset($_REQUEST['action'])=="getRecords")
                    {
                        $searchStudent=$_POST['search_student'];
                        $searchStudent=$_REQUEST['search_student'];

                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
                        */?><!--

                        <?php
/*                        if(!empty($studentID))
                        {
                            */?>
                            <table class="table table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Student Name Code</th>
                                    <th>Reg.Number</th>
                                    <th>Gender</th>
                                    <th>Level</th>
                                    <th>Programme Name</th>
                                    <th>Programme Duration</th>
                                    <!-- <th>Study Year</th>
                                    <th>Study Mode</th>
                                    <th>Student Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
/*                                $count = 0;
                                foreach($studentID as $std)
                                {
                                    $count++;
                                    $studentID=$std['studentID'];
                                    $fname=$std['firstName'];
                                    $mname=$std['middleName'];
                                    $lname=$std['lastName'];
                                    $gender=$std['gender'];
                                    $regNumber=$std['registrationNumber'];
                                    $programmeID=$std['programmeID'];
                                    $statusID=$std['statusID'];
                                    $batchID=$std['batchID'];
                                    $name="$fname $mname $lname";


                                    $today=date("Y-m-d");
                                    $sm=$db->readSemesterSetting($today);
                                    foreach ($sm as $s)
                                    {
                                        $semisterID=$s['semesterID'];
                                        $academicYearID=$s['academicYearID'];
                                        $semesterName=$s['semesterName'];
                                        $semesterSettingID=$s['semesterSettingID'];
                                    }


                                    echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>";

                                    $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
                                    $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
                                    if(!empty($level))
                                    {
                                        foreach ($level as $lvl) {
                                            $programme_level_code=$lvl['programmeLevelCode'];
                                            echo "$programme_level_code</td><td>";
                                        }
                                    }

                                    $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
                                    if(!empty($programme))
                                    {
                                        foreach ($programme as $pro) {
                                            $programmeName=$pro['programmeName'];
                                            $programmeDuration=$pro['programmeDuration'];
                                            echo "$programmeName</td><td>";
                                        }
                                    }

                                    echo "$programmeDuration</td>";


                                    /*$study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
                                    if(!empty($study_year))
                                    {
                                        foreach ($study_year as $sy)
                                        {
                                            $studyYear=$sy['studyYear'];
                                        }
                                    }
                                    else
                                    {
                                        $studyYear="";
                                    }
                                    echo "<td>".$studyYear."</td>echo"<td>";

                                    echo $db->getData("batch","batchName","batchID",$batchID)."</td><td>";
                                    $status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
                                    if(!empty($status))
                                    {
                                        foreach ($status as $st) {
                                            $status_value=$st['statusValue'];
                                            echo "$status_value</td>";
                                        }
                                    }

                                }
                                */?>
                                <!--<td><a href='index3.php?sp=studentregister&action=getDatails&studentID=<?php /*echo $studentID;?>'>Details</a></td></tr>
                                </tbody>
                            </table>
                            <!--<div class="row">
                                <div class="col-md-12">
                                <div class="pull-right">
                                                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Register New Course</button>
                                            </div>
                                 </div>
                                </div>-->
                            <hr>
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <h4 class="text-primary">Add New Course</h4>
                                </div>
                            </div>
                            <form name="" method="post" action="action_student_register.php">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="MiddleName">Course Name</label>
                                        <select name="courseID" class="form-control" required>
                                            <?php
/*                                            $course = $db->getRows('course',array('where'=>array('status'=>1),'order_by'=>'courseName ASC'));
                                            if(!empty($course)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($course as $c){ $count++;
                                                    $course_name=$c['courseName'];
                                                    $course_code=$c['courseCode'];
                                                    $course_id=$c['courseID'];
                                                    ?>
                                                    <option value="<?php echo $course_id;>"><?php /*echo $course_code."-".$course_name;*/?></option>
                                                <?php /*}}
                                            */?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="FirstName">Semester Name</label>
                                        <select name="semesterID" class="form-control" required="">
                                            <?php
/*                                            $semister = $db->getRows('semester_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'semesterName ASC'));
                                            if(!empty($semister)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($semister as $sm){ $count++;
                                                    $semister_name=$sm['semesterName'];
                                                    $semister_id=$sm['semesterSettingID'];
                                                    */?>
                                                    <option value="<?php /*echo $semister_id;*/?>"><?php /*echo $semister_name;*/?></option>
                                                <?php /*}}

                                            */?>
                                        </select>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="FirstName">Course Status</label>
                                        <select name="courseStatusID" class="form-control" required="">
                                            <?php
/*                                            $course_status = $db->getRows('coursestatus',array('order_by'=>'courseStatus ASC'));
                                            if(!empty($course_status)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($course_status as $cstatus){ $count++;
                                                    $courseStatus=$cstatus['courseStatus'];
                                                    $courseStatusID=$cstatus['courseStatusID'];
                                                    */?>
                                                    <option value="<?php /*echo $courseStatusID;*/?>"><?php /*echo $courseStatus;*/?></option>
                                                <?php /*}}

                                            */?>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="add"/>
                                        <input type="hidden" name="regNumber" value="<?php /*echo $regNumber;*/?>">
                                        <input type="hidden" name="searchStudent" value="<?php /*echo $searchStudent;*/?>">
                                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                                    </div>
                                    <!--<div class="col-lg-3">
                                        <input type="submit" value="Cancel" class="btn btn-primary form-control" />
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <?php
/*                                if(!empty($_REQUEST['msg']))
                                {
                                    if($_REQUEST['msg']=="succ")
                                    {
                                        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course data has been inserted successfully</strong>.
</div>";
                                    }
                                    else if($_REQUEST['msg']=="deleted") {
                                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course Data has been delete successfully</strong>.
</div>";
                                    }

                                    else if($_REQUEST['msg']=="exist") {
                                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Course already Registered</strong>.
</div>";
                                    }
                                    else if($_REQUEST['msg']=="unsucc") {
                                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Something wrong happening, contact System Administrator</strong>.
</div>";
                                    }
                                }
                                */?>
                            </div>

                            <?php
/*                            echo "<h4 class='text-info'>List of Registered Courses</h4>";
                            $courseList = $db->getCourseList($regNumber);
                            if(!empty($courseList))
                            {
                                ?>
                                <table class="table table-striped table-bordered table-condensed" id="example" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Course Units</th>
                                        <th>Course Type</th>
                                        <th>Course Status</th>
                                        <th>Semister Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
/*                                    $count = 0;
                                    foreach($courseList as $list)
                                    {
                                        $count++;
                                        $studentCourseID=$list['studentCourseID'];
                                        $courseID=$list['courseID'];
                                        $semesterSettingID=$list['semesterSettingID'];
                                        $courseStatus=$list['courseStatus'];
                                        $courseCode=$list['courseCode'];
                                        $courseName=$list['courseName'];
                                        $units=$list['units'];
                                        $courseTypeID=$list['courseTypeID'];
                                        if($courseStatus==1)
                                            $status="Core";
                                        else
                                            $status="Option";

                                        echo "<tr><td>$count</td>";

/*                                        $semister= $db->getRows('semester_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),' order_by'=>' semesterName ASC'));
                                        if(!empty($semister))
                                        {
                                            foreach ($semister as $sm) {
                                                $semister_name=$sm['semesterName'];
                                                echo "<td>$semister_name</td>";
                                            }
                                        }
                                        ?>

                                        <td><a href="action_student_register.php?action_type=drop&id=<?php /*echo $studentCourseID;?>&regNumber=<?php echo $searchStudent;?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                                        </tr>

                                        <?php
/*                                    }
                                    ?>

                                    </tbody>
                                </table>
                                --><?php
/*                            }
                            else
                            {
                                echo "<h4 class='text-danger'>No Course Registered for that Student</h4>";
                            }

                            //End of List
                        }
                        else
                        {
                            echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
                        }
                    }*/
                    ?>
                </div>

            </div>
            <!-- End -->
        </div>

    </div></div>