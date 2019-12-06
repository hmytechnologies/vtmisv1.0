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

<script type="text/javascript">
    $(document).ready(function()
    {
        $("#centerIDD").change(function()
        {
            var centerID=$("#centerIDD").val();
            var dataString = 'centerID='+centerID;
            $.ajax
            ({
                type: "POST",
                url: "ajax_center_programme.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#programmeID").html(html);
                }
            });

        });

    });
</script>

<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">

        <h3>View course in a year</h3>

        <div class="row">
            <form name="" method="post" action="">
                <div class="col-lg-3">
                    <label for="Physical Address">Center Name</label>
                    <select name="centerID" id="centerIDD"  class="form-control" required>
                        <option value="">Select Here</option>
                        <?php
                        $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                        if(!empty($center)){

                            $count = 0; foreach($center as $cnt){ $count++;
                                $centerRegistrationID=$cnt['centerRegistrationID'];
                                $centerName=$cnt['centerName'];
                                ?>
                                <option value="<?php echo $centerRegistrationID;?>"><?php echo $centerName;?></option>
                            <?php }
                        }?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="Physical Address">Trade Name</label>
                    <select name="programmeID" id="programmeID"  class="form-control" required>
                        <option value="">Select Here</option>
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
                $academicYearID=$_POST['academicYearID'];


                $courseprogramme = $db->getSemesterProgrammeCourse($programmeID,$academicYearID);
                if(!empty($courseprogramme))
                {
                    ?>
                    <h3 id="titleheader">List of Registered Course for <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>-<?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h3>
                    <hr>
                    <table  id="course_list" class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th width="10">No.</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Subject Type</th>
                            <th>Level</th>
                            <th>Student No</th>
                            <th>View</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count=0;
                        foreach($courseprogramme as $std)
                        {
                            $count++;
                            $courseID=$std['courseID'];
                            $programmeLevelID=$std['programmeLevelID'];
                            $cStatus=$std['courseStatus'];


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

                            $studentNumber=$db->getStudentCourseSum($courseID,$academicYearID);
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
                                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID); ?></td>
                                <td><?php echo $studentNumber;?></td>
                                <!-- <td><?php /*echo $instructorName;*/?></td>
--> <td><?php echo $viewButton;?></td>
                            </tr>

                        <?php }?>
                        </tbody>

                    </table>

                    <?php
                }

                else
                {
                    echo "<h3 class='text-danger'>No course found</h3>";
                }
            }
            ?>
        </div>
    </div>
</div>

