<h1>Edit Programme Data</h1>
<hr>
<?php
$db = new DBHelper();
$programmeID=$db->my_simple_crypt($_REQUEST['id'],'d');
$user = $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID)));
if(!empty($user)) {
    foreach ($user as $userData) {
        ?>
        <form name="" method="post" action="action_programme.php">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Programme Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $userData['programmeName']; ?>"
                                   class="form-control"/>
                        </div>
                        </div>

                        <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Programme Code</label>
                            <input type="text" id="code" name="code" value="<?php echo $userData['programmeCode']; ?>"
                                   class="form-control"/>
                        </div>
                        </div>
                    </div>

                        <div class="row">
                        <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Programme Duration</label>
                            <input type="number" id="duration" name="duration"
                                   value="<?php echo $userData['programmeDuration']; ?>" class="form-control"/>
                        </div>
                        </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Trade Type</label>
                                    <select name="programme_type_id"  class="form-control">
                                        <option value='<?php echo $db->getData('programme_type', 'programmeTypeID', 'programmeTypeID', $userData['programmeTypeID']); ?>'>
                                            <?php echo $db->getData('programme_type', 'programmeType', 'programmeTypeID', $userData['programmeTypeID']); ?></option>

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
                            <label for="email">Programme Level<span style="font-size: 10px;color: red">(If you dont want to change your level then leave empty,otherwise select level)</span></label>
                            <select name="programme_level_id[]" class="form-control chosen-select" multiple>
                                <option value='<?php echo $db->getData('programme_level', 'programmeLevelID', 'programmeLevelID', $userData['programmeLevelID']); ?>'>
                                    <?php echo $db->getData('programme_level', 'programmeLevel', 'programmeLevelID', $userData['programmeLevelID']); ?></option>
                                <?php
                                $programme_level = $db->getRows('programme_level', array('order_by' => 'programmeLevel ASC'));
                                if (!empty($programme_level)) {
                                    $count = 0;
                                    foreach ($programme_level as $level) {
                                        $count++;
                                        $programme_level = $level['programmeLevel'];
                                        $programme_level_id = $level['programmeLevelID'];
                                        ?>
                                        <option value="<?php echo $programme_level_id; ?>"><?php echo $programme_level; ?></option>
                                    <?php }
                                } ?>
                            </select>

                        </div>
                            </div>

                            <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Department Name</label>
                            <select name="departmentID" class="form-control">
                                <option
                                        value='<?php echo $db->getData('departments', 'departmentID', 'departmentID', $userData['departmentID']); ?>'>
                                    <?php echo $db->getData('departments', 'departmentName', 'departmentID', $userData['departmentID']); ?></option>
                                <?php
                                $department = $db->getRows('departments', array('order_by' => 'departmentName DESC'));
                                if (!empty($department)) {
                                    $count = 0;
                                    foreach ($department as $dept) {
                                        $count++;
                                        $department_name = $dept['departmentName'];
                                        $department_id = $dept['departmentID'];
                                        ?>
                                        <option
                                                value="<?php echo $department_id; ?>"><?php echo $department_name; ?></option>
                                    <?php }
                                } ?>
                            </select>

                        </div></div></div>

                        <div class="row">
                        <div class="col-lg-4">
                        <div class="form-group">
                            <label for="email">Programme Status</label>
                            <?php if ($userData['status'] == 1) {
                                ?>
                                <input type="radio" name="status" value="1" checked>Active
                                <input type="radio" name="status" value="0">Not Active
                            <?php } else { ?>
                                <input type="radio" name="status" value="1">Active
                                <input type="radio" name="status" value="0" checked>Not Active
                            <?php } ?>
                        </div></div></div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="hidden" name="id" value="<?php echo $programmeID; ?>">
                            <input type="submit" name="doSubmit" value="Update Records" class="btn btn-success"
                                   tabindex="8">
                        </div>
                        <div class="col-lg-3">
                            <a href="index3.php?sp=programmes" class="btn btn-danger form-control">Cancel</a>
                        </div>
                    </div>
        </form>
        </div>
        </div>

    <?php }
}?>