<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
  <h1>Payment Setting</h1>
  <hr>
<div class="row"> 
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Define New Payment Setting</button>
            </div>   
 </div>
    <br><br><br>
</div>
     <?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Semester data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Semester data has been delete successfully</strong>.
</div>";
  }
}
?>
        <div id="semestercourse">
           
        <div class="row">
            <?php
               $payment= $db->getRows('payment_setting',array(' order_by'=>'startDate ASC'));
    
               if(!empty($payment))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Semester Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Required Percent</th>
                        <!--<th>Penalty</th>-->
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($payment as $sm)
                    { 
                      $count++;
                      $paymentSettingID=$sm['paymentSettingID'];
                      $semesterSettingID=$sm['semesterSettingID'];
                      $startDate=$sm['startDate'];
                      $endDate=$sm['endDate'];
                      $amount=$sm['minimumAmount'];
                      $penalty=$sm['penalty'];
                      $unitOfValue=$sm['unitOfValue'];
                      if($unitOfValue==1)
                          $penalty=$penalty."%";
                      else
                          $penalty=number_format($penalty);
                          ?>
                          <tr>
                          <td><?php echo $count;?></td>
                          <td><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);?></td>
                          <td><?php echo $startDate;?></td>
                          <td><?php echo $endDate;?></td>
                          <td><?php echo $amount;?>%</td>
                          <!--<td><?php /*echo $penalty;*/?></td>-->
                          <td>
                              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $semesterSettingID;?>">
                                  <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                  <span><strong></strong></span>
                             <!-- <a href="action_payment_setting.php?action_type=delete&id=<?php /*echo $paymentSettingID; */?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Payment Setting?');"></a>-->
                          </td>
                          </tr>
                    <div id="message<?php echo $semesterSettingID;?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <form name="" id="" role="form" method="post" action="action_semester_setting.php">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Update Record</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="modal-body">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Current Semester?</label>
                                                <?php
                                                if($semesterStatus==1) {
                                                    ?>
                                                    <input type="radio" id="status" name="status" value="1"
                                                           checked/>Yes
                                                    <input type="radio" id="status" name="status" value="0"/>No
                                                    <?php
                                                }else
                                                {
                                                    ?>
                                                    <input type="radio" id="status" name="status" value="1"/>Yes
                                                    <input type="radio" id="status" name="status" value="0" checked/>No
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                        </div></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" name="action_type" value="edit"/>
                                        <input type="hidden" name="id" value="<?php echo $semesterSettingID;?>">
                                        <input type="hidden" name="semisterID" value="<?php echo $semisterID;?>">
                                        <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                        <input type="hidden" name="batchID" value="<?php echo $batchID;?>">
                                        <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                          <?php
                    }
                    ?>
                    </tbody>
                    </table>
                    
                    <?php 
                }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Payment Setting found......</h4>
                        <?php 
                    } 
                   ?>
                   
                 <?php
        
?>
        </div>
        </div>
        
        
    </div>
    </div>
 
 <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<form name="" method="post" action="action_payment_setting.php">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">
    <?php
    $semister = $db->getRows('semester_setting',array('where'=>array('semesterStatus'=>1),'order_by'=>'semesterName ASC'));
    if(!empty($semister)) {
        foreach ($semister as $sm) {
            $semisterName=$sm['semesterName'];
            $semisterSettingID=$sm['semesterSettingID'];
            $startDate=$sm['startDate'];
            $endDate=$sm['endDate'];
        }
    }
    else {
        $semisterName="";
        $semisterSettingID="";
        $startDate="";
        $endDate="";
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#start_date").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,

                onSelect:function(dateText){
                    $("#end_date").datepicker('option','minDate',dateText);
                }
            });
            $("#end_date").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
                autoclose: true,

                onSelect:function(dateText){
                    $("#examStart_Date").datepicker('option','minDate',dateText);
                }
            });
        });
    </script>


<div class="form-group">
<label for="FirstName">Semester Name</label>
    <select name="semisterID" class="form-control" required>
        <?php
        echo"<option value=''>Please Select Here</option>";
        ?>
        <option value="<?php echo $semisterSettingID;?>"><?php echo $semisterName;?></option>
    </select>
</div>

<div class="form-group">
 <label for="FirstName">Start Date</label>
    <input type="text" name="startDate" value="<?php echo $startDate;?>" class="form-control" id="start_date">
</div>

<div class="form-group">
 <label for="FirstName">End Date</label>
    <input type="text" name="endDate" class="form-control" id="end_date">
</div>
 
<div class="form-group">
<label for="email">Minimum Amount to be Paid(%)</label>
<input type="text" id="email" name="amount" size="30" placeholder="Minimum amount to be paid in percentage" class="form-control" />
</div>

<!--<div class="form-group">
<label for="email">Penalty</label>
<input type="text" id="penalty" name="penalty" size="30" placeholder="Minimum amount to be paid in percentage" class="form-control" />
</div>

<div class="form-group">
<label for="email">Unit of Penalty</label>
<input type="radio" name="unit" value="0" checked>Fixed<input type="radio" name="unit" value="1">Percentage
</div>-->

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
</div>
</div>
</div>
</form>

</div>
</div>
</div>