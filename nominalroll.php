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
<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
      <div class="row">
          <h1>Nominal Roll</h1>
          <hr>
      </div>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#departments">View By Center</a></li>
        <li><a data-toggle="tab" href="#programmelevel">View By Trade Levels</a></li>
        <li><a data-toggle="tab" href="#programme">View By Trade</a></li>
        <li><a data-toggle="tab" href="#employment">View By Employment Status</a></li>
        <li><a data-toggle="tab" href="#view_remarks">Remarks Report</a></li>
        <li><a data-toggle="tab" href="#statistical">Disable Students</a></li>
    </ul>
    <div class="tab-content">
        <div id="departments" class="tab-pane fade in active">
           <h3>View By Departments</h3>
           <hr>
            <div class="row">
            <form name="" method="post" action="">
                
           
            <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                      <div class="col-lg-4">
                            <label for="LastName">Center Name</label>
                           <select name="centerID" class="form-control" required>
                             <?php 
                                   $center = $db->getRows('center_registration',array('order_by'=>'centerName ASC'));
                                   if(!empty($center)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($center as $cept){ $count++;
                                    $centerName=$cept['centerName'];
                                    $centerID=$cept['centerRegistrationID'];
                                   ?>
                                   <option value="<?php echo $centerID;?>"><?php echo $centerName;?></option>
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
            if(isset($_POST['doSearch'])=="Search Records")
            {
                $academicYearID=$_POST['admissionYearID'];
                $centerID=$_POST['centerID'];
                $student = $db->getStudentByDepartment($academicYearID,$centerID);
                if(!empty($student))
                {
                    ?>
                <h4><span class="text-danger">
                List of Student for <?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerID);?>
                <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
                <hr>
                    <table  id="example" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                        <th>Student Status</th>
                        <th>Programme Level</th>
                        <th>Programme Name</th>
                        <th>View</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($student as $st)
                        { 
                                $count++;
                                $studentID=$st['studentID'];
                                $fname=$st['firstName'];
                                $mname=$st['middleName'];
                                $lname=$st['lastName'];
                                $name="$fname $mname $lname";
                                $programmeID=$st['programmeID'];
                                $programmeLevelID=$st['programmeLevelID'];
                                 ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $st['gender']; ?></td>
                                                <td><?php echo $st['registrationNumber']; ?></td>
                                                <td><?php echo $db->getData("status","statusValue","statusID",$st['statusID']);?></td>
                                                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID); ?></td>
                                                <td><?php echo $db->getData("programmes","programmeName","programmeID",$st['programmeID']); ?></td>
                                            	<td><a href="index3.php?sp=view_student_profile&id=<?php echo $db->my_simple_crypt($studentID,'e');?>" class="glyphicon glyphicon-eye-open">
                	</a></td>
                                            </tr>
                            <?php 
                            }
                            ?>
                             </tbody>
                 </table>
                            <?php 
                        }
                        else
                        { 
                        ?>
                        <h4 class="text-danger">No Student(s) found......</h4>
                        <?php 
                        } 
                        ?>
               
      <?php     }
?>
        </div>
        </div>
        <div id="programmelevel" class="tab-pane fade">
            <h3>View By Programme Levels</h3>
            <hr>
            <div class="row">
            <form name="" method="post" action="">
            <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                      <div class="col-lg-4">
                           <label for="FirstName">Programme Level</label>
                            <select name="programmeLevelID" class="form-control" required>
                              <?php
                                 $programme_level = $db->getRows('programme_level',array('order_by'=>'programmeLevel DESC'));
                                 if(!empty($programme_level)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($programme_level as $level){ $count++;
                                  $programme_level=$level['programmeLevel'];
                                  $programme_level_id=$level['programmeLevelID'];
                                 ?>
                                 <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                   </form>
        </div>
        <div class="row">
            <?php
            if(isset($_POST['doFind'])=="Find Records")
            {
                $academicYearID=$_POST['admissionYearID'];
                $programmeLevelID=$_POST['programmeLevelID'];
                
                $student = $db->getStudentByLevel($academicYearID,$programmeLevelID);

    
                if(!empty($student))
                {
                    ?>
                    <h4><span class="text-danger">
                    List of Student for <?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?>
                    <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                    </span></h4>
                    <hr>
                    <table  id="exampleexample" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                        <th>Student Status</th>
                        <th>Programme Name</th>
                        <th>Details</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($student as $st)
                        { 
                                $count++;
                                $fname=$st['firstName'];
                                $mname=$st['middleName'];
                                $lname=$st['lastName'];
                                $studentID=$st['studentID'];
                                $name="$fname $mname $lname";
                                 ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $name ?></td>
                                                <td><?php echo $st['gender']; ?></td>
                                                <td><?php echo $st['registrationNumber']; ?></td>
                                                <td><?php echo $db->getData("status","statusValue","statusID",$st['statusID']);?></td>
                                                <td><?php echo $db->getData("programmes","programmeName","programmeID",$st['programmeID']); ?></td>
                                            <td><a href="index3.php?sp=view_student_profile&id=<?php echo $db->my_simple_crypt($studentID,'e');?>" class="glyphicon glyphicon-eye-open">
                	</a></td>
                                            </tr>
                            <?php 
                            } 
                             ?>
                		</tbody>
                 </table>
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
        <div id="programme" class="tab-pane fade">
            <h3>View By Trade</h3>
            <hr>
           <div class="row">
            <form name="" method="post" action="">
            <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                      <div class="col-lg-4">
                           <label for="MiddleName">Trade Name</label>
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
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="Search" value="Search" class="btn btn-primary form-control" /></div>
               </form>    
        </div>


        <div class="row">
            
        <?php
        if(isset($_POST['Search'])=="Search")
            {
                $academicYearID=$_POST['admissionYearID'];
                $programmeID=$_POST['programmeID'];

                $student_programme=$db->getRows("student_programme",array('where'=>array('programmeID'=>$programmeID,'academicYearID'=>$academicYearID)));
                if(!empty($student_programme)) {
                    ?>
                    <h4><span class="text-danger">
                List of Student for <?php echo $db->getData("programmes", "programmeName", "programmeID", $programmeID); ?>
                            <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
                </span></h4>
                    <hr>
                    <table  id="exampleexampleexample" class="display nowrap">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                        <th>Center Name</th>
                        <th>Student Status</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
            <?php
                    foreach ($student_programme as $sp) {
                        $regNumber = $sp['regNumber'];
                        $centerID=$sp['centerID'];
                        $student = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'fname ASC'));
                        if (!empty($student)) {
                            ?>

                            <?php
                            $count = 0;
                            foreach ($student as $st) {
                                $count++;
                                $studentID = $st['studentID'];
                                $fname = $st['firstName'];
                                $mname = $st['middleName'];
                                $lname = $st['lastName'];
                                $name = "$fname $mname $lname";
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $name ?></td>
                                    <td><?php echo $st['gender']; ?></td>
                                    <td><?php echo $st['registrationNumber']; ?></td>
                                    <td><?php echo $db->getData("center_registration","centerName","centerRegistrationID",$centerID);?></td>
                                    <td><?php echo $db->getData("status", "statusValue", "statusID", $st['statusID']); ?></td>
                                    <td>
                                        <a href="index3.php?sp=view_student_profile&id=<?php echo $db->my_simple_crypt($studentID, 'e'); ?>"
                                           class="glyphicon glyphicon-eye-open">
                                        </a></td>
                                </tr>
                                <?php
                            }
                            ?>

                            <?php
                        }
                    }
                }
                else
                    { 
                        ?>
                        <h4><span class="text-danger">No Student(s) found......</span> </h4>
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
        <div id="employment" class="tab-pane fade">


            <script type="text/javascript">
                $(document).ready(function () {
                    $("#employmenttab").dataTable({
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
            <h3>Employment Report</h3>
            <div class="row">
                <form name="" method="post" action="">
                <div class="col-lg-4">
                <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                     
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doView" value="View Records" class="btn btn-primary form-control" /></div>
            </form>
                   
        </div>

            <div class="row">

                <?php
                if(isset($_POST['doView'])=="View Records")
                {
                    $academicYearID=$_POST['admissionYearID'];

                    $student = $db->getRows('student',array('where'=>array('academicYearID'=>$academicYearID,'employmentStatus'=>'yes'),' order_by'=>'fname ASC'));

                    if(!empty($student))
                    {
                        ?>
                        <h4><span class="text-danger">
                Employed List <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
                        <hr>
                        <table  id="employmenttab" class="display nowrap">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Reg.Number</th>
                            <th>Student Status</th>
                            <th>Designation</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach($student as $st)
                        {
                            $count++;
                            $studentID=$st['studentID'];
                            $fname=$st['firstName'];
                            $mname=$st['middleName'];
                            $lname=$st['lastName'];
                            $name="$fname $mname $lname";

                            $employmentData=$db->getRows("employmentstatus",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                            foreach($employmentData as $sp)
                            {
                                $employer=$sp['employer'];
                                $placework=$sp['placeOfWork'];
                                $designation=$sp['designation'];
                            }

                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $st['gender']; ?></td>
                                <td><?php echo $st['registrationNumber']; ?></td>
                                <td><?php echo $db->getData("status","statusValue","statusID",$st['statusID']);?></td>
                                <td><?php echo $employer;?></td>
                                <td><a href="index3.php?sp=view_student_profile&id=<?php echo $db->my_simple_crypt($studentID,'e');?>" class="glyphicon glyphicon-eye-open">
                                    </a></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <?php
                    }
                    else
                    {
                        ?>
                        <h4><span class="text-danger">No Student(s) found......</span> </h4>
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
        <div id="view_remarks" class="tab-pane fade">
            <!-- Start -->

            <h3>View Student Remarks</h3>
            <hr>
            <div class="row">
                <form name="" method="post" action="">
                    <div class="col-lg-3">

                        <label for="MiddleName">Academic Year</label>
                        <select name="academicYearID" class="form-control" required>
                            <?php
                            $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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


                    <div class="col-lg-3">
                        <label for="MiddleName">Status Name</label>
                        <select name="statusID" class="form-control" required>
                            <?php
                            $status = $db->getRows('status', array('order_by' => 'statusValue ASC'));
                            if (!empty($status)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($status as $c) {
                                    $count++;
                                    $statusValue = $c['statusValue'];
                                    $statusID = $c['statusID'];
                                    if ($statusID != 1) {
                                        ?>
                                        <option value="<?php echo $statusID; ?>"><?php echo $statusValue; ?></option>
                                    <?php }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for=""></label>
                        <input type="submit" name="doViewList" value="View List" class="btn btn-primary form-control" /></div>
                </form>
            </div>


            <?php
            if(isset($_POST['doViewList'])=="View List")
            {
                $academicYearID=$_POST['academicYearID'];
                $statusID=$_POST['statusID'];
                $studentStatus=$db->getStudentYearStatus($academicYearID,$statusID);
                if(!empty($studentStatus))
                {
                    ?>
                    <h4><span class="text-danger" id="titleheader">
                List of <?php echo $db->getData("status","statusValue","statusID",$statusID);?>
            Students  in <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
                    <hr>
                    <table  id="thirdtab" class="display nowrap">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Reg.Number</th>
                            <th>Programme Name</th>
                            <th>Programme Level</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach($studentStatus as $st)
                        {
                            $count++;
                            $studentID=$st['studentID'];
                            $fname=$st['firstName'];
                            $mname=$st['middleName'];
                            $lname=$st['lastName'];
                            $programmeID=$st['programmeID'];
                            $semesterSettingID=$st['semesterSettingID'];
                            $name="$fname $mname $lname";
                            $regNumber=$st['registrationNumber'];
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name;?></td>
                                <td><?php echo $st['gender']; ?></td>
                                <td><?php echo $st['registrationNumber']; ?></td>
                                <td><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID); ?></td>
                                <td></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }
                else {
                    ?>
                    <h4><span class="text-danger">No Student(s) found......</span></h4>
                    <?php
                }

            }
            ?>




            <!-- End -->
        </div>
        <div id="statistical" class="tab-pane fade">
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#disabletab").dataTable({
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
            <h3>Disable Students</h3>
            <form name="" method="post" action="">
            <div class="row">
           <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                     
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doViewDisable" value="View List" class="btn btn-primary form-control" /></div>
                   
            </div></form>



            <div class="row">

                <?php
                if(isset($_POST['doViewDisable'])=="View List")
                {
                    $academicYearID=$_POST['admissionYearID'];
                    $student = $db->getRows('student',array('where'=>array('academicYearID'=>$academicYearID,'disabilityStatus'=>'Yes'),' order_by'=>'fname ASC'));

                    if(!empty($student))
                    {
                        ?>
                        <h4><span class="text-danger">
                Employed List <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
                        <hr>
                        <table  id="disabletab" class="display nowrap">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Reg.Number</th>
                            <th>Study Mode</th>
                            <th>Student Status</th>
                            <th>Designation</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        foreach($student as $st)
                        {
                            $count++;
                            $studentID=$st['studentID'];
                            $fname=$st['firstName'];
                            $mname=$st['middleName'];
                            $lname=$st['lastName'];
                            $name="$fname $mname $lname";



                            $disabilityData=$db->getRows("disability",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                            if(!empty($disabilityData)) {
                                foreach ($disabilityData as $sp) {
                                    $disabilityName = $sp['disabilityName'];
                                    $disabilityDescription = $sp['disabilityDescription'];
                                }
                            }

                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $st['gender']; ?></td>
                                <td><?php echo $st['registrationNumber']; ?></td>
                                <td><?php echo $db->getData("batch","batchName","batchID",$st['batchID']);?></td>
                                <td><?php echo $db->getData("status","statusValue","statusID",$st['statusID']);?></td>
                                <td><?php echo $disabilityName;?></td>
                                <td><a href="index3.php?sp=view_student_profile&id=<?php echo $db->my_simple_crypt($studentID,'e');?>" class="glyphicon glyphicon-eye-open">
                                    </a></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <?php
                    }
                    else
                    {
                        ?>
                        <h4><span class="text-danger">No Student(s) found......</span> </h4>
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