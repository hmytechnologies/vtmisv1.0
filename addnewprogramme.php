<form name="" method="post" action="action_programme.php">
                    <script type="text/javascript" src="js/jquery.min.js"></script>
                    <script src="js/jquery-1.4.2.min.js"></script>


                <div class="row">
                    <div class="col-lg-12">
                    <h3>Add new Trade/Course/Programme</h3>
                    </div>
                    <div class="col-md-8">
                        <div class="row">

                                <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Trade Name</label>
                                <input type="text" id="name" name="name" placeholder="Course Name" class="form-control" required/>
                            </div>
                                </div>

                                <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Trade Code</label>
                                <input type="text" id="code" name="code" placeholder="Course Code" class="form-control" required/>
                            </div>
                                </div></div>

                                <div class="row">
                                <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Trade Duration</label>
                                <input type="number" id="duration" name="duration" placeholder="Course Duration" class="form-control" required/>
                            </div>
                                </div>

                                <div class="col-lg-6">
                            <div class="form-group">
                                        <label for="email">Trade Type</label>
                                        <select name="programme_type_id"  class="form-control">
                                            <option value="">Select Here</option>
                                            <?php
                                            $programme_type = $db->getRows('programme_type',array('order_by'=>'programmeTypeID ASC'));
                                            if(!empty($programme_type)){ $count = 0; foreach($programme_type as $pt){ $count++;
                                                $programmeType=$pt['programmeType'];
                                                $programmeTypeID=$pt['programmeTypeID'];
                                                ?>
                                                <option value="<?php echo $programmeTypeID;?>"><?php echo $programmeType;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                    </div>
                        </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Trade Level</label>
                                <select name="programme_level_id[]"  class="form-control chosen-select" multiple>
                                    <option value="">Select Here</option>
                                    <?php
                                    $programme_level = $db->getRows('programme_level',array('order_by'=>'programmeLevel ASC'));
                                    if(!empty($programme_level)){ $count = 0; foreach($programme_level as $level){ $count++;
                                        $programme_level=$level['programmeLevel'];
                                        $programme_level_id=$level['programmeLevelID'];
                                        ?>
                                        <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
                                    <?php }}?>
                                </select>

                            </div></div>
                            

                                    <div class="col-lg-6">
                            <div class="form-group">
                                        <label for="email">Department Name</label>
                                        <select name="departmentID"  class="form-control">
                                            <option value="">Select Here</option>
                                            <?php
                                            $department = $db->getRows('departments',array('order_by'=>'departmentName ASC'));
                                            if(!empty($department)){ $count = 0; foreach($department as $dept){ $count++;
                                                $departmentName=$dept['departmentName'];
                                                $departmentID=$dept['departmentID'];
                                                ?>
                                                <option value="<?php echo $departmentID;?>"><?php echo $departmentName;?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                    </div>



                    </div>

                    <div class="row">
                        <br><br>
                        <div class="col-md-10"
                        <div class="col-lg-6">
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="form-control btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="submit" name="doSubmit" value="Save Records" class="form-control btn btn-success">
                        </div>
                    </div>

                    </div></div>
            </form>