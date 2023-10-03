<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>

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
        <h1>Supp/Special Results Management</h1>
        <hr>
        <h3>Manage results by course or by individual student</h3>
                <h3>Choose Semester</h3>
                <div class="row">
                    <form name="" method="post" action="" onsubmit="return view_supp_special_list();">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="MiddleName">Academic Year</label>
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
                                    <label for=""><br></label>
                                    <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="result">

                        <?php
                            if (isset($_POST['doSearch']) == "Search Records") {
                                $academicYearID = $_POST['academicYearID'];
                                $semester= $db->getRows('semester_setting',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYearID ASC'));
                                
                                foreach($semester as $sm)
                                    {
                                        // $semisterID=$sm['semesterID'];
                                        // $academicYearID=$sm['academicYearID'];
                                        // $semesterName=$sm['semesterName'];
                                    $semesterSettingID=$sm['semesterSettingID'];
                                    }

                                 $courseprogramme = $db->getMappingCourseList($academicYearID, $_SESSION['main_role_session'], $_SESSION['department_session']);
                                //  $courseprogramme= $db->getRows('center_programme_course',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYearID ASC'));

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
                                                <h3 class="box-title">Registered Course for <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
                                                <table id="example" class="table table-striped table-bordered table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Course Name</th>
                                                            <th>Course Code</th>
                                                            <th>Number of Students</th>
                                                            <!-- <th>Slot Name</th> -->
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


                                                                        $studentNumber = $db->getStudentNumber($academicYearID, $programmeLevelID, $programmeID);

                                                                        $boolExamStatus = $db->checkFinalExamResultStatus($courseID, $academicYearID, $programmeID, $programmeLevelID);
                                                                        
                                                                       // $student=$db->getRows('student',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>' registrationNumber ASC'));
                                                                        // if(!empty($student))
                                                                        // {
                                                                        //     $studentNo=0;
                                                                        //     foreach($student as $st)
                                                                        //     {
                                                                        //         $regNumber=$st['registrationNumber'];

                                                                               
                                                                        //         $examNumber = $db->getExamNumber($regNumber, $academicYearID);

                                                                        //         $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 1));
                                                                        //         $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 2));
                                                                        //         $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));
                                                                               
                                                                        //         $exam_category_marks = $db->getTermCategorySetting();
                                                                        //             if (!empty($exam_category_marks)) {
                                                                        //                 foreach ($exam_category_marks as $gd) {
                                                                        //                     $mMark = $gd['mMark'];
                                                                        //                     $pMark = $gd['passMark'];
                                                                        //                     $wMark = $gd['wMark'];
                                                                        //                 }
                                                                        //             }

                                                                        //             $term1m = ($term1Score / $mMark) * $wMark;
                                                                        //             $term2m = ($term2Score / $mMark) * $wMark;
                                                                                
                                                                        //             $finalm = ($finalScore / 100) * 50;
                                                                        //             $totalMarks = $term1m + $term2m + $finalm;

                                                                        //               if(($totalMarks) < 40)
                                                                        //             $studentNo += 1;
                                                                               
                                                                        //         // $cwk = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 1));
                                                                        //         // $sfe = $db->decrypt($db->getFinalGrade($semesterSettingID, $courseID, $regNumber, 2));
                                                                        //         // $prj = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 5));
                                                                        //         // $pt = $db->decrypt($db->getGrade($semesterSettingID, $courseID, $regNumber, 6));
                                
                                
                                                                        //         // $present=$db->getStudentExamStatus($regNumber,$courseID,$semesterSettingID,2);
                                
                                                                        //         // if(($cwk+$sfe) < 40)
                                                                        //         //     $studentNo += 1;
                                                                        //         // if($present == 0)
                                                                        //         //     $studentNo += 1;
                                                                        //     }
                                                                        // }
                                                                        // $student=$db->getRows('student',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>' registrationNumber ASC'));
                                                                        //     if(!empty($student))
                                                                        //     {
                                                                        //         $studentNo=0;
                                                                        //         foreach($student as $st)
                                                                        //         {
                                                                        //           $regNumber=$st['registrationNumber'];
                                                                        //            echo  $sfe = $db->decrypt($db->getFinalGrade($academicYearID, $courseID, $regNumber, 3));
                                                                        //             //   $examNumber = $db->getExamNumber($regNumber, $academicYearID);

                                                                        //             // $term1Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 1));
                                                                        //             // $term2Score = $db->decrypt($db->getTermGrade($academicYearID, $courseID, $regNumber, 2));
                                                                        //             // $finalScore = $db->decrypt($db->getFinalTermGrade($academicYearID, $courseID, $examNumber, 3));

                                                                        //             // $term1m = ($term1Score / $mMark) * $wMark;
                                                                        //             // $term2m = ($term2Score / $mMark) * $wMark;
                                            
                                                                        //             // $finalm = ($finalScore / 100) * 50;
                                                                        //             // $totalMarks = $term1m + $term2m + $finalm;


                                                                        //             // if(($totalMarks) < 40)
                                                                        //             //     $studentNo += 1;
                                                                               
                                                                        //         }
                                                                        //     }

                                                                             $published=$db->checkStatus($courseID,$semesterSettingID,'status');


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
                                                                            } 
                                                                         ?>

                                                                                

                                                                            <tr>
                                                                                    <td><?php echo $count;?></td>
                                                                                    <td><?php echo $courseName;?></td>
                                                                                    <td><?php echo $courseCode;?></td>
                                                                                    <td><?php echo $studentNumber;?></td>
                                                                                    <!-- <td><#?php echo $db->getData("batch","batchName","batchID",$batchID);?></td> -->
                                                                                    <td><?php echo $instructorName;?></td>
                                                                                    <td><?php echo $addButton; echo  $viewButton;?></td>
                                                                                    <!--<td><?php /*echo $viewButton;*/?></td>-->
                                                                                </tr>



                                                                <?php

                                                                     }
                                                             
                                                             
                                                                ?>

                                                     </tbody>
                                                </tabe>
                                           
                                            </div>
                                        </div>



                                        

                                    <?php 
                                         }
                                      }
                                    ?>

                        </div>
                    </div>
                </div>
    </div></div>

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-time">
                    </span>Please wait...page is loading
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped active"
                         style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>