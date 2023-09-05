 <script type="text/javascript">
 $(document).ready(function () {
     var titleheader = $('#titleheader').text();
     $("#student_list").DataTable({
     "dom": 'Blfrtip',

             "paging":true,
             "buttons":[
                     {
                         extend:'excel',
                         title: titleheader,
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2,3,4,5]
                         }
                     },
                     ,
                     {
                         extend:'csvHtml5',
                         title: titleheader,
                         customize: function (csv) {
                             return titleheader+"\n"+  csv +"\n";
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: titleheader,
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,4,5]
                         },

                     }
                     ,
                     {
                         extend: 'copy',
                         title: titleheader,
                         footer: true,
                        exportOptions: {
                             columns: [5]
                         },

                     }

                     ],
		"order": []
	});
 });
</script>            
<div class="container">

<?php $db=new DBHelper();
?>
<?php
 $corsID=$db->decrypt($_REQUEST['id']);
 $instractorID=$db->decrypt($_REQUEST['instID']); 

  $yearID=$db->decrypt($_REQUEST['year']);
  $levelID=$db->decrypt($_REQUEST['level']);

 $cID=$db->decrypt($_REQUEST['centerID']);

 $proID=$db->decrypt($_REQUEST['programmeID']);
 

$course=$db->getListOfCourse($instractorID,$corsID,$yearID,$levelID, $cID);

foreach ($course as $std) {
    //  $count++;
     $courseID = $std['courseID'];
     $courseCode = $std['courseCode'];
     $courseName = $std['courseName'];
     $courseTypeID = $std['courseTypeID'];
     $programmeLevelID = $std['programmeLevelID'];
     $programmeID = $std['programmeID'];
     $staffID = $std['staffID'];
     $academicYearID=$std['academicYearID'];
}
// $studentNumber=$db->getStudentCourseSum($cID,$yearID,$programmeID,$levelID);
 $studentNumber = $db->getStudentCourseSum($_SESSION['department_session'], $yearID,  $levelID,$programmeID);
?>
    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Student List</h3>
            </div>
            <div class="box-body">
                <table id="" class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Subject Type</th>
                        <th>Level</th>
                        <th>Trade Name</th>
                        <th>No.of Students</th>
                        <th>Academic Year</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $courseName;?></td>
                        <td><?php echo $courseCode;?></td>
                        <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                        <td><?php echo $db->getData("programme_level", "programmeLevel", "programmeLevelID", $levelID); ?></td>
                        <td><?php echo $db->getData("programmes", "programmeName", "programmeID", $proID);?></td>
                        <td><?php echo $studentNumber;?></td>
                        <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$yearID);?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>
<?php
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
?>

<div class="row">
 <div class="col-md-12">
<hr>
<table  id="student_list" class="display">
  <thead>
  <tr>
    <th>No.</th>
    <th>Registration Number</th>
    <th>Name</th>
    <th>Gender</th>
      <th>Email</th>
      <th>Program</th>
      <th>Center Registered </th>
     </tr>
  </thead>
  <tbody>
<?php 
$getStudentCourse=$db->getStudentCourseInfo($courseID);
if(!empty($getStudentCourse))
{
    $count = 0; 
    foreach($getStudentCourse as $cid)
    {
        $count++;
        $regNumber=$cid['regNumber'];
        $student = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),'order_by'=>'studentID ASC'));
        
        if(!empty($student)){
            
        foreach($student as $std){ 
        $studentID=$std['studentID'];
        $fname=$std['firstName'];
        $mname=$std['middleName'];
        $lname=$std['lastName'];
        $gender=$std['gender'];
        $dob=$std['dateOfBirth'];
        $name="$fname $mname $lname";
        $registrationNumber=$std['registrationNumber'];
        $email=$std['email'];
        // $programmeID=$std['programmeID'];
        // $std = $db->getRows(' student_programme',array('where'=>array('regNumber'=>$registrationNumber),'order_by'=>'regNumber ASC'));

        //     if(!empty($std)){
        //             foreach($std as $Pro){
        //                 $centerID =$Pro['centerID'];
        //                 $programmeID=$Pro['programmeID'];

        //              }
        //         }
       
       
        echo "<tr><td>$count</td><td>$regNumber</td><td>$name</td><td>$gender</td><td>$email</td><td>".$db->getData("programmes","programmeName","programmeID", $proID)."</td><td>".$db->getData("center_registration","centerName","centerRegistrationID", $db->decrypt($_REQUEST['centerID']))."</td></tr>";
        }

        }
    }
}
?>
 </tbody>
 </table>
 </div>
 </div>
<div class="row">
        <div class="col-lg-3">
            <?php
            if($_SESSION['role_session']==3)
            {
                ?>
                <a href="index3.php?sp=instructor_mycourse" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            else {
                ?>
                <a href="index3.php?sp=courselist" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            ?>
        </div>
</div>

</div>
