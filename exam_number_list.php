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
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $("#course_list").DataTable({
            "dom": 'Blfrtip',
            /*"scrollX":false,*/
            "paging":true,
            "buttons":[
                {
                    extend:'excel',
                    title: titleheader,
                    footer:false,
                    exportOptions:{
                        columns: [0, 1, 2,3,4,5,6]
                    }
                },
                ,
                {
                    extend:'csvHtml5',
                    title: titleheader,
                    customize: function (csv) {
                        return titleheader+"\n"+  csv +"\n";
                    },
                    exportOptions:{
                        columns: [0, 1, 2,3,4,5,6]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3,4,5,6]
                    },

                }

            ],
            "order": []
        });
    });
</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<?php $db=new DBHelper();
?>
<div class="container">
    <h1>Semester Courses</h1>
    <hr>
    <div class="content">

        <h3>View course in a semester</h3>

        <div class="row">
            <form name="" method="post" action="">
                <div class="col-lg-3">
                    <label for="MiddleName">Programme Name</label>
                    <select name="programmeID" id="programID" class="form-control chosen-select" required>
                        <?php
                        if($_SESSION['main_role_session']==4)
                        {
                            $programmes = $db->getRows('programmes',array('where'=>array('departmentID'=>$_SESSION['department_session']),'order_by'=>'programmeName ASC'));
                        }
                        else if($_SESSION['main_role_session']==9)
                        {
                            $programmes = $db->getRows('programmes',array('where'=>array('schoolID'=>$_SESSION['department_session']),'order_by'=>'programmeName ASC'));

                        }

                        else
                        {
                            $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                        }
                        if(!empty($programmes)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeName'];
                                $programmeID=$prog['programmeID'];
                                ?>
                                <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                                <?php
                            }}
                        ?>
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


                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="submit" name="doFind" value="View Courses" class="btn btn-primary form-control" /></div>
            </form>
        </div>



        <div class="row"><br></div>

        <div class="row">
            <?php
            if(isset($_POST['doFind'])=="View Courses")
            {
            $programmeID=$_POST['programmeID'];
            $semesterID=$_POST['semisterID'];
            $batchID=$_POST['batchID'];


            $courseprogramme = $db->getSemesterProgrammeCourse($programmeID,$semesterID,$batchID);
            if(!empty($courseprogramme))
            {
            ?>
            <h3 id="titleheader">List of Registered Course for <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>-<?php echo $db->getData("batch","batchName","batchID",$batchID);?>
                -<?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterID);?></h3>
            <hr>
            <table  id="course_list" class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th width="10">No.</th>
                    <th>Course Name</th>
                    <th width="100">Course Code</th>
                    <th width="100">Course Type</th>
                    <!--      <th width="100">Course Status</th>
                    -->    <th width="80">Study Year</th>
                    <th width="80">Student No</th>
                    <th>Lecturer</th>
                    <th width="50">View</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count=0;
                foreach($courseprogramme as $std)
                {
                    $count++;
                    $courseID=$std['courseID'];
                    $batchID=$std['batchID'];
                    $courseProgrammeID=$std['courseProgrammeID'];
                    $studyYear=$std['studyYear'];
                    $cStatus=$std['courseStatus'];

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

                    $instructor = $db->getRows('instructor_course',array('where'=>array('courseID'=>$courseID,'batchID'=>$batchID,'semesterSettingID'=>$semesterID),'order_by'=>'courseID ASC'));
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

                    $studentNumber=$db->getStudentCourseSum($courseID,$semesterID,$batchID);
                    if($studentNumber>0)
                    {
                        $viewButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=student_list&id='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterID).'&bid='.$db->encrypt($batchID).'"class="glyphicon glyphicon-eye-open"></a>
	</div>';
                    }
                    else
                    {
                        $viewButton = '
      <div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
      </div>';
                    }

                    ?>

                    <tr>
                        <td><?php echo $count;?></td>
                        <td><?php echo $courseName;?></td>
                        <td><?php echo $courseCode;?></td>
                        <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                        <!-- <td><?php
                        /*         if($cStatus==1)
                                     echo "Core";
                                 else
                                     echo "Elective";
                                 */?></td>-->
                        <td><?php echo $sYear." Year";?></td>
                        <td><?php echo $studentNumber;?></td>
                        <td><?php echo $instructorName;?></td>
                        <td><?php echo $viewButton;?></td>
                    </tr>

                <?php }?>
                </tbody>

            </table>
        </div>
    </div>
    <?php
    }

    else
    {
        echo "<h3 class='text-danger'>No course is taught in this semester for this study mode</h3>";
    }
    }
    ?>
</div>

