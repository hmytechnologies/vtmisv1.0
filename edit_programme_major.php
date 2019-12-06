<h1>Edit Programme Major</h1>
<?php
$db = new DBHelper();
$programmeMajorID=$db->my_simple_crypt($_REQUEST['id'],'d');
$user = $db->getRows('programme_major',array('where'=>array('programmeMajorID'=>$programmeMajorID)));
if(!empty($user)) {
    foreach ($user as $userData) {
        ?>
        <form name="" method="post" action="action_programme.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Programme Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $userData['programmeName']; ?>"
                                   class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Programme Code</label>
                            <input type="text" id="code" name="code" value="<?php echo $userData['programmeMajorCode']; ?>"
                                   class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Programme Duration</label>
                            <input type="number" id="duration" name="duration"
                                   value="<?php echo $userData['programmeDuration']; ?>" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Programme Credits</label>
                            <input type="number" id="credits" name="credits"
                                   value="<?php echo $userData['programmeCredits']; ?>" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="email">Programme Level</label>
                            <select name="programme_level_id" class="form-control">
                                <option
                                    value='<?php echo $db->getData('programme_level', 'programmeLevelID', 'programmeLevelID', $userData['programmeLevelID']); ?>'>
                                    <?php echo $db->getData('programme_level', 'programmeLevel', 'programmeLevelID', $userData['programmeLevelID']); ?></option>
                                <?php
                                $programme_level = $db->getRows('programme_level', array('order_by' => 'programmeLevel DESC'));
                                if (!empty($programme_level)) {
                                    $count = 0;
                                    foreach ($programme_level as $level) {
                                        $count++;
                                        $programme_level = $level['programmeLevel'];
                                        $programme_level_id = $level['programmeLevelID'];
                                        ?>
                                        <option
                                            value="<?php echo $programme_level_id; ?>"><?php echo $programme_level; ?></option>
                                    <?php }
                                } ?>
                            </select>

                        </div>


                                <div class="form-group">
                                    <label for="email">School Name</label>
                                    <select name="schoolID" class="form-control" required>
                                        <option
                                            value='<?php echo $db->getData('schools', 'schoolID', 'schoolID', $userData['schoolID']); ?>'>
                                            <?php echo $db->getData('schools', 'schoolName', 'schoolID', $userData['schoolID']); ?></option>                                        <?php
                                        $schools = $db->getRows('schools',array('order_by'=>'schoolID DESC'));
                                        if(!empty($schools)){ $count = 0; foreach($schools as $level){ $count++;
                                            $schoolName=$level['schoolName'];
                                            $schoolID=$level['schoolID'];
                                            ?>
                                            <option value="<?php echo $schoolID;?>"><?php echo $schoolName;?></option>
                                        <?php }}?>
                                    </select>
                                </div>


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

                        </div>

                            <div class="form-group">
                                <label for="email">Campus Name</label>
                                <select name="campusID"  class="form-control">
                                    <option
                                        value='<?php echo $db->getData('campus', 'campusID', 'campusID', $userData['campusID']); ?>'>
                                        <?php echo $db->getData('campus', 'campusName', 'campusID', $userData['campusID']); ?></option>
                                    <?php
                                    $campus = $db->getRows('campus',array('order_by'=>'campusName ASC'));
                                    if(!empty($campus)){ $count = 0; foreach($campus as $dept){ $count++;
                                        $campusName=$dept['campusName'];
                                        $campusID=$dept['campusID'];
                                        ?>
                                        <option value="<?php echo $campusID;?>"><?php echo $campusName;?></option>
                                    <?php }
                                    }?>
                                </select>
                            </div>

                        <div class="form-group">
                            <label for="email">Programme Status</label>
                            <?php if ($userData['status'] == 1) {
                                ?>
                                <input type="radio" name="status" value="1" checked>Active
                                <input type="radio" name="status" value="0">Not Active
                            <?php } else { ?>
                                <input type="radio" name="status" value="1">Active <input type="radio" name="status"
                                                                                          value="0" checked>Not Active
                            <?php } ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="action_type" value="edit_programme_major"/>
                            <input type="hidden" name="id" value="<?php echo $programmeMajorID; ?>">
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