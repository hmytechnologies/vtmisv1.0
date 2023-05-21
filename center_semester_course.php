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
            <h1>Annual Course Assignment</h1>
        </div>
        <div class="col-md-4">
            <!--<div class="pull-right">
                 <a href="index3.php?sp=semester_setting_hod" class="btn btn-warning">Back to Semester Settings</a>
             </div>-->
        </div></div>
    <hr>
    <div class="content">
        <h3>View/Assign Course In a Year</h3>
        <form name="" method="post" action="">
            <div class="row">
                <div class="col-lg-3">
                    <label for="MiddleName">Trade Name</label>
                    <select name="programmeID" id="programID" class="form-control chosen-select" required>
                        <?php
                        $programmes = $db->getCenterProgrammes($_SESSION['department_session']);
                        //$programmes = $db->getCenterAnnualCourse($academicYearID,$_SESSION['department_session'])

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
                    <label for="FirstName">Level</label>
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
                <strong>Course data has been inserted successfully</strong>.
            </div>";
            }
            else if($_REQUEST['msg']=="deleted") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Course Data has been delete successfully</strong>.
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
                    $academicYearID = $_POST['academicYearID'];
                    $programmeLevelID=$_POST['programme_level_id'];
                }
                else {
                    $programmeID = $db->my_simple_crypt($_REQUEST['programmeID'],'d');
                    $academicYearID = $db->my_simple_crypt($_REQUEST['academicYearID'],'d');
                    $programmeLevelID=$db->my_simple_crypt($_REQUEST['levelID'],'d');
                }
                $course=$db->getCenterCourseProgramme($_SESSION['department_session'],$programmeID,$academicYearID,$programmeLevelID);

                if(!empty($course))
                {
                    ?>
                    <div class="row">
                        <h4 class="text-info">
                            Confirm Subjects for <?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?> - <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                            - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                        </h4>
                    </div>
                    <form name="" method="post" action="action_center_course.php">
                        <table  id="exampleexample" class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th><input type="checkbox" id="selecctall"/></th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
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
                                $courseStatusID=$cs['courseStatusID'];

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
                                            <td>
                                                <!--change the course status-->
                                                <?php echo $db->getData("coursestatus","courseStatus","courseStatusID",$courseStatusID);?>
                                            </td>
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
                                <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                                <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                <input type="hidden" name="programmeLevelID" value="<?php echo $programmeLevelID;?>">
                                <input type="submit" name="doSubmit" value="Confirm Subjects" class="btn btn-primary form-control"/>
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
                    <h4 class="text-danger">No Course(s) found for confirmation......</h4>
                    <?php
                }
                ?>
                <hr>
                <?php
                //List of Registered Course
                $courseprogramme = $db->getRows('center_programme_course',array('where'=>array('centerID'=>$_SESSION['department_session'],'programmeID'=>$programmeID,'academicYearID'=>$academicYearID),' order_by'=>' courseID ASC'));
                if(!empty($courseprogramme))
                {

                    ?>
                    <div class="row">
                        <h4 class="">
                            List of Registered Course for <?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?> - <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                            - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                        </h4>
                    </div>

                    <table  id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Course Type</th>
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
                                    ?>
                                    <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $c['courseCode'];?></td>
                                        <td><?php echo $c['courseName'];?></td>
                                        <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                                        <?php
                                        $courseStatus = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID,'programmeID'=>$programmeID),' order_by'=>' courseID ASC'));
                                        foreach($courseStatus as $cs)
                                        {
                                            $courseStatus=$cs['courseStatusID'];
                                        }

                                        ?>
                                        <td><?php echo $db->getData("coursestatus","courseStatus","courseStatusID",$courseStatus);?></td>
                                        <td><a href="action_center_course.php?action_type=delete&id=<?php echo $db->my_simple_crypt($courseProgrammeID,'e'); ?>&programmeID=<?php echo $db->my_simple_crypt($programmeID,'e');?>&academicYearID=<?php echo $db->my_simple_crypt($academicYearID,'e');?>&levelID=<?php echo $db->my_simple_crypt($programmeLevelID,'e');?>"
                                               class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to drop this course?');"></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
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


</div>