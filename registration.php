<?php
$db = new DBHelper();
?>
<div id="" class="">
                <div class="well">

                <fieldset>
                  <legend>Personal Information</legend>
               
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">First Name</label>
                            <input type="text" name="fname" class="form-control" required="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Middle Name</label>
                            <input type="text" name="mname"  class="form-control" />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="lname"  class="form-control" required="" />
                        </div>

                        <div class="col-lg-3">
                            <label for="Geder">Gender</label>
                           <select name="gender" class="form-control" required="">
                             <option value="">Select Here</option>
                             <option value="Male">Male</option>
                             <option value="Female">Female</option>
                           </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="Date of Birth">Date of Birth</label>
                             <div class="input-group date" data-provide="datepicker">
                                <input type="text" id="pickyDate" name="dob"  class="form-control datepicker" required="" />
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div></div>
                        </div>

                        
                    </div>

                     </fieldset>
                     <fieldset>
                  <legend>Programme Information</legend>
                    <div class="row">
                      <div class="col-lg-4">
                            <label for="LastName">Department Name</label>
                           <select name="departmentID" class="form-control" required="">
                             <?php 
                            
                             
                                   $department = $db->getRows('departments',array('order_by'=>'department_name ASC'));
                                   if(!empty($department)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($department as $dept){ $count++;
                                    $department_name=$dept['department_name'];
                                    $department_id=$dept['department_id'];
                                   ?>
                                   <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
                                   <?php }}

                              ?>
                           </select>
                        </div>
                       <div class="col-lg-4">
                            <label for="FirstName">Programme Level</label>
                            <select name="programmeLevelID" class="form-control" required="">
                              <?php
                                 $programme_level = $db->getRows('programme_level',array('order_by'=>'programme_level DESC'));
                                 if(!empty($programme_level)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($programme_level as $level){ $count++;
                                  $programme_level=$level['programme_level'];
                                  $programme_level_id=$level['programme_level_id'];
                                 ?>
                                 <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>

                        <div class="col-lg-4">
                            <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control" required="">
                              <?php
                               $programmes = $db->getRows('programmes',array('order_by'=>'programme_name ASC'));
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programme_name'];
                                $programme_id=$prog['programme_id'];
                               ?>
                               <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                      
                    </div></fieldset>
                    <div class="row">
                        
                        <div class="col-lg-3">
                            <label for="FirstName">Registration Number</label>
                            <input type="text" name="regNumber" class="form-control" required />
                        </div>

                        <div class="col-lg-3">
                            <label for="MiddleName">Year of Admission</label>
                            <input type="text" name="yearOfAdmission"  class="form-control" required />
                        </div>
                        <div class="col-lg-3">
                            <label for="LastName">Admission Number</label>
                            <input type="text" name="admissionNumber" class="form-control" readonly />
                        </div>

                        <div class="col-lg-3">
                            <label for="Geder">Programme Status</label>
                           <select name="programmeStatus" class="form-control" required>
                             <option value="">Select Here</option>
                             <option value="Full Time">Full Time</option>
                             <option value="Evening">Evening</option>
                           </select>
                        </div>
                        
                    </div>
                   
                    <br />
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" value="Cancel" class="btn btn-primary form-control" />
                        </div>
                    </div>
                    <br />
                </div>
            </div>
