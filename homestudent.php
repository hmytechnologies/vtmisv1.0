<h1>Dashboard</h1>
<hr>
              <?php
              require_once("DB.php");
              $db = new DBHelper();
              $userID = $_SESSION['user_session'];
               $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
               //studyYear

             //today semester
                   $today=date("Y-m-d");
                   $sm=$db->readSemesterSetting($today);
                   foreach ($sm as $s)
                   {
                       $semisterID=$s['semesterID'];
                       $academicYearID=$s['academicYearID'];
                       $semesterName=$s['semesterName'];
                       $semesterSettingID=$s['semesterSettingID'];
                       $endDateRegistration=$s['endDateRegistration'];
                   }
               
               ?>
              
                <?php
                if(!empty($studentID))
                {
                ?>
              <div class="box box-solid box-success">
                  <div class="box-header with-border text-center">
                      <h3 class="box-title">Personal Information</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <table class="table table-striped table-bordered table-condensed">
                          <thead>
                          <tr>
                              <th>Name</th>
                              <th>Reg.Number</th>
                              <th>Gender</th>
                              <th>Level & Programme</th>
                              <th>Duration</th>
                              <th>Study Year</th>
                              <th>Study Mode</th>
                              <th>Admitted Year</th>
                              <th>School</th>
                              <th>Campus</th>
                              <th>Status</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          $count = 0;
                          foreach ($studentID as $std)
                          {
                          $count++;
                          $studentID = $std['studentID'];
                          $fname = $std['firstName'];
                          $mname = $std['middleName'];
                          $lname = $std['lastName'];
                          $gender = $std['gender'];
                          $regNumber = $std['registrationNumber'];
                          $programmeID = $std['programmeID'];
                          $statusID = $std['statusID'];
                          $batchID = $std['batchID'];
                          $admissionYearID=$std['academicYearID'];
                          $name = "$fname $mname $lname";


                          echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";
                          $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);
                          $level = $db->getRows('programme_level', array('where' => array('programmeLevelID' => $programmeLevelID), ' order_by' => ' programmeLevelCode ASC'));
                          if (!empty($level)) {
                              foreach ($level as $lvl) {
                                  $programme_level_code = $lvl['programmeLevelCode'];
                                  //echo "<td>$programme_level_code</td>";
                              }
                          }

                          $programme = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID), ' order_by' => ' programmeName ASC'));
                          if (!empty($programme)) {
                              foreach ($programme as $pro) {
                                  $programmeName = $pro['programmeName'];
                                  $programmeDuration = $pro['programmeDuration'];
                                  $schoolID=$pro['schoolID'];
                                  $campusID=$pro['campusID'];
                              }
                          }


                          echo "<td>$programme_level_code-$programmeName</td><td>$programmeDuration</td>";


                          $study_year = $db->getRows('student_study_year', array('where' => array('regNumber' => $regNumber, 'studyYearStatus' => 1), ' order_by' => 'studyYear ASC'));
                          if (!empty($study_year)) {
                              foreach ($study_year as $sy) {
                                  $studentStudyYear = $sy['studyYear'];
                                  $studyAcademicYearID=$sy['academicYearID'];
                              }
                          }
                          else
                          {
                              $studentStudyYear = "None";
                              $studyAcademicYearID="";
                          }

                              echo "<td>".$studentStudyYear . "</td>";

                              echo "<td>".$db->getData("batch", "batchName", "batchID", $batchID) . "</td>";
                              echo "<td>".$db->getData("academic_year","academicYear","academicYearID",$admissionYearID)."</td>";
                              echo "<td>".$db->getData("schools","schoolCode","schoolID",$schoolID)."</td>";
                              echo "<td>".$db->getData("campus","campusCode","campusID",$campusID)."</td>";
                              $status = $db->getRows('status', array('where' => array('statusID' => $statusID), ' order_by' => 'status_value ASC'));
                              if (!empty($status)) {
                                  foreach ($status as $st) {
                                      $status_value = $st['statusValue'];
                                      echo "<td>$status_value</td>";
                                  }
                              }

                          /*else {
                              if ($programmeDuration > $studyYear) {
                                  $maxStudyYear = $db->getMaxStudyYear($regNumber);
                                  $studyYear = $maxStudyYear + 1;
                                  $study_data = array(
                                      'regNumber' => $regNumber,
                                      'studyYear' => $studyYear,
                                      'academicYearID' => $academicYearID,
                                      'studyYearStatus' => 1
                                  );
                                  $insert = $db->insert("student_study_year", $study_data);
                              }
                              else {
                                    $studyYear = $studyYear;
                                  }
                          }*/

                             /* $sumofallfees = $db->getAllFees($programmeID);
                              $sumoncefees = $db->getOnceFees($programmeID);

                              $amount = $sumofallfees - $sumoncefees;

                              //Student Account
                              $account_data = array(
                                  'regNumber' => $regNumber,
                                  'studyYear' => $studyYear,
                                  'academicYearID' => $academicYearID,
                                  'amount' => $amount,
                                  'feesDescription' => 'University/Tuition Fees'
                              );
                              if(!empty($sumofallfees)) {
                                  $condition=array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID,'studyYear'=>$studyYear);
                                  $update = $db->update("student_fees",$account_data,$condition);
                              }
                              else {
                                  $insert = $db->insert("student_fees", $account_data);
                              }*/




                              //register exam_number
                              /*$exam_number=$db->getRows("exam_number",array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID)));
                              if(empty($exam_number))
                              {
                                  $exam_data = array(
                                      'programmeID'=>$programmeID,
                                      'semesterSettingID'=>$semesterSettingID,
                                      'regNumber' => $regNumber,
                                      'examNumber'=>$regNumber
                                  );
                                  $insert = $db->insert("exam_number", $exam_data);
                              }*/

                              //end of registration



                          ?>
                          </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <section class="content">
                  <div class="row">
                      <!-- left column -->
                      <div class="col-md-8">

                          <?php
                              if($academicYearID != $studyAcademicYearID)
                              {
                                  if ($programmeDuration > $studentStudyYear) {
                                      $maxStudyYear = $db->getMaxStudyYear($regNumber);
                                      $studyYear = $maxStudyYear + 1;
                                  }
                                  else
                                  {
                                      $studyYear = $studentStudyYear;
                                  }
                              }
                              else {
                                  $studyYear = $studentStudyYear;
                              }
                              $courseList = $db->filterRecords($programmeID, $semesterSettingID, $batchID, $studyYear, $regNumber);
                          if (!empty($courseList)) {
                              ?>
                              <!-- general form elements -->
                              <div class="box box-primary">
                                  <div class="box-header with-border text-center">

                                      <h3 class="box-title">List of Confirmation Course for <?php echo $semesterName; ?>
                                      </h3>
                                  </div>
                                  <!-- /.box-header -->
                                  <!-- form start -->
                                  <form role="form" name="" method="post" action="action_student_confirmation.php">
                                      <div class="box-body">
                                          <table class="table table-striped table-bordered table-condensed">
                                              <thead>
                                              <tr>
                                                  <th>No</th>
                                                  <th></th>
                                                  <th>Course Code</th>
                                                  <th>Course Name</th>
                                                  <th>Course Units</th>
                                                  <th>Course Type</th>
                                                  <th>Course Category</th>
                                              </tr>
                                              </thead>
                                              <tbody>
                                              <?php
                                              $count = 0;
                                              foreach ($courseList as $list) {
                                                  $count++;
                                                  $courseID = $list['courseID'];

                                                  $course_status = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID,'programmeID'=>$programmeID),'order_by'=>'courseStatus ASC'));                    if(!empty($course_status)) {
                                                      foreach ($course_status as $cstatus) {
                                                          $courseStatusID = $cstatus['courseStatusID'];
                                                      }
                                                  }
                                                  else
                                                  {
                                                      $courseStatusID=0;
                                                  }

                                                  echo "<tr><td>$count</td>";

                                                  ?>
                                                  <td>
                                                  <?php if ($courseStatusID == 1 || $courseStatusID == 3) {
                                                      ?>
                                                      <input class="checkbox1" type="checkbox"
                                                             name="courseID[<?php echo $courseID; ?>]"
                                                             value="<?php echo $courseID; ?>" checked="checked"
                                                             readonly="readonly">
                                                  <?php } else {
                                                      ?>
                                                      <input class="checkbox1" type="checkbox"
                                                             name="courseID[<?php echo $courseID; ?>]"
                                                             value="<?php echo $courseID; ?>">
                                                      <?php
                                                  }

                                                  $course = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
                                                  if (!empty($course)) {
                                                      foreach ($course as $c) {
                                                          ?>
                                                          </td>
                                                          <td><?php echo $c['courseCode']; ?></td>
                                                          <td><?php echo $c['courseName']; ?></td>
                                                          <td><?php echo $c['units']; ?></td>
                                                          <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $c['courseTypeID']); ?></td>
                                                          <td><?php echo $db->getData("coursestatus","courseStatusCode","courseStatusID",$courseStatusID); ?></td>
                                                          <?php
                                                      }
                                                  }

                                                  ?>
                                                  <?php
                                                  echo "</tr>";
                                              }
                                              ?>

                                              </tbody>
                                          </table>
                                      </div>

                                      <div class="box-footer">
                                          <input type="hidden" name="action_type" value="add"/>
                                          <!--<input type="hidden" name="courseStatus" value="<?php /*echo $courseStatus;*/?>">-->

                                          <input type="hidden" name="number_student" value="<?php echo $count; ?>">
                                          <input type="hidden" name="studyYear" value="<?php echo $studentStudyYear; ?>">
                                          <input type="hidden" name="programmeID" value="<?php echo $programmeID; ?>">
                                          <input type="hidden" name="academicYearID" value="<?php echo $studyAcademicYearID; ?>">
                                          <input type="hidden" name="semisterID" value="<?php echo $semesterSettingID; ?>">
                                          <input type="hidden" name="regNumber" value="<?php echo $regNumber; ?>">
                                          <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">
                                          <?php
                                          if($today<=$endDateRegistration) {
                                              ?>
                                              <input type="submit" class="btn btn-primary pull-right" value="Confirm">
                                              <?php
                                          }
                                          else
                                          {
                                              ?>
                                              <h4 class="text-danger">Course Registration is closed;<button type="button" class="btn btn-success pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i>Generate Invoice</h4>
                                              <?php
                                           }
                                              ?>
                                      </div>
                                  </form>
                              </div>
                              <?php
                          }
                          ?>




                          <div class="box box-solid box-primary">
                              <div class="box-header with-border">
                                  <h3 class="box-title">List of Registered Course for <?php echo $semesterName; ?>
                                  </h3>
                              </div>
                              <!-- /.box-header -->
                              <!-- form start -->
                              <form role="form">
                                  <div class="box-body">
                                      <?php
                                      $courseList = $db->getRows('student_course', array('where' => array('regNumber' => $regNumber, 'semesterSettingID' => $semesterSettingID), ' order_by' => ' semesterSettingID ASC'));
                                      if (!empty($courseList)) {
                                          ?>
                                          <table class="table table-striped table-bordered table-condensed" >
                                              <thead>
                                              <tr>
                                                  <th>No</th>
                                                  <th>Course Code</th>
                                                  <th>Course Name</th>
                                                  <th>Course Units</th>
                                                  <th>Course Type</th>
                                                  <th>Course Category</th>
                                                  <th>Lecturer</th>
                                                  <th>Action</th>
                                              </tr>
                                              </thead>
                                              <tbody>
                                              <?php
                                              $count = 0;
                                              $total_credits = 0;
                                              foreach ($courseList as $list) {
                                                  $count++;
                                                  $studentCourseID = $list['studentCourseID'];
                                                  $courseID = $list['courseID'];
                                                  $semisterID = $list['semesterSettingID'];
                                                  $sCourseStatus=$list['courseStatus'];


                                                  echo "<tr><td>$count</td>";
                                                  //instructor
                                                 $courseprogramme = $db->getSemesterInstructorCourse($courseID, $semisterID);
                                                  if (!empty($courseprogramme)) {
                                                      foreach ($courseprogramme as $std) {
                                                          $batchID=$std['batchID'];
                                                          $instructor = $db->getRows('instructor_course', array('where' => array('courseID' => $courseID,'batchID'=>$batchID,'semesterSettingID' => $semisterID), 'order_by' => 'courseID ASC'));
                                                          if (!empty($instructor)) {
                                                              foreach ($instructor as $i) {
                                                                  $instructorID = $i['instructorID'];
                                                                  $instructorName = $db->getData("instructor", "instructorName", "instructorID", $instructorID);
                                                              }
                                                          } else {
                                                              $instructorName = "Not assigned";
                                                          }
                                                      }
                                                  }
                                                  $course = $db->getRows('course', array('where' => array('courseID' => $courseID), ' order_by' => ' courseName ASC'));
                                                  if (!empty($course)) {
                                                      foreach ($course as $c) {
                                                          $total_credits += $c['units'];
                                                      }
                                                  }
                                                  else
                                                  {
                                                      $total_credits=0;
                                                  }

                                                  ?>
                                                  <td><?php echo $c['courseCode']; ?></td>
                                                  <td><?php echo $c['courseName']; ?></td>
                                                  <td><?php echo $c['units']; ?></td>
                                                  <td><?php echo $db->getData("course_type", "courseType", "courseTypeID", $c['courseTypeID']); ?></td>
                                                  <td><?php echo $db->getData("coursestatus","courseStatusCode","courseStatusID",$sCourseStatus); ?></td>
                                                  <td><?php echo $instructorName; ?></td>
                                                  <?php
                                                  if ($sCourseStatus == 1 || $sCourseStatus == 3) {
                                                      ?>
                                                      <td>No</td>
                                                      <?php
                                                  } else {
                                                      ?>
                                                      <td>
                                                          <?php
                                                         if($today<=$endDateRegistration) {
                                                              ?>
                                                              <a href="action_student_confirmation.php?action_type=drop&id=<?php echo $studentCourseID; ?>"
                                                                 class="glyphicon glyphicon-trash"
                                                                 onclick="return confirm('Are you sure you want to drop this course?');"
                                                                 title="Drop Course"></a>
                                                              <?php
                                                          }
                                                          else
                                                          {
                                                              echo "No";
                                                          }
                                                              ?>
                                                      </td>
                                                      <?php
                                                  }
                                                  echo "</tr>";
                                              }
                                              ?>

                                              </tbody>
                                              <tfoot>
                                              <tr>
                                                  <th colspan=3>Total Number of Credits:
                                                  </th>
                                                  <th colspan=5><?php echo $total_credits; ?></th>
                                              </tr>
                                              </tfoot>
                                          </table>
                                          <?php
                                      } else {
                                          echo "<h4 class='text-danger'>No Course Registered for the Academic Year</h4>";
                                      }

                                      }
                          ?>
                      </div>
                      <!-- /.box-body -->


                  </form>
              </div>

                    <?php
                    ?>

          </div>

                    <?php
                  //debit
                  $debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>' regNumber ASC'));
                  if(!empty($debit))
                  {
                      $totalFeesC=0;
                      foreach($debit as $dbt)
                      {
                          $studentFeesID=$dbt['studentFeesID'];
                          $amount=$dbt['amount'];
                          $dacademicYearID=$dbt['academicYearID'];
                          $totalFeesC+=$amount;
                      }
                  }
                  else
                  {
                      $totalFeesC = 0;
                  }
                  //Payment
                  $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID),'order_by'=>'paymentDate   ASC'));
                  if(!empty($paymentList))
                  {
                      $totalSemester=0;
                      foreach($paymentList as $list)
                      {
                          $amount=$list['amount'];
                          $totalSemester+=$amount;
                      }
                  }
                  else
                  {
                      $totalSemester=0;
                  }

                  //payment setting
                  $payment_setting = $db->getRows('payment_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'paymentSettingID   ASC'));
                  if(!empty($payment_setting)) {
                      foreach ($payment_setting as $ps) {
                          $mAmount = $ps['minimumAmount'];
                          $penalty = $ps['penalty'];
                          $endDateF = $ps['endDate'];
                      }
                  }
                  else
                  {
                      $mAmount=number_format($totalFeesC/2,2);
                      $penalty = 0;
                      $endDateF = "";
                  }

                  //discount
                  $discount= $db->getRows('student_discount',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'semesterSettingID   ASC'));
                  if(!empty($discount))
                  {
                      foreach($discount as $ps)
                      {
                          $dsemesterSettingID=$ps['semesterSettingID'];
                          if($dsemesterSettingID==$semesterSettingID)
                          {
                            $amountPercent=$ps['amountPercent'];
                          }
                          else
                          {
                            $amountPercentP=$ps['amountPercent'];
                          }
                      }
                  }
                  else
                  {
                      $amountPercent=0;
                      $amountPercentP=0;
                  }

                  $totalSemesterDebit=($mAmount/100)*($totalFeesC);

                  if($amountPercent==0)
                     $requiredTotalSemesterDebit=$totalSemesterDebit;
                  else
                    $requiredTotalSemesterDebit=($amountPercent/100)*$totalSemesterDebit;

                  $balance=($totalFeesC/2)-$totalSemester;
                  ?>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Examination Information for <?php echo $semesterName;?></h3>
                                </div>
                                <form class="form-horizontal">
                                    <div class="box-body">
                  <?php
                  if($balance >= 0) {
                      ?>
                    <h5 style="text-align: center;" class="text-danger">Confirm the following details to get Examination Number</h5>
                      <div class="box-body table-responsive">
                          <table class="table table-striped table-bordered table-condensed">
                              <tr>
                                  <th>Item</th>
                                  <th>Amount</th>
                                  <th>Description</th>
                              </tr>
                              <tr>
                                  <td>Total Fees:</td>
                                  <td>
                                      <strong><?php echo number_format($totalFeesC); ?></strong>
                                  </td>
                                  <td>University Fees</td>
                              </tr>

                              <tr>
                                  <td>Semester Fees:</td>
                                  <td>
                                      <strong><?php echo number_format($totalFeesC / 2); ?></strong>
                                  </td>
                                  <td>Required Fees per Semester</td>
                              </tr>

                              <tr>
                                  <td>Amount Paid:</td>
                                  <td>
                                      <strong><?php echo number_format($totalSemester); ?></strong>
                                  </td>
                                  <td>Paid Amount</td>
                              </tr>

                              <tr>
                                  <td>Balance this Semester:</td>
                                  <td>
                                      <strong><?php
                                          //$balance = $totalFeesP / 2 + $penalty1 + $penalty2 - $totalSemester;
                                          $balance = ($totalFeesC / 2)- $totalSemester;

                                          echo number_format($balance); ?></strong>
                                  </td>
                                  <td>Remaining Balance</td>

                              </tr>
                          </table>
                      </div>
                      <h4 class="text-danger">Your Semester Registration is Incomplete, You can only complete semester registration
                          if you have cleared your semester outstanding balance.<br><br><!--Please
                          <a href='complete_semester_registration.php?action_type=register&sid=<?php /*echo $db->encrypt($semesterSettingID);*/?>&regno=<?php /*echo $db->my_simple_crypt($regNumber,'e');*/?>'>Click Here
                          </a> to get your Examination Number--></h4>

                      <!--<h5 class="text-danger">Your Semester Registration is Incomplete, You can only complete semester registration if you have cleared your semester outstanding balance to Chief Accountant
                          Please <a href='complete_semester_registration.php?action_type=register&sid=<?php /*echo $db->encrypt($semesterSettingID);*/?>&regno=<?php /*/*echo $db->my_simple_crypt($regNumber,'e');*/?>'>
                          Click Here</a> to Complete Semester Registration</h4>
-->
                      <?php
                  }
                  else {
                      ?>
                        <?php
                      $exam_number = $db->getRows("exam_number", array('where' => array('regNumber' => $regNumber, 'semesterSettingID' => $semesterSettingID)));
                      if (!empty($exam_number)) {
                          foreach ($exam_number as $enumber) {
                              $exam_number = $enumber['examNumber'];
                              ?>
                              <h4 class="text-danger">Your Exam Number is: <?php echo $exam_number; ?></h4>
                              <?php
                          }
                      }
                  }
                  ?>
                      <!-- <h4 class="text-danger">Your Semester Registration is Incomplete, You can only complete semester registration
                if you have cleared your semester outstanding balance.<br><br>Please <a href='complete_semester_registration.php?action_type=register&sid=<?php /*echo $db->encrypt($semesterSettingID);*/?>&regno=<?php /*echo $db->my_simple_crypt($regNumber,'e');*/?>'>Click Here</a> to Complete Semester Registration</h4>
              --><?php

              ?>
              </div>
            </form>
          </div>
          </div>


      <!--<div class="row">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Performance Summary</h3>
            </div>
            <form class="form-horizontal">
              <div class="box-body">
              </div>
              <div class="box-footer">
                 <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i>View Full Report
          </button>
              </div>
            </form>
          </div>
          </div> -->
          </div></div>
      </section>
                <?php }?>