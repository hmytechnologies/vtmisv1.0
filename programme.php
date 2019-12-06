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
<?php $db=new DBHelper();
?>
<div class="container">
  <div class="content">
  <h1>Trade Management</h1>

  <div class="pull-right">
                <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Settings</a>
            </div>
            <br>
  <hr>
    <ul class="nav nav-tabs" id="myTab">
    
        <li class="active"><a data-toggle="tab" href="#plevels"><span style="font-size: 16px"><strong>Trade Level</strong></span></a></li>
        <li><a data-toggle="tab" href="#trades"><span style="font-size: 16px"><strong>Trades </strong></span></a></li>
        
    </ul>

<div class="tab-content">
<!-- trade Level -->
<div id="plevels" class="tab-pane fade in active">
<!-- Start -->
<div class="container">
<h3>List of Trade Levels</h3>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Trade Levels</button>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Trade Levels data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Trade Level data has been edited Successfully</strong>.
</div>";
  }
  else
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<?php
          
            $db = new DBHelper();
            $users = $db->getRows('programme_level',array('order_by'=>'status DESC'));
?>
<table  id="example" class="display nowrap">
  <thead>
  <tr>
      <th>No.</th>
    <th>Trade Level Name</th>
    <th>Trade Level Code</th>
    <th>Minimum Units</th>
    <th>Status</th>
    <th>Edit</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
  if($user['status']==1)
  {
    $status="Active";
  }
  else
  {
    $status="Not Active";
  }
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['programmeLevel']; ?></td>
                <td><?php echo $user['programmeLevelCode']; ?></td>
                <td><?php echo $user['units']; ?></td>
                <td><?php echo $status; ?></td>
              <td>
                    <a href="index3.php?sp=edit_levels&id=<?php echo $user['programmeLevelID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<form name="" method="post" action="action_programme_level.php">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Trade Level Name</label>
<input type="text" id="name" name="name" placeholder="Programme Level Name" class="form-control" />
</div>

<div class="form-group">
<label for="email">Trade Level Code</label>
<input type="text" id="code" name="code" placeholder="Programme Level Code" class="form-control" />
</div>

<div class="form-group">
<label for="email">Minimum Number of Units</label>
<input type="number" id="number" name="number" placeholder="Minimum Number of Units" class="form-control" />
</div>

</div></div></div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
</div>
</form>
</div>
</div>
</div>
<!-- End -->
</div>
    <!--Programmes-->
    <div id="trades" class="tab-pane fade">
        <h2>List of Registered Trades</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_programme">Add New Trade</button> -->
                    <a href="index3.php?sp=addnewprogramme" class="btn btn-success">Add New Trade</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <?php
                if(!empty($_REQUEST['msg']))
                {
                    if($_REQUEST['msg']=="succ")
                    {
                        echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme data has been inserted successfully</strong>.
</div>";
                    }
                    else if($_REQUEST['msg']=="edited_prog")
                    {
                        echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme data has been edited Successfully</strong>.
</div>";
                    }
                    else
                    {
                        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=programmes' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
                    }
                }
                ?>


            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $db = new DBHelper();
                $users = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                ?>
                <table  id="exampleexampleexample" class="display">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Duration</th>
                       <th>Trade Type</th>
                        <th>Trade Level</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $user['programmeName']; ?></td>
                            <td><?php echo $user['programmeCode']; ?></td>
                            <td><?php echo $user['programmeDuration']; ?></td>
                           <td><?php echo $db->getData('programme_type','programmeType','programmeTypeID',$user['programmeTypeID']);?></td>
                            <td><?php
                                $tradeLevel=array();
                                $tradeLevelCode=$db->getRows("programme_trade_level",array('where'=>array('programmeID'=>$user['programmeID'])));
                                if(!empty($tradeLevelCode))
                                {
                                    foreach($tradeLevelCode as $tcode)
                                    {
                                        $programmeLevelID=$tcode['programmeLevelID'];
                                        $programmeLevel=$db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);
                                        $tradeLevel[]=$programmeLevel;
                                    }
                                }
                                ?><?php echo implode(',',$tradeLevel);?></td>
                            <td><?php echo $db->getData("departments","departmentCode","departmentID",$user['departmentID']); ?></td>
                                <td><?php
                                if($user['status']==1)
                                    echo "Active";
                                else
                                    echo "Not Active";
                                ?>
                            </td>
                            <td>
                                <a href="index3.php?sp=edit_programme&id=<?php echo $db->my_simple_crypt($user['programmeID'],'e'); ?>" class="glyphicon glyphicon-edit"></a>
                            </td>
                        </tr>
                    <?php }
                    }?>
                    </tbody>
                </table>
            </div></div>

        <div class="modal fade" id="add_new_record_programme" style="overflow:hidden;" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form name="" method="post" action="action_programme.php">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-body"  style="overflow:hidden;">
                                    <script type="text/javascript" src="js/jquery.min.js"></script>
                                    <div class="form-group">
                                        <label for="email">Trade Name</label>
                                        <input type="text" id="name" name="name" placeholder="Trade Name" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Trade Code</label>
                                        <input type="text" id="code" name="code" placeholder="Trade Code" class="form-control" />
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Trade Duration</label>
                                        <input type="text" id="duration" name="duration" placeholder="Trade Duration" class="form-control" />
                                    </div>

                                    <!--<div class="form-group">
                                        <label for="email">Minimum Number of Units</label>
                                        <input type="number" id="credits" name="credits" placeholder="Minimum Number of Units" class="form-control" />
                                    </div>-->


                                    <div class="form-group">
                                        <label for="email">Trade Level</label>
                                        <select name="departmentID"  class="form-control  chosen-select" multiple>
                                            <option value="">Select Here</option>
                                        <?php
                                        $programme_level = $db->getRows('programme_level',array('order_by'=>'programmeLevel ASC'));
                                        if(!empty($programme_level)) {
                                            $count = 0;
                                            foreach ($programme_level as $level) {
                                                $count++;
                                                $programme_level = $level['programmeLevel'];
                                                $programme_level_id = $level['programmeLevelID'];
                                                ?>
                                                <option value="<?php echo $programme_level_id;?>"><?php echo $programme_level;?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div>

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


                                </div></div></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!--end 0f programme major-->

</div>
</div>
</div>

<script>
    $('#add_new_record_programme').on('shown.bs.modal', function () {
        $('.chosen-select', this).chosen();
    });
</script>