<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#admit').dataTable(
            {
                paging: false,
                searching:false
            });
    });
</script>
<div class="container">
    <h4>View Hostel Fees</h4>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="index3.php?sp=addnewhostelfees"><span class="btn btn-success">Add New Hostel Fees</span></a>
            </div>
        </div>
        <div class="col-lg-12">

            <div class="row">
                <table id="admit" class="display nowrap">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Fees Type</th>
                        <th>Amount(TSH)</th>
                        <th>Amount(US$)</th>
                        <th>Paid Once</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalTz=0;$totalUsa=0;
                    $fees = $db->getRows('hostelfees',array('order_by'=>'feesTypeID ASC'));
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
                            <td><?php echo number_format($feestz,2);?></td>
                            <td><?php echo number_format($feesusa,2);?></td>
                            <td><?php
                                if($fee['paidOnce']==1)
                                    echo "Yes";
                                else
                                    echo "No";
                                ?></td>
                        </tr>
                    <?php } }?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="2">Total Fees</td>
                        <td><?php echo number_format($totalTz,2);?></td>
                        <td><?php echo number_format($totalUsa,2);?></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>



