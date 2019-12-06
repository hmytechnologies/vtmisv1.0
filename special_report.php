<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>

<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#programID").change(function()
        {
            var id=$(this).val();
            var dataString = 'id='+ id;
            $.ajax
            ({
                type: "POST",
                url: "ajax_studyear.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#studyYear").html(html);
                }
            });

        });

    });
</script>

<!--<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#programID").change(function()
        {
            var id=$(this).val();
            var dataString = 'id='+ id;
            $.ajax
            ({
                type: "POST",
                url: "ajax_studyear.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#studyYear").html(html);
                }
            });

        });

    });
</script>-->

<style type="text/css">
    .bs-example{
        margin: 10px;
    }
</style>


<style type="text/css">
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }

    div.container {
        width: 100%;
    }
</style>

<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Special Examination Report</h1>
        <hr>

        <div class="tab-content">
            <!-- Current Semester -->
            <?php $db=new DBHelper();?>
            <form name="" method="post" action="" onsubmit="return view_special_result();">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="MiddleName">Programme Name</label>
                        <select name="programmeID" id="programID" class="form-control" required>
                            <?php
                            $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                            if(!empty($programmes)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                    $programme_name=$prog['programmeName'];
                                    $programmeID=$prog['programmeID'];
                                    ?>
                                    <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                                <?php }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="FirstName">Study Year</label>
                        <select name="studyYear" id="studyYear" class="form-control" required>
                            <option selected="selected">--Select Study Year--</option>


                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="FirstName">Academic Year</label>
                        <select name="academicYearID" id="academicYearID" class="form-control" required>
                            <?php
                            $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYearID ASC'));
                            if(!empty($academic_year)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($academic_year as $sm){ $count++;
                                    $academicYear=$sm['academicYear'];
                                    $academicYearID=$sm['academicYearID'];
                                    ?>
                                    <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                <?php }}

                            ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label for="FirstName">Study Mode</label>
                        <select name=batchID id="batchID" class="form-control" required>
                            <?php
                            $batch = $db->getRows('batch',array('order_by'=>'batchID DESC'));
                            if(!empty($batch)){
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($batch as $sm){ $count++;
                                    $batchName=$sm['batchName'];
                                    $batchID=$sm['batchID'];
                                    ?>
                                    <option value="<?php echo $batchID;?>" selected><?php echo $batchName;?></option>
                                <?php }}

                            ?>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-9"></div>
                    <div class="col-lg-3">
                        <label for=""></label>
                        <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                </div>
            </form>

            <div class="row"><br></div>


        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="result">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-time">
                    </span>Please wait...page is loading
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped active"
                         style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End -->