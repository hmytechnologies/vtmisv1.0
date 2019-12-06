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
        <h1>Student Academic Reports</h1>
        <hr>
        <ul class="nav nav-tabs" id="myTab">

            <li class="active"><a data-toggle="tab" href="#semester_report"><span style="font-size: 16px"><strong>Semester Report</strong></span></a></li>
            <!--        <li><a data-toggle="tab" href="#progress_report"><span style="font-size: 16px"><strong>Progress Report</strong></span></a></li>
            -->        <li><a data-toggle="tab" href="#transcripts"><span style="font-size: 16px"><strong>Transcripts</strong></span></a></li>

        </ul>

        <div class="tab-content">
            <!-- Current Semester -->
            <div id="semester_report" class="tab-pane fade in active">
                <!-- Start -->
                <div class="form-group">
                    <form name="" method="post" action="">
                        <h3>Search student to manage his/her results</h3>
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
                <br>
                <hr>
                <div class="row">

                    <?php
                    $db=new DBhelper();
                    if(((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")))
                    {
                        $searchStudent=$_POST['search_student'];
                        $searchStudent=$_REQUEST['search_student'];

                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
                        ?>

                        <?php
                        if(!empty($studentID))
                        {

                            ?>
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Personal Information</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Reg.No</th>
                                            <th>Gender</th>
                                            <th>Level</th>
                                            <th>Programme Name</th>
                                            <!--  <th>Programme Duration</th> -->
                                            <th>Study Year</th>
                                            <th>Study Mode</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
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


                                            $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'studyYearStatus'=>1),' order_by'=>'studentID ASC'));
                                            if(!empty($study_year))
                                            {
                                                foreach ($study_year as $sy)
                                                {
                                                    $studyYear=$sy['studyYear'];
                                                }
                                            }
                                            echo $studyYear."</td><td>";

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
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>


                            <div class="row">

                                <?php
                                $semester=$db->getSemester($regNumber);
                                if(!empty($semester))
                                {
                                    ?>
                                    <div class="col-md-9">
                                        <?php
                                        $totalPoints=0;
                                        $totalUnits=0;
                                        foreach($semester as $sm)
                                        {
                                            $semesterSettingID=$sm['semesterSettingID'];
                                            $semesterName=$sm['semesterName'];
                                            $course = $db->getStudentSearchResult($regNumber,$semesterSettingID);
                                            if(!empty($course))
                                            {
                                                ?>

                                                <div class="box box-solid box-primary">
                                                    <div class="box-header with-border text-center">
                                                        <h3 class="box-title">Exam Result Information for <?php echo $semesterName;?></h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body table-responsive">
                                                        <table  id="" class="table table-striped table-bordered table-condensed">
                                                            <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Course Code</th>
                                                                <th>Course Name</th>
                                                                <th>Status</th>
                                                                <th>Units</th>
                                                                <th>Total Marks</th>
                                                                <th>Grade</th>
                                                                <th>Remarks</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $count = 0;
                                                            $i=1;
                                                            $tunits=0;
                                                            $tpoints=0;
                                                            foreach($course as $st)
                                                            {
                                                                $count++;
                                                                $courseID=$st['courseID'];
                                                                $courseStatus=$st['courseStatus'];
                                                                $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                                                if(!empty($course))
                                                                {
                                                                    ?>
                                                                    <?php
                                                                    $i=1;
                                                                    foreach ($course as $c)
                                                                    {
                                                                    }
                                                                }
                                                                $courseCode=$c['courseCode'];
                                                                $courseName=$c['courseName'];
                                                                $units=$c['units'];
                                                                $courseTypeID=$c['courseTypeID'];

                                                                if($courseStatus==1)
                                                                    $cStatus="Core";
                                                                else
                                                                    $cStatus="Elective";
                                                                ?>

                                                                <tr>
                                                                    <?php
                                                                    echo"<td>$count</td><td>$courseCode</td><td>$courseName</td><td>$cStatus</td><td>$units</td>";
                                                                    $tunits+=$units;
                                                                    // $totalPoints+=$units;
                                                                    //include("grade.php");
                                                                    // getMarksGrade($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)

                                                                    $cwk=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,1));
                                                                    $sfe=$db->decrypt($db->getFinalGrade($semesterSettingID,$courseID,$regNumber,2));
                                                                    $sup=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,3));
                                                                    $spc=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,4));
                                                                    $prj=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,5));
                                                                    $pt=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,6));

                                                                    $gradeID=$db->getMarksID($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                                                                    $gradePoint=$db->getData("grades","gradePoints","gradeID",$gradeID);
                                                                    $points=$gradePoint*$units;
                                                                    $tpoints+=$points;


                                                                    echo "<td>".$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>
                                <td>".$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>
                                <td>".$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                                                                    $published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);

                                                                    //<td>".$db->calculateGrade($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>
                                                                    ?>
                                                                    <td>
                                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $courseID;?>">
                                                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                                        </button>
                                                                    </td>
                                                                </tr>



                                                                <!-- Result Details -->
                                                                <div id="message<?php echo $courseID;?>" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog">

                                                                        <!-- Modal content-->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Details of <?php echo $courseCode."-".$courseName;?></h4>
                                                                            </div>
                                                                            <form name="register" id="register" enctype="multipart/form-data" method="post" action="action_add_exmption.php">
                                                                                <div class="modal-body">
                                                                                    <div class="row"  style="background-color:lightgray;">
                                                                                        <div class="col-lg-1"><strong>No</strong></div>
                                                                                        <div class="col-lg-3"><strong>Ass.Title</strong></div>
                                                                                        <div class="col-lg-2"><strong>Max.Marks</strong></div>
                                                                                        <div class="col-lg-2"><strong>Present</strong></div>
                                                                                        <div class="col-lg-2"><strong>Scored</strong></div>
                                                                                        <div class="col-lg-2"><strong>Weights</strong></div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-1">1</div>
                                                                                        <div class="col-lg-3">Course Work</div>
                                                                                        <div class="col-lg-2">40</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($cwk>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $cwk;?></div>
                                                                                        <div class="col-lg-2"><?php echo $cwk;?></div>
                                                                                    </div>
                                                                                    <div class="row" style="background-color:lightgray;">
                                                                                        <div class="col-lg-1">2</div>
                                                                                        <div class="col-lg-3">Final Exam</div>
                                                                                        <div class="col-lg-2">60</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($sfe>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $sfe;?></div>
                                                                                        <div class="col-lg-2"><?php echo $sfe;?></div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-lg-1">3</div>
                                                                                        <div class="col-lg-3">Supplementary</div>
                                                                                        <div class="col-lg-2">100</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($sup>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $sup;?></div>
                                                                                        <div class="col-lg-2"><?php echo $sup;?></div>
                                                                                    </div>

                                                                                    <div class="row" style="background-color:lightgray;">
                                                                                        <div class="col-lg-1">4</div>
                                                                                        <div class="col-lg-3">Special Exam</div>
                                                                                        <div class="col-lg-2">60</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($spc>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $spc;?></div>
                                                                                        <div class="col-lg-2"><?php echo $spc;?></div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-lg-1">5</div>
                                                                                        <div class="col-lg-3">Project</div>
                                                                                        <div class="col-lg-2">100</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($prj>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $prj;?></div>
                                                                                        <div class="col-lg-2"><?php echo $prj;?></div>
                                                                                    </div>

                                                                                    <div class="row" style="background-color:lightgray;">
                                                                                        <div class="col-lg-1">6</div>
                                                                                        <div class="col-lg-3">Field Training</div>
                                                                                        <div class="col-lg-2">100</div>
                                                                                        <div class="col-lg-2">
                                                                                            <?php
                                                                                            if($pt>0)
                                                                                                echo "Yes";
                                                                                            else
                                                                                                echo "No";
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-lg-2"><?php echo $pt;?></div>
                                                                                        <div class="col-lg-2"><?php echo $pt;?></div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-lg-10">
                                                                                            <strong><span class="text-danger">Total Marks:</span></strong>
                                                                                        </div>
                                                                                        <div class="col-lg-2">
                                                                                            <strong><span class="text-danger"><?php echo $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);?></span></strong>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <!-- End of Result Details -->
                                                                <?php
                                                            }
                                                            $totalPoints+=$tpoints;
                                                            $totalUnits+=$tunits;
                                                            ?>
                                                            <tr><td colspan="5">Total Credits:</td><td><?php echo $tunits;?></td></tr>
                                                            <tr><td colspan="10" align="center" style="font-size: 20px;"><strong><span class="text-danger">GPA:<?php echo $db->getGPA($tpoints, $tunits);?></span></strong></td></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>



                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <h4 class="text-danger">No Result(s) found......</h4>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="box box-solid box-primary">
                                            <div class="box-header with-border text-center">
                                                <h3 class="box-title">Perfomance</h3>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body">
                                                <table  id="" class="table table-striped table-bordered table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>Total Credits</th>
                                                        <th>Total Points</th>
                                                        <th>Overall GPA</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td style="font-size: 20px;"><strong><span class="text-danger"><?php echo $totalUnits;?></span></strong></td>
                                                        <td style="font-size: 20px;"><strong><span class="text-danger"><?php echo $totalPoints;?></span></strong></td>
                                                        <td style="font-size: 20px;"><strong><span class="text-danger"><?php echo $db->getGPA($totalPoints,$totalUnits);?></span></strong></td>
                                                        <?php $gpa=$db->getGPA($totalPoints,$totalUnits);?>
                                                        <td style="font-size:12px;"><strong><span class="text-danger"><?php echo $db->getGPARemarks($regNumber,$gpa);?></span></strong></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                else
                                {
                                    echo "<h3 class='text-danger'>No Result Found</h3>";
                                }
                                ?>
                            </div>
                            <?php
                        }
                        else
                        {
                            echo "<h3 class='text-danger'>No Student Found with Reg.Number: ".$searchStudent."</h3>";
                        }
                    }
                    ?>

                </div>
                <!-- End -->
            </div>
            <!-- End of Current Semester -->

            <!-- Previous Semester -->
            <div id="progress_report" class="tab-pane fade">
                <!-- Start -->
                <div class="form-group">
                    <form name="" method="post" action="">
                        <h3>Search student to view his/her statement of results</h3>
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
                <br>
                <hr>
                <div class="row">

                    <?php
                    $db=new DBhelper();
                    if(((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")))
                    {
                        $searchStudent=$_POST['search_student'];
                        $searchStudent=$_REQUEST['search_student'];

                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
                        ?>

                        <?php
                        if(!empty($studentID))
                        {

                            ?>
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Personal Information</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Reg.No</th>
                                            <th>Gender</th>
                                            <th>Level</th>
                                            <th>Programme Name</th>
                                            <th>Study Year</th>
                                            <th>Study Mode</th>
                                            <th>Status</th>
                                            <th>Preview</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
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

                                            //echo "$programmeDuration</td><td>";


                                            $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'studentID ASC'));
                                            if(!empty($study_year))
                                            {
                                                foreach ($study_year as $sy)
                                                {
                                                    $studyYear=$sy['studyYear'];
                                                }
                                            }
                                            echo $studyYear."</td><td>";

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
                                        ?>
                                        <td><a href=''>Preview</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php
                        }
                        else
                        {
                            echo "<h3 class='text-danger'>No Student Found with Reg.Number: ".$searchStudent."</h3>";
                        }
                    }
                    ?>

                </div>
                <!-- End -->
            </div>

            <!-- End -->

            <div id="transcripts" class="tab-pane fade">
                <!-- Start -->

                <div class="form-group">
                    <form name="" method="post" action="">
                        <h3>Search student to preview his/her Transcript</h3>
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
                <br>
                <hr>
                <div class="row">

                    <?php
                    $db=new DBhelper();
                    if(((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")))
                    {
                        $searchStudent=$_POST['search_student'];
                        $searchStudent=$_REQUEST['search_student'];

                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
                        ?>

                        <?php
                        if(!empty($studentID))
                        {

                            ?>
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Personal Information</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Reg.No</th>
                                            <th>Gender</th>
                                            <th>Level</th>
                                            <th>Programme Name</th>
                                            <th>Study Year</th>
                                            <th>Study Mode</th>
                                            <th>Status</th>
                                            <th>Picture</th>
                                            <th>Preview</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
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

                                            //echo "$programmeDuration</td><td>";


                                            $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'studentID ASC'));
                                            if(!empty($study_year))
                                            {
                                                foreach ($study_year as $sy)
                                                {
                                                    $studyYear=$sy['studyYear'];
                                                }
                                            }
                                            echo $studyYear."</td><td>";

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
                                        ?>
                                        <td>Picture</td>
                                        <td><a href=''>Preview</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php
                        }
                        else
                        {
                            echo "<h3 class='text-danger'>No Student Found with Reg.Number: ".$searchStudent."</h3>";
                        }
                    }
                    ?>

                </div>

                <div class="row">
                    <div id="example1">

                    </div>
                </div>

                <!-- End -->
            </div>

        </div>

    </div></div>
