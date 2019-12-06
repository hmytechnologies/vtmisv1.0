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
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#singlestudent"><span style="font-size: 16px"><strong>Single Student Payment</strong></span></a></li>
            <li><a data-toggle="tab" href="#batch"><span style="font-size: 16px"><strong>Batch Processing</strong></span></a></li>
            <!--<li><a data-toggle="tab" href="#discounts"><span style="font-size: 16px"><strong>Payment Discounts</strong></span></a></li>-->
        </ul>
        <div class="tab-content">
            <!-- singlestudent -->
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
                    if((isset($_POST['doSearch'])=="Search Student") ||(isset($_REQUEST['action'])=="getRecords"))
                    {
                        if(isset($_POST['doSearch'])    =="Search Student")
                        {
                            $searchStudent = $_POST['search_student'];
                        }
                        else
                        {
                            $searchStudent = $db->my_simple_crypt($_REQUEST['search_student'],'d');
                        }

                        $today=date("Y-m-d");
                        $sm=$db->readSemesterSetting($today);
                        foreach ($sm as $s)
                        {
                            $semisterID=$s['semesterID'];
                            $academicYearID=$s['academicYearID'];
                            $semesterName=$s['semesterName'];
                            $semesterSettingID=$s['semesterSettingID'];
                        }

                        $studentID = $db->getRows('student',array('where'=>array('registrationNumber'=>$searchStudent),' order_by'=>' studentID ASC'));
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
                                        $totalFeesP=0;
                                    }
                                    else
                                    {
                                        $totalFeesP+=$amount;
                                        $totalFeesC=0;
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
                                    if($psesmesterSettingID==$semesterSettingID) {
                                        $totalSPC += $amount;
                                        $totalSPP=0;
                                    }
                                    else {
                                        $totalSPP += $amount;
                                        $totalSPC=0;
                                    }
                                    //Academic Year
                                    if($pacademicYearID==$academicYearID) {
                                        $totalAPC += $amount;
                                        $totalAPP=0;
                                    }
                                    else {
                                        $totalAPP += $amount;
                                        $totalAPC=0;
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
                                                            <strong><?php echo number_format($totalFeesP-(($amountPercentP/100)*($totalFeesP))-$totalAPP);?></strong>
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
                                                        <label for="inputEmail3" class="col-sm-2 control-label">Amount Paid</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount Paid">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-2 control-label">Receipt No</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="receiptno" name="receiptno" placeholder="Receipt Number">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword3" class="col-sm-2 control-label">Payment Date</label>

                                                        <div class="col-sm-8">
                                                            <div class="input-group date form_date col-md-9" data-date="" data-date-format="yyyy MM dd"
                                                                 data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                <input class="form-control" size="16" type="text" name="paymentDate" value="" id="pickyDate">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
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
                        else
                        {
                            echo "<h4 class='text-danger'>No Student Found with that Registration Number: ".$searchStudent."</h4>";
                        }
                    }
                    ?>
                </div>



            </div>



            <!-- Batch Processing -->
            <div id="batch" class="tab-pane fade">

                <h3>Upload Student Payment From CSV File</h3>

                <h5 class="text-danger">NB:Your file must have this format(RegNumber,Amount Paid(without comma or decimal),Receipt Number,Date of Payment(YYYY-MM-DD))</h5>
                <hr>
                <div class="row">
                    <?php
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="usucc")
                        {
                            echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Payment data has been uploaded successfully</strong>.
                            </div>";
                        }

                        else if($_REQUEST['msg']=="uerror") {
                            echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                        }
                        else if($_REQUEST['msg']=="unsuccu") {
                            echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                            <strong>Something wrong happening, contact System Administrator</strong>.
                            </div>";
                        }
                    }
                    ?>
                </div>
                <form name="" method="post" action="action_upload_student_payment.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="FirstName">Semester Name</label>
                            <select name="semesterID" class="form-control" required>
                                <?php
                                $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                if(!empty($semister)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($semister as $sm){ $count++;
                                        $semister_name=$sm['semesterName'];
                                        $semister_id=$sm['semesterSettingID'];
                                        ?>
                                        <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                    <?php }}

                                ?>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label for="FirstName">CSV/Excel File</label>
                            <input type='file' name="csv_file" accept=".csv" />
                        </div>

                        <div class="col-lg-3">
                            <label for=""></label>
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doFind" value="Upload File" class="btn btn-primary form-control" /></div>
                    </div>
                </form>

                <div class="row"><br></div>

            </div>
            <!-- End -->

            <!-- Discounts -->

            <!-- End -->
        </div>

    </div></div>