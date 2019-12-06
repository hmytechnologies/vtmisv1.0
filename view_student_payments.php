<div class="content">
        <h3>Student Finances</h3>
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
            $regNumber=$db->my_simple_crypt($_REQUEST['id'],'d');
            $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>' studentID ASC'));
            if(!empty($studentID))
            {
                ?>
                <div class="box box-solid box-success">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Personal Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reg.Number</th>
                                <th>Gender</th>
                                <th>Level</th>
                                <th>Programme Name</th>
                                <th>Programme Duration</th>
                                <th>Study Year</th>
                                <th>Study Mode</th>
                                <th>Student Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;
                            foreach ($studentID as $std) {
                                $count++;
                                $studentID = $std['studentID'];
                                $fname = $std['firstName'];
                                $mname = $std['middleName'];
                                $lname = $std['lastName'];
                                $gender = $std['gender'];
                                $regNumber = $std['registrationNumber'];
                                $programmeID = $std['programmeID'];
                                $statusID = $std['statusID'];
                                $batchID = $std['batchID'];
                                $name = "$fname $mname $lname";


                                echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>";
                                $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);
                                $level = $db->getRows('programme_level', array('where' => array('programmeLevelID' => $programmeLevelID), ' order_by' => ' programmeLevelCode ASC'));
                                if (!empty($level)) {
                                    foreach ($level as $lvl) {
                                        $programme_level_code = $lvl['programmeLevelCode'];
                                        echo "<td>$programme_level_code</td>";
                                    }
                                }

                                $programme = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID), ' order_by' => ' programmeName ASC'));
                                if (!empty($programme)) {
                                    foreach ($programme as $pro) {
                                        $programmeName = $pro['programmeName'];
                                        $programmeDuration = $pro['programmeDuration'];
                                    }
                                }


                                $programme = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID), ' order_by' => ' programmeName ASC'));
                                if (!empty($programme)) {
                                    foreach ($programme as $pro) {
                                        $programmeName = $pro['programmeName'];
                                        $programmeDuration = $pro['programmeDuration'];
                                    }
                                }


                                echo "<td>$programmeName</td>";


                                $study_year = $db->getRows('student_study_year', array('where' => array('regNumber' => $regNumber, 'studyYearStatus' => 1), ' order_by' => 'studyYear ASC'));
                                if (!empty($study_year)) {
                                    foreach ($study_year as $sy) {
                                        $studentStudyYear = $sy['studyYear'];
                                        $studyAcademicYearID=$sy['academicYearID'];
                                    }
                                }
                                else
                                {
                                    $studentStudyYear = "None";
                                    $studyAcademicYearID="";
                                }
                                echo "<td>".$studentStudyYear . "</td>";
                                echo "<td>".$db->getData("batch", "batchName", "batchID", $batchID) . "</td>";
                                $status = $db->getRows('status', array('where' => array('statusID' => $statusID), ' order_by' => 'status_value ASC'));
                                if (!empty($status)) {
                                    foreach ($status as $st) {
                                        $status_value = $st['statusValue'];
                                        echo "<td>$status_value</td>";
                                    }
                                }
                            }
                            ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            <div class="col-md-6">
                <div class="row">
            <?php
//debi
                $debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
                if(!empty($debit))
                {
                    ?>

                    <div class="box box-solid box-primary">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">Students Bill</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Fees Type</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

            <?php
                    $total=0;
                    $count=0;
                    foreach($debit as $dbt)
                    {
                        $count++;
                        $studentFeesID=$dbt['studentFeesID'];
                        $amount=$dbt['amount'];
                        $total+=$amount;
                        $academicYearID=$dbt['academicYearID'];
                        $invoiceNumber=$dbt['invoiceNumber'];
                        $feesType=$dbt['feesDescription'];
                        $invoiceDate=$dbt['invoiceDate'];

                        echo "<tr>
                                  <td>$count</td>
                                  <td>$invoiceNumber</td>
                                  <td>$invoiceDate</td>
                                  <td>$feesType</td>
                                  <td>".number_format($amount,2)."</td>
                                  ";
                        echo "<td>Pay Now</td>";
                        echo "</tr>";
                    }
                    ?>
                                <tbody>
                                <tr>

                                </tr>
                                </tbody>
                                <tfoot>
                                <th colspan="3">
                                    Total Amount:
                                </th>
                                <th>
                                    <?php
                                    echo number_format($total,2);
                                    ?>
                                </th>
                                </tfoot>
                            </table>
                        </div>
                        <!--<div class="box-footer">
                            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Print Bill
                            </button>
                            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Print Invoice
                            </button>
                        </div>-->
                    </div>
                                <?php
                }
                ?>
            </div></div>
            <div class="col-md-6">
                <div class="row">
                <?php
//Payment
                $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                if(!empty($paymentList))
                {
                    ?>
            <div class="box box-solid box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Students Payments</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Receipt Number</th>
                            <th>Receipt Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    <?php
                    $i=0;
                    $total=0;
                    foreach($paymentList as $list)
                    {
                        $i++;
                        $psesmesterSettingID=$list['semesterSettingID'];
                        $pacademicYearID=$list['academicYearID'];
                        $amount=$list['amount'];
                        $total+=$amount;
                        $receiptNumber=$list['receiptNumber'];
                        $paymentDate=$list['paymentDate'];


                        echo "<tr>
                            <td>$i</td>
                            <td>$receiptNumber</td>
                            <td>".$paymentDate."</td>
                            <td>".number_format($amount,2)."</td>
                            ";
                            echo "<td>Drop</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tbody>
                        <tr>

                        </tr>
                        </tbody>
                        <tfoot>
                        <th colspan="3">
                            Total Amount Paid:
                        </th>
                        <th>
                            <?php
                            echo number_format($total,2);
                            ?>
                        </th>
                        </tfoot>
                    </table>
                </div>
               <!-- <div class="box-footer">
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> Print Bill
                    </button>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> Print Invoice
                    </button>
                </div>-->
            </div>
                    <?php
                }
                ?>
                </div></div>
                <!--<div class="col-md-12">
                    <div class="row">


                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">My Bill</h3>
                                </div>
                                <!-- /.box-header -->
                                <!--<div class="box-body table-responsive">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Invoice Number</th>
                                            <th>Invoice Date</th>
                                            <th>Fees Type</th>
                                            <!--<th>Fees Heads</th>

                                            <th>Amount</th>
                                            <th>Fine</th>
                                            <th>Advance Amount</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>

                                        </tr>
                                        </tbody>
                                    </table>-->
                                    <!--<table class="table table-striped table-bordered table-condensed">
                                        <tr>
                                            <td>Student Name:</td>
                                            <td>
                                                <strong><?php /*echo "$fname $mname $lname";*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Reg.Number:</td>
                                            <td>
                                                <strong><?php /*echo $regNumber;*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Programme Name:</td>
                                            <td>
                                                <strong><?php /*echo $programmeName;*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Year of Study:</td>
                                            <td>
                                                <strong><?php /*echo $studyYear;*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Balance Previous Year:</td>
                                            <td>
                                                <strong><?php /*echo number_format($totalFeesP+$totalChargesP-(($amountPercentP/100)*($totalChargesP+$totalFeesP))-$totalAPP);*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Invoice this Year:</td>
                                            <td>
                                                <strong><?php /*echo number_format($totalFeesC+$totalChargesC);*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Invoice this Semester:</td>
                                            <td>
                                                <strong><?php /*echo number_format($requiredTotalSemesterDebit);*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount Paid:</td>
                                            <td>
                                                <strong><?php /*echo number_format($totalAPC+$totalAPP);*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Outstanding Balance this Year:</td>
                                            <td>
                                                <strong><?php /*echo number_format(($totalFeesC+$totalChargesC)-($totalAPC));*/?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Outstanding Balance this Semester:</td>
                                            <td>
                                                <strong><?php /*echo number_format($requiredTotalSemesterDebit-$totalSPC);*/?></strong>
                                            </td>
                                        </tr>
                                    </table>-->
                               <!-- </div>
                                <div class="box-footer">
                                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                        <i class="fa fa-download"></i> Print Bill
                                    </button>
                                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                        <i class="fa fa-download"></i> Print Invoice
                                    </button>
                                </div>
                            </div></div></div></div>-->



                <?php
            }
            else
            {
                echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
            }
            ?>
        </div>


    </div>


