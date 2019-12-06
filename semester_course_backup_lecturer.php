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

                <script>
                    $(document).ready(function(){

                        $("#course_id").change(function(){
                            var course_id = $(this).val();

                            $.ajax({
                                url: 'json_course_name.php',
                                type: 'post',
                                data: {courseID:course_id},
                                dataType: 'json',
                                success:function(response){

                                    var len = response.length;

                                    $("#sel_credits").empty();
                                    $("#sel_type").empty();
                                    $("#sel_status").empty();
                                    for( var i = 0; i<len; i++){
                                        var courseType = response[i]['courseType'];
                                        var units = response[i]['units'];
                                        var courseStatus=response[i]['courseStatus'];
                                        var courseStatusID=response[i]['courseStatusID'];

                                        $("#sel_credits").append("<option value='"+units+"' selected>"+units+"</option>");
                                        $("#sel_type").append("<option value='"+courseType+"' selected>"+courseType+"</option>");
                                        $("#sel_status").append("<option value='"+courseStatusID+"' selected>"+courseStatus+"</option>");

                                    }
                                }
                            });
                        });

                    });
                </script>

                <form name="" method="post" action="action_semister_course.php">
                    <!-- <table  id="exampleexample" class="table table-striped table-bordered table-condensed">
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
                    <?php /*
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
                        $status="Option";
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
                            */?>
                            <tr>
                            <td><?php /*echo $count;*/?></td>
                            <td><input class="checkbox1" type="checkbox" name="course[]" value="<?php /*echo $courseID;*/?>"></td>
                            <td><?php /*echo $c['courseCode'];*/?></td>
                            <td><?php /*echo $c['courseName'];*/?></td>
                            <td><?php /*echo $c['units'];*/?></td>
                                <td><?php /*echo $stYear;*/?></td>
                                <td><?php /*echo $semister_Name;*/?></td>
                            <td><?php /*echo $status;*/?></td>
                            </tr>
                            <?php
                    /*                          }
                                            }
                                        }
                                        */?>
                    </tbody>
                    </table>-->



                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Course Name</label>
                                        <select name="courseID" id="course_id" class="form-control chosen-select" required>
                                            <?php
                                            echo"<option value=''>Please Select Here</option>";
                                            foreach($course as $cn) {
                                                $courseID = $cn['courseID'];
                                                $courseStatusID=$cn['courseStatusID'];
                                                $course_name = $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                                if(!empty($course_name)) {
                                                    foreach ($course_name as $c) {
                                                        $cCode=$c['courseCode'];
                                                        $cName=$c['courseName'];
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $courseID;?>"><?php echo $cCode."-".$cName; ?></option>
                                                <?php
                                            }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Course Credits</label>
                                        <select name="courseCredits" id="sel_credits" class="form-control" disabled>
                                            <option value="">--show--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Course Type</label>
                                        <select name="courseTypeID" id="sel_type" class="form-control" disabled>
                                            <option value="">--show--</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="email">Course Category</label>
                                        <select name="courseStatusID" id="sel_status" class="form-control">
                                            <option value="">--show--</option>
                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Course Grading</label>
                                        <select name=courseGradeID id="courseGradeID" class="form-control" required>
                                            <?php
                                            /*$cwork=$db->getExamCategoryMarks($programmeID,1,'wMark');
                                            $fexam=$db->getExamCategoryMarks($programmeID,2,'wMark');
                                            echo"<option value='' selected>".$cwork."CW-".$fexam."FE</option>";*/

                                            $cgrading = $db->getRows('course_grade',array('order_by'=>'courseGradeID DESC'));
                                            if(!empty($cgrading)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($cgrading as $cg){ $count++;
                                                    $courseGrade=$cg['courseGrade'];
                                                    $courseGradeID=$cg['courseGradeID'];
                                                    ?>
                                                    <option value="<?php echo $courseGradeID;?>"><?php echo $courseGrade;?></option>
                                                <?php }}

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Pass Mark</label>
                                        <select name=passMarkID id="passMarkID" class="form-control" required>
                                            <?php
                                            /*$pcwork=$db->getExamCategoryMarks($programmeID,1,'passMark');
                                            $pfexam=$db->getExamCategoryMarks($programmeID,2,'passMark');
                                            echo"<option value='' selected>".$pcwork."CW-".$pfexam."FE</option>";*/
                                            $pass_mark = $db->getRows('pass_mark',array('order_by'=>'passMarkID DESC'));
                                            if(!empty($pass_mark)){
                                                echo"<option value=''>Please Select Here</option>";
                                                $count = 0; foreach($pass_mark as $pm){ $count++;
                                                    $passMark=$pm['passMark'];
                                                    $passMarkID=$pm['passMarkID'];
                                                    ?>
                                                    <option value="<?php echo $passMarkID;?>"><?php echo $passMark;?></option>
                                                <?php }}

                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Lecturer</label>
                                        <select name="instructorID" class="form-control chosen-select">
                                            <?php
                                            $instructor = $db->getRows('instructor',array('where'=>array('instructorStatus'=>1),'order_by'=>' instructorName ASC'));
                                            if(!empty($instructor)){
                                                echo"<option value=''>Please Select Here</option>";
                                                foreach($instructor as $inst){
                                                    $fname=$inst['firstName'];
                                                    $lname=$inst['lastName'];
                                                    $departID=$inst['departmentID'];
                                                    $instructorID=$inst['instructorID'];
                                                    $deptCode=$db->getData("departments","departmentCode","departmentID",$departID);
                                                    ?>
                                                    <option value="<?php echo  $instructorID;?>"><?php echo "$fname $lname"."(".$deptCode.")";?></option>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                            </div>


                            <div class="row">

                            </div>

                            <br />
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-3">
                                    <input type="hidden" name="action_type" value="add"/>
                                    <input type="hidden" name="number_subject" value="<?php echo $count;?>">
                                    <!--<input type="text" name="courseStatusID" value="<?php /*echo $courseStatusID;*/?>">-->
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
                        <th>Course Grade</th>
                        <th>Pass Mark</th>
                        <th>Instructor Name</th>
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
                        $instructorID=$cs['instructorID'];
                        $courseGradeID=$cs['courseGradeID'];
                        $passMarkID=$cs['passMarkID'];
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
                                    /*if($courseStatus==1)
                                     $status="Core";
                                    else
                                      $status="Elective";*/

                                    ?>
                                    <td><?php echo $db->getData("coursestatus","courseStatus","courseStatusID",$courseStatus);?></td>
                                    <td><?php echo $db->getData("course_grade","courseGrade","courseGradeID",$courseGradeID);?></td>
                                    <td><?php echo $db->getData("pass_mark","passMark","passMarkID",$passMarkID);?></td>
                                    <td><?php echo $db->getName("instructor","instructorID",$instructorID);?></td>
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
                        <th colspan=6><?php echo $totalCredits;?></th>
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