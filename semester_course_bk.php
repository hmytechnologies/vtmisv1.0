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
<?php $db=new DBHelper();?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Semester Course Assignment</h1>
        </div>
        <div class="col-md-4">
            <div class="pull-right">
                <a href="index3.php?sp=semester_setting_hod" class="btn btn-warning">Back to Semester Settings</a>
            </div>
        </div></div>
    <hr>
    <div class="content">
        <h3>View/Assign Course In a Semester</h3>
        <form name="" method="post" action="">
            <div class="row">
                <div class="col-lg-3">
                    <label for="MiddleName">Programme Name</label>
                    <select name="programmeID" id="programID" class="form-control chosen-select" required>
                        <?php
                        if($_SESSION['role_session']==9) {
                            $programmes = $db->getRows('programmes', array('where'=>array('schoolID'=>$_SESSION['department_session']),'order_by' => 'programmeName ASC'));
                        }else
                        {
                            // $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                            $programmes = $db->getRows('programmes',array('where'=>array('departmentID'=>$_SESSION['department_session']),'order_by'=>'programmeName ASC'));
                        }

                        if(!empty($programmes)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeName'];
                                $programmeID=$prog['programmeID'];
                                ?>
                                <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                            <?php }}
                        ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="FirstName">Study Year</label>
                    <select name="studyYear" id="studyYear" class="form-control" required>
                        <option selected="selected">--Select Study Year--</option>


                    </select>
                </div>
                <div class="col-lg-3">
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

                <div class="col-lg-3">
                    <label for="FirstName">Study Mode</label>
                    <select name=batchID id="batchID" class="form-control" required>
                        <?php
                        $batch = $db->getRows('batch',array('order_by'=>'batchID DESC'));
                        if(!empty($batch)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($batch as $sm){ $count++;
                                $batchName=$sm['batchName'];
                                $batchID=$sm['batchID'];
                                ?>
                                <option value="<?php echo $batchID;?>" selected><?php echo $batchName;?></option>
                            <?php }}

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
            if(isset($_POST['doFind'])=="Find Records" ||(isset($_REQUEST['action'])=="getRecords"))
            {
                if(isset($_POST['doFind'])=="Find Records") {
                    $programmeID = $_POST['programmeID'];
                    $studyYear = $_POST['studyYear'];
                    $semisterID = $_POST['semisterID'];
                    $batchID = $_POST['batchID'];
                }
                else {
                    $programmeID = $_REQUEST['programmeID'];
                    $studyYear = $_REQUEST['studyYear'];
                    $semisterID = $_REQUEST['semisterID'];
                    $batchID = $_REQUEST['batchID'];
                }

                if($studyYear==1)
                    $sYear="First";
                else if($studyYear==2)
                    $sYear="Second";
                else if($studyYear==3)
                    $sYear="Third";
                else if($studyYear==4)
                    $sYear="Fourth";
                else if($studyYear==5)
                    $sYear="Fifth";


                //$course = $db->getRows('programmemaping',array('where'=>array('programmeID'=>$programmeID,'studyYear'=>$studyYear,'semesterID'=>$semisterID),' order_by'=>' courseID ASC'));

                $course=$db->getCourseProgramme($programmeID,$batchID,$studyYear,$semisterID);

                if(!empty($course))
                {
                    ?>
                    <div class="row">
                        <h4 class="">

                            Assign Course for <?php echo $sYear;?> year <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                            - <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semisterID);?>
                            -<?php echo $db->getData("batch","batchName","batchID",$batchID);?>
                        </h4>
                    </div>
                    <form name="" method="post" action="action_semister_course.php">
                        <table  id="exampleexample" class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th><input type="checkbox" id="selecctall"/></th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Study Year</th>
                                <th>Semester</th>
                                <th>Course Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0; $totalCredits=0;
                            foreach($course as $cs)
                            {
                                $count++;
                                $courseID=$cs['courseID'];
                                $studyYearID=$cs['studyYear'];
                                $semesterID=$cs['semesterID'];

                                if($studyYearID==1)
                                    $stYear="First";
                                else if($studyYearID==2)
                                    $stYear="Second";
                                else if($studyYearID==3)
                                    $stYear="Third";
                                else if($studyYearID==4)
                                    $stYear="Fourth";
                                else if($studyYearID==5)
                                    $stYear="Fifth";

                                /* $courseStatus=$cs['courseStatusID'];
                                if($courseStatus==1)
                                  $status="Core";
                                 else
                                   $status="Option";*/
                                $courseStatus = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseID ASC'));
                                foreach($courseStatus as $cs)
                                {
                                    $courseStatus=$cs['courseStatusID'];

                                }
                                if($courseStatus==1)
                                    $status="Core";
                                else
                                    $status="Elective";

                                $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                if(!empty($course))
                                {
                                    $i=0;
                                    foreach($course as $c)
                                    {
                                        $i++;

                                        $semister = $db->getRows('semister',array('where'=>array('semisterID'=>$semesterID),' order_by'=>'semesterName ASC'));
                                        if(!empty($semister)){

                                            foreach($semister as $sm){
                                                $semister_Name=$sm['semisterName'];
                                                $semister_ID=$sm['semisterID'];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td><input class="checkbox1" type="checkbox" name="course[]" value="<?php echo $courseID;?>"></td>
                                            <td><?php echo $c['courseCode'];?></td>
                                            <td><?php echo $c['courseName'];?></td>
                                            <td><?php echo $c['units'];?></td>
                                            <td><?php echo $stYear;?></td>
                                            <td><?php echo $semister_Name;?></td>
                                            <td><?php echo $status;?></td>
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
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="hidden" name="number_subject" value="<?php echo $count;?>">
                                <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
                                <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                                <input type="hidden" name="semisterID" value="<?php echo $semisterID;?>">
                                <input type="hidden" name="studyYear" value="<?php echo $studyYear;?>">
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
                    <h4 class="text-danger">No Course(s) found for assignment......</h4>
                    <?php
                }
                ?>
                <?php
                //List of Registered Course
                $courseprogramme = $db->getRows('courseprogramme',array('where'=>array('programmeID'=>$programmeID,'semesterSettingID'=>$semisterID,'batchID'=>$batchID),' order_by'=>' courseID ASC'));
                if(!empty($courseprogramme))
                {
                    ?>
                    <div class="row">
                        <h4 class="">
                            List of Registered Course <!--for <?php /*echo $sYear;*/?> year -->
                            <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                            - <?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semisterID);?>
                            -<?php echo $db->getData("batch","batchName","batchID",$batchID);?>


                        </h4>
                    </div>

                    <table  id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Course Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0; $totalCredits=0;
                        foreach($courseprogramme as $cs)
                        {
                            $count++;
                            $courseID=$cs['courseID'];
                            $courseProgrammeID=$cs['courseProgrammeID'];
                            $courseStatus=$cs['courseStatus'];
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

                                        <td><?php echo $c['courseCode'];?></td>
                                        <td><?php echo $c['courseName'];?></td>
                                        <td><?php echo $c['units'];?></td>
                                        <?php
                                        /* $courseStatus = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseID ASC'));
                                           foreach($courseStatus as $cs)
                                           {
                                             $courseStatus=$cs['courseStatusID'];
                                           }*/
                                        if($courseStatus==1)
                                            $status="Core";
                                        else
                                            $status="Elective";

                                        ?>
                                        <td><?php echo $status;?></td>
                                        <td><a href="action_semister_course.php?action_type=delete&id=<?php echo $courseProgrammeID; ?>&programmeID=<?php echo $programmeID;?>&studyYear=<?php echo $studyYear;?>&semisterID=<?php echo $semisterID;?>&batchID=<?php echo $batchID;?>"
                                               class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to drop this course?');"></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </tbody>

                        <tr>
                            <tfoot>
                            <th colspan="3">Total Number of Credits</th>
                            <th colspan=3><?php echo $totalCredits;?></th>
                            </tfoot>
                        </tr>
                    </table>
                    <?php
                }
                else
                {
                    ?>
                    <h4 class="text-danger">No Course(s) found Registered for this Program</h4>
                    <?php
                }
                ?>
                <?php
            }
            ?>
        </div>
    </div>


</div>