<h4 class="text-info">View Student Result Searching Student</h4>
<div class="form-group">
<form name="" method="post" action="">
<div class="col-xs-12">
   <label class="col-xs-3 control-label"> Enter Student Reg.Number:</label>
	<div class="col-xs-4">
		<input type="text" name="search_student" id="search_text" class="form-control">
	</div>
	<div class="col-xs-4"><input  type="submit" class="btn btn-success" name="doSearch" value="Search Student"/>
	</div>
	</div>
	</form>
</div>
<br><br>

    <?php
    $db=new DBhelper();
    if((isset($_POST['doSearch'])=="Search Student"))
    {
        $searchStudent=$_POST['search_student'];
        /*$searchStudent=$_REQUEST['search_student'];*/

        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
        ?>

        <?php
        if(!empty($studentID))
        {

            ?>
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Personal Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Reg.No</th>
                            <th>Gender</th>
                            <th>Level</th>
                            <th>Programme Name</th>
                            <!--  <th>Programme Duration</th> -->
                            <!-- <th>Study Year</th> -->
                            <th>Study Mode</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach($studentID as $std)
                        {
                                 $count++;
                      $studentID=$std['studentID'];
                      $fname=$std['firstName'];
                      $mname=$std['middleName'];
                      $lname=$std['lastName'];
                      $gender=$std['gender'];
                      $regNumber=$std['registrationNumber'];
                      $statusID=$std['statusID'];
                      $academicYearID=$std['academicYearID'];
                      $name="$fname $mname $lname";

                      
                     echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";

                    $student_programme=$db->getRows("student_programme",array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID)));
                    if(!empty($student_programme)) {
                        foreach ($student_programme as $sp) {
                            $programmeID = $sp['programmeID'];
                            $programmeLevelID = $sp['programmeLevelID'];
                        }
                    }


                     $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
		                if(!empty($level))
		                {
		                	foreach ($level as $lvl) {
		                		$programme_level_code=$lvl['programmeLevelCode'];

		                	}
		                }

		               $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		$programmeDuration=$pro['programmeDuration'];

		                	}
		                }

		                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'studentID ASC'));
                            if(!empty($study_year))
                            {
                                foreach ($study_year as $sy)
                                {
                                    $studyYear=$sy['studyYear'];
                                }
                            }
                           
		                $status= $db->getRows('status',array('where'=>array('statusID'=>$statusID),' order_by'=>'status_value ASC'));
		                if(!empty($status))
		                {
		                    foreach ($status as $st) {
		                        $status_value=$st['statusValue'];

		                    }
		                }

                        echo "<td>$programme_level_code</td>";echo "<td>$programmeName</td>";echo "<td>$studyYear</td>"; echo "<td>$status_value</td>";  
		                
                    }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>


            <div class="row">

                <?php
                $semester=$db->getSemester1($regNumber);
                if(!empty($semester))
                {
                    ?>
                    <div class="col-md-9">
                        <?php
                        $totalPoints=0;
                        $totalUnits=0;
                        foreach($semester as $sm)
                        {
                            $semesterSettingID=$sm['semesterSettingID'];
                             $academicYear=$sm['academicYearID'];
                            $examCategoryID=$sm['examCategoryID'];
                            // $semesterName=$sm['examCategory'];
                            
                            $co = $db->getStudentSearchResult($regNumber,$semesterSettingID,$academicYear,$examCategoryID);
                            if(!empty($co))
                            {
                                ?>

                                <div class="box box-solid box-primary">
                                    <div class="box-header with-border text-center">
                                        <h3 class="box-title">Exam Result for 
                                    
                                        <?php echo $db->getData("exam_category", "examCategory", "examCategoryID", $examCategoryID);
                                        echo " ";
                                     echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYear); ?>
                                    
                                    </h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body table-responsive">
                                        <table  id="" class="table table-striped table-bordered table-condensed">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Status</th>
                                                <th>Units</th>
                                                <th>Total Marks</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $count = 0;
                                            $i=1;
                                            $tunits=0;
                                            $tpoints=0;
                                            foreach($co as $st)
                                            {
                                                $count++;
                                                $courseID=$st['courseID'];
                                                $courseCategoryID=$st['courseCategoryID'];
                                                $academicYearID=$st['academicYearID'];
                                                $academic_year=$st['academic_year'];
                                                $courseCategoryID=$st['courseCategoryID'];
                                                // // $crstatus=$st['courseStatus'];
                                                // ELECT DISTINCT(courseCode) ,er.courseID,courseName,c.courseCategoryID,courseCategory,courseCode,units ,
                                                // er.examScore,semesterSettingID,cc.courseCategoryID,er.academicYearID 
                                                //    from 
                                             $coursec= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                                                if(!empty($coursec))
                                                {
                                                    ?>
                                                    <?php
                                                    $i=1;
                                                    foreach($coursec as $c)
                                                    {
                                                    }
                                                }
                                                $courseCode=$c['courseCode'];
                                                $courseName=$c['courseName'];
                                                $units=$c['units'];
                                                $courseCategoryID=$c['courseCategoryID'];
                                                $courseName=$c['courseName'];

                                                /*if($crstatus==1)
                                                    $cStatus="Core";
                                                else
                                                    $cStatus="Elective";*/
                                                $courseStatus = $db->getRows('course_category', array('where' => array('courseCategoryID' => $courseCategoryID), ' order_by' => ' courseStatusID ASC'));
                                                foreach ($courseStatus as $cs) {
                                                    $courseCategory = $cs['courseCategory'];
                                                    $courseCategory = $cs['courseCategoryID'];
                                                    if ($courseCategory == 1)
                                                        $status = "Core Subjects";
                                                    else
                                                        $status = "General Subjects";
                                                }
                                                ?>

                                                <tr>
                                                    <?php
                                                      $termScore = $db->decrypt($db->getTermGrade($academicYear, $courseID, $regNumber, $examCategoryID));
                                                     
                                                      $exam_category_marks = $db->getTermCategorySetting();
                                                      if (!empty($exam_category_marks)) {
                                                          foreach ($exam_category_marks as $gd) {
                                                              $mMark = $gd['mMark'];
                                                              $pMark = $gd['passMark'];
                                                              $wMark = $gd['wMark'];
                                                          }
                                                      }

                                                      $term = ($termScore / $mMark) * $wMark;
                                                      $totalMark = round($term);
                                                      $grade = $db->calculateTermGrade($termScore);

                                                      if ($totalMark >= 12 && $totalMark <= 25)
                                                        $remarks = "Pass";
                                                    else if($totalMark <= 11 && $totalMark > 9)
                                                        $remarks = "Fair";
                                                    else 
                                                        $remarks="Fail";

                                                    echo"<td>$count</td><td>$courseCode</td><td>$courseName</td><td>$status</td><td>$units</td>
                                                    <td>$totalMark</td> <td>$grade</td> <td>$remarks</td>";
                                                    $tunits+=$units;
                                                    // $totalPoints+=$units;
                                                    //include("grade.php");
                                                    // getMarksGrade($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)

                                                    
                                                  

                                                    // echo "<td>".$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>
                                                    // <td>".$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>
                                                    // <td>".$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";

                                                    ?>

                                                   
   
                                                
                                                    <td>
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $courseID;?>">
                                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                                            <span><strong></strong></span>
                                                        </button>

                                                        <?php
                                                        $published=$db->checkStatus($courseID,$semesterSettingID,'status');
                                                        if($published==1)
                                                        {
                                                            if($role_session==7) {
                                                                ?>
                                                                <button type="button" class="btn btn-success" data-toggle="modal"
                                                                        data-target="#message<?php echo $courseID; ?>">
                                                                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                                                    <span><strong></strong></span>
                                                                </button>
                                                                <?php
                                                            }
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $courseID;?>">
                                                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                                <span><strong></strong></span>
                                                            </button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>



                                                <!-- Result Details -->
                                                <div id="message<?php echo $courseID;?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Details of <?php echo $courseCode."-".$courseName;?></h4>
                                                            </div>
                                                            <form name="register" id="register" enctype="multipart/form-data" method="post" action="action_add_exmption.php">
                                                                <div class="modal-body">
                                                                    <div class="row"  style="background-color:lightgray;">
                                                                        <div class="col-lg-1"><strong>No</strong></div>
                                                                        <div class="col-lg-3"><strong>Ass.Title</strong></div>
                                                                        <div class="col-lg-2"><strong>Max.Marks</strong></div>
                                                                        <div class="col-lg-2"><strong>Present</strong></div>
                                                                        <div class="col-lg-2"><strong>Scored</strong></div>
                                                                        <div class="col-lg-2"><strong>Weights</strong></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-1">1</div>
                                                                        <div class="col-lg-3">Course Work</div>
                                                                        <div class="col-lg-2">25</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($cwk>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $cwk;?></div>
                                                                        <div class="col-lg-2"><?php echo $cwk;?></div>
                                                                    </div>
                                                                    <div class="row" style="background-color:lightgray;">
                                                                        <div class="col-lg-1">2</div>
                                                                        <div class="col-lg-3">Final Exam</div>
                                                                        <div class="col-lg-2">50</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($sfe>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $sfe;?></div>
                                                                        <div class="col-lg-2"><?php echo $sfe;?></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-1">3</div>
                                                                        <div class="col-lg-3">Supplementary</div>
                                                                        <div class="col-lg-2">40</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($sup>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $sup;?></div>
                                                                        <div class="col-lg-2"><?php echo $sup;?></div>
                                                                    </div>

                                                                    <div class="row" style="background-color:lightgray;">
                                                                        <div class="col-lg-1">4</div>
                                                                        <div class="col-lg-3">Special Exam</div>
                                                                        <div class="col-lg-2">50</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($spc>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $spc;?></div>
                                                                        <div class="col-lg-2"><?php echo $spc;?></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-1">5</div>
                                                                        <div class="col-lg-3">Project</div>
                                                                        <div class="col-lg-2">100</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($pro>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $prj;?></div>
                                                                        <div class="col-lg-2"><?php echo $prj;?></div>
                                                                    </div>

                                                                    <div class="row" style="background-color:lightgray;">
                                                                        <div class="col-lg-1">6</div>
                                                                        <div class="col-lg-3">Field Training</div>
                                                                        <div class="col-lg-2">100</div>
                                                                        <div class="col-lg-2">
                                                                            <?php
                                                                            if($pt>0)
                                                                                echo "Yes";
                                                                            else
                                                                                echo "No";
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-lg-2"><?php echo $pt;?></div>
                                                                        <div class="col-lg-2"><?php echo $pt;?></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-10">
                                                                            <strong><span class="text-danger">Total Marks:</span></strong>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <strong><span class="text-danger"><?php echo $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);?></span></strong>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- End of Result Details -->
                                                <?php
                                            }



                                            
                                            $totalPoints+=$tpoints;
                                            $totalUnits+=$tunits;
                                            ?>
                                            <tr>
                                                <!-- <td colspan="2" align="left" style="font-size: 20px;">
                                                    <strong><span class="text-danger">Total Credits:<?php echo $tunits;?></span></strong>
                                                </td>
                                                <td colspan="2" align="left" style="font-size: 20px;">
                                                    <strong><span class="text-danger">Total Points:<?php echo $tpoints;?></span></strong>
                                                </td>
                                                <td colspan="3" align="left" style="font-size: 20px;">
                                                    <strong><span class="text-danger">GPA:<?php echo $db->getGPA($tpoints, $tunits);?></span></strong>
                                                </td> -->
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                                <?php
                                // echo $semesterSettingID;
                            }
                            else
                            {
                                ?>
                                <h4 class="text-danger">No Result(s) found......</h4>
                                <?php
                            }
                            ?>

                            <?php
                        }
                        
                       
                        ?>
                    </div>
                    
                    <?php
                }
                else
                {
                    echo "<h3 class='text-danger'>No Result Found</h3>";
                }
                ?>
            </div>
            <?php
        }
        else
        {
            echo "<h3 class='text-danger'>No Student Found with Reg.Number: ".$searchStudent."</h3>";
        }
    }
    ?>

