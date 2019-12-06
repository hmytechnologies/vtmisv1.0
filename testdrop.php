<form name="" method="post" action="">
            
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