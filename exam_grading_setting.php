<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#admit').dataTable(
            {
                paging: false,
                searching:false
            });
    });
</script>
<div class="container">
    <h4>Define Exam Grading Setting</h4>
    <hr>
    <form name="" method="post" action="action_add_grading_setting.php">
    <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="MiddleName">Programme Level</label>
                            <select name="programmeLevelID[]" id="programmeLevelID[]" multiple class="form-control chosen-select" required>
                                <?php
                                $programmes = $db->getRows('programme_level',array('order_by'=>'programmeLevel ASC'));
                                if(!empty($programmes)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($programmes as $prog){ $count++;
                                        $programme_name=$prog['programmeLevel'];
                                        $programmeID=$prog['programmeLevelID'];
                                        ?>
                                        <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                                    <?php }}
                                ?>
                            </select>
                        </div></div>
                    <div class="col-lg-4">
                        <label for="MiddleName">Grading Year</label>
                        <select name="gradingYearID" id="gradingYearID" class="form-control" required>
                            <?php
                            $academicYear = $db->getRows('grading_year',array('where'=>array('status'=>1),'order_by'=>'Status ASC'));
                            if(!empty($academicYear)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($academicYear as $sm){ $count++;
                                    $gradingYearID=$sm['gradingYearID'];
                                    $gradingYearName=$sm['gradingYearName'];
                                    ?>
                                    <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYearName;?></option>
                                <?php }}

                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <table id="admit" class="display nowrap">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Exam Category</th>
                            <th>Start Mark</th>
                            <th>End Mark</th>
                            <th>Grade Point</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $users = $db->getRows('grade_category',array('where'=>array('status'=>1),'order_by'=>'gradeCategoryID ASC'));
                        if(!empty($users)){
                            $count = 0;
                            foreach($users as $user){
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <input type="text" hidden name="gradeCode<?php echo $count;?>" value="<?php echo $user['gradeCode'];?>">
                                    <td><?php echo $user['gradeCode'];?></td>
                                    <td><input type="text" name="startMark<?php echo $count;?>" class="form-control"></td>
                                    <td><input type="text" name="endMark<?php echo $count;?>" class="form-control"></td>
                                    <td><input type="text" name="gradePoint<?php echo $count;?>" class="form-control"></td>
                                    <td>
                                        <select name="remarks<?php echo $count;?>" class="form-control">
                                            <?php
                                            $remarks = $db->getRows('remarks',array('order_by'=>'remark ASC'));
                                            if(!empty($remarks)){
                                                echo"<option value=''>Please Select Here</option>";
                                                foreach($remarks as $sm){
                                                    $remark=$sm['remark'];
                                                    $remarkID=$sm['remarkID'];
                                                    ?>
                                                    <option value="<?php echo $remarkID;?>"><?php echo $remark;?></option>
                                                <?php }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php }
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6"></div>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="hidden" name="number" value="<?php echo $count;?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
                </div>
                <div class="col-lg-3">
                    <input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">
                </div></div>

        </div>
    </form>
</div>
