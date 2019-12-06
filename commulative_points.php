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

<style type="text/css">
    .bs-example{
        margin: 10px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#nactereport').removeAttr('width').dataTable( {
            scrollY:        "100%",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            //dom: 'Blfrtip',
            columnDefs: [
                { width: "200px", targets: 1}
            ],
            fixedColumns: {
                leftColumns: 2
            }
            /*buttons:[
                           {
                               extend:'excel',
                               footer:false,
                           }]*/
        } );
    });
</script>
<script type="text/javascript">

    $('#btnExport').tableToExcel({
        table: '#nactereport',
        exclude: '.exclude',
        name: 'testing-export'
    });

</script>
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
        <!-- Current Semester -->
        <div id="semester" class="tab-pane fade in active">

            <?php $db=new DBHelper();?>
            <h3>Annual Course Report</h3>
            <hr>
            <form name="" method="post" action="">
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
                                <?php }}
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
                        <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                </div>
            </form>

            <div class="row"><br></div>
            <div class="row">

                <?php
                if(isset($_POST['doFind'])=="View Records")
                {
                    $programmeID=$_POST['programmeID'];
                    $studyYear=$_POST['studyYear'];
                    $batchID=$_POST['batchID'];
                    $academicYearID=$_POST['academicYearID'];

                    $student = $db->getStudentAnnualProgramme($programmeID,$academicYearID,$studyYear,$batchID);
                }
                ?>

            </div>


        </div>
        <!-- End of Current Semester -->

    </div></div>