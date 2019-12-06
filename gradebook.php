 <?php //session_start();?>
 
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
             
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>

<?php
          if(!empty($_SESSION['statusMsg'])){
              //echo "<div class='alert alert-success fade in'>".$_SESSION['statusMsg']."</div>";
             echo "<div class='alert alert-success fade in'>
              <a href='#' class='close' data-dismiss='alert'>&times;</a>
              <strong>".$_SESSION['statusMsg']."</strong>.
          </div>";
              unset($_SESSION['statusMsg']);
          }?>

<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#gradebook">Grade Book</a></li>
        <li><a data-toggle="tab" href="#viewsemestercourse">Grade Book Result</a></li>
    </ul>
    <div class="tab-content">
        
        <div id="gradebook" class="tab-pane fade in active">
            <h3>Add Course Result</h3>
            <form name="" method="post" action="">
            <div class="row">
            
            
            <div class="row">
            <div class="col-lg-3">
                           <label for="FirstName">Semester</label>
                            <select name="semisterID" id="semisterID" class="form-control" required="">
                              <?php
                                 $semister = $db->getRows('semister',array('order_by'=>'semisterName ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semisterName'];
                                  $semister_id=$sm['semisterID'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                         <div class="col-lg-3">
                            <label for="AcademicYear">Academic Year</label>
                            <select name="academicYearID" id="academicYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
                                      ?>
                           </select>
                        </div>

                 </div>
                 <div class="row">        

                     <div class="col-lg-6">
                           <label for="CourseName">Course Name</label>
                            <select name="courseID" id="courseID" class="form-control" >
                            <option selected="selected">--Select Study Year--</option>
                           </select>
                        </div>
                  </div>  
                  <div class="row"> 
                  <div class="col-lg-3">
                           <label for="CourseName">Exam Category</label>
                            <select name="examCategoryID" id="examCategoryID" class="form-control" >
                            <?php
                                 $examCategory = $db->getRows('exam_category',array('order_by'=>'examCategory ASC'));
                                 if(!empty($examCategory)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($examCategory as $exam){ $count++;
                                  $exam_category=$exam['examCategory'];
                                  $exam_category_id=$exam['examCategoryID'];
                                 ?>
                                 <option value="<?php echo $exam_category_id;?>"><?php echo $exam_category;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>   
                        <div class="col-lg-3">
                           <label for="ExamDate">Exam Date</label>
                            <!--<input  type="text" placeholder="Exam Date"  id="pickyDate" name="examDate" class="form-control" /> -->
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" name="examDate" value="" id="pickyDate" readonly>
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </div>
                        </div>
                  </div>
</div>
<div class="row">
                    <div class="col-lg-1"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                      <div class="col-lg-8"></div>
                  
        </div>
         </form>
        <br>
        <div class="row">

        <br></div>
        
        <div class="row">
            <?php
            if(isset($_POST['doFind'])=="Find Records")
            {
              $courseID=$_POST['courseID'];
              $semisterID=$_POST['semisterID'];
              $academicYearID=$_POST['academicYearID'];
              $examCategoryID=$_POST['examCategoryID'];
              $examDate=$_POST['examDate'];

               $student= $db->getRows('student_course',array('where'=>array('course_id'=>$courseID,'academic_year_id'=>$academicYearID,'semister_id'=>$semisterID),' order_by'=>'student_id ASC'));
                ?>
              <div class="row">
              <h4 class='text-info'>Add <?php 
              $examCategory=$db->getRows('exam_category',array('where'=>array('exam_category_id'=>$examCategoryID),' order_by'=>'exam_category ASC'));
              foreach ($examCategory as $ec) {
                # code...
                echo $ec['exam_category'];
              }
              ?> for <?php 
              $courseCode=$db->getRows('course',array('where'=>array('course_id'=>$courseID),' order_by'=>'course_code ASC'));
              foreach ($courseCode as $cc) {
                echo $cc['course_code'];
              }
              ?> in <?php ?> </h4>
              <br>
              </div>
                <?php
                if(!empty($student))
                {
                    ?>
                    <form name="" method="post" action="action_exam_score.php">
                    <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Registration Number</th>
                        <th>Exam Number</th>
                        <th>Score</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($student as $st)
                    { 
                      $count++;
                      $studentID=$st['student_id'];
                      $_SESSION['student'.$count]=$studentID;
                        //echo "<input type=\"hidden\" name=\"student$count\" value=\"$studentID\" \>";
                        //echo "<input type=\"text\" name=\"student$count\" value=\"35\">";
                       $studentDetails = $db->getRows('student',array('where'=>array('student_id'=>$studentID),' order_by'=>' registration_number ASC'));
                        if(!empty($studentDetails))
                        {
                          foreach($studentDetails as $std)
                          {  
                            //$student_id=$std['student_id'];
                            $fname=$std['fname'];
                            $mname=$std['mname'];
                            $lname=$std['lname'];
                            $name="$fname $mname $lname";
                            ?>
                            <tr>

                            <td><?php echo $count;?></td>
                            <td><?php echo $name;?></td>
                            <td><?php echo $std['gender'];?></td>
                            <td><?php echo $std['registration_number'];?></td>
                            <td><?php echo "Exam Number";?></td>
                           <?php 

                              $score=$db->getRows('exam_result',array('where'=>array('exam_category_id'=>$examCategoryID,'student_id'=>$studentID,'course_id'=>$courseID,'academic_year_id'=>$academicYearID),' order_by'=>'student_id ASC'));
                              if(!empty($score))
                              {
                                foreach ($score as $sc)
                                {
                                  ?>
                                  <td><input type="text" name="score<?php echo $count;?>" value="<?php echo $sc['exam_score'];?>" class='form-control'></td>
                                  <?php
                                }
                                ?>
                                
                                <?php 
                              }
                              else
                              {
                                ?>
                                <td><input type='text' name="score<?php echo $count;?>" class='form-control'></td>
                              <?php }?>
                            </tr>
                            <?php
                          }
                        }
                        ?>
                       
                        <?php
                    }
                    ?>
                    </tbody>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                         <input type="hidden" name="action_type" value="add"/>
                         <input type="hidden" name="courseID" value="<?php echo $courseID;?>">
                        <input type="hidden" name="number_student" value="<?php echo $count;?>">
                        <input type="hidden" name="semisterID" value="<?php echo $semisterID;?>">
                        <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                        <input type="hidden" name="examCategoryID" value="<?php echo $examCategoryID;?>">
                        <input type="hidden" name="examDate" value="<?php echo $examDate;?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="reset" value="Cancel" class="btn btn-primary form-control" />
                        </div>
                    </div>
                    
                    </form>
                    <?php 
                }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Student(s) found......</h4>
                        <?php 
                    } 
                   ?>
                   
                 <?php
        }
?>
        </div>
        </div>
        <div id="viewsemestercourse" class="tab-pane fade">
            <h3>View Course By Semester</h3>
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
          <div class="row">
            
            <form name="" method="post" action="">
            <div class="row">
            <div class="col-lg-3">
                           <label for="FirstName">Semester</label>
                            <select name="semisterID" id="semisterID" class="form-control" required="">
                              <?php
                                 $semister = $db->getRows('semister',array('order_by'=>'semister_name ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semister_name'];
                                  $semister_id=$sm['semister_id'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                         <div class="col-lg-3">
                            <label for="AcademicYear">Academic Year</label>
                            <select name="academicYearID" id="academicYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academic_year ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academic_year'];
                                $academic_year_id=$year['academic_year_id'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
                                      ?>
                           </select>
                        </div>

                 </div>
                 <div class="row">        

                     <div class="col-lg-6">
                           <label for="CourseName">Course Name</label>
                            <select name="courseID" id="courseID" class="form-control" >
                            <option selected="selected">--Select Study Year--</option>
                           </select>
                        </div>
                  </div>  
</div>
<div class="row">
                    <div class="col-lg-1"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                      <div class="col-lg-8"></div>
                   </form>
        </div>
      


        <div class="row">
            
        <?php
        if(isset($_POST['Search'])=="Search")
            {
                $academicYearID=$_POST['admissionYearID'];
                $programmeID=$_POST['programmeID'];
               $student = $db->getRows('student',array('where'=>array('admission_year'=>$academicYearID,'programme_id'=>$programmeID),' order_by'=>'fname ASC'));
    
                if(!empty($student))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($student as $st)
                            { 
                                $count++;
                                $fname=$st['fname'];
                                $mname=$st['mname'];
                                $lname=$st['lname'];
                                $name="$fname $mname $lname";
                                 ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $st['gender']; ?></td>
                                                <td><?php echo $st['registration_number']; ?></td>
                                            </tr>
                            <?php 
                            } 
                             ?>
                
                 <?php
                }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Student(s) found......</h4>
                        <?php 
                    } 
                   ?>
                   </tbody>
                 </table>
                 <?php
        }
        ?>

        </div>


        </div>
        
        
    </div>
    </div>
</div>