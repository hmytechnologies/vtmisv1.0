            <h3>View Course Result By Semester</h3>
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
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academic_year ASC'));
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
                    <div class="col-lg-3"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                      <div class="col-lg-6"></div>
                   
        </div>
</form>
      


        <div class="row">
            
        <?php
        if(isset($_POST['doFind'])=="View Records")
        {
                $academicYearID=$_POST['academicYearID'];
                $semisterID=$_POST['semisterID'];
                $courseID=$_POST['courseID'];

              /* $student = $db->getRows('exam_result',array('where'=>array('academic_year_id'=>$academicYearID,'semister_id'=>$semisterID,'course_id'=>$courseID),' order_by'=>'student_id ASC'));*/
              $student = $db->getExamStudent($semisterID,$academicYearID,$courseID);
    
                if(!empty($student))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                        <th>CW</th>
                        <th>SFE</th>
                        <th>SUP</th>
                        <th>SPC</th>
                        <th>PRJ</th>
                        <th>PRT</th>
                        <th>TTL</th>
                        <th>GRD</th>
                        <th>RMK</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($student as $st)
                            { 
                                $count++;
                                $studentID=$st['student_id'];
                                $studentDetails=$db->getRows('student',array('where'=>array('student_id'=>$studentID),' order_by'=>'fname ASC'));
                              foreach ($studentDetails as $std) {
                                # code...
                                $fname=$std['fname'];
                                $mname=$std['mname'];
                                $lname=$std['lname'];
                                $name="$fname $mname $lname";
                                $gender=$std['gender'];
                                $regNumber=$std['registration_number'];
                                echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td>";
                              
                                //include("grade.php");
                                $cwk=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,1);
                                $sfe=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,2);
                                $sup=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,3);
                                $spc=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,4);
                                $pro=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,5);
                                $pt=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,6);
                           
                        echo "<td>".$cwk."</td><td>".$sfe."</td><td>".$sup."</td><td>".$spc."</td><td>".$pro."</td><td>".$pt."</td>";

                                      echo "<td>".$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt)."</td><td>".$db->calculateGrade($cwk, $sfe, $sup, $spc, $prj, $pt)."</td><td>".$db->courseRemarks($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                             ?>
                
                 <?php
                }
                }
              }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Result(s) found......</h4>
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


