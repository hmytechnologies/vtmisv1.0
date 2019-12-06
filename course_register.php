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
        <h1>Subject Register Management</h1>
        <hr>
        <h3>Manage subject by programme or by individual student</h3>
        <ul class="nav nav-tabs" id="myTab">

            <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Trade Subjects</strong></span></a></li>
            <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>Student Subject</strong></span></a></li>
            <!--<li><a data-toggle="tab" href="#course_info"><span style="font-size: 16px"><strong>Search Subject Info</strong></span></a></li>-->
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
                        <h3>View/Assign Subject In a Semester</h3>
                        <form name="" method="post" action="">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="MiddleName">Trade Name</label>
                                    <select name="programmeID" id="programID" class="form-control chosen-select" required>
                                        <?php
                                        $programmes = $db->getCenterProgrammes($_SESSION['department_session']);
                                        if(!empty($programmes)){
                                            echo"<option value=''>Please Select Here</option>";
                                            $count = 0; foreach($programmes as $prog){ $count++;
                                                $programme_name=$prog['programmeName'];
                                                $programmeID=$prog['programmeID'];
                                                ?>
                                                <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                                            <?php }
                                        }
                                        else
                                        {
                                            echo "<option value=''>No Programme Available</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="FirstName">Level Name</label>
                                    <select name="programme_level_id" class="form-control" required>
                                        <?php
                                        $programmeLevel= $db->getRows('programme_level',array('order_by'=>'programmeLevelID ASC'));
                                        if(!empty($programmeLevel)){
                                            echo"<option value=''>Please Select Here</option>";
                                            $count = 0; foreach($programmeLevel as $plevel){ $count++;
                                                $programmeLevelID=$plevel['programmeLevelID'];
                                                $programmeLevelName=$plevel['programmeLevel'];
                                                ?>
                                                <option value="<?php echo $programmeLevelID;?>"><?php echo $programmeLevelName;?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="FirstName">Academic Year</label>
                                    <select name="academicYearID" class="form-control" required>
                                        <?php
                                        $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
                                        if(!empty($adYear)){
                                            echo"<option value=''>Please Select Here</option>";
                                            $count = 0; foreach($adYear as $year){ $count++;
                                                $academic_year=$year['academicYear'];
                                                $academic_year_id=$year['academicYearID'];
                                                ?>
                                                <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-9"></div>
                                <div class="col-lg-3">
                                    <label for=""></label>
                                    <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                            </div>
                        </form>

                        <div class="row"><br></div>


                        <?php
                        if(!empty($_REQUEST['msg']))
                        {
                            if($_REQUEST['msg']=="succ")
                            {
                                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course data has been inserted successfully</strong>.
            </div>";
                            }
                            else if($_REQUEST['msg']=="deleted") {
                                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course Data has been delete successfully</strong>.
            </div>";
                            }
                        }
                        ?>
                        <div class="row">
                            <?php
                            if(isset($_POST['doFind'])=="Find Records")
                            {
                                $programmeID=$_POST['programmeID'];
                                $programmeLevelID=$_POST['programme_level_id'];
                                $academicYearID=$_POST['academicYearID'];


                                //List of Registered Course
                                $courseprogramme = $db->getRows('center_programme_course',array('where'=>array('centerID'=>$_SESSION['department_session'],'programmeID'=>$programmeID,'academicYearID'=>$academicYearID,'programmeLevelID'=>$programmeLevelID),' order_by'=>' courseID ASC'));
                                if(!empty($courseprogramme))
                                {
                                    ?>
                                    <div class="row">
                                        <h4 class="">
                                            List of Registered Subject
                                            <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                                            - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                                        </h4>
                                    </div>
                            <form name="" method="post" action="action_student_register.php">
                                    <table  id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th><input type="checkbox" id="selecctall"/></th>
                                            <th>Class Number</th>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Subject Status</th>
                                            <th>Instructor Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0; $totalCredits=0;
                                        foreach($courseprogramme as $cs)
                                        {
                                            $count++;
                                            $courseID=$cs['courseID'];
                                            $staffID=$cs['staffID'];

                                            $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                            if(!empty($course))
                                            {
                                                $i=0;
                                                foreach($course as $c)
                                                {
                                                    $i++;
                                                    $totalCredits+=$c['units'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count;?></td>
                                                        <td><input class="checkbox1" type="checkbox" name="course[]" value="<?php echo $courseID;?>"></td>
                                                        <td><?php echo $cs['classNumber'];?></td>
                                                        <td><?php echo $c['courseCode'];?></td>
                                                        <td><?php echo $c['courseName'];?></td>
                                                        <td>3</td>
                                                        <td><?php echo $staffID;?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                <br />
                                <div class="row">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="add_course_register"/>
                                        <input type="hidden" name="number_subject" value="<?php echo $count;?>">
                                        <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                                        <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                        <input type="hidden" name="programmeLevelID" value="<?php echo $programmeLevelID;?>">
                                        <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="reset" value="Cancel" class="btn btn-primary form-control" />
                                    </div>
                                </div>
                            </form>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <h4 class="text-danger">No Subject(s) found Registered for this Program</h4>
                                    <?php
                                }
                                ?>
                                <?php
                            }
                            ?>
                        </div>
                    </div>





            <!-- End of Current Semester -->

            <!-- Previous Semester -->
            <div id="previous" class="tab-pane fade">
                <h4 class="text-info"><b>Register Student Course By Searching Student</b></h4>
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
                    $db=new DBhelper();
                    if((isset($_POST['doSearch'])=="Search Student") || isset($_REQUEST['action'])=="getRecords") {
                        if (isset($_POST['doSearch']) == "Search Student") {
                            $searchStudent = $_POST['search_student'];
                        } else {
                            $searchStudent = $db->my_simple_crypt($_REQUEST['search_student'], 'd');
                        }
                        $studentID = $db->getRows('student', array('where' => array('registrationNumber' => $searchStudent), ' order_by' => ' studentID ASC'));
                        ?>

                        <?php
                        if (!empty($studentID)) {
                            ?>
                            <table class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Reg.Number</th>
                                <th>Gender</th>
                                <th>Admission Year</th>
                                <th>Level</th>
                                <th>Trade Name</th>
                                <th>Student Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            foreach ($studentID as $std) {
                                $count++;
                                $studentID = $std['studentID'];
                                $fname = $std['firstName'];
                                $mname = $std['middleName'];
                                $lname = $std['lastName'];
                                $gender = $std['gender'];
                                $regNumber = $std['registrationNumber'];
                                $statusID = $std['statusID'];
                                $academicYearID=$std['academicYearID'];
                                $name = "$fname $mname $lname";


                                /*$today = date("Y-m-d");
                                $sm = $db->readSemesterSetting($today);
                                foreach ($sm as $s) {
                                    $semisterID = $s['semesterID'];
                                    $academicYearID = $s['academicYearID'];
                                    $semesterName = $s['semesterName'];
                                    $semesterSettingID = $s['semesterSettingID'];
                                }*/


                                echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>" . $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID) . "</td>";

                                $level = $db->getRows('student_programme', array('where' => array('regNumber' => $regNumber, 'currentStatus' => 1), ' order_by' => ' programmeLevelID ASC'));
                                if (!empty($level)) {
                                    foreach ($level as $lvl) {
                                        $programmeLevelID = $lvl['programmeLevelID'];
                                        $programmeID = $lvl['programmeID'];
                                    }
                                }

                                $status = $db->getRows('status', array('where' => array('statusID' => $statusID), ' order_by' => 'status_value ASC'));
                                if (!empty($status)) {
                                    foreach ($status as $st) {
                                        $status_value = $st['statusValue'];
                                    }
                                }
                                echo "<td>" . $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID) . "</td><td>" . $db->getData("programmes", "programmeName", "programmeID", $programmeID) . "</td><td>$status_value</td>";

                                }
                                ?>
                                </tbody>
                                </table>

                                    </div>




                                <?php
                                echo "<h4 class='text-info'>List of Registered Courses</h4>";
                                $tunits = 0;
                                $courseList = $db->getCourseList($regNumber);
                                if (!empty($courseList)) {
                                    ?>
                                    <table class="table table-striped table-bordered table-condensed" id="example"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                            <th>Subject Status</th>
                                            <th>Academic Year</th>
                                            <th>Instructor</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($courseList as $list) {
                                            $count++;
                                            $studentCourseID = $list['studentCourseID'];
                                            $courseID = $list['courseID'];
                                            $academicYearID=$list['academicYearID'];
                                            $courseCode = $list['courseCode'];
                                            $courseName = $list['courseName'];
                                            $courseTypeID = $list['courseTypeID'];

                                            echo "<tr><td>$count</td>";
                                            ?>
                                            <td><?php echo $courseCode; ?></td>
                                            <td><?php echo $courseName; ?></td>
                                            <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                                            <td>32424</td>
                                            <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></td>
                                            <td>Inst</td>
                                            </tr>

                                            <?php
                                        }
                                        ?>

                                        </tbody>

                                    </table>
                                    <?php
                                } else {
                                    echo "<h4 class='text-danger'>No Course Registered for that Student</h4>";
                                }

                                //End of List
                            }
                        else
                            {
                                echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
                            }
                    }
                    ?>
                </div>

            </div>
            <!-- End -->

        </div>

    </div></div>