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
        <h1>Annual Course Management</h1>
        <hr>
        <ul class="nav nav-tabs" id="myTab">

            <li class="active"><a data-toggle="tab" href="#currentdata"><span style="font-size: 16px"><strong>Current Year</strong></span></a></li>
         <li><a data-toggle="tab" href="#programme_course"><span style="font-size: 16px"><strong>Trade Subjects</strong></span></a></li>
            <li><a data-toggle="tab" href="#center_programme_course"><span style="font-size: 16px"><strong>Center Trade Subjects</strong></span></a></li>
            <!--<li><a data-toggle="tab" href="#subject_info"><span style="font-size: 16px"><strong>Subject Info</strong></span></a></li>-->

        </ul>

        <div class="tab-content">
            <!-- Current Semester -->
            <div id="currentdata" class="tab-pane fade in active">
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
                $sm=$db->readSemesterSetting();
                foreach ($sm as $s) {
                    $academicYearID=$s['academicYearID'];
                }

                $courseprogramme = $db->getSemesterCourse($academicYearID,$_SESSION['main_role_session'],$_SESSION['department_session']);
                if(!empty($courseprogramme))
                {
                    ?>
                    <div class="row">

                        <h3 class="box-title">Registered Courses <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h3>
                        <hr>
                        <!-- /.box-header -->
                        <table  id="exampleexample" class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Subject Type</th>
                                <th>Trade Name</th>
                                <th>Level</th>
                                <th>No.of Students</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0; foreach($courseprogramme as $std){ $count++;
                                $courseProgrammeID=$std['courseProgrammeID'];
                                $courseID=$std['courseID'];
                                $courseCode=$std['courseCode'];
                                $courseName=$std['courseName'];
                                $courseTypeID=$std['courseTypeID'];
                                $programmeLevelID=$std['programmeLevelID'];
                                $programmeID=$std['programmeID'];

                                $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
                                if(!empty($course))
                                {
                                    foreach($course as $c)
                                    {
                                        $courseCode=$c['courseCode'];
                                        $courseName=$c['courseName'];
                                        $courseTypeID=$c['courseTypeID'];
                                        $credits=$c['units'];
                                    }
                                }

                                $studentNumber=$db->getStudentCourseSum($courseID,$academicYearID,$programmeID,$programmeLevelID);
                                if($studentNumber>0)
                                {
                                    $viewButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=student_list&cid='.$db->encrypt($courseProgrammeID).'"class="glyphicon glyphicon-eye-open"></a>
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
                                    <td><?php echo $db->getData("course_type","courseTypeCode","courseTypeID",$courseTypeID);?></td>
                                    <td><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?></td>
                                    <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID); ?></td>
                                    <td><?php echo $studentNumber;?></td>
                                    <td><?php echo $viewButton;?></td>
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
            <!-- End of Current Semester -->


            <!-- Start -->
            <div id="programme_course" class="tab-pane fade">
                <?php
                include("courselist.php");
                ?>
            </div>
                <!-- End -->

            <!-- Start -->
            <div id="center_programme_course" class="tab-pane fade">
                <?php
               // include("center_course_list.php");
                ?>
            </div>
            <!-- End -->

                <!-- subject info -->
                <div id="subject_info" class="tab-pane fade">
                    <h3>Subject Information</h3>
                    <div class="row">
                        <form name="" method="post" action="">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="FirstName">Enter Subject Code/Name</label>
                                       <input type="text" name="searchText" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for=""><br></label>
                                        <input type="submit" name="doFind" value="Search" class="btn btn-primary form-control" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        Done
                    </div>
                </div>
                <!-- End -->
        </div>

    </div></div>