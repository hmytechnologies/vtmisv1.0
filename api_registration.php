<?php $db=new DBHelper();
?>
<div class="container">
    <h3>API Registration</h3>

    <h5 class="text-danger">NB:Select Programme and Year and Filter from Admission System</h5>
    <hr>
    <form name="" method="post" action="" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-4">
                <label for="MiddleName">Programme Name</label>
                <select name="programmeCode" id="programID" class="form-control" required>
                    <?php
                    $programmes = $db->getRows('programmes',array('where'=>array('status'=>1),'order_by'=>'programmeName ASC'));
                    if(!empty($programmes)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($programmes as $prog){ $count++;
                            $programme_name=$prog['programmeName'];
                            $programmeID=$prog['programmeID'];
                            $programmeCode=$prog['programmeCode'];
                            ?>
                            <option value="<?php echo $programmeCode;?>"><?php echo $programme_name;?></option>
                        <?php }}
                    ?>
                </select>
            </div>

            <div class="col-lg-4">
                <label for="FirstName">Academic Year</label>
                <select name="academicYearID" id="academicYearID" class="form-control" required>
                    <?php
                    $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                    if(!empty($academic_year)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($academic_year as $sm){ $count++;
                            $academicYear=$sm['academicYear'];
                            $academicYearID=$sm['academicYearID'];
                            ?>
                            <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                        <?php }
                    }

                    ?>
                </select>
            </div>

            <div class="col-lg-4">
                           <label for="FirstName">Study Mode/Admission Intake</label>
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
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
        </div>
    </form>

    <div class="row"><br></div>

</div>