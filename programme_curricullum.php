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
    <ul class="nav nav-tabs" id="myTab">
    
        <li class="active"><a data-toggle="tab" href="#currentdata">Programme Core</a></li>
        <li><a data-toggle="tab" href="#previous">Programme Electives</a></li>
        
    </ul>

<div class="tab-content">
    <!-- Current Semester -->
<div id="currentdata" class="tab-pane fade in active">
 <script type="text/javascript">
 $(document).ready(function () {
 $("#studentdata").DataTable({
	         "dom": 'Blfrtip',
             "scrollX":true,
             "paging":true,
             "buttons":[
                     {
                         extend:'excel',
                         title: 'List of all Register',
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     ,
                     {
                         extend: 'print',
                         title: 'List of all Register',
                         footer: false,
                         exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: 'List of all Register',
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         },
                         
                     }

                     ],
		"order": []
	});
 });
</script>
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Programme Core</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
     <?php
     $db = new DBHelper();
     $userID = $_SESSION['user_session'];
     $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
     if(!empty($studentID))
     {
         foreach($studentID as $std)
         {
             $programmeID=$std['programmeID'];
             $courseList = $db->getRows('programmemaping',array('where'=>array('programmeID'=>$programmeID,'courseStatus'=>1),' order_by'=>'studyYear ASC'));
                if(!empty($courseList))
                {
                  ?>
                  <table class="table table-striped table-bordered table-condensed" id="example">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Units</th>
                        <th>Course Type</th>
                        <th>Course Status</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; $total_credits=0;
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $courseID=$list['courseID'];
                     $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                    if(!empty($course))
                    {
                      foreach ($course as $c) {
                        $total_credits+=$c['units'];
                      }
                    }
                        
                        ?>    <td><?php echo $count;?></td>
                        	  <td><?php echo $c['courseCode'];?></td>
                              <td><?php echo $c['courseName'];?></td>
                              <td><?php echo $c['units'];?></td>
                              <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                              <td>Core</td>
                              <?php    
                            echo "</tr>";
                      
                }
                  ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                  <th colspan=3>Total Number of Credits:
                  </th><th colspan=3><?php echo $total_credits;?></th>
                  </tr>
                  </tfoot>
                  </table>
                  <?php
              }
              else
              {
                echo "<h4 class='text-danger'>No Course Registered for ".$semesterName."<br>Please Please <a href='index3.php?sp=semister_registration'>Click Here</a> to make Course Semester Registration</h4>";
              }
         }
     }
              ?>
</div></div>
</div>  
<!-- End of Current Semester -->

 <!-- Previous Semester -->       
<div id="previous" class="tab-pane fade">
<script type="text/javascript">
 $(document).ready(function () {
 $("#studentdata").DataTable({
	         "dom": 'Blfrtip',
             "scrollX":true,
             "paging":true,
             "buttons":[
                     {
                         extend:'excel',
                         title: 'List of all Register',
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     ,
                     {
                         extend: 'print',
                         title: 'List of all Register',
                         footer: false,
                         exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: 'List of all Register',
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         },
                         
                     }

                     ],
		"order": []
	});
 });
</script>
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Programme Electives</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
     <?php
     $db = new DBHelper();
     $userID = $_SESSION['user_session'];
     $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
     if(!empty($studentID))
     {
         foreach($studentID as $std)
         {
             $programmeID=$std['programmeID'];
             $courseList = $db->getRows('programmemaping',array('where'=>array('programmeID'=>$programmeID,'courseStatus'=>2),' order_by'=>'studyYear ASC'));
                if(!empty($courseList))
                {
                  ?>
                  <table class="table table-striped table-bordered table-condensed" id="example">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Units</th>
                        <th>Course Type</th>
                        <th>Course Status</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; $total_credits=0;
                    foreach($courseList as $list)
                    { 
                      $count++;
                      $courseID=$list['courseID'];
                     $course= $db->getRows('course',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseName ASC'));
                    if(!empty($course))
                    {
                      foreach ($course as $c) {
                        $total_credits+=$c['units'];
                      }
                    }
                        
                        ?>    <td><?php echo $count;?></td>
                        	  <td><?php echo $c['courseCode'];?></td>
                              <td><?php echo $c['courseName'];?></td>
                              <td><?php echo $c['units'];?></td>
                              <td><?php echo $db->getData("course_type","courseType","courseTypeID",$c['courseTypeID']);?></td>
                              <td>Core</td>
                              <?php    
                            echo "</tr>";
                      
                }
                  ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                  <th colspan=3>Total Number of Credits:
                  </th><th colspan=3><?php echo $total_credits;?></th>
                  </tr>
                  </tfoot>
                  </table>
                  <?php
              }
              else
              {
                echo "<h4 class='text-danger'>No Programme Electives Found</h4>";
              }
         }
     }
              ?>
</div></div>
</div>
         <!-- End -->

            </div>
            
</div></div>