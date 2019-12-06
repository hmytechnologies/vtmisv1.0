<?php $db=new DBHelper();
$regNumber=$db->my_simple_crypt($_REQUEST['regNo'],'d');
$feesID=$db->my_simple_crypt($_REQUEST['feeID'],'d');
$studentFeesID=$db->my_simple_crypt($_REQUEST['id'],'d');
?>
<div class="container">
    <h1>Process Payment for <?php echo $regNumber;?></h1>
                <div class="row">
                    <div class="row">
                        <?php
                        if(!empty($_REQUEST['msg']))
                        {
                            if($_REQUEST['msg']=="succ")
                            {
                                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Payment data has been uploaded successfully</strong>.
                            </div>";
                            }

                            else if($_REQUEST['msg']=="error") {
                                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                            }
                            else if($_REQUEST['msg']=="unsucc") {
                                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                            }
                        }
                        ?>
                    </div>
                    <?php
                        $today=date("Y-m-d");
                        $sm=$db->readSemesterSetting($today);
                        foreach ($sm as $s)
                        {
                            $semisterID=$s['semesterID'];
                            $academicYearID=$s['academicYearID'];
                            $semesterName=$s['semesterName'];
                            $semesterSettingID=$s['semesterSettingID'];
                        }


                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>' studentID ASC'));
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



                                $study_year= $db->getRows('student_study_year',array('where'=>array('regNumber'=>$regNumber,'studyYearStatus'=>1),' order_by'=>'regNumber ASC'));
                                if(!empty($study_year))
                                {
                                    foreach ($study_year as $sy)
                                    {
                                        $studyYear=$sy['studyYear'];
                                    }
                                }


                            }

//debit
                            $debit = $db->getRows('student_fees',array('where'=>array('feesID'=>$feesID,'regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
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
                                       // $totalFeesP=0;
                                    }
                                    else
                                    {
                                        $totalFeesP+=$amount;
                                        //$totalFeesC=0;
                                    }
                                }
                            }
                            else
                            {
                                $totalFeesC=0;
                                $totalFeesP=0;
                            }
//Payment
                            $paymentList = $db->getRows('student_payment',array('where'=>array('feesID'=>$feesID,'regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                            if(!empty($paymentList))
                            {
                                $totalSPC=0;$totalSPP=0; $totalAPC=0;$totalAPP=0;
                                foreach($paymentList as $list)
                                {
                                    $psesmesterSettingID=$list['semesterSettingID'];
                                    $pacademicYearID=$list['academicYearID'];
                                    $amount=$list['amount'];
                                    //Semester
                                    if($psesmesterSettingID==$semesterSettingID) {
                                        $totalSPC += $amount;
                                        //$totalSPP=0;
                                    }
                                    else {
                                        $totalSPP += $amount;
                                        //$totalSPC=0;
                                    }
                                    //Academic Year
                                    if($pacademicYearID==$academicYearID) {
                                        $totalAPC += $amount;
                                        //$totalAPP=0;
                                    }
                                    else {
                                        $totalAPP += $amount;
                                        //$totalAPC=0;
                                    }
                                }
                            }
                            else
                            {
                                $totalAPC=0;
                                $totalAPP=0;
                                $totalSPP=0;
                                $totalSPC=0;
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
                            else
                            {
                                $mAmount=number_format($totalFeesC/2,2);
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
                            else
                            {
                                $amountPercent=0;
                                $amountPercentP=0;
                            }

                            $totalSemesterDebit=($mAmount/100)*($totalFeesC);

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
                                                <h3 class="box-title">Student Bill</h3>
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
                                                            <strong>
                                                                <?php
                                                                $balancepreviousyear=$totalFeesP-(($amountPercentP/100)*($totalFeesP))-$totalAPP;
                                                                if($balancepreviousyear <= 0)
                                                                    echo 0.00;
                                                                else
                                                                    echo number_format($balancepreviousyear);
                                                                ?>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Invoice this Year:</td>
                                                        <td>
                                                            <strong><?php echo number_format($totalFeesC);?></strong>
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
                                                            <strong><?php echo number_format(($totalFeesC)-($totalAPC));?></strong>
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
                                        </div></div></div></div>


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
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box box-solid box-primary">
                                            <div class="box-header with-border text-center">
                                                <h3 class="box-title">Process Payment</h3>
                                            </div>
                                            <form class="form-horizontal" method="post" action="action_student_payment.php">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="inputEmail3" class="col-sm-3 control-label">Amount Paid</label>

                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount Paid">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-3 control-label">Payment Method</label>
                                                        <div class="col-sm-9">
                                                            <select name="semisterID" id="semisterID" class="form-control">
                                                                <?php
                                                                $pmethod = $db->getRows('payment_method',array('order_by'=>'paymentMethod ASC'));
                                                                if(!empty($pmethod)){
                                                                    echo"<option value=''>Please Select Here</option>";
                                                                    foreach($pmethod as $pm){
                                                                        $paymentMethod=$pm['paymentMethod'];
                                                                        $paymentMethodID=$pm['paymentMethodID'];
                                                                        ?>
                                                                        <option value="<?php echo $paymentMethodID;?>"><?php echo $paymentMethod;?></option>
                                                                    <?php }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-3 control-label">Receipt No</label>

                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="receiptno" name="receiptno" placeholder="Receipt Number">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-3 control-label">Payment Date</label>

                                                        <div class="col-sm-9">
                                                            <input type="text" name="startDate" class="form-control" id="start_date">
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer">
                                                    <input type="hidden" name="action_type" value="add"/>
                                                    <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                                                    <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                                    <input type="hidden" name="semesterID" value="<?php echo $semesterSettingID;?>">
                                                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary pull-right">
                                                </div>
                                                <!-- /.box-footer -->
                                            </form>

                                        </div></div>
                                </div></div>

                            <?php
                    }
                    ?>
                </div>


    <div class="row">
        <div class="col-lg-3">
                <a href="index3.php?sp=process_payment" class="btn btn-success form-control">Go Back</a>
        </div>
    </div>
            </div>