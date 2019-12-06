<script type="text/javascript">
    $(document).ready(function () {
        $("#organization").DataTable();
    });
</script>

<h1>Financial Assistant</h1>

<?php
$db = new DBHelper();
$today=date("Y-m-d");
$sm=$db->readSemesterSetting($today);
foreach ($sm as $s)
{
    $semisterID=$s['semesterID'];
    $academicYearID=$s['academicYearID'];
    $semesterName=$s['semesterName'];
    $semesterSettingID=$s['semesterSettingID'];
}
$studentID = $db->getRows('student',array('where'=>array('userID'=>$_SESSION['user_session']),' order_by'=>' studentID ASC'));
if(!empty($studentID)) {
    $count = 0;
    foreach ($studentID as $std) {
        $count++;
        $studentID = $std['studentID'];
        $fname = $std['firstName'];
        $mname = $std['middleName'];
        $lname = $std['lastName'];
        $gender = $std['gender'];
        $regNumber = $std['registrationNumber'];
    }
}
$sfassistant = $db->getRows('student_financial_assistant',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterSettingID),'order_by'=>'regNumber DESC'));
?>
<?php
if(empty($sfassistant)) {
    $data= $db->getRows('misc_date_setting',array('where'=>array('examCategoryID'=>4,'semesterSettingID'=>$semesterSettingID),'order_by=>startDate DESC'));
    if(!empty($data)) {
        foreach($data as $dt) {
            $sDate = $dt['startDate'];
            $eDate = $dt['endDate'];
            $today=date('Y-m-d');
            if($today<=$eDate) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">
                                Apply
                                for
                                Financial Assistant
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button class="btn btn-danger">Application Closed</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    else
    {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <button class="btn btn-danger">Application Closed</button>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <br>
        <?php
        if(!empty($_REQUEST['msg']))
        {
            if($_REQUEST['msg']=="succ")
            {
                echo "<div class='alert alert-success fade in'><a href='index3.php?sp=financial_assistant' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been inserted successfully</strong>.
</div>";
            }
            else if($_REQUEST['msg']=="edited")
            {
                echo "<div class='alert alert-success fade in'><a href='index3.php?sp=financial_assistant' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been edited Successfully</strong>.
</div>";
            }
            else if($_REQUEST['msg']=="deleted")
            {
                echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=financial_assistant' class='close' data-dismiss='alert'>&times;</a>
    <strong>Organization data has been deleted Successfully</strong>.
</div>";
            }
            else
            {
                echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=financial_assistant' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
            }
        }
        ?>


    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table  id="organization" class="table table-bordered table-responsive-xl table-hover display">
            <thead>
            <tr>
                <th>Semester Name</th>
                <th>Assistant Type</th>
                <th>Reason</th>
                <th>Applied/Expire Date</th>
                <th>Status</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($sfassistant)){ $count = 0; foreach($sfassistant as $sfas){ $count++;
                ?>
                <tr>
                    <td><?php echo $db->getData('semester_setting','semesterName','semesterSettingID',$sfas['semesterSettingID']); ?></td>
                    <td><?php echo $db->getData('financial_assistant_type','financialAssistantType','financialAssistantID',$sfas['financialAssistantID']); ?></td>
                    <td><?php echo $sfas['reason']; ?></td>
                    <td><?php echo $sfas['appliedDate'].'/'.$sfas['expiredDate']; ?></td>
                    <td><?php echo $sfas['status'];?></td>

                    <td><img src="img/<?php echo $sfas['attachment'];?>"></td>
                    <td>
                       <!-- <a href="action_organization.php?action_type=delete_org&id=<?php /*echo $db->my_simple_crypt($user['organizationID'],'e'); */?>
                " class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Organization?');"></a>-->

                    </td>
                </tr>
            <?php } }else{ ?>

            <?php } ?>
            </tbody>
        </table>
    </div></div>



<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form name="" method="post" action="action_financial_assistant.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email">Semester Name</label>
                                <select name="semesterID" id="semesterID" class="form-control" required>
                                    <?php
                                    $semister = $db->getRows('semester_setting',array('where'=>array('semesterStatus'=>1),'order_by'=>'semesterName ASC'));
                                    if(!empty($semister)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($semister as $sm){ $count++;
                                            $semister_name=$sm['semesterName'];
                                            $semister_id=$sm['semesterSettingID'];
                                            ?>
                                            <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                        <?php }}

                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Financial Assistant Type</label>
                                <select name="financialID" id="financialID" class="form-control" required>
                                    <?php
                                    $fassistant = $db->getRows('financial_assistant_type',array('order_by'=>'financialAssistantID ASC'));
                                    if(!empty($fassistant)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($fassistant as $fasst){ $count++;
                                            $assistant=$fasst['financialAssistantType'];
                                            $id=$fasst['financialAssistantID'];
                                            ?>
                                            <option value="<?php echo $id;?>"><?php echo $assistant;?></option>
                                        <?php }}

                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Reason for Applying</label>
                                <textarea name="reason"  class="form-control"></textarea>
                            </div>


                            <div class="form-group">
                                <label for="email">Proof of Applying</label>
                                <input type="file" id="image" name="image" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>