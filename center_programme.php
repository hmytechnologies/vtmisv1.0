<?php $db=new DBHelper();
?>
<div class="container">
    <h1>Center Programme Mapping</h1>
    <hr>
    <div class="col-md-12">
        <div class="row">

            <h3>Select Center to Map with Programme/Trade</h3>

            <div class="row">
                <form name="" method="post" action="">
                    <div class="col-lg-4">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerRegistrationID" class="form-control chosen-select" required="">
                            <?php
                                $center = $db->getRows('center_registration', array('order_by' => 'centerName ASC'));
                            if(!empty($center)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($center as $cnt){ $count++;
                                    $centerName=$cnt['centerName'];
                                    $centerID=$cnt['centerRegistrationID'];
                                    ?>
                                    <option value="<?php echo $centerID;?>"><?php echo $centerName;?></option>
                                <?php }}
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for=""></label>
                        <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" /></div>
                </form>
            </div>
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <?php
                //Save Records Buttoon

                if((isset($_POST['doSearch'])=="Search Records")||(isset($_REQUEST['action'])=="getRecords"))
                {
                    if(isset($_POST['doSearch'])=="Search Records") {
                        $centerRegistrationID = $_POST['centerRegistrationID'];
                    }
                    else {
                        $centerRegistrationID = $db->my_simple_crypt($_REQUEST['centerID'],'d');
                    }
                    ?>
                    <div class="col-lg-12">
                    <div class="row"><h4 class="text-info">Register New Trade for <?php echo $db->getData('center_registration','centerName','centerRegistrationID',$centerRegistrationID); ?></h4></div>



                    <form name="" method="post" action="action_center_programme_mapping.php">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="MiddleName">Trade Name</label>

                                <select name="programmeID" class="form-control chosen-select" required="">

                                    <?php
                                    $programmes = $db->filterTrade($centerID);
                                    //$programmes=$db->getRows('programmes',array('order by programmeName ASC'));
                                    if(!empty($programmes)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($programmes as $c){ $count++;
                                            $tradeName=$c['programmeName'];
                                            $tradeID=$c['programmeID'];
                                            ?>
                                            <option value="<?php echo $tradeID;?>"><?php echo $tradeName;?></option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="FirstName">Trade Level</label>
                                <select name="programme_level_id[]" class="form-control chosen-select" multiple>
                                    <?php
                                    $programmeLevel= $db->getRows('programme_level',array('order_by'=>'programmeLevelID ASC'));
                                    if(!empty($programmeLevel)){
                                        echo"<option value=''>Please Select Here</option>";
                                        $count = 0; foreach($programmeLevel as $plevel){ $count++;
                                            $programmeLevelID=$plevel['programmeLevelID'];
                                            $programmeLevelName=$plevel['programmeLevel'];
                                            ?>
                                            <option value="<?php echo $programmeLevelID;?>"><?php echo $programmeLevelName;?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="hidden" name="centerRegID" value="<?php echo $centerRegistrationID;?>">
                                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                            </div>
                            <div class="col-lg-3">
                                <input type="reset" value="Cancel" class="btn btn-danger form-control" />
                            </div>
                        </div>
                    </form>
                    </div>
            </div>
                    <div class="row">
                        <?php
                        if(!empty($_REQUEST['msg']))
                        {
                            if($_REQUEST['msg']=="succ")
                            {
                                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Mapping data has been inserted successfully</strong>.
</div>";
                            }
                            else if($_REQUEST['msg']=="deleted") {
                                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Mapping Data has been delete successfully</strong>.
</div>";
                            }
                        }
                        ?>
                    </div>

                    <?php
                    /*$data= $db->getRows("center_programme",array('where'=>array('centerRegistrationID'=>$db->my_simple_crypt($_REQUEST['centerID'],'d')),' order_by'=>'centerRegistrationID ASC'));*/
                    $data= $db->getCenterProgramme($centerRegistrationID);
                    if(!empty($data))
                    {
                        ?>
                        <div class="row">
                            <h4 class="text-info">List of Registerd Trade/Programmes for:<?php echo $db->getData('center_registration','centerName','centerRegistrationID',$centerRegistrationID); ?></h4>
                            <table  id="exampleexampleexample" class="display">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Trade Name</th>
                                    <th>Trade Code</th>
                                    <th>Duration</th>
                                    <th>Trade Type</th>
                                    <th>Trade Level</th>
                                    <th>Status</th>
                                    <!--<th>Edit</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $countl = 0;
                                foreach($data as $dt)
                                {
                                    $programmeID=$dt['programmeID'];
                                    /*$programmeLevelID=$dt['programmeLevelID'];*/
                                    $users=$db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID)));
                                    if(!empty($users)) {
                                    foreach ($users as $user) {
                                        $countl++;
                                        ?>
                                        <tr>
                                            <td><?php echo $countl; ?></td>
                                            <td><?php echo $user['programmeName']; ?></td>
                                            <td><?php echo $user['programmeCode']; ?></td>
                                            <td><?php echo $user['programmeDuration']; ?></td>
                                            <td><?php echo $db->getData('programme_type', 'programmeType', 'programmeTypeID', $user['programmeTypeID']); ?></td>
                                            <td><?php
                                                $tradeLevel = array();
                                                $tradeLevelCode = $db->getRows("center_programme", array('where' => array('programmeID' => $user['programmeID'],'centerRegistrationID'=>$centerRegistrationID)));
                                                if (!empty($tradeLevelCode)) {
                                                    foreach ($tradeLevelCode as $tcode) {
                                                        $programmeLevelID = $tcode['programmeLevelID'];
                                                        $programmeLevel = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
                                                        $tradeLevel[] = $programmeLevel;
                                                    }
                                                }
                                                ?><?php echo implode(',', $tradeLevel); ?></td>
                                            <td><?php
                                                if ($user['status'] == 1)
                                                    echo "Active";
                                                else
                                                    echo "Not Active";

                                                if ($user['status'] == 1) {
                                                $blockButton = '<a href="action_user.php?action_type=deactivate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-unlock"  title="Deactivate User" onclick="return confirm("Are you sure,you want to Deactivate this User?");"></a>';
                                                $statusOutput = "<span style='color: green'>Active</span>";
                                                } else {
                                                $blockButton = '<a href="action_user.php?action_type=activate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-lock" title="Activate User" onclick="return confirm("Are you sure, you want to Activate this User?");"></a>';
                                                $statusOutput = "<span style='color: red'>Blocked</span>";
                                                }

                                                ?>

                                            </td>

                                            <!--<td>
                                                <a href="index3.php?sp=view_center_programmes&pid=<?php /*echo $db->my_simple_crypt($user['programmeID'], 'e');*/?>&centerID=<?php /*echo $_REQUEST['centerID'];*/?>"
                                                   class="glyphicon glyphicon-eye-open" title="View/Edit Details"></a>
                                            </td>-->
                                        </tr>
                                    <?php }
                                }
                                }?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    else
                        echo "<h4 class='text-danger'>No Registered Course</h4>";

                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>