<?php
/**
 * Created by PhpStorm.
 * User: massoudhamad
 * Date: 11/3/18
 * Time: 6:40 PM
 */
?>
            <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
      <script type="text/javascript">
             $(document).ready(function()
              {
              $("#academicYearID").change(function()
              {
              var academicYearID=$(this).val();
              var semisterID=$("#semisterID").val();

              var dataString = 'academicYearID='+ academicYearID+'&semisterID='+semisterID;

              $.ajax
              ({
              type: "POST",
              url: "ajax_student_course.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#courseID").html(html);
              }
              });

              });

              });
        </script>

<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
      <h1>Results Publishing</h1>
      <hr>
          <div class="row">
          <form name="" method="post" action="">
          <div class="col-lg-12">
            <div class="row">
            <div class="col-lg-3">

                         <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control" required>
                              <?php
                               $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                               if(!empty($programmes)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeName'];
                                $programme_id=$prog['programmeID'];
                               ?>
                               <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
            <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semesterID" id="semesterID" class="form-control">
                              <?php
                                 $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semesterName'];
                                  $semister_id=$sm['semesterSettingID'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
             </div>

             <div class="col-lg-3">
                            <label for="LastName">Mode of Enrollment</label>
                            <select name="batchID" id="batchID"  class="form-control" required>
                  			<?php
                               $batch = $db->getRows('batch',array('order_by'=>'batchName ASC'));
                               if(!empty($batch)){
                                   echo "<option value=''>Select Here</option>";
                                   $count = 0; foreach($batch as $btc){ $count++;
                                   $batchID=$btc['batchID'];
                                   $batchName=$btc['batchName'];
                               ?>
                               <option value="<?php echo $batchID;?>"><?php echo $batchName;?></option>
                               <?php }}?>
							</select>
                        </div>

                  </div>
					<div class="row">
                    <div class="col-lg-6"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                      <div class="col-lg-6"></div>

        </div>
      </div>
        </form>
        </div>
        <div class="row">

        <?php
        if(isset($_POST['doFind'])=="View Records")
        {
                $semesterSettingID=$_POST['semesterID'];
                $programmeID=$_POST['programmeID'];
                $batchID=$_POST['batchID'];

                $courseprogramme = $db->getSemesterProgrammeCourse($programmeID,$semesterSettingID,$batchID);
                if(!empty($courseprogramme))
                {
                 ?>
                    <h3 id="titleheader">Registered Course for <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>-<?php echo $db->getData("batch","batchName","batchID",$batchID);?>
                        -<?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);?></h3>
                    <hr>

                    <form name="register" id="register" method="post" action="action_publish.php">
             <table  id="exampleexampleexample" class="display nowrap">
                      <thead>
                      <tr>
                      	<th>No.</th>
                      	<th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                        <th>Course Name</th>
                        <th>Course Code</th>
                        <th>#Students</th>
                        <th>Batch</th>
                        <th>Study Year</th>
                        <th>Lecturer</th>
                        <th>Published</th>
                      </tr>
                      </thead>
                      <tbody>
            <?php
            $count=0;
            foreach($courseprogramme as $cs)
                 {
                     $count++;
                     $courseID=$cs['courseID'];
                     $batchID=$cs['batchID'];
                     $studyYear=$cs['studyYear'];
                     $courseProgrammeID=$cs['courseProgrammeID'];
                     $course=$db->getRows("course",array('where'=>array('courseID'=>$courseID),' order_by'=>'courseName ASC'));
                     if(!empty($course))
                     {
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
                             $statusPublished="<span class='label label-success'>Yes</span>";
                        else
                             $statusPublished="<span class='label label-danger'>No</span>";

                       ?>
                       <tr>
                         <td><?php echo $count;?></td>
                         <?php
                         if($boolExamStatus==false)
                         {
                         ?>
                         <td>NA</td>
                         <?php
                         }
                         else
                         {
                         ?>
                         <td><input type='checkbox' class='checkbox_class' name='id[]' value='<?php echo $courseID;?>'></td>
                         <?php
                         }?>
                         <td><?php echo $courseName;?></td>
                         <td><?php echo $courseCode;?></td>
                         <td><?php echo $studentNumber;?></td>
                         <td><?php echo $db->getData("batch","batchName","batchID",$batchID);?></td>
                         <td><?php echo $studyYear;?></td>
                         <td><?php echo $instructorName;?></td>
                         <td><?php echo $statusPublished;?></td>
                         </tr>
                       <?php

                            }
                      }

                 ?>
                        </tbody>
             </table>
            <div class="row">
            <div class="col-lg-6"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $count;?>">
            <input type="hidden" name="semesterID" value="<?php echo $semesterSettingID;?>">
            <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doAdmit" value="Publish" class="btn btn-success form-control">
            </div>
             <div class="col-lg-3">
                 <input type="hidden" name="action_type" value="edit"/>
                <input type="submit" name="doReject" value="Unpublish" class="btn btn-danger form-control">
            </div>
        </div>
        </form>
                        <?php
             }
             else
             {
                 echo "<h3 class='text-danger'>No Course Found</h3>";
             }


        }
        ?>

        </div>
        </div>
        </div>