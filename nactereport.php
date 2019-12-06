             <script type="js/jquery-1.12.4.js"></script>
             <link href="plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" />
    <script src="plugins/datatables/dataTables.fixedColumns.min.js" type="text/javascript"></script>
       <script src="tableToExcel.js"></script>
            <h3>View Programme Report By Semester</h3>
           <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
              <script type="text/javascript">
              $(document).ready(function()
              {
              $("#programID").change(function()
              {
              var id=$(this).val();
              var dataString = 'id='+ id;

              //$("#studyYear").load('ajax_studyear.php?id='+id);

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

 /* $(document).ready(function () {
            $('#nactereport').dataTable(
                {
                  paging: true,
                    dom: 'Blfrtip',
                  scrollX: true,

                  "columnDefs": [ {
                  "visible": true,
                  "targets": -1
                   } ],
                   //order: [],
                   //columnDefs: [ { orderable: true, targets: [1,3] } ],
                   //"bSort":true,
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            /*exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Waqfs',
                            footer: false,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Waqfs',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }
                            orientation: 'landscape',
                        }

                        ]
                });
          });*/
</script>  
<script type="text/javascript">

$('#btnExport').tableToExcel({
    table: '#nactereport',
    exclude: '.exclude',
    name: 'testing-export'
});

</script>
<style type="text/css">
/*table.dataTable thead th {
  vertical-align: top;
}
body {
  font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
  color: #333;
  background-color: #fff;
}*/
th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 100%;
    }
</style>

<style type="text/css">
/*.dataTables_wrapper {
    font-family: tahoma;
    font-size: 9px;
    position: relative;
    clear: both;
    *zoom: 1;
    zoom: 1;
}
div.container {
        width: 80%;
    }
.dataTables_wrapper {
        width: 100%;
        font-size: 12px; 
   
    }
   .dataTable thead th {
  vertical-align: top;
} */ 
    </style> 


<?php $db=new DBHelper();
include("grade.php");
?>
<div class="container">
  <div class="content">
    <div class="tab-content">
        <div id="semestercourse" class="tab-pane fade in active">
            <form name="" method="post" action="">
            <div class="row">        
                         <div class="col-lg-3">
                           <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" id="programID" class="form-control" required="">
                              <?php
                               $programmes = $db->getRows('programmes',array('order_by'=>'programme_name ASC'));
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programme_name'];
                                $programmeID=$prog['programme_id'];
                               ?>
                               <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Study Year</label>
                            <select name="studyYear" id="studyYear" class="form-control" >
                            <option selected="selected">--Select Study Year--</option>


                           </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="AcademicYear">Academic Year</label>
                            <select name="academicYearID" class="form-control" required="">
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

                         <div class="col-lg-3">
                           <label for="FirstName">Semester</label>
                            <select name="semisterID" class="form-control" required="">
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
                        </div>
                    <div class="row">
                    <div class="col-lg-9"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                      </div>
                   </form>

                   <div class="row"><br></div>
        </div>
      


        <div class="row">
            
        <?php
        if(isset($_POST['doFind'])=="View Records")
            {
              $programmeID=$_POST['programmeID'];
              $studyYear=$_POST['studyYear'];
                $academicYearID=$_POST['academicYearID'];
                $semisterID=$_POST['semisterID'];

              /* $student = $db->getRows('exam_result',array('where'=>array('academic_year_id'=>$academicYearID,'semister_id'=>$semisterID,'course_id'=>$courseID),' order_by'=>'student_id ASC'));*/
              $student = $db->getStudentProgramme($programmeID,$semisterID,$academicYearID);
    
                if(!empty($student))
                {
                    ?>
                    <table  id="nactereport" class="stripe row-border order-column" cellspacing="0" width="100%" rules="groups" frame="hsides">
                    <!--<caption>CODE-PAGE SUPPORT IN MICROSOFT WINDOWS</caption>-->
                      <colgroup span="6"></colgroup>
                        <colgroup></colgroup>
                        <colgroup span="2"></colgroup>
                        <colgroup span="3"></colgroup>
                      <thead valign="top">
                      <tr><th colspan="6" align="center">Module Credits<br>Module Code<br>Max Marks</th>
                      <?php $course=$db->getCourseCredit($programmeID,$academicYearID,$semisterID);
                      foreach ($course as $cs) {
                        echo "<th colspan='5' align='center'>".$cs['units']."<br>".$cs['course_code']."<br>100</th>";
                      }
                      ?></th>
                     </tr>
                      <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Reg.Number</th>
                        <th>Date of Entry</th>
                        <?php $course=$db->getCourseCredit($programmeID,$academicYearID,$semisterID);
                       foreach ($course as $cs) {
                       ?>
                        <th>CA</th>
                        <th>SE</th>
                        <th>TL</th>
                        <th>GD</th>
                        <th>PT</th>
                       <?php
                      }
                      ?>
                        <th>GPA</th><th>CW Attendance</th><th>Remarks</th>
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
                                $dob=$std['date_birth'];
                                echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$dob</td><td>$regNumber</td><td>DOE</td>";
                              $course=$db->getCourseCredit($programmeID,$academicYearID,$semisterID);
                              $tunits=0;
                              $tpoints=0;
                              foreach ($course as $cs) {
                                $courseID=$cs['course_id'];
                                $units=$cs['units'];
                               /*echo $semisterID."-";
                               echo $academicYearID."-";
                               echo $courseID."-";
                               echo $studentID;*/
                               $cwk=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,1);
                               $sfe=$db->getGrade($semisterID,$academicYearID,$courseID,$studentID,2);
                               $total=$cwk+$sfe;
                               if($total>=80)
                               {
                                  $grade="A";
                                  $points=$units*5;
                               }
                               else if($total>=70)
                               {
                                  $grade="B";
                                  $points=$units*4;
                               }
                                else if($total>=60)
                               {
                                  $grade="C";
                                  $points=$units*3;
                               }
                                else if($total>=50)
                               {
                                  $grade="D";
                                  $points=$units*2;
                               }
                               else
                               {
                                $grade="F";
                                $points=$units*0;
                               }

                               $tpoints=$tpoints+$points;
                                $tunits=$tunits+$units;
                                $gpa=round($tpoints/$tunits,1); 
                                if($gpa>=2.0)
                                $gparemarks="Pass";
                                else
                                $gparemarks="Fail";
                                
                                if(($grade=="D")or ($grade=="F"))
                                {
                                 $countsupp=$countsupp+1;
                                }
                                else
                                {
                                  $countpass=$countpass+1;
                                }


                               echo "<td>$cwk</td><td>$sfe</td><td>$total</td><td>$grade</td><td>$points</td>";
                              }
                        

                                      echo "<td>$gpa</td><td>$cwkattendance</td><td>$gparemarks</td>";
                             ?>
                
                 <?php
                }
                }
                 ?>
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
                   </tbody>
                 </table>
                 <?php
        }
        ?>

        </div>
        </div>
        </div>


