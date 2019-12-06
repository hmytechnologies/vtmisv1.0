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
      <h1>Student Details<hr></h1>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#personal"><strong>Personal Information</strong></a></li>
        <li><a data-toggle="tab" href="#academic"><strong>Academic Information</strong></a></li>
        <li><a data-toggle="tab" href="#emergency"><strong>Other Information</strong></a></li>
    </ul>
    <div class="tab-content">
    <?php 
    $studentID=$db->my_simple_crypt($_REQUEST['id'],'d');
    $studentData=$db->getRows('student',array('where'=>array('studentID'=>$studentID),'order_by'=>'studentID ASC'));
    if(!empty($studentData)){ 
    foreach($studentData as $std)
    {
       $studentID=$std['studentID'];
       $fname=$std['firstName'];
       $mname=$std['middleName'];
       $lname=$std['lastName'];
       $oname=$std['otherNames'];
       $gender=$std['gender'];
       $pobirth=$std['placeOfBirth'];
       $mstatus=$std['maritalStatus'];
       $citizenship=$std['citizenship'];
       $dob=$std['dateOfBirth'];
       $paddress=$std['physicalAddress'];
       $pnumber=$std['phoneNumber'];
       $email=$std['email'];
       $nkin=$std['nextOfKinName'];
       $nphone=$std['nextOfkinPhoneNumber'];
       $naddress=$std['nextOfKinAddress'];
       $nrelation=$std['relationship'];
       $dstatus=$std['disabilityStatus'];
       $empStatus=$std['employmentStatus'];
       $sponsor=$std['sponsor'];
       $studentPicture=$std['studentPicture'];
       
       $programmeID=$std['programmeID'];
       $registrationNumber=$std['registrationNumber'];
       $admissionNumber=$std['admissionNumber'];
       $academicYearID=$std['academicYearID'];
       $mannerEntryID=$std['mannerEntryID'];
       $batchID=$std['batchID'];
       $formfournumber=$std['formFourIndexNumber'];
       $rgStatus=$std['rgStatus'];
    ?>    
        <div id="personal" class="tab-pane fade in active">
           <h3>Personal Information</h3>
        <div class="row">
        	<div class="col-lg-12">
            <hr>
        </div></div>
        <div class="well">
                    <div class="row">
                    <div class="col-lg-8">
                    <div class="row">
                    <div class="col-lg-6">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" value="<?php echo $fname;?>" class="form-control" required disabled/>
                        </div>
                        <div class="col-lg-6">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname" value="<?php echo $mname;?>"  class="form-control" disabled/>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname" value="<?php echo $lname;?>"  class="form-control" required disabled/>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="LastName">Other Names</label>
                            <input type="text" name="oname" value="<?php echo $oname;?>"  class="form-control" disabled/>
                        </div>
                        
                    </div>
                    <div class="row">
                    <div class="col-lg-6">
                            <label for="gender">Gender</label>
                           
                            <select name="gender" class="form-control" disabled>
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
                            <label for="Geder">Marital Status</label>
                           <select name="mstatus" class="form-control" required disabled>
                             
                             <?php
                             if($mstatus=="")
                             {
                             ?><option value="">Select Here</option>
                             <?php }
                             else
                             {
                             ?>
                             <option value="<?php echo $mstatus;?>" selected><?php echo $mstatus;?></option>
                             <?php }?>
                             <option value="Single">Single</option>
                             <option value="Married">Married</option>
                             <option value="Windowed">Windowed</option>
                             <option value="Divorced">Divorced</option>
                           </select>
                        </div>   
                    </div>
                    </div>
                    <div class="col-lg-2">
                    <!-- Picture -->
			 <div class="row">
			  <div class="col-lg-12">
			   <label for="Picture">Student Picture</label>
				<img id="image" src="student_images/<?php echo $studentPicture;?>" height="150px" width="150px;" />
				
			  </div></div>
                    <!-- Picture -->
                    </div>
                        
                    </div>
                    <div class="row">
    				<div class="col-lg-8">
                    <div class="row">
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
                              <select name="date" class="form-control" required disabled>
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
                             <select name="month" class="form-control" required disabled>
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
                             <select name="year" class="form-control" required disabled>
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
  
                        <div class="col-lg-6">
                            <label for="Physical Address">Place of Birth</label>
                            <input type="text" name="placeOfBirth" value="<?php echo $pobirth;?>"  class="form-control" required disabled/>
                        </div>
                        
                        
                    </div>
                  <div class="row">
                        <div class="col-lg-3">
                            <label for="Physical Address">Nationality</label>
                            
                            <input type="text" name="citizenship" value="<?php echo $citizenship;?>"  class="form-control" required disabled/>
                        </div>

                        <div class="col-lg-3">
                            <label for="Physical Address">Physical Address</label>
                            <input type="text" name="address" value="<?php echo $paddress;?>"  class="form-control" required disabled/>
                        </div>
                        <div class="col-lg-3">
                            <label for="Email">Email</label>
                            <input type="text" name="appemail" value="<?php echo $email;?>"  class="form-control" disabled/>
                        </div>

                        <div class="col-lg-3">
                            <label for="Phone">Phone Number</label>
                            <input type="text" name="phoneNumber" value="<?php echo $pnumber;?>" class="form-control" required disabled>
                        </div>
                    </div>
                  <div class="row">
                      

                        <div class="col-lg-3">
                            <label for="Geder">Do you have any disability?</label>
                            <select name="disability" id="disability" class="form-control" required disabled>
                             <?php
                             if($dstatus=="")
                             {
                             ?>
                                <option value="" selected>Select Here</option>
                             <option value="ndio">Yes</option>
                             <option value="hapana">No</option>
                             <?php
                             }
                             else if($dstatus=='No')
                             {
                             ?>
                             <option value="ndio">Yes</option>
                             <option value="hapana" selected>No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="ndio" selected>Yes</option>
                             <option value="hapana">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                       <?php
                        if($dstatus=="Yes")
                        {
                            $disabilityData=$db->getRows("disability",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                            if(!$empty($disabilityData)){
                                foreach($disabilityData as $sp)
                                {
                                    $disabilityName=$sp['disabilityName'];
                                    $disabilityDescription=$sp['disabilityDescription'];
                                }
                            }else
                            {
                                $disabilityName="";
                                $disabilityDescription="";
                            }

                        ?>
                        <div class="ndio"><div class="col-lg-3">
                            <label for="FirstName">Disability Name</label>
                            <input type="text" name="dname"  value="<?php echo $disabilityName;?>" class="form-control" required disabled/>
                        </div>

                        <div class="col-lg-6">
                            <label for="MiddleName">Disability Description</label>
                            <input type="text" name="ddescription" value="<?php echo $disabilityDescription;?>"  class="form-control" required disabled/>
                        </div>

                        </div>
                            <?php
                        }
                       ?>

                            </div>
                     </div></div>
                    
        </div></div>
        
        <div id="academic" class="tab-pane fade">
            <h3>Academic Information</h3>
        <div class="row">
        	<div class="col-lg-12">
            <hr>
        </div></div>
        
        <!-- Start -->
        <div class="col-lg-10">
                     <div class="row">
                     <div class="col-lg-6">
                            <label for="LastName">Academic Year</label>
                             <select name="academicYearID" id="academicYearID"  class="form-control" required disabled>
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
                            <input type="text" name="regNumber" value="<?php echo $registrationNumber;?>"  class="form-control" required disabled>
                        	<?php 
                            }?>
                        </div>
                    
                        
                  </div>  
                  
                  
                  <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Manner of Entry</label>
                            <select name="mannerOfEntryID" id="mannerOfEntryID"  class="form-control" required disabled>
                            <option value="<?php echo $mannerEntryID;?>"selected><?php echo $db->getData("manner_entry","mannerEntry","mannerEntryID",$mannerEntryID);?></option>
                  			<?php
                               $entry = $db->getRows('manner_entry',array('order_by'=>'mannerEntry ASC'));
                               if(!empty($entry)){
                                   
                                   $count = 0; foreach($entry as $ety){ $count++;
                                   $mannerEntryID=$ety['mannerEntryID'];
                                   $mannerEntry=$ety['mannerEntry'];
                               ?>
                               <option value="<?php echo $mannerEntryID;?>"><?php echo $mannerEntry;?></option>
                               <?php }}?>
							</select>
                        </div>
                    
                        <div class="col-lg-6">
                            <label for="LastName">Mode of Enrollment</label>
                            <select name="batchID" id="batchID"  class="form-control" required disabled>
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
                            <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required disabled>
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
                            <select name="programmeID" id="programmeID"  class="form-control" required disabled>
                             <option value="<?php echo $programmeID;?>"selected><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?></option>
                               <option value="">Select Here</option>
							</select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="LastName">Admission Number</label>
                            <input type="text" name="admissionnumber" value="<?php echo $admissionNumber;?>" class="form-control" required disabled/>
                        </div>
                    
                        <div class="col-lg-6">
                            <label for="LastName">Form Four Index Number</label>
                            <input type="text" name="formfournumber" value="<?php echo $formfournumber?>"  class="form-control" required disabled/>
                        </div>
                  </div>
                  </div>
        <!-- End -->
        
        </div>
        
        
        <div id="emergency" class="tab-pane fade">
            <h3>Other Information</h3>
        <div class="row">
        	<div class="col-lg-12">
            <hr>
        </div></div>
        <!-- Start Here -->
        <fieldset>
                  <legend class="text-success">Employment Information</legend>
               
                    <div class="row">
                	<div class="col-lg-10">
                	<div class="row">
                        <div class="col-lg-3">
                            <label for="Geder">Are You Employed?</label>
                            <select name="employed" id="employed" class="form-control" required disabled>
                             <?php
                             if($empStatus=="")
                             {
                             ?>
                             <option value="" selected>Select Here</option>
                             <option value="yes">Yes</option>
                             <option value="no">No</option>
                             <?php
                             }
                             else if($empStatus=='no')
                             {
                             ?>
                             <option value="yes">Yes</option>
                             <option value="no" selected>No</option>
                             <?php
                             }else
                             {
                             ?>
                             <option value="yes" selected>Yes</option>
                             <option value="no">No</option>
                             <?php 
                             }
                             ?>
                           </select>
                        </div>
                        <?php
                        if($empStatus=="yes")
                        {
                            $sponsorData=$db->getRows("employmentstatus",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
       
                            foreach($sponsorData as $sp)
                            {
                                $employer=$sp['employer'];
                                $placework=$sp['placeOfWork'];
                                $designation=$sp['designation'];
                            }
                        }
                        else
                        {
                            $employer="";
                            $placework="";
                            $designation="";
                        }
                        ?>
                        <div class="yes"><div class="col-lg-3">
                            <label for="FirstName">Name of Employer</label>
                            <input type="text" name="employer" value="<?php echo $employer;?>" class="form-control" required disabled />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address/Place of Work</label>
                            <input type="text" name="placework" value="<?php echo $placework;?>"  class="form-control" required disabled />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Designation</label>
                            <input type="text" name="designation" value="<?php echo $designation;?>"  class="form-control" required disabled/>
                        </div>
                        </div></div></div></div></fieldset>

                    <fieldset>
                  <legend>Emergency Information</legend>
               		<div class="row">
                	<div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">Name of Next of Kin</label>
                            <input type="text" name="nextName" value="<?php echo $nkin;?>" class="form-control" required disabled/>
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address of Next of Kin </label>
                            <input type="text" name="nextAddress" value="<?php echo $naddress;?>" class="form-control" required disabled/>
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Phone Number</label>
                            <input type="text" name="nextPhoneNumber" value="<?php echo $nphone;?>"  class="form-control" required disabled/>
                        </div>

                        <div class="col-lg-3">
                            <label for="Geder">Relationship</label>
                            <input type="text" name="relationship"  class="form-control" value="<?php echo $nrelation;?>" required disabled/>
                        </div>
                    </div></div></div></fieldset>

                   <fieldset>
                  <legend>Sponsorship Information</legend>
               		<div class="row">
                	<div class="col-lg-10">
                    <div class="row">
                    <div class="col-lg-3">
                            <label for="gender">Sponsor</label>
                            <select name="sponsor" id="sponsor" class="form-control" required disabled>
                                <?php
                                if($sponsor=="")
                                {
                                    ?>
                                    <option value="">Select Here</option>
                                <?php }
                                else {
                                    ?>
                                    <option value="<?php echo $sponsor;?>"><?php echo $db->getData("sponsor_type","sponsorCode","sponsorTypeID",$sponsor); ?></option>
                                    <?php
                                }
                                ?>

                                <?php
                                $stype = $db->getRows('sponsor_type',array('order_by'=>'sponsorCode ASC'));
                                if(!empty($stype)){

                                    foreach($stype as $spl){
                                        $sponsorTypeID=$spl['sponsorTypeID'];
                                        $sponsorCode=$spl['sponsorCode'];
                                        ?>
                                        <option value="<?php echo $sponsorTypeID;?>"><?php echo $sponsorCode;?></option>
                                    <?php }}?>
                           </select>
                        </div>
                        <?php
                        if($sponsor=="others")
                        {
                            $sponsorData=$db->getRows("sponsor",array('where'=>array('studentID'=>$studentID),'order_by studentID ASC'));
                            if(!empty($sponsorData)) {
                                foreach ($sponsorData as $sp) {
                                    $sponsorname = $sp['sponsorName'];
                                    $sponsoraddress = $sp['sponsorAddress'];
                                    $sponsorphone = $sp['sponsorPhoneNumber'];
                                }
                            }else
                            {
                                $sponsorname = "";
                                $sponsoraddress = "";
                                $sponsorphone = "";
                            }

                       ?>
                        <div class="others">
                        <div class="col-lg-3">
                            <label for="FirstName">Sponsor's Full Name</label>
                            <input type="text" name="sponsorname" value="<?php echo $sponsorname;?>" class="form-control" required disabled/>
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Address</label>
                            <input type="text" name="sponsoraddress" value="<?php echo $sponsoraddress;?>"  class="form-control" required disabled/>
                        </div>
                        <div class="col-lg-3">
                            <label for="MiddleName">Phone Number</label>
                            <input type="text" name="sponsorphonenumber" value="<?php echo $sponsorphone;?>"  class="form-control" required disabled/>
                        </div>
                        </div>
                            <?php
                        }
                            ?>
                    </div></div></div></fieldset>
        
        <!-- End Here -->
        </div>
      <?php 
    }
    }?>  
    </div>
    </div>
</div>