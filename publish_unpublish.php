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
<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#academicYearID").change(function()
        {
            var academicYearID=$(this).val();
            // var semisterID=$("#semisterID").val();

            // var dataString = 'academicYearID='+ academicYearID+'&semisterID='+semisterID;
            var dataString = 'academicYearID='+ academicYearID;

            $.ajax
            ({
                type: "POST",
                url: "ajax_student_course.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#courseID").html(html);
                }
            });

        });

    });
</script>

<?php $db=new DBHelper();?>
<div class="container">
    <div class="content">
        <h1>Results Publishing</h1>
        <hr>
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>By Academic Year</strong></span></a></li>
            <li><a data-toggle="tab" href="#previous"><span style="font-size: 16px"><strong>By Trade Name </strong></span></a></li>
        </ul>
        <div class="tab-content">
            <!-- Current Semester -->
            <div id="currentdata" class="tab-pane fade in active">
                <div class="row">
                    <form name="" method="post" action="">
                        <div class="col-lg-12">
                            <div class="row">

                                <div class="col-lg-3">
                                    <label for="MiddleName">Academic Year</label>
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
                                <div class="col-lg-3">
                                    <label for=""></label>
                                    <input type="submit" name="doSearch" value="View List" class="btn btn-primary form-control" /></div>
                                <div class="col-lg-9"></div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">

                    <?php
                    if(isset($_POST['doSearch'])=="View List") {
                        // $semesterSettingID = $_POST['semesterID'];

                        $academicYearID = $_POST['academicYearID'];

                        $courseprogramme = $db->getSemesterPublishCourse1($academicYearID);
                        if (!empty($courseprogramme)) {
                            ?>
                            <h3 id="titleheader">Registered Course
                                for <?php 


                                // $semesterSettingID = $db->getData("semester_setting", "semesterSettingID", "academicYearID", $academicYearID);
                                echo $academicYearName= $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h3>
                            <hr>

                            <form name="register" id="register" method="post" action="action_publish.php">
                                <table id="exampleexampleexample" class="display nowrap">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                        <th>Program </th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                        <th>#Students</th>
                                        <th>Study Year</th>
                                        <th>Lecturer</th>
                                        <th>Published</th>
                                        <th>Checked</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($courseprogramme as $cs) {
                                        $count++;
                                        $courseID = $cs['courseID'];
                                        $centerID = $cs['centerID'];
                                        $programmeLevelID = $cs['programmeLevelID'];
                                        $staffID = $cs['staffID'];
                                        $progID = $cs['programmeID'];
                                        // $batchID = $cs['batchID'];
                                        // $studyYear = $cs['studyYear'];
                                        $course = $db->getRows("course", array('where' => array('courseID' => $courseID), ' order_by' => 'courseName ASC'));
                                        if (!empty($course)) {
                                            $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
                                            if (!empty($course)) {
                                                foreach ($course as $c) {
                                                    $courseCode = $c['courseCode'];
                                                    $courseName = $c['courseName'];
                                                    $courseTypeID = $c['courseTypeID'];
                                                }
                                            }



                                         $programme = $db->getRows('programmes', array('where' => array('programmeID' => $progID), 'order_by' => 'programmeID ASC'));
                                            if (!empty($programme)) {
                                                foreach ($programme as $pro) {
                                                    $programmeName = $pro['programmeName'];
                                                  
                                                }
                                            }
                                            // $instructor = $db->getRows('center_programme_course', array('where' => array('staffID' => $staffID, 'courseID' => $courseID, 'academicYearID' => $academicYearID,), 'order_by' => 'courseID ASC'));
                                           
                                            $instructor = $db->getRows('instructor', array('where' => array('instructorID' => $staffID), 'order_by' => 'instructorID ASC'));
                                            if (!empty($instructor)) {
                                                foreach ($instructor as $i) {
                                                    $instructorID = $i['instructorID'];
                                                    $instructorName = $i['instructorName'];
                                                    // $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                                }
                                            } else {
                                                $instructorName = "Not assigned";
                                            }

                                            // public function getStudentCourseSum($centerID, $academicYearID, $programmeLevelID, $progID)

                                            $studentNumber = $db->getStudentCourseSum($centerID, $academicYearID, $programmeLevelID, $progID);

                                            $checked=$db->checkStatus($courseID,$academicYearID,'checked');
                                            $published=$db->checkStatus($courseID,$academicYearID,'status');

                                            $boolExamStatus = $db->checkFinalResultStatus1($courseID, $academicYearID);

                                            if ($published == 1)
                                                $statusPublished = "<span class='label label-success'>Yes</span>";
                                            else
                                                $statusPublished = "<span class='label label-danger'>No</span>";

                                            if($checked == 1)
                                                $statusChecked = "<span class='label label-success'>Yes</span>";
                                            else
                                                $statusChecked = "<span class='label label-danger'>No</span>";

                                            ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <?php
                                                if ($boolExamStatus == false) {
                                                    ?>
                                                    <td>NA</td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td><input type='checkbox' class='checkbox_class' name='id[]'
                                                               value='<?php echo $courseID; ?>'></td>
                                                    <?php
                                                } ?> 
                                                <td><?php echo $programmeName; ?></td>
                                                <td><?php echo $courseName; ?></td>
                                                <td><?php echo $courseCode; ?></td>
                                                <td><?php echo $studentNumber; ?></td>
                                                <td><?php echo $academicYearName; ?></td>
                                                <td><?php echo $instructorName; ?></td>
                                                <td><?php echo $statusPublished; ?></td>
                                                <td><?php echo $statusChecked;?></td>
                                            </tr>
                                            <?php

                                        }
                                    }

                                    ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <!--            <div class="col-lg-6"></div>
                                    -->
                                    <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                                    <input type="hidden" name="academicYearID" value="<?php echo $academicYearID ; ?>">
                                    
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="check"/>
                                        <input type="submit" name="doCheck" value="Check"
                                               class="btn btn-success form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="uncheck"/>
                                        <input type="submit" name="doUncheck" value="UnCheck"
                                               class="btn btn-danger form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="add"/>
                                        <input type="submit" name="doAdmit" value="Publish"
                                               class="btn btn-success form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="edit"/>
                                        <input type="submit" name="doReject" value="UnPublish"
                                               class="btn btn-danger form-control">
                                    </div>
                                </div>
                            </form>
                            <?php
                        } else {
                            echo "<h3 class='text-danger'>No Course Found</h3>";
                        }
                    }

                    ?>

                </div>
            </div>
            <div id="previous" class="tab-pane fade">
                <div class="row">
            <form name="" method="post" action="">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">

                            <label for="MiddleName">Trade Name</label>
                            <select name="programmeID" class="form-control" required>
                                <?php
                                $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                                if(!empty($programmes)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($programmes as $prog){ $count++;
                                        $programme_name=$prog['programmeName'];
                                        $programme_id=$prog['programmeID'];
                                        ?>
                                        <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                                    <?php }}
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="MiddleName">Academic Year</label>
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
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <label for=""></label>
                            <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                        <div class="col-lg-6"></div>

                    </div>
                </div>
            </form>
        </div>
                <div class="row">

            <?php
            if(isset($_POST['doFind'])=="View Records")
            {
                $academicYearID=$_POST['academicYearID'];
                $programmeID=$_POST['programmeID'];
               
                // getSemesterProgrammeCourse($programmeID, $academicYearID)
                $courseprogramme = $db->getSemesterProgrammeCourse1($programmeID, $academicYearID);
                if(!empty($courseprogramme))
                {
                    ?>
                    <h3 id="titleheader">Registered Course for <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                        -<?php echo $academicYearName =$db->getData("academic_year","academicYear","academicYearID", $academicYearID);?></h3>
                    <hr>

                    <form name="register" id="register" method="post" action="action_publish.php">
                        <table  id="exampleexampleexample" class="display nowrap">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                                <th>Program</th>
                                <th>Course Name</th>
                                <th>Course Code</th>
                                <th>#Students</th>
                                
                                <th>Study Year</th>
                                <th>Lecturer</th>
                                <th>Published</th>
                                <th>Checked</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count=0;
                            foreach ($courseprogramme as $cs) {
                                $count++;
                                $courseID = $cs['courseID'];
                                $centerID = $cs['centerID'];
                                $programmeLevelID = $cs['programmeLevelID'];
                                $staffID = $cs['staffID'];
                                $progID = $cs['programmeID'];
                                // $batchID=$cs['batchID'];
                                // $studyYear=$cs['studyYear'];
                                $course=$db->getRows("course",array('where'=>array('courseID'=>$courseID),' order_by'=>'courseName ASC'));
                                if(!empty($course))
                                {
                                    $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
                                    if(!empty($course))
                                    {
                                        foreach($course as $c)
                                        {
                                            $courseCode=$c['courseCode'];
                                            $courseName=$c['courseName'];
                                            $courseTypeID=$c['courseTypeID'];
                                        }
                                    }

                                    $programme = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID), 'order_by' => 'programmeID ASC'));
                                            if (!empty($programme)) {
                                                foreach ($programme as $pro) {
                                                    $programmeName = $pro['programmeName'];
                                                  
                                                }
                                            }
                                    $instructor = $db->getRows('instructor', array('where' => array('instructorID' => $staffID), 'order_by' => 'instructorID ASC'));
                                    
                                    if(!empty($instructor))
                                    {
                                        foreach($instructor as $i)
                                        {
                                            $instructorID=$i['instructorID'];
                                            $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
                                        }
                                    }
                                    else
                                    {
                                        $instructorName="Not assigned";
                                    }

                                    $studentNumber=$db->$db->getStudentCourseSum($centerID,$academicYearID, $programmeLevelID, $progID);

                                    $checked=$db->checkStatus($courseID,$academicYearID,'checked');
                                    $published=$db->checkStatus($courseID,$academicYearID,'status');

                                    $boolExamStatus=$db->checkExamResultStatus($courseID,$academicYearID);

                                    if($published==1)
                                        $statusPublished="<span class='label label-success'>Yes</span>";
                                    else
                                        $statusPublished="<span class='label label-danger'>No</span>";

                                    if($checked == 1)
                                        $statusChecked = "<span class='label label-success'>Yes</span>";
                                    else
                                        $statusChecked = "<span class='label label-danger'>No</span>";


                                    ?>
                                    <tr>
                                        <td><?php echo $count;?></td>
                                        <?php
                                        if($boolExamStatus==false)
                                        {
                                            ?>
                                            <td>NA</td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <td><input type='checkbox' class='checkbox_class' name='id[]' value='<?php echo $courseID;?>'></td>
                                            <?php
                                        }?>
                                        <td><?php echo $programmeName; ?></td>
                                        <td><?php echo $courseName;?></td>
                                        <td><?php echo $courseCode;?></td>
                                        <td><?php echo $studentNumber;?></td>
                                        
                                        <td><?php echo $academicYearName;?></td>
                                        <td><?php echo $instructorName;?></td>
                                        <td><?php echo $statusPublished;?></td>
                                        <td><?php echo $statusChecked;?></td>
                                    </tr>
                                    <?php

                                }
                            }

                            ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <!--            <div class="col-lg-6"></div>
                            -->
                            <input type="hidden" name="number_applicants" value="<?php echo $count;?>">
                            <input type="hidden" name="academicYearID" value="<?php echo $academicYearID ; ?>">
                            
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="check"/>
                                <input type="submit" name="doCheck" value="Check" class="btn btn-success form-control">
                            </div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="uncheck"/>
                                <input type="submit" name="doUncheck" value="UnCheck" class="btn btn-danger form-control">
                            </div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="submit" name="doAdmit" value="Publish" class="btn btn-success form-control">
                            </div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="edit"/>
                                <input type="submit" name="doReject" value="UnPublish" class="btn btn-danger form-control">
                            </div>
                        </div>
                    </form>
                    <?php
                }
                else
                {
                    echo "<h3 class='text-danger'>No Course Found</h3>";
                }


            }
            ?>

        </div>
            </div>
    </div>
</div>