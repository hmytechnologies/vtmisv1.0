    <h3>Previous Semester</h3>
    <div class="row">
        <form name="" method="post" action="">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="FirstName">Semester Name</label>
                        <select name="semisterID" id="semisterID" class="form-control">
                            <?php
                            $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                            if(!empty($semister)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($semister as $sm){ $count++;
                                    $semister_name=$sm['semesterName'];
                                    $semister_id=$sm['semesterSettingID'];
                                    ?>
                                    <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
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
        if(isset($_POST['doFind'])=="Find Records")
        {
            $semesterID=$_POST['semisterID'];
            $semester=$db->getRows("semester_setting",array('where'=>array('semesterSettingID'=>$semesterID),'order_by semesterName ASC'));
            if(!empty($semester))
            {
                foreach($semester as $sm)
                {
                    $semisterID=$sm['semesterID'];
                    $academicYearID=$sm['academicYearID'];
                    $semesterName=$sm['semesterName'];
                    $semesterSettingID=$sm['semesterSettingID'];
                }

                $courseprogramme = $db->getSemesterCourse($semesterSettingID,$_SESSION['main_role_session'],$_SESSION['department_session']);
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
                            <h3 class="box-title">Registered Course for <?php echo $semesterName;?></h3>
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
                                    <th>Bulk Post</th>
                                    <th>View</th>
                                    <th>Published</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($courseprogramme as $std)
                                {
                                    $count++;
                                    $courseID=$std['courseID'];
                                    $batchID=$std['batchID'];
                                    /*            $courseProgrammeID=$std['courseProgrammeID'];*/

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

                                    $instructor = $db->getRows('instructor_course',array('where'=>array('courseID'=>$courseID,'batchID'=>$batchID,'semesterSettingID'=>$semesterSettingID),'order_by'=>'courseID ASC'));
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


                                    if($checked==1)
                                        $statusCheck="<span class='text-success'>Yes</span>";
                                    else
                                        $statusCheck="<span class='text-danger'>No</span>";

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
                                        <td><?php echo $statusPublished;?></td>
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
        }
        ?>
    </div>