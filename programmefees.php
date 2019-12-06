<div class="container">
<div class="row"> 
    <h2>Programme Fees</h2>
<div class="col-md-12">
<div class="pull-right">
                <a href="index3.php?sp=addnewprogrammefees"><span class="btn btn-success">Add New Programme Fees</span></a>
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
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme feess data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="drop")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme fess data has been droped successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="deactivate")
  {
      echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme fess data has been deactivated successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="activate")
  {
      echo "<div class='alert alert-success fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme fess data has been activated successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Subject Code/Subject Name is already Exists</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=programmefees' class='close' data-dismiss='alert'>&times;</a>
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
          
            
?>
     
<table  id="example" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
      <th>No.</th>
    <th>Programme Name</th>
    <th>Academic Year</th>
    <th>Total Amount(TSH)</th>
    <th>Total Amount(US$)</th>
    <th>Fees Status</th>
    <!--<th>Drop</th>-->
     </tr>
  </thead>
  <tbody>
<?php 
$db = new DBHelper();
$programmeFees = $db->getDistinctProgrammeFees();
if(!empty($programmeFees)){ $count = 0; foreach($programmeFees as $fees){ $count++;
$programmeID=$fees['programID'];
$academicYearID=$fees['academicYearID'];
$status=$fees['programFeesStatus'];
?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><a href='index3.php?sp=programmefeesall&id=<?php echo $db->my_simple_crypt($programmeID,'e');?>&acadID=<?php echo $db->my_simple_crypt($academicYearID,'e');?>'><?php echo $db->getData("programmes","programmeName","programmeID",$programmeID); ?></a></td>
                <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID) ?></td>
                <td><?php echo number_format($db->getSumFees("feesTz",$programmeID,$academicYearID),2); ?></td>
                <td><?php echo number_format($db->getSumFees("feesUsa",$programmeID,$academicYearID),2);?></td>
                <td><?php
                    if($status == 1) {
                    $blockButton = '<a href="action_add_programme_fees.php?action_type=deactivate&id=' . $db->my_simple_crypt($programmeID, 'e') . '"title="Deactivate Programe Fees" onclick="return confirm("Are you sure,you want to Deactivate this Programme Fees?");"><span style=\'color: green\'>Active</span></a>';
                        echo $blockButton;
                    } else {
                    $blockButton = '<a href="action_add_programme_fees.php?action_type=activate&id=' . $db->my_simple_crypt($programmeID, 'e') . '"title="Activate Programme Fees" onclick="return confirm("Are you sure, you want to Activate this Programme Fees?");"><span style=\'color: red\'>Blocked</span></a>';
                    echo $blockButton;
                    }?>
                </td>
              <!--<td>
                    <a href="action_add_programme_fees.php?action_type=drop&id=<?php /*echo $db->my_simple_crypt($fees['programID'],'e'); */?>&yearID=<?php /*echo $academicYearID;*/?>" onclick="return confirm('Are you sure you want to Drop this Programme Fees?');" class="glyphicon glyphicon-trash"></a>
                   
                </td>-->
            </tr>
            <?php } }
?>
</tbody>
 </table>
 </div></div>  
</div>