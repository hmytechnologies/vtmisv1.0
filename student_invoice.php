<?php
$db = new DBHelper();
$userID = $_SESSION['user_session'];
$studentID = $db->getRows('student',array('where'=>array('userID'=>$userID),' order_by'=>' studentID ASC'));
?>
              
                <?php
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
                      $name="$fname $mname $lname";
                      
		                $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
		                if(!empty($programme))
		                {
		                	foreach ($programme as $pro) {
		                		$programmeName=$pro['programmeName'];
		                		
		                	}
		                }
                    }
                }

                	
                  
                	?>
                
<!-- Content Wrapper. Contains page content -->
  <div class="container">
  <div class="box box-solid box-primary">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <span class="pull-center"><i class="fa fa-globe"></i>Payment Information</span>
            <small class="pull-right">Date:<?php echo date("d-m-Y");?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-6 invoice-col">
          <h4><strong>Reg.Number:</strong><?php echo $regNumber;?></h4><br>
          <h4><strong>Programme Name:</strong><?php echo $programmeName;?> </h4>
        </div>
        <!-- /.col -->
        <div class="col-sm-6 invoice-col">
          <h4><strong>Name of Student:</strong><?php echo "$fname $mname $lname";?></h4><br>
          <h4><strong>Campus:</strong>Tunguu-Main Campus</h4>
        </div>
      </div>
      <!-- /.row -->
	<hr>
	<div class="row">
	<div class="col-lg-4">
	</div>
	<div class="col-lg-6">
	<strong class="text-danger"><h3>Amount Due</h3></strong>
	</div>
	</div>
      <!-- Table row -->
      <?php 
      $programmes=$db->getProgrammeFees($programmeID);
      if(!empty($programmes)){
          foreach($programmes as $prg)
          {
              $programmeName=$prg['programmeName'];
              $programmeID=$prg['programID'];
              $academicYearID=$prg['academicYearID'];
          }
      }
      $totalTz=0;$totalUsa=0;
        ?>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Fees Type</th>
              <th>Description</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $fees = $db->getRows('programmefees',array('where'=>array('programID'=>$programmeID),'order_by'=>'feesTypeID ASC'));
            if(!empty($fees)){ $count = 0; foreach($fees as $fee){ $count++;
            $feesTypeID=$fee['feesTypeID'];
            $feestz=$fee['feesTz'];
            $feesusa=$fee['feesUsa'];
            $totalTz+=$feestz;
            $totalUsa+=$feesusa;
            ?>
            <tr>
              <td><?php echo $count; ?></td>
                <td><?php echo $db->getData('feestype','feesType','feesTypeID',$feesTypeID);?></td>
                <td><?php echo $db->getData('feestype','feesTypeDesc','feesTypeID',$feesTypeID);?></td>
                <td><?php echo number_format($feestz,2);?></td>
                
            </tr>
           <?php }
            }
            ?> 
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
<hr>
      <div class="row">
      <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Payment Methods:</p>
          <img src="dist/img/credit/visa.png" alt="Visa">
          <img src="dist/img/credit/mastercard.png" alt="Mastercard">
          <img src="dist/img/credit/american-express.png" alt="American Express">
          <img src="dist/img/credit/paypal2.png" alt="Paypal">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              <?php
              $organization = $auth_user->getRows('organization',array('order_by'=>'organizationName DESC'));
              if(!empty($organization))
              {
                  foreach($organization as $org)
                  {
                      $organizationName=$org['organizationName'];
                      $organizationCode=$org['organizationCode'];
                      $organizationPicture="img/".$org['organizationPicture'];
                  }
              }
              else
              {
                  $organizationName="Soft Dev Academy";
                  $organizationCode="SDVA";
                  $organizationPicture="img/SkyChuo.png";
              }
              ?>
           Account Name: <?php echo $organizationName;?>
           <br>
           Account Number: ########################
          </p>
        </div>
        <!-- /.col -->
        <!-- /.col -->
        <div class="col-xs-6">
          

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td><?php echo number_format($totalTz,2);?></td>
              </tr>
              <tr>
                <th>Penalty</th>
                <td>25,000.00</td>
              </tr>
              
              <tr>
                <th>Total:</th>
                <td><?php echo number_format($totalTz+25000,2);?></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
		
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>
      </div>
    </section>
    <!-- /.content -->
   </div>
  </div>
  <!-- /.content-wrapper -->