<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Exam Schedule</h1>
        <hr>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $("#studentdata").DataTable({
                            "ajax": "api/marksmanagement.php",
                            "dom": 'Blfrtip',
                            "scrollX":true,
                            "paging":true,
                            "buttons":[
                                {
                                    extend:'excel',
                                    title: 'List of all Register',
                                    footer:false,
                                    exportOptions:{
                                        columns: [0, 1, 2, 3,5,6,7]
                                    }
                                },
                                ,
                                {
                                    extend: 'print',
                                    title: 'List of all Register',
                                    footer: false,
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,5,6,7]
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'List of all Register',
                                    footer: true,
                                    exportOptions: {
                                        columns: [0, 1, 2, 3,5,6,7]
                                    },

                                }

                            ],
                            "order": []
                        });
                    });
                </script>
                <?php
                $today=date("Y-m-d");
                $sm=$db->readSemesterSetting($today);
                foreach ($sm as $s) {
                    $semisterID=$s['semesterID'];
                    $academicYearID=$s['academicYearID'];
                    $semesterName=$s['semesterName'];
                    $semesterSettingID=$s['semesterSettingID'];
                }
                $courseprogramme = $db->getSemesterCourse($semesterSettingID,$_SESSION['role_session'],$_SESSION['department_session']);
                if(!empty($courseprogramme))
                {
                    ?>
                    <div class="row">

                        <h3 class="box-title">Registered Courses for <?php echo $semesterName;?></h3>
                        <hr>
                        <!-- /.box-header -->
                        <table  id="exampleexample" class="table table-striped table-bordered table-condensed table-responsive">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Course Name</th>
                                <th>Course Code</th>
                                <th>No.of Students</th>
                                <th>Batch</th>
                                <th>Lecturer</th>
                                <th>Date</th>
                                <th>Start Time-End Time</th>
                                <th>Room</th>
                                <th>Invigillators</th>
                                <th>Print</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0; foreach($courseprogramme as $std){ $count++;
                                $courseID=$std['courseID'];
                                $courseProgrammeID=$std['courseProgrammeID'];
                                $batchID=$std['batchID'];

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

                                $instructor = $db->getRows('instructor_course',array('where'=>array('courseProgrammeID'=>$courseProgrammeID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseProgrammeID ASC'));
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

                                $studentNumber=$db->getStudentCourseSum($courseID,$semesterSettingID,$batchID);

                                $checked=$db->checkStatus($courseID,$semesterSettingID,'checked',$batchID);
                                $published=$db->checkStatus($courseID,$semesterSettingID,'status',$batchID);

                                $boolExamStatus=$db->checkExamResultStatus($courseID,$semesterSettingID,$batchID);


                                if($published==1)
                                    $statusPublished="<span class='text-success'>Yes</span>";
                                else
                                    $statusPublished="<span class='text-danger'>No</span>";

                                if($studentNumber==0)
                                {
                                    $addButton = '
	<div class="btn-group">
	     <i class="fa fa-plus" aria-hidden="true"></i>
	</div>';

                                    $excelButton = '
	<div class="btn-group">
        <i class="fa fa-file" aria-hidden="true"></i>
	</div>';

                                    $viewButton = '
	<div class="btn-group">
        <i class="fa fa-eye" aria-hidden="true"></i>
	</div>';
                                }
                                else
                                {
                                    if($published==1)
                                    {
                                        $addButton = '
    	<div class="btn-group">
    	     <i class="fa fa-plus" aria-hidden="true"></i>
    	</div>';

                                        $excelButton = '
    	<div class="btn-group">
            <i class="fa fa-file" aria-hidden="true"></i>
    	</div>';

                                        $viewButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
                                    }
                                    else
                                    {
                                        $addButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=add_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-plus"></a>
    	</div>';

                                        $excelButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=import_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'"><i class="glyphicon glyphicon-import"></i></a>
    	</div>';

                                        if($boolExamStatus==true)
                                        {
                                            $viewButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid='.$db->encrypt($courseID).'&sid='.$db->encrypt($semesterSettingID).'&bid='.$db->encrypt($batchID).'" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
                                        }
                                        else
                                        {
                                            $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
                                        }

                                    }
                                }
                                ?>

                                <tr>
                                    <td><?php echo $count;?></td>
                                    <td><?php echo $courseName;?></td>
                                    <td><?php echo $courseCode;?></td>
                                    <td><?php echo $studentNumber;?></td>
                                    <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
                                    <td><?php echo $instructorName;?></td>
                                    <td><?php echo $addButton;?></td>
                                    <td><?php echo $excelButton;?></td>
                                    <td><?php echo $viewButton;?></td>
                                    <td><?php echo $viewButton;?></td>
                                    <td><?php echo $statusPublished;?></td>
                                </tr>

                                <?php
                            }
                            ?>
                            </tbody>
                        </table></div>
                    <?php
                }
                else
                {
                    echo "<h4 class='text-danger'>No Course Found</h4>";
                }
                ?>
            </div>
        </div>