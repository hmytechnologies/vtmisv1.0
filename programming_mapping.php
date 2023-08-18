<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>

<?php $db=new DBHelper();

?>
<div class="container">
  <h1>Trade Course Mapping</h1>
  <a href="index3.php?sp=sysconf" class="btn btn-warning pull-right">Back to Main Setting</a>
  <br>
  <hr>
    <div class="col-md-12"> 
    <div class="row">
      
           <h3>Select Programme to Map with Courses</h3>
           
            <div class="row">
            <form name="" method="post" action="">
                       <div class="col-lg-4">
                           <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control chosen-select" required="">
                              <?php
                              if($_SESSION['role_session']==9) {
                                  $programmes = $db->getRows('programmes', array('where'=>array('schoolID'=>$_SESSION['department_session']),'order_by' => 'programmeName ASC'));
                              }else
                              {
                                  $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                              }
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
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" /></div>
          </form>          
        </div>
        <div class="row">
            <hr>
        </div>
        <div class="row">
            <?php
            //Save Records Buttoon

            if((isset($_POST['doSearch'])=="Search Records")||(isset($_REQUEST['action'])=="getRecords"))
            {
                if(isset($_POST['doSearch'])=="Search Records") {
                    $programmeID = $_POST['programmeID'];
                }
                else {
                    $programmeID = $_REQUEST['programmeID'];
                }
                ?>
                <div class="row"><h4 class="text-info">Register New Course for:
                <?php
                $data= $db->getRows("programmes",array('where'=>array('programmeID'=>$programmeID),' order_by'=>'programmeName ASC'));
               if(!empty($data))
               { 
                    $count = 0; 
                    foreach($data as $dt)
                    { 
                        $count++;
                        $programme_name=$dt['programmeName'];  
                        echo $programme_name;
                    }
                }
                ?></h4></div>



               <form name="" method="post" action="action_programme_mapping.php"> 
               <div class="row">
                   <div class="col-lg-3">
                            <label for="MiddleName">Course Name</label>
                            
                            <select name="courseID" class="form-control chosen-select" required="">
                            
                              <?php
                              $course = $db->filterCourse($programmeID);
                               if(!empty($course)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($course as $c){ $count++;
                                $course_name=$c['courseName'];
                                $course_code=$c['courseCode'];
                                $course_id=$c['courseID'];
                               ?>
                               <option value="<?php echo $course_id;?>"><?php echo $course_code."-".$course_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Trade Level</label>
                            <select name="programme_level_id[]" class="form-control chosen-select" multiple required>
                              <?php
                                 $programmeLevel2 = $db->getRows('programme_trade_level',array('where'=>array('programmeID'=>$programmeID),'order_by'=>'programmeID ASC'));
                                 if(!empty($programmeLevel2)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($programmeLevel2 as $plevel2){ $count++;
                                         $programmeLevelID2=$plevel2['programmeLevelID'];
                                         $programmeLevelName2=$db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID2);
                                         ?>
                                         <option value="<?php echo $programmeLevelID2;?>"><?php echo $programmeLevelName2;?></option>
                                <?php
                                    }
                                 }
                                 ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Course Category</label>
                            <select name="courseStatusID" class="form-control" required>
                              <?php
                                 $course_status = $db->getRows('course_category',array('order_by'=>'courseCategoryID DESC'));
                                 if(!empty($course_status)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($course_status as $cstatus){ $count++;
                                  $courseStatus=$cstatus['courseCategory'];
                                  $courseStatusID=$cstatus['courseCategoryID'];
                                 ?>
                                 <option value="<?php echo $courseStatusID;?>"><?php echo $courseStatus;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                      </div>
                 <br>
                  <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="reset" value="Cancel" class="btn btn-danger form-control" />
                        </div>
                    </div>
                </form>
<div class="row">
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Mapping data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Mapping Data has been delete successfully</strong>.
</div>";
  }
}
?> 
</div>
                <div class="row">
                <?php
                       $data= $db->getRows("programmemaping",array('where'=>array('programmeID'=>$programmeID),' order_by'=>'studyYear ASC'));
                       if(!empty($data))
                       {
                       ?>
                       <h4 class="text-info">List of Registerd Course for:<?php echo $programme_name;?></h4>

                       <table  id="example" class="display nowrap">
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Course Code</th>
                          <th>Course Name</th>
                            <th>Course Type</th>
                            <th>Course Credits</th>
                          <th>Level</th>
                          <th>Course Category</th>
                          <th>Status</th>
                          <th>Action</th>
                           </tr>
                        </thead>

                        <tbody>
                       <?php
                            $count = 0;
                            $total_credits=0;
                            foreach($data as $dt)
                            {
                                $count++;
                                $programmeMappingID=$dt['programmeMappingID'];
                                $courseID=$dt['courseID'];
                                // $semisterID=$dt['semesterID'];
                                $programmeLevelID=$dt['programmeLevelID'];
                                $courseStatusID=$dt['courseStatusID'];
                                $courseStatus=$dt['courseStatus'];

                               $course=$db->getRows("course",array('where'=>array('courseID'=>$courseID),' order_by'=>'courseName ASC'));
                               if(!empty($course))
                               {
                                    foreach($course as $c)
                                    {
                                        $cCode=$c['courseCode'];
                                        $cName=$c['courseName'];
                                        $cType=$c['courseTypeID'];
                                        $credits=$c['units'];

                                    }
                              }

                                $course_credits= $db->getRows('course',array('where'=>array('courseID'=>$courseID,'status'=>1),' order_by'=>' courseName ASC'));
                                if(!empty($course_credits))
                                {
                                    foreach ($course_credits as $cc) {
                                        $total_credits+=$cc['units'];
                                    }
                                }

                              $course_status = $db->getRows('coursestatus',array('where'=>array('courseStatusID'=>$courseStatusID),'order_by'=>'courseStatus ASC'));
                                 if(!empty($course_status)){
                                  foreach($course_status as $cstatus){
                                  $courseStatusName=$cstatus['courseStatus'];
                                  $courseStatusID=$cstatus['courseStatusID'];
                                }
                              }

                              if($courseStatus==1)
                              {
                                $status="Active";
                                $link="<a href=''>Disable</a>";
                              }
                              else
                              {
                                $status="Not Active";
                                $link="<a href=''>Enable</a>";
                              }

                 ?>
                        <tr><td><?php echo $count;?></td><td><?php echo $cCode;?></td><td><?php echo $cName;?></td>
                            <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);;?></td><td><?php echo $credits;?></td>
                            <td><?php echo $db->getData('programme_level','programmeLevel','programmeLevelID',$programmeLevelID);?></td>
                            <td><?php echo $courseStatusName;?></td>
                            <td><?php echo $status;?></td>
                            <td><a href="action_programme_mapping.php?action_type=delete&programmeID=<?php echo $programmeID;?>&id=<?php echo $programmeMappingID; ?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                            </tr>

                  <?php

                  }
                    ?>

                    </tbody>
                        </table>
                    </div>
                    <?php
                       }
                       else {
                            echo "<h4 class='text-danger'>No Registered Course</h4>";
                        }
                    ?>
        </div>
            <?php
                 }
            ?>
        </div>
    </div>
    </div>