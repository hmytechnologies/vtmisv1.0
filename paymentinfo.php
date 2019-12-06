<div class="container">
<script type="text/javascript">
  $(document).ready(function () {
            $('#payments').dataTable(
                {
                   responsive:true,
                    paging: true,
                    dom: 'Blfrtip',
                    "lengthMenu": [[5,10,15,20,-1],[5,10,15,20,"All"]],
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Transaction',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Transaction',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<div class="box box-solid box-success">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Payment Information</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">

			
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-lg-12">
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Debit Information</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
  <?php
  $db = new DBHelper();
  $userID = $_SESSION['user_session'];
  $studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
  if(!empty($studentID))
  {
      foreach($studentID as $std)
      {
          $regNumber=$std['registrationNumber'];
  $debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
  if(!empty($debit))
  {
  ?>
  <table class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
  						<th>#</th>
                        <th>Amount</th>
                        <th>Penalty</th>
                        <th>Discount</th>
                        <th>Exmption</th>
                        <th>Exmption Percent</th>
                        <th>Attachment</th>
                        <th>Study Year</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody> 
<?php 
$count=0;
$totalA=0;$totalD=0;$totalE=0;$totalP=0;$total=0;
foreach($debit as $dbt)
{
    $count++;
    $studentFeesID=$dbt['studentFeesID'];
    $discount=$dbt['discount'];
    $exmption=$dbt['exmption'];
    $fileurl=$dbt['attachment'];
    $amount=$dbt['amount'];
    $exmptionPercent=$dbt['exmptionPercent'];
    if($discount==0)
    {
        $discount="No";
    }
    else 
    {
        $discount=$discount;
    }
    
    if($exmption==0)
    {
        $exmption="No";
    }
    else
    {
        $exmption="Yes";
        $examount=($exmptionPercent/100)*$amount;
    }
    
      $totalD+=$discount;
      $totalA+=$amount;
      $totalE+=$examount;
      $totalP+=$penalty;
    ?>
    <tr>
    <td><?php echo $count;?></td>
    <td><?php echo number_format($dbt['amount']);?></td>
    <td><?php echo number_format($dbt['penalty']);?></td>
    <td><?php echo number_format($discount);?></td>
    <td><?php echo $exmption;?></td>
    <td><?php echo $dbt['exmptionPercent'];?>%</td>
    <?php 
                   if($fileurl=="")
                   {
                       ?>
                       <td>-</td>
                    <?php 
                   }else
                    {
                   ?>
                  <td><a href="account_file/<?php echo $fileurl;?>" class="glyphicon glyphicon-download-alt" target="_blank"></a></td>
                  <?php }
                  ?>
    <td><?php echo $dbt['studyYear'];?></td>
    <td>
    <?php echo $link= '<a href="print_invoice.php?action_type=print_invoice&id='.$db->encrypt($studentFeesID).'&rg='.$db->my_simple_crypt($searchStudent,'e').'" class="glyphicon glyphicon-search"></a>'; 
    ?>
    </td>
    </tr>
    <?php
    
}
$total=$totalA+$totalP-$totalD-$totalE;
?>
                      </tbody>
                      </table>
  <?php 
  }
  ?> 
  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    <strong>Total Amount: <?php echo number_format($total);?></strong>
  </div>
  <!-- box-footer -->
</div>
<!-- /.box -->
</div></div>

<div class="row">
<div class="col-lg-12">
<div class="box box-solid box-danger">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Summary Information</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
  <?php
  $debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
  if(!empty($debit))
  {
      $totalA=0;$totalD=0;$totalE=0;$totalP=0;$total=0;
      $count=0;
      foreach($debit as $dbt)
      {
          $amount=$dbt['amount'];
      }
      $total=$totalA+$totalP-$totalD-$totalE;
  }
  
  //payment
  $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
  if(!empty($paymentList))
  {
      $totalAmount=0;
      foreach($paymentList as $list)
      {
          $count++;
          $amount=$list['amount'];
          $totalAmount+=$amount;
      }
  }
  
  ?>
  <table class="table table-striped table-bordered table-condensed">
  <thead>
  <tr>
  <th>Debit Amount</th>
  <th>Credit Amount</th>
  <th>Balance</th>
  <th>Percent Paid</th>
  </tr>
  </thead>
  <tbody> 
    <tr>
    <td><?php echo number_format($total);?></td>
    <td><?php echo number_format($totalAmount);?></td>
    <td><?php echo number_format($total-$totalAmount);?></td>
    <td><?php echo round(($totalAmount/$total)*100);?>%</td>
    </tr>
                      </tbody>
                      </table> 
  </div>
  <!-- /.box-body -->
 
  <!-- box-footer -->
</div>
</div>
</div>

</div>

<div class="col-md-6">
<div class="row">
<div class="col-lg-12">
<div class="box box-solid box-primary">
  <div class="box-header with-border text-center">
    <h3 class="box-title">Credit Information</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
<?php
                $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                if(!empty($paymentList))
                {
                	?>
                	<h4 class='text-info'>List of Transaction</h4>
                	<table class="table table-striped table-bordered table-condensed" id="payments">
                      <thead>
                      <tr>
                      	<th>No</th>
                      	<th>Receipt</th>
                        <th>Amount</th>
                        <th>Payment Type</th>
                        <th>Date</th>
                        <th>Confirmed</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($paymentList as $list)
                    { 
                      $count++;
                      $studentPaymentID=$list['studentPaymentID'];
                      $checked=$list['checked'];
                          $link= '<a href="print_receipt.php?action_type=print_receipt&id='.$db->encrypt($studentPaymentID).'&rg='.$db->my_simple_crypt($searchStudent,'e').'" class="glyphicon glyphicon-search"></a>'; 
                      echo "<tr><td>$count</td>";
		              ?>
		              <td><?php echo $list['receiptChequeNumber']?></td>
                      <td><?php echo number_format($list['amount']);?></td>
                      <td><?php echo $list['paymentMethod'];?></td>
                      <td><?php echo $list['paymentDate'];?></td>  
                      <td><?php echo $checked;?></td>
                      <td><?php echo $link;?></td>
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
</div>
<!-- /.box -->
</div></div>
</div>

</div>			

<?php
}
}
    ?>
    </div>
</div>
