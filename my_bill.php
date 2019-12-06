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


    <div class="container">
    <div class="content">
        <h1>My Finances</h1>
        <hr>
<div class="row">

	<?php
			$db=new DBhelper();

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
                if(!empty($studentID))
                {
                    $count = 0; 
                    foreach($studentID as $std)
                    { 
                      $count++;
                      $studentID=$std['studentID'];
                      $fname=$std['firstName'];
                      $mname=$std['middleName'];
                      $lname=$std['lastName'];
                      $gender=$std['gender'];
                      $regNumber=$std['registrationNumber'];
                      $programmeID=$std['programmeID'];
                      $statusID=$std['statusID'];
                      $batchID=$std['batchID'];
                      $name="$fname $mname $lname";

                     
		                $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		$programmeDuration=$pro['programmeDuration'];
		                	}
		                }

		                
		                
		                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID),' order_by'=>'regNumber ASC'));
		                if(!empty($study_year))
		                {
		                    foreach ($study_year as $sy) 
		                    {
		                        $studyYear=$sy['studyYear'];
		                    }
		                }
		                
		                
                    }
		                
//debit
$debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
if(!empty($debit))
{
 $totalFeesC=0; $totalFeesP=0;
foreach($debit as $dbt)
{
    $studentFeesID=$dbt['studentFeesID'];
    $amount=$dbt['amount'];
    $dacademicYearID=$dbt['academicYearID'];

    if($dacademicYearID == $academicYearID)
    {
        $totalFeesC+=$amount;
    }
    else
    {
        $totalFeesP+=$amount;
    }
}
}
//Payment
  $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
  if(!empty($paymentList))
  {
      $totalSPC=0;$totalSPP=0; $totalAPC=0;$totalAPP=0;
      foreach($paymentList as $list)
      {
          $psesmesterSettingID=$list['semesterSettingID'];
          $pacademicYearID=$list['academicYearID'];
          $amount=$list['amount'];
          //Semester
          if($psesmesterSettingID==$semesterSettingID)
              $totalSPC+=$amount;
          else 
              $totalSPP+=$amount;
          //Academic Year
          if($pacademicYearID==$academicYearID)
              $totalAPC+=$amount;
          else 
              $totalAPP+=$amount;
      }
  }
//payment setting
  $payment_setting = $db->getRows('payment_setting',array('where'=>array('semesterSettingID'=>$semesterSettingID),'order_by'=>'paymentSettingID   ASC'));
  if(!empty($payment_setting))
  {
      foreach($payment_setting as $ps)
      {
          $mAmount=$ps['minimumAmount'];
          $penalty=$ps['penalty'];
          $endDateF=$ps['endDate'];
      }
  }
  
  //Other fees
  $otherFees = $db->getRows('student_other_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
  if(!empty($otherFees))
  {
      $totalChargesC=0;$totalChargesP=0;
      foreach($otherFees as $dbt)
      {
          $osemesterSettingID=$dbt['semesterSettingID'];
          $amount=$dbt['amount'];
          if($osemesterSettingID==$semesterSettingID)
          {
              $penaltyStatus=$dbt['penaltyStatus'];
              $totalChargesC+=$amount;
          }
          else
          {
              $totalChargesP+=$amount;
          }
      }
  }
  
  if($penaltyStatus==1)
  {
  }
  else 
  {
      if($today>$endDateF)
      {
             $data=array(
                 'amount'=>$penalty,
                 'semesterSettingID'=>$semesterSettingID,
                 'regNumber'=>$regNumber,
                 'studyYear'=>$studyYear,
                 'feesDescription'=>'Penalty',
                 'penaltyStatus'=>1
             ); 
             $update=$db->insert("student_other_fees",$data);
             location.reload();             
        }
  }
  //discount
  $discount= $db->getRows('student_discount',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'semesterSettingID   ASC'));
  if(!empty($discount))
  {
      foreach($discount as $ps)
      {
          $dsemesterSettingID=$ps['semesterSettingID'];
          if($dsemesterSettingID==$semesterSettingID)
          {
            $amountPercent=$ps['amountPercent'];
          }
          else 
          {
              $amountPercentP=$ps['amountPercent'];
          }
      }
  }
  $totalSemesterDebit=($mAmount/100)*($totalFeesC+$totalChargesC);
  
  if($amountPercent==0)
    $requiredTotalSemesterDebit=$totalSemesterDebit;
  else 
      $requiredTotalSemesterDebit=($amountPercent/100)*$totalSemesterDebit;
      
      
 ?>
<div class="col-md-6">
<div class="row">
<div class="col-lg-12">

 <div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">My Bill</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
  <table class="table table-striped table-bordered table-condensed">
                <tr>
                  <td>Student Name:</td>
                  <td>
                   <strong><?php echo "$fname $mname $lname";?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Reg.Number:</td>
                  <td>
                   <strong><?php echo $regNumber;?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Programme Name:</td>
                  <td>
                   <strong><?php echo $programmeName;?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Year of Study:</td>
                  <td>
                   <strong><?php echo $studyYear;?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Balance Previous Year:</td>
                  <td>
                   <strong><?php echo number_format($totalFeesP+$totalChargesP-(($amountPercentP/100)*($totalChargesP+$totalFeesP))-$totalAPP);?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Total Invoice this Year:</td>
                  <td>
                   <strong><?php echo number_format($totalFeesC+$totalChargesC);?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Total Invoice this Semester:</td>
                  <td>
                   <strong><?php echo number_format($requiredTotalSemesterDebit);?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Total Amount Paid:</td>
                  <td>
                   <strong><?php echo number_format($totalAPC+$totalAPP);?></strong> 
                  </td>
                </tr>
                <tr>
                  <td>Outstanding Balance this Year:</td>
                  <td>
                   <strong><?php echo number_format(($totalFeesC+$totalChargesC)-($totalAPC));?></strong> 
                  </td>
                </tr>
                 <tr>
                  <td>Outstanding Balance this Semester:</td>
                  <td>
                   <strong><?php echo number_format($requiredTotalSemesterDebit-$totalSPC);?></strong> 
                  </td>
                </tr>
  </table>
  </div>
  <div class="box-footer">
				<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Print Bill
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Print Invoice
          </button>
              </div>
</div></div></div></div>

<div class="col-md-6">
<div class="row">
<div class="col-lg-12">
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Payment History</h3>
  </div>              <div class="box-body">
                <?php
                $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                if(!empty($paymentList))
                {
                	?>
                	<h4 class='text-info'>List of Transaction</h4>
                	<table class="table table-striped table-bordered table-condensed" id="example">
                      <thead>
                      <tr>
                      	<th>No</th>
                      	<th>Receipt Number</th>
                        <th>Amount</th>
                        <th>Date</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($paymentList as $list)
                    { 
                      $count++;
                      $studentPaymentID=$list['studentPaymentID'];
                      
                      echo "<tr><td>$count</td>";
		              ?>
		              <td><?php echo $list['receiptNumber']?></td>
                      <td><?php echo number_format($list['amount']);?></td>
                      <td><?php echo date('d-m-Y',strtotime($list['paymentDate']));?></td>
                      </tr>
		            <?php 
                    }
                	?>
                	</tbody>
                	</table>
                	<?php
           		}
           		else
           		{
           			echo "<h4 class='text-danger'>No Payment Found</h4>";
           		}?>
  
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Print Payment History
          </button>
              </div>
              <!-- /.box-footer --> 
</div></div>
</div></div>

<?php
}
else
{
   echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
}
    ?>
    </div>


</div>

</div>

