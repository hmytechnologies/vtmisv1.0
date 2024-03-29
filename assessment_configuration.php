<script type="text/javascript">
    $(document).ready(function () {
        $("#studentdata").DataTable({
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

<?php $db=new DBHelper();
$instructorID=$db->getData("instructor","instructorID","userID",$_SESSION['user_session']);
?>
<div class="row">
    <div class="col-lg-12">
    <h1>Assessment Configuration</h1>
</div></div>

<div class="row">
    <form name="" method="post" action="">
        <div class="col-lg-12">
            <div class="row">

                <div class="col-lg-3">
                    <label for="FirstName">Academic Year</label>
                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                        <?php
                        // $academic_year = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                        $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                        if(!empty($academic_year)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($academic_year as $sm){ $count++;
                                $academicYear=$sm['academicYear'];
                                $academicYearID=$sm['academicYearID'];
                                ?>
                                <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>



            </div>
            <div class="row">

                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                <div class="col-lg-6"></div>

            </div>
        </div>
    </form>
</div>
<br><br>
                <div class="row">
                    <?php
                    if(isset($_POST['doFind'])=="View Records") {
                        $academicYearID = $_POST['academicYearID'];

                        $courseprogramme = $db->getInstructorAcademicCourse($academicYearID, $instructorID);
                        $semesterSettingID = $db->getData("semester_setting","semesterSettingID","academicYearID",$academicYearID);
                        if (!empty($courseprogramme)) {
                            ?>
                            <div class="col-md-12">
                                <div class="box box-solid box-primary">
                                    <div class="box-header with-border text-center">
                                        <h3 class="box-title">List of Assigned Courses
                                            for <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <table id="" class="table table-striped table-bordered table-condensed">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Class Number</th>
                                                <th>Course Name</th>
                                                <th>Course Code</th>
                                                <th>Course Type</th>
                                                <th>Level Name</th>
                                                <th>Trade Name</th>
                                                <th>No.of Students</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $totalHours = 0;
                                            $count = 0;
                                            foreach ($courseprogramme as $std) {
                                                $count++;
                                                $courseID = $std['courseID'];
                                                $classNumber = $std['classNumber'];
                                                $programmeID = $std['programmeID'];
                                                $programmeLevelID = $std['programmeLevelID'];

                                                $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
                                                if (!empty($course)) {
                                                    foreach ($course as $c) {
                                                        $courseCode = $c['courseCode'];
                                                        $courseName = $c['courseName'];
                                                        $courseTypeID = $c['courseTypeID'];
                                                        $units = $c['units'];
                                                        $nhours = $c['numberOfHours'];
                                                        $totalHours += $nhours;
                                                    }
                                                }

                                                $studentNumber=$db->getStudentCourseSum($_SESSION['department_session'],$academicYearID,$programmeLevelID,$programmeID);


                                                $viewButton = '
	   <div class="btn-group">
	         <a href="index3.php?sp=marks_configuration&id=' . $db->encrypt($courseID) . '&sid=' . $db->encrypt($semesterSettingID).'&instID='.$db->encrypt($instructorID). '&ac=' . $db->encrypt($academicYearID). '"class="fa fa-eye" title="View Configuration Settings"></a>
	   </div>';
                                                ?>

                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $classNumber;?></td>
                                                    <td><?php echo $courseName; ?></td>
                                                    <td><?php echo $courseCode; ?></td>
                                                    <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $courseTypeID); ?></td>
                                                    <td><?php echo $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID); ?></td>
                                                    <td><?php echo $db->getData('programmes','programmeName','programmeID',$programmeID); ?></td>
                                                    <td><?php echo $studentNumber; ?></td>
                                                    <td><?php echo $viewButton; ?></td>
                                                </tr>

                                            <?php } ?>
                                            </tbody>
                                            <tr>
                                                <th colspan="5" style="font-size:16px;">Total Number of Hours per Week
                                                </th>
                                                <th style="font-size:16px;"><?php echo $totalHours; ?></th>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <h4 class="text-danger">No Course Found</h4>
                            <?php
                        }
                    }
                    ?>
                </div>
