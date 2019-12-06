 <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
              <script type="text/javascript">
              $(document).ready(function()
              {
              $("#programID").change(function()
              {
              var id=$(this).val();
              var dataString = 'id='+ id;
              $.ajax
              ({
              type: "POST",
              url: "ajax_studyear.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#studyYear").html(html);
              } 
              });

              });

              });
              </script>
             
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<script type="text/javascript">
$(document).ready(function () {
var table = $('#nactereport').removeAttr('width').dataTable( {
        scrollY:        "100%",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
         //dom: 'Blfrtip',
        columnDefs: [
            { width: "200px", targets: 1}
        ],
        fixedColumns: {
           leftColumns: 2
        }
         /*buttons:[
                        {
                            extend:'excel',
                            footer:false,
                        }]*/
    } );
});
</script>
<script type="text/javascript">

$('#btnExport').tableToExcel({
    table: '#nactereport',
    exclude: '.exclude',
    name: 'testing-export'
});

</script>
<style type="text/css">
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 100%;
    }
</style>

<?php $db=new DBHelper();
?>
<div class="container">
  <div class="content">
    <!-- Current Semester -->
<div id="semester" class="tab-pane fade in active">

<?php $db=new DBHelper();?>
            <h3>Annual Course Report</h3>
            <hr>
            <form name="" method="post" action="">
            <div class="row">        
                         <div class="col-lg-3">
                           <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" id="programID" class="form-control" required>
                              <?php
                               $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeName'];
                                $programmeID=$prog['programmeID'];
                               ?>
                               <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Study Year</label>
                            <select name="studyYear" id="studyYear" class="form-control" required>
                            <option selected="selected">--Select Study Year--</option>


                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Academic Year</label>
                            <select name="academicYearID" id="academicYearID" class="form-control" required>
                              <?php
                                 $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYearID ASC'));
                                 if(!empty($academic_year)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($academic_year as $sm){ $count++;
                                  $academicYear=$sm['academicYear'];
                                  $academicYearID=$sm['academicYearID'];
                                 ?>
                                 <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                        
                        <div class="col-lg-3">
                           <label for="FirstName">Study Mode</label>
                            <select name=batchID id="batchID" class="form-control" required>
                              <?php
                                 $batch = $db->getRows('batch',array('order_by'=>'batchID DESC'));
                                 if(!empty($batch)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($batch as $sm){ $count++;
                                  $batchName=$sm['batchName'];
                                  $batchID=$sm['batchID'];
                                 ?>
                                 <option value="<?php echo $batchID;?>" selected><?php echo $batchName;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                         
                        </div>
                    <div class="row">
                    <div class="col-lg-9"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                      </div>
                   </form>

                   <div class="row"><br></div>
<div class="row">
            
<?php
if(isset($_POST['doFind'])=="View Records")
{
   $programmeID=$_POST['programmeID'];
   $studyYear=$_POST['studyYear'];
   $batchID=$_POST['batchID'];
   $academicYearID=$_POST['academicYearID'];
   
   $student = $db->getStudentAnnualProgramme($programmeID,$academicYearID,$studyYear,$batchID);   
   if(!empty($student))
   {
       ?>
                       <div class="box box-solid box-primary">
                  <div class="box-header with-border text-center">
                  <?php 
                  if($studyYear==1)
                      $yearStudy="First Year";
                  else if($studyYear==2)
                      $yearStudy="Second Year";
                  else if($studyYear==3)
                      $yearStudy="Third Year";
                  else 
                      $yearStudy="Fourth Year";
                  ?>
                    <h3 class="box-title">Annual Report for <?php echo $yearStudy." "; echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID); echo " ";echo $db->getData("programmes","programmeName","programmeID",$programmeID);?> <?php echo $db->getData("batch","batchName","batchID",$batchID);?></h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                      <table  id="" class="table table-hover table-bordered" cellspacing="0" border=0 width="100%" rules="groups">
                      <thead>
                      <tr><th colspan="6" style='text-align:center;'>Module Credits</th>
                       <?php $course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                      foreach ($course as $cs)
                      {
                        echo "<th colspan='5' style='text-align:center;'>".$cs['units']."</th>";
                      }
                      ?></th>
                      <th rowspan="4">GPA</th><th rowspan="4">CW Attendance</th><th rowspan="4">Remarks</th>
                      </tr>
                      <tr><th colspan="6" style='text-align:center;'>Module Code</th>
                       <?php $course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                      foreach ($course as $cs)
                      {
                        echo "<th colspan='5' style='text-align:center;'>".$cs['courseCode']."</th>";
                      }
                      ?></th>
                      </tr>
                      <tr><th colspan="6" style='text-align:center;'>Max Marks</th>
                       <?php $course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                      foreach ($course as $cs)
                      {
                        echo "<th colspan='5' style='text-align:center;'>100</th>";
                      }
                      ?></th>
                      </tr>
                      
                     </tr>
                      <tr>
                      
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Reg.Number</th>
                        <th>Date of Entry</th>
                        <?php 
                        $course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                       foreach($course as $cs){
                       ?>
                        <th>CA</th>
                        <th>SE</th>
                        <th>TL</th>
                        <th>GD</th>
                        <th>PT</th>
                       <?php
                      }
                      ?>
                     
                     </tr>
                      </thead>
                      <tbody>
                    <?php 
                       $count = 0; 
                        foreach($student as $st)
                        { 
                                $count++;
                                $regNumber=$st['regNumber'];
                                $studentDetails=$db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>'firstName ASC'));
                                foreach($studentDetails as $std) {
                                # code...
                                $fname=$std['firstName'];
                                $mname=$std['middleName'];
                                $lname=$std['lastName'];
                                $name="$fname $mname $lname";
                                $gender=$std['gender'];
                                $dob=$std['dateOfBirth'];
                                echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>".date('d-m-Y',strtotime($dob))."</td><td>$regNumber</td><td>DOE</td>";
                               
                                $course=$db->getAnnualCourseCredit($programmeID,$academicYearID);
                                $tunits=0;
                                $tpoints=0;
                                $countpass=0;
                                $countsupp=0;
                                foreach ($course as $cs) 
                                {
                                    
                                    $courseID=$cs['courseID'];
                                    $units=$cs['units'];
                                    $semesterID=$cs['semesterSettingID'];
                                    $student_course=$db->getStudentExamCourse($regNumber,$semesterID,$courseID);
                                    if(!empty($student_course))
                                    {
                                        $cwk=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,1));
                                        $sfe=$db->decrypt($db->getFinalGrade($semesterID,$courseID,$regNumber,2));
                                        $sup=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,3));
                                        $spc=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,4));
                                        $prj=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,5));
                                        $pt=$db->decrypt($db->getGrade($semesterID,$courseID,$regNumber,6));
                                    
                                        $totalMarks=$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);
                                    
                                        $gradeID=$db->getMarksID($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                                        $gradePoint=$db->getData("grades","gradePoints","gradeID",$gradeID);
                                    
                                        $points=$gradePoint*$units;

                                        $remarks=$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
                                        //$grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                                        $grade=$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
    
                                        $tpoints+=$points;
                                        $tunits+=$units;
                                    
                                        $gpa=$db->getGPA($tpoints,$tunits);
                                    
                                    
                                        if(($grade=="D")or ($grade=="F") or ($grade=="E")or ($grade=="I"))
                                        {
                                            $countsupp=$countsupp+1;
                                        }
                                        else
                                        {
                                            $countpass=$countpass+1;
                                        }   
                                    
                                        if($gpa<2 || $countsupp>0)
                                            $gparemarks="Fail";
                                        else 
                                            $gparemarks="Pass";
                                    }
                                    else 
                                    {
                                        $cwk="-";
                                        $sfe="-";
                                        $totalMarks="-";
                                        $grade="-";
                                        $units=0;
                                    }
                                    
                                   echo "<td>$cwk</td><td>$sfe</td><td>$totalMarks</td><td>$grade</td><td>$points</td>";
                              }
                        

                                      echo "<td>$gpa</td><td>75%</td><td>$gparemarks</td></tr>";
                             ?>
                
                 <?php
                }
                }
                 ?>
              
              </tbody>
                 </table>
                 </div>
                 </div>
                 <input type="button" id="btnExport" onclick="tableToExcel('nactereport', 'NACTE REPORT')" value="Export to Excel">
              <?php
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
                   
 
 </div>  
<!-- End of Current Semester -->
            
</div></div>