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
<script type="text/javascript">
    $(document).ready(function () {
        $("#studentdata").DataTable({
            "dom": 'Blfrtip',
            "scrollX":true,
            "paging":true,
            "buttons":[
                {
                    extend:'excel',
                    title: 'List of all Register',
                    footer:false,
                    exportOptions:{
                        columns: [0, 1, 2, 3,5,6,7]
                    }
                },
                ,
                {
                    extend: 'print',
                    title: 'List of all Register',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3,5,6,7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of all Register',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3,5,6,7]
                    },

                }

            ],
            "order": []
        });
    });
</script>

<?php $db=new DBHelper();
?>
<div class="container">
    <h1>Payment Processing</h1>
    <hr>
    <div class="content">
            <div id="singlestudent" class="tab-pane fade in active">
                <h3>Search Student to Process Payment</h3>
                <div class="form-group">
                    <form name="" method="post" action="">
                        <div class="col-xs-12">
                            <label class="col-xs-3 control-label"> Enter Student Reg.Number:</label>
                            <div class="col-xs-4">
                                <input type="text" name="search_student" id="search_text" class="form-control">
                            </div>
                            <div class="col-xs-4"><input  type="submit" class="btn btn-success" name="doSearch" value="Search Student"/>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <hr>

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
                    $db=new DBhelper();
                    if((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords")) {
                        if (isset($_POST['doSearch']) == "Search Student") {
                            $searchStudent = $_POST['search_student'];
                        } else {
                            $searchStudent = $db->my_simple_crypt($_REQUEST['search_student'], 'd');
                        }
                        $studentID = $db->getRows('student', array('where' => array('registrationNumber' => $searchStudent), ' order_by' => ' registrationNumber ASC'));
                        if (!empty($studentID)) {
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
                                            <th>Study Year</th>
                                            <th>Study Mode</th>
                                            <th>Student Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
                                        foreach ($studentID as $std) {
                                            $levelID = $db->getRows('student_programme', array('where' => array('regNumber' => $searchStudent), ' order_by' => ' regNumber ASC'));
                                            foreach ($levelID as $lv) {

                                                $programmeID = $lv['programmeID'];
                                                $programmeLevelID = $lv['programmeLevelID'];
                                               

                                            }
                                            $count++;
                                            $studentID = $std['studentID'];
                                            $fname = $std['firstName'];
                                            $mname = $std['middleName'];
                                            $lname = $std['lastName'];
                                            $gender = $std['gender'];
                                            $regNumber = $std['registrationNumber'];
                                            
                                            $statusID = $std['statusID'];
                                            $academicYearID = $std['academicYearID'];
                                            $name = "$fname $mname $lname";


                                            echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td>";
                                            // $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);
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

                                            echo "<td>$programmeName</td>";


                                            $study_year = $db->getRows('student_study_year', array('where' => array('regNumber' => $regNumber, 'studyYearStatus' => 1), ' order_by' => 'studyYear ASC'));
                                            if (!empty($study_year)) {
                                                foreach ($study_year as $sy) {
                                                    $studentStudyYear = $sy['studyYear'];
                                                    $studyAcademicYearID = $sy['academicYearID'];
                                                }
                                            } else {
                                                $studentStudyYear = "None";
                                                $studyAcademicYearID = "";
                                            }
                                            echo "<td>" . $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); "</td>";
                                            // echo "<td>" . $db->getData("batch", "batchName", "batchID", $batchID) . "</td>";
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


                            <div class="col-md-12">
                                <div class="row">
                                    <?php
                                    //debi
                                    //$debit = $db->getRows('student_fees', array('where' => array('regNumber' => $regNumber), ' order_by' => ' regNumber ASC'));
                                    $debit = $db->getStudentBill($regNumber);
                                    if (!empty($debit)) {
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
                                                        <!--<th>Study Year</th>
                                                        <th>Invoice Number</th>
                                                        <th>Invoice Date</th>-->
                                                        <th>Fees Type</th>
                                                        <th>Fees Descripion</th>
                                                        <th>Amount</th>
                                                        <th>Details</th>
                                                        <th>Pay Now</th>
                                                    </tr>
                                                    </thead>

                                                    <?php
                                                    $total = 0;
                                                    $count = 0;
                                                    $totalPayment=0;
                                                    foreach ($debit as $dbt) {
                                                        $count++;
                                                        /* $studyYear=$dbt['studyYear'];
                                                         $studentFeesID = $dbt['studentFeesID'];*/
                                                        $amount = $dbt['amount'];

                                                        $academicYearID = $dbt['academicYearID'];
                                                        $invoiceNumber = $dbt['invoiceNumber'];
                                                        $feesID = $dbt['feesID'];
                                                        $feesType = $dbt['feesDescription'];
                                                        $invoiceDate = $dbt['invoiceDate'];

                                                        $fees = $db->getData("fees", "fees", "feesID", $feesID);


                                                        //studentpayments per feesID
                                                        $paymentList = $db->getRows('student_payment',array('where'=>array('feesID'=>$feesID,'regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                                                        if(!empty($paymentList))
                                                        {
                                                            $totalSPC=0;$totalSPP=0; $totalAPC=0;$totalAPP=0;  $totalPayment=0;
                                                            foreach($paymentList as $list)
                                                            {
                                                                $psesmesterSettingID=$list['semesterSettingID'];
                                                                $pacademicYearID=$list['academicYearID'];
                                                                $amountpayment=$list['amount'];
                                                                $totalPayment+=$amountpayment;

                                                            }
                                                        }
                                                        $remain=$amount-$totalPayment;

                                                        $total += $remain;

                                                        echo "<tr>
                                                          <td>$count</td>
                                                          <td>$fees</td>
                                                          <td>$feesType</td>                                                       
                                                        <td>" . number_format($remain, 2) . "</td>";

                                                        $detailsButton = '<div class="btn-group"><a href="index3.php?sp=payment_details&regNo=' . $db->my_simple_crypt($regNumber) . '&feeID=' . $db->my_simple_crypt($feesID) . '&id=' . $db->my_simple_crypt($dbt['studentFeesID'], 'e') . '" title="Payment Details" class="btn btn-success fa fa-eye"></a></div>';

                                                        $payButton = '<div class="btn-group"><a href="index3.php?sp=pay_now&regNo=' . $db->my_simple_crypt($regNumber) . '&feeID=' . $db->my_simple_crypt($feesID) . '&id=' . $db->my_simple_crypt($dbt['studentFeesID'], 'e') . '" title="Payment Now" class="btn btn-success fa fa-cc-visa"></a></div>';
                                                        ?>
                                                        <td>
                                                            <?php
                                                            if ($feesID == 1 || $feesID == 2) {
                                                                echo $detailsButton;
                                                            } else {
                                                                ?>
                                                                No
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($feesID == 1 || $feesID == 2) {
                                                                echo $payButton;
                                                            } else {
                                                                ?>
                                                                <button type="button" class="btn btn-success"
                                                                        data-toggle="modal"
                                                                        data-target="#message<?php echo $user['courseID']; ?>">
                                                            <span class="fa fa-upload" aria-hidden="true"
                                                                  title="Upload Course Outline"></span></button>
                                                                <?php
                                                            }

                                                            ?>
                                                        </td>
                                                        </tr>

                                                        <div id="message<?php echo $user['courseID']; ?>"
                                                             class="modal fade"
                                                             role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal">&times;
                                                                        </button>

                                                                        <h4 class="modal-title">Upload Course Outline
                                                                            for <?php echo $user['courseCode'] . "-" . $user['courseName']; ?></h4>
                                                                    </div>
                                                                    <form name="register" id="register" method="post"
                                                                          enctype="multipart/form-data"
                                                                          action="action_upload_course_outline.php">
                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label for="message-text"
                                                                                       class="control-label">File
                                                                                    Upload:</label>
                                                                                <input type="file" name="user_image"
                                                                                       accept="application/pdf">
                                                                            </div>

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-default"
                                                                                    data-dismiss="modal">Close
                                                                            </button>
                                                                            <input type="hidden" name="courseID"
                                                                                   value="<?php echo $user['courseID']; ?>">
                                                                            <input type="hidden" name="action_type"
                                                                                   value="add"/>
                                                                            <input type="submit" name="doUpdate"
                                                                                   value="Save Records"
                                                                                   class="btn btn-success">
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <?php
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
                                                        echo number_format($total, 2);
                                                        ?>
                                                    </th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="box-footer">
                                                <button type="button" class="btn btn-primary pull-right"
                                                        style="margin-right: 5px;">
                                                    <i class="fa fa-download"></i> Print Invoice
                                                </button>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="row">
                                        <div class="box box-solid box-info">
                                            <div class="box-header with-border text-center">
                                                <h3 class="box-title">Payment History</h3>
                                            </div>              <div class="box-body">
                                                <?php
                                                $paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
                                                if(!empty($paymentList))
                                                {
                                                    ?>
                                                    <h4 class='text-info'>List of Transaction</h4>
                                                <div class="box-body table-responsive">
                                                    <table class="table table-striped table-bordered table-condensed">
                                                        <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Receipt Number</th>
                                                            <th>Receipt Date</th>
                                                            <th>Fees Type</th>
                                                            <th>Payment Method</th>
                                                            <th>Amount</th>
                                                            <th>Details</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $count = 0;$total=0;
                                                        foreach($paymentList as $list)
                                                        {
                                                            $count++;
                                                            $studentPaymentID=$list['studentPaymentID'];
                                                            $total+=$list['amount'];
                                                            echo "<tr><td>$count</td>";
                                                            $detailsButton = '<div class="btn-group"><a href="index3.php?sp=payment_details&regNo=' . $db->my_simple_crypt($regNumber) . '&feeID=' . $db->my_simple_crypt($feesID) . '&id=' . $db->my_simple_crypt($dbt['studentFeesID'], 'e') . '" title="Payment Details" class="btn btn-success fa fa-eye"></a></div>';

                                                            $fees = $db->getData("fees", "fees", "feesID", $list['feesID']);
                                                            $paymentMethod=$db->getData("payment_method","paymentMethod","paymentMethodID",$list['paymentMethod']);
                                                            ?>
                                                            <td><?php echo $list['receiptNumber']?></td>
                                                            <td><?php echo date('d-m-Y',strtotime($list['paymentDate']));?></td>
                                                            <td><?php echo $fees;?></td>
                                                            <td><?php echo $paymentMethod;?></td>
                                                            <td><?php echo number_format($list['amount']);?></td>
                                                            <td><?php echo $detailsButton; ?></td>

                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <th colspan="5">
                                                            Total Amount:
                                                        </th>
                                                        <th>
                                                            <?php
                                                            echo number_format($total, 2);
                                                            ?>
                                                        </th>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                    <?php
                                                }
                                                else
                                                {
                                                    echo "<h4 class='text-danger'>No Payment Found</h4>";
                                                }?>


                                            </div>
                                            <div class="box-footer">
                                                <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                                    <i class="fa fa-download"></i> Print Payment History
                                                </button>
                                            </div>
                                        </div></div>
                                </div>

                            <?php
                        } else {
                            echo "<h4 class='text-danger'>No Student Found with that Registration Number</h4>";
                        }
                    }
                    ?>
        </div>

    </div>