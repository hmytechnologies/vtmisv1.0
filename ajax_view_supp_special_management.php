<?php
session_start();
?>
<div class="row">
            <?php
            include ('DB.php');
            $db=new DBHelper();
          echo   $academicYearID = $_POST['academicYearID'];
                // $semester=$db->getRows("semester_setting",array(' where ' =>array( 'academicYearID '=>$academicYearID),' order_by semesterSettingID ASC'));
                $semester= $db->getRows('semester_setting',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYearID ASC'));
                if(!empty($semester))
                {
                    foreach($semester as $sm)
                    {
                        // $semisterID=$sm['semesterID'];
                        // $academicYearID=$sm['academicYearID'];
                        // $semesterName=$sm['semesterName'];
                     $semesterSettingID=$sm['semesterSettingID'];
                    }
                    
                   // getInstructorCourse
                    // $courseprogramme = $db->getSemesterCourse($semesterSettingID,$_SESSION['main_role_session'],$_SESSION['department_session']);
                // echo $academicYearID;
                  
                //   echo  $courseprogramme=$db->getRows("courseprogramme",array('where'=>array('academicYearID'=>$academicYearID),' order_by programmeID ASC'));



                   $courseprogramme= $db->getRows('center_programme_course',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYearID ASC'));
                    if(!empty($courseprogramme))
                    {
                        $count = 0;
                        ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="box-title">Registered Course for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
                                <!-- /.box-header -->
                                <table id="example" class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Course Name</th>
                                        <th>Course Code</th>
                                        <th>Number of Students</th>
                                        <th>Slot Name</th>
                                        <th>Lecturer</th>
                                        <th>Post</th>
                                        <!--<th>View</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($courseprogramme as $std)
                                    {
                                        $count++;
                                        $courseID=$std['courseID'];
                                        // $batchID=$std['batchID'];

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

                                        $instructor = $db->getRows('center_programme_course',array('where'=>array('courseID'=>$courseID,'academicYearID'=>$academicYearID),'order_by'=>'courseID ASC'));
                                        if(!empty($instructor))
                                        {
                                            foreach($instructor as $i)
                                            {
                                                $instructorID=$i['staffID'];
                                                $instructorName=$db->getData("instructor","instructorName","instructorID",$instructorID);
                                            }
                                        }
                                        else
                                        {
                                            $instructorName="Not assigned";
                                        }

                                        //getstudentresult
                                        // $student=$db->getStudentSuppSpecialRegNumber($courseID,$semesterSettingID);
                                       echo $student=$db->getRows('student',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>' registrationNumber ASC'));
                                        if(!empty($student))
                                        {
                                            $studentNo=0;
                                            foreach($student as $st)
                                            {
                                                $regNumber=$st['registrationNumber'];
                                                $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                                                $sfe = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 2));
                                                $prj = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 5));
                                                $pt = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 6));


                                                // $present=$db->getStudentExamStatus($regNumber,$courseID,$semesterSettingID,2);

                                                if(($cwk+$sfe) < 40)
                                                    $studentNo += 1;
                                                // if($present == 0)
                                                //     $studentNo += 1;
                                            }
                                        }


                                        $published=$db->checkStatus($courseID,$semesterSettingID,'status');

                                        if($studentNo==0)
                                        {
                                            $addButton = '
                        <div class="btn-group">
                             <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>';
                                            $viewButton = '
                        <div class="btn-group">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </div>';
                                        }
                                        else {
                                            if ($published == 1) {

                                                $addButton = '
        <div class="btn-group">
              <a href="index3.php?sp=view_score&cid=' . $db->encrypt($courseID) . '&sid=' . $db->encrypt($semesterSettingID) .  '" class="glyphicon glyphicon-eye-open"></a>
        </div>';
                                            } else {
                                                $addButton = '
    	<div class="btn-group">
    	     <a href="index3.php?sp=add_score_sup&cid=' . $db->encrypt($courseID) . '&sid=' . $db->encrypt($semesterSettingID) .  '" class="glyphicon glyphicon-plus"></a>
    	</div>';
                                            }
                                        }

                                        ?>

                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td><?php echo $courseName;?></td>
                                            <td><?php echo $courseCode;?></td>
                                            <td><?php echo $studentNo;?></td>
                                            <!-- <td><#?php echo $db->getData("batch","batchName","batchID",$batchID);?></td> -->
                                            <td><?php echo $instructorName;?></td>
                                            <td><?php echo $addButton;?></td>
                                            <!--<td><?php /*echo $viewButton;*/?></td>-->
                                        </tr>

                                    <?php }?>
                                    </tbody>
                                </table></div></div>
                        <?php
                    }
                    else
                    {
                        ?>
                        <h4 class="text-danger">No Course Found</h4>
                        <?php
                    }
                }
            ?>
        </div>
    </div></div>