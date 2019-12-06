<script type="text/javascript" src="ajax/index.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>  
<script type="text/javascript">
             $(document).ready(function()
              {
              $("#programmeLevelID").change(function()
              {
              var programmeLevelID=$(this).val();
              var dataString = 'programmeLevelID='+programmeLevelID;
              $.ajax
              ({
              type: "POST",
              url: "ajax_programme.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#programmeID").html(html);
              } 
              });

              });

              });
        </script>  

<style>
.no-padding-right
{
    padding-right: 0;
}
.no-padding-left
{
    padding-left: 0;
}
</style>
<script>
function goBack() {
    window.history.back();
}
</script>


<?php
$db = new DBHelper();
$id=$db->my_simple_crypt($_REQUEST['id'],'d');
$student = $db->getRows('student',array('where'=>array('studentID'=>$id),'order_by'=>'studentID ASC'));
if(!empty($student))
{
    $x=0;
    foreach ($student as $std)
    {
        $x++;
        $studentID=$std['studentID'];
        $fname=$std['firstName'];
        $mname=$std['middleName'];
        $lname=$std['lastName'];
        $gender=$std['gender'];
        $dob=$std['dateOfBirth'];
        $programmeID=$std['programmeID'];
        $registrationNumber=$std['registrationNumber'];
        $admissionNumber=$std['admissionNumber'];
        $academicYearID=$std['academicYearID'];

        $formfournumber=$std['formFourIndexNumber'];
        $email=$std['email'];
        $phoneNumber=$std['phoneNumber'];

        $rgStatus=$std['rgStatus'];
    }
?>
    <div class="container">
    <div class="content">
    <h1>Student Information</h1>
    <hr>
    <h3>Manage results by course or by individual student</h3>
    <ul class="nav nav-tabs" id="myTab">

        <li class="active"><a data-toggle="tab" href="#student_details"><span style="font-size: 16px"><strong>Student Details</strong></span></a></li>
        <li><a data-toggle="tab" href="#student_documents"><span style="font-size: 16px"><strong>Student Documents</strong></span></a></li>
    </ul>

    <div class="tab-content">
    <div id="student_details" class="tab-pane fade in active">
<div class="row">
    <form action="action_register_new_student.php" method="post" name="register" id="register">
<div class="col-md-8">
<div class="modal-body">
                <div class="well">
               <fieldset>
                  <legend>Edit Student Information</legend>
                     <div class="row">
                        <div class="col-lg-4">
                            <label for="MiddleName">First Name</label>
                            <input type="text" name="fname" class="form-control" value="<?php echo $fname;?>" />
                        </div>
                         <div class="col-lg-4">
                            <label for="LastName">Middle Name</label>
                            <input type="text" name="mname"  class="form-control" value="<?php echo $mname;?>" required />
                        </div>
                         <div class="col-lg-4">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname"  class="form-control" value="<?php echo $lname; ?>" required />
                        </div>
                    </div>
                  <div class="row">
                        
                          <div class="col-lg-6">
                            <label for="Geder">Gender</label>
                            <select name="gender" class="form-control">
                            <?php 
                            if($gender=="")
                            {
                            ?>
                                <option value="">Select Here</option>
                            <?php 
                            }
                            else 
                            {
                                if($gender=="M")
                                    $genderI="Male";
                                else 
                                    $genderI="Female";
                            ?>
                            	<option value="<?php echo $gender;?>" selected><?php echo $genderI;?></option>
                       <?php }?>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div> 
                       <div class="col-lg-6">
                            <label for="Date of Birth">Date of Birth</label>
                             <?php 
                             $dob2=explode("-",$dob);
                             $year=$dob2[0];
                             $month=$dob2[1];
                             $date=$dob2[2];
                             if($month==00)
                                 $month=0;
                             else
                                 $month=ltrim($dob2[1],0);
                             ?>
                            <div class="row">
                             <div class="col-lg-4 no-padding-right">
                              <select name="date" class="form-control" required>
                              <?php 
                              if(!empty($dob))
                              {
                              ?>
                              <option value="<?php echo $date;?>" selected><?php echo $date;?></option>
                              <?php 
                              }else 
                              {?>
                              <option value="">--Date--</option>
                             <?php 
                              }
                                        for($x=1;$x<=31;$x++)
                                        {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>
                             </div><div class="col-lg-4 no-padding-right no-padding-left">
                             <select name="month" class="form-control" required>
                             <?php 
                             $arrmonth=array();
                             $arrmonth[]="00";
                             $arrmonth[1] ="January";
                             $arrmonth[2] ="February";
                             $arrmonth[3] ="March";
                             $arrmonth[4] ="April";
                             $arrmonth[5] ="May";
                             $arrmonth[6] ="June";
                             $arrmonth[7] ="July";
                             $arrmonth[8] ="August";
                             $arrmonth[9] ="September";
                             $arrmonth[10] ="October";
                             $arrmonth[11] ="November";
                             $arrmonth[12] ="December";



                              if(!empty($dob))
                              {
                              ?>
                              <option value="<?php echo $month;?>" selected><?php echo $arrmonth[$month];?></option>
                              <?php 
                              }else 
                              {?>
                             <option value="">--Month--</option>
                             <?php 
                              }
                               ?>
                                        <?php                 
                                       
                                                        
            							for($i = 1; $i<=12; $i++)
            							{
                                               echo "<option value='$i'>$arrmonth[$i]</option>";
            							}
                                    ?>
                                    </select>
                             </div><div class="col-lg-4 no-padding-left">
                             <select name="year" class="form-control" required>
                               <?php 
                              if(!empty($dob))
                              {
                              ?>
                              <option value="<?php echo $year;?>" selected><?php echo $year;?></option>
                              <?php 
                              }else 
                              {?>
                             <option value="">--Year--</option>
                             <?php 
                              }
                               ?>
                                       
                                        <?php 
                                        $year=date('Y');
                                        $year1=date('Y')-60;
                                        for($x=$year;$x>=$year1;$x--)
                                        {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>
                             
                             </div>
                             </div>       
                                
                        </div>
                  </div>
                      <div class="row">
                          
                       
                    </div>
                    
                     
                     <div class="row">
                     <div class="col-lg-6">
                            <label for="LastName">Academic Year</label>
                             <select name="academicYearID" id="academicYearID"  class="form-control" required>
                             <option value="<?php echo $academicYearID;?>"selected><?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></option>
                  			<?php
                               $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                               if(!empty($academicYear)){
                                   $count = 0; 
                                   foreach($academicYear as $yr){ $count++;
                                   $academicYearID=$yr['academicYearID'];
                                   $academicYear=$yr['academicYear'];
                               ?>
                               <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                               <?php }}?>
							</select>
                        </div>
                        <div class="col-lg-6">
                            <label for="LastName">Registration Number</label>
                            <?php 
                            if($rgStatus==1)
                            {
                                ?>
                                <input type="text" name="regNumber" value="<?php echo $registrationNumber;?>"  class="form-control" readonly/>
                            <?php
                            }
                            else 
                            {?>
                            <input type="text" name="regNumber" value="<?php echo $registrationNumber;?>"  class="form-control" required/>
                        	<?php 
                            }?>
                        </div>
                    
                        
                  </div>  
                  

                  <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Manner of Entry</label>
                            <select name="mannerOfEntryID" id="mannerOfEntryID"  class="form-control" required>
                            <option value="<?php echo $mannerEntryID;?>"selected><?php echo $db->getData("manner_entry","mannerEntry","mannerEntryID",$mannerEntryID);?></option>
                  			<?php
                               $entry = $db->getRows('manner_entry',array('order_by'=>'mannerEntry ASC'));
                               if(!empty($entry)){
                                   $count = 0;
                                   foreach($entry as $ety){ $count++;
                                   $mannerEntryID=$ety['mannerEntryID'];
                                   $mannerEntry=$ety['mannerEntry'];
                               ?>
                               <option value="<?php echo $mannerEntryID;?>"><?php echo $mannerEntry;?></option>
                               <?php }}?>
							</select>
                        </div>

                        <div class="col-lg-6">
                            <label for="LastName">Mode of Enrollment</label>
                            <select name="batchID" id="batchID"  class="form-control" required>
                            <option value="<?php echo $batchID;?>"selected><?php echo $db->getData("batch","batchName","batchID",$batchID);?></option>
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
                   <div class="col-lg-6">
                            <label for="Physical Address">Programme Level</label>
                            <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required>
                            <?php 
                            $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
                            ?>
                            <option value="<?php echo $programmeLevelID;?>"selected><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?></option>
                  			<?php
                               $level = $db->getRows('programme_level',array('order_by'=>'programmeLevelCode ASC'));
                               if(!empty($level)){
                                   
                                   $count = 0; foreach($level as $lvl){ $count++;
                                   $programmeLevelID=$lvl['programmeLevelID'];
                                   $programmeLevel=$lvl['programmeLevel'];
                               ?>
                               <option value="<?php echo $programmeLevelID;?>"><?php echo $programmeLevel;?></option>
                               <?php }}?>
							</select>
						</div>
                        <div class="col-lg-6">
                            <label for="Physical Address">Programe Name</label>
                            <select name="programmeID" id="programmeID"  class="form-control" required>
                             <option value="<?php echo $programmeID;?>"selected><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?></option>
                               <option value="">Select Here</option>
							</select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Admission Number</label>
                            <input type="text" name="admissionnumber" value="<?php echo $admissionNumber;?>" class="form-control" required/>
                        </div>
                    
                        <div class="col-lg-6">
                            <label for="LastName">Form Four Index Number</label>
                            <input type="text" name="formfournumber" value="<?php echo $formfournumber?>"  class="form-control" required />
                        </div>
                  </div>

                   <div class="row">
                       <div class="col-lg-4">
                           <label for="LastName">Phone Number</label>
                           <input type="text" name="phoneNumber" value="<?php echo $phoneNumber;?>" class="form-control" required/>
                       </div>

                       <div class="col-lg-4">
                           <label for="LastName">Citizenship</label>
                           <input type="text" name="nationality" value="<?php echo $citizenship;?>"  class="form-control" required />
                       </div>

                       <div class="col-lg-4">
                           <label for="LastName">Email</label>
                           <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email" id="email" value="<?php echo $email;?>"  class="form-control" required="required email" />
                       </div>

                   </div>
                   
                 </fieldset>
						
                </div>
</div>

<div class="row">
<div class="col-lg-3"></div>

<input type="hidden" name="studentID" value="<?php echo $id;?>">
<input type="hidden" name="action_type" value="edit"/>
<div class="col-lg-3">
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-success btn-block  form-control"">
</div>
<div class="col-lg-3">
<button onclick="goBack()" class="btn btn-danger btn-block form-control">Cancel</button>
</div>
</div>

</div>
</form>
</div>
    </div>
    <div id="student_documents" class="tab-pane fade">

    </div>
    </div>
    </div>
    </div>
<?php }?>