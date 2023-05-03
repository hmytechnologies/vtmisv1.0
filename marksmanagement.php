<?php
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
?>

<?php $db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Results Management</h1>
        <hr>
        <div class="row">
            <form name="" method="post" action="">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">Academic Year</label>
                            <select name="academicYearID" class="form-control" required>
                                <?php
                                $adYear = $db->getRows('academic_year', array('order_by' => 'academicYear DESC'));
                                if (!empty($adYear)) {
                                    echo "<option value=''>Please Select Here</option>";
                                    $count = 0;
                                    foreach ($adYear as $year) {
                                        $count++;
                                        $academic_year = $year['academicYear'];
                                        $academic_year_id = $year['academicYearID'];
                                ?>
                                        <option value="<?php echo $academic_year_id; ?>"><?php echo $academic_year; ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for=""><br></label>
                            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" />
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="row">
            <?php
            if (isset($_POST['doFind']) == "Find Records") {
                $academicYearID = $_POST['academicYearID'];
                $semesterSettingID = $db->getData("semester_setting", "semesterSettingID", "academicYearID", $academicYearID);
                $courseprogramme = $db->getMappingCourseList($academicYearID, $_SESSION['main_role_session'], $_SESSION['department_session']);
                //$courseprogramme = $db->getSemesterCourse($semesterSettingID, $_SESSION['main_role_session'], $_SESSION['department_session']);
                if (!empty($courseprogramme)) {
                    $count = 0;
            ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="box-title">Registered Course for <?php echo $db->getData('academic_year', 'academicYear', 'academicYearID', $academicYearID); ?></h3>
                            <!-- /.box-header -->
                            <table id="exampleexample" class="table table-striped table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Subject Name</th>
                                        <th>Subject Code</th>
                                        <th>Level</th>
                                        <th>Trade</th>
                                        <th>No.of Students</th>
                                        <th>Exam List</th>
                                        <th>Post Results</th>
                                        <th>Bulk Post</th>
                                        <th>View Results</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($courseprogramme as $std) {
                                        $count++;
                                        $courseID = $std['courseID'];
                                        $courseCode = $std['courseCode'];
                                        $courseName = $std['courseName'];
                                        $courseTypeID = $std['courseTypeID'];
                                        $programmeLevelID = $std['programmeLevelID'];
                                        $programmeID = $std['programmeID'];

                                        $course = $db->getRows('course', array('where' => array('courseID' => $courseID), 'order_by' => 'courseID ASC'));
                                        if (!empty($course)) {
                                            foreach ($course as $c) {
                                                $courseCode = $c['courseCode'];
                                                $courseName = $c['courseName'];
                                                $courseTypeID = $c['courseTypeID'];
                                            }
                                        }




                                        $studentNumber = $db->getStudentNumber($academicYearID, $programmeLevelID, $programmeID);



                                        //$checked=$db->checkStatus($courseID,$academicYearID,$programmeID,$programmeLevelID,'checked'); 

                                        //Commented from 23March,2022
                                        //$published=$db->checkStatus($courseID,$academicYearID,$programmeID,$programmeLevelID,'status');

                                        $boolExamStatus = $db->checkFinalExamResultStatus($courseID, $academicYearID, $programmeID, $programmeLevelID);


                                        if ($studentNumber == 0) {
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
                                        } else {
                                            $addButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=add_score&cid=' . $db->encrypt($courseID) . '&acadID=' . $db->encrypt($academicYearID) . '&lvlID=' . $db->encrypt($programmeLevelID) . '&pid=' . $db->encrypt($programmeID) . '" class="glyphicon glyphicon-plus"></a>
    	</div>';

                                            $excelButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=import_score&cid=' . $db->encrypt($courseID) . '&acadID=' . $db->encrypt($academicYearID) . '&lvlID=' . $db->encrypt($programmeLevelID) . '&pid=' . $db->encrypt($programmeID) . '"class="glyphicon glyphicon-plus"></a>
    	</div>';

                                            if ($boolExamStatus == true) {
                                                $viewButton = '
    	    <div class="btn-group">
    	         <a href="index3.php?sp=view_score&cid=' . $db->encrypt($courseID) . '&acadID=' . $db->encrypt($academicYearID) . '&lvlID=' . $db->encrypt($programmeLevelID) . '&pid=' . $db->encrypt($programmeID) . '" class="glyphicon glyphicon-eye-open"></a>
    	   </div>';
                                            } else {
                                                $viewButton = '
        	<div class="btn-group">
                <i class="fa fa-eye" aria-hidden="true"></i>
        	</div>';
                                            }

                                            //}
                                        }
                                    ?>

                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $courseName; ?></td>
                                            <td><?php echo $courseCode; ?></td>
                                            <td><?php echo $db->getData('programme_level', 'programmeLevel', 'programmeLevelID', $programmeLevelID); ?></td>
                                            <td><?php echo $db->getData('programmes', 'programmeName', 'programmeID', $programmeID); ?></td>
                                            <td><?php echo $studentNumber; ?></td>
                                            <td>Exam List</td>
                                            <td><?php echo $addButton; ?></td>
                                            <td><?php echo $excelButton; ?></td>
                                            <td><?php echo $viewButton; ?></td>
                                            <!-- <td><?php //echo $statusPublished;
                                                        ?></td> -->
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
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
    </div>
</div>