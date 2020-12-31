<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<!--<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
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
</script>-->


<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#programID").change(function() {
            var id = $(this).val();
            var dataString = 'id=' + id;
            $.ajax({
                type: "POST",
                url: "ajax_studyear.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#studyYear").html(html);
                }
            });

        });

    });
</script>

<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#programID").change(function() {
            var id = $(this).val();
            var dataString = 'id=' + id;
            $.ajax({
                type: "POST",
                url: "ajax_studyear.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#studyYear").html(html);
                }
            });

        });

    });
</script>

<script>
    /*    function print_exam_result() {
        var programmeID = document.getElementById("programID").value;
        var studyYear = document.getElementById("studyYear").value;
        var semesterID = document.getElementById("semesterID").value;
        var batchID = document.getElementById("batchID").value;
        var dataString = 'programmeID=' + programmeID+'&studyYear='+studyYear+'&semesterID='+semesterID+'&batchID='+batchID;
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            url: "ajax_view_semester_result.php",
            data: dataString,
            cache: false,
            success: function(html) {
                $('#myPleaseWait').modal('hide');
                $("#result").html(html);
            }
        });
        return false;
    }*/
</script>

<style type="text/css">
    .bs-example {
        margin: 10px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#nactereport').removeAttr('width').dataTable({
            scrollY: "100%",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            //dom: 'Blfrtip',
            columnDefs: [{
                width: "200px",
                targets: 1
            }],
            fixedColumns: {
                leftColumns: 2
            }
            /*buttons:[
                           {
                               extend:'excel',
                               footer:false,
                           }]*/
        });
    });
</script>

<style type="text/css">
    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        margin: 0 auto;
    }

    div.container {
        width: 100%;
    }
</style>

<?php $db = new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Term Report</h1>
        <hr>

        <div class="tab-content">
            <!-- Current Semester -->
            <?php $db = new DBHelper(); ?>
            <!-- <form name="" method="post" action="">-->
            <form name="" method="post" action="" onsubmit="return print_exam_result();">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="MiddleName">Center Name</label>
                        <select name="centerID" class="form-control chosen-select" required="">
                            <?php
                            $center = $db->getRows('center_registration', array('order_by' => 'centerName ASC'));
                            if (!empty($center)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($center as $cnt) {
                                    $count++;
                                    $centerName = $cnt['centerName'];
                                    $centerID = $cnt['centerRegistrationID'];
                            ?>
                                    <option value="<?php echo $centerID; ?>"><?php echo $centerName; ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-lg-2">
                        <label for="MiddleName">Trade Name</label>
                        <select name="programmeID" id="programID" class="form-control" required>
                            <?php
                            $programmes = $db->getRows('programmes', array('order_by' => 'programmeName ASC'));
                            if (!empty($programmes)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($programmes as $prog) {
                                    $count++;
                                    $programme_name = $prog['programmeName'];
                                    $programmeID = $prog['programmeID'];
                            ?>
                                    <option value="<?php echo $programmeID; ?>"><?php echo $programme_name; ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="FirstName">Study Year</label>
                        <select name="studyYear" id="studyYear" class="form-control" required>
                            <option selected="selected">--Select Study Year--</option>


                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="FirstName">Term Name</label>
                        <select name="examCategoryID" class="form-control" id="examCategoryID">
                            <?php
                            $term = $db->getRows("exam_category", array('where' => array('responsible' => 1), 'order by examCategoryID ASC'));
                            if (!empty($term)) {
                                echo "<option value=''>Please Select Here</option>";
                                foreach ($term as $trm) {
                                    $examCategory = $trm['examCategory'];
                                    $examCategoryID = $trm['examCategoryID'];
                                    echo "<option value='$examCategoryID'>$examCategory</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label for="FirstName">Academic Year</label>
                        <select name="academicYearID" id="academicYearID" class="form-control" required>
                            <?php
                            $academic_year = $db->getRows('academic_year', array('where' => array('status' => 1), 'order_by' => 'academicYear ASC'));
                            if (!empty($academic_year)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($academic_year as $sm) {
                                    $count++;
                                    $academicYear = $sm['academicYear'];
                                    $academicYearID = $sm['academicYearID'];
                            ?>
                                    <option value="<?php echo $academicYearID; ?>"><?php echo $academicYear; ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>



                </div>
                <div class="row">
                    <div class="col-lg-9"></div>
                    <!-- <div class="col-lg-3">
                            <label for=""></label>
                            <input type="submit" name="doPreview" value="Preview PDF" class="btn btn-primary form-control" /></div>-->
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

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                    <div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--<div id="add_new_atype_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
<!-- <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview Course Result</h4>
            </div>
            <div class="modal-body">-->

<?php
/*                $programmeID=$_POST['programmeID'];
                $studyYear=$_POST['studyYear'];
                $batchID=$_POST['batchID'];
                $semesterID=$_POST['semesterID'];
                */ ?>

<!-- <embed src="print_semester_report_sumait.php?action=getPDF&prgID=<?php /*echo $programmeID;*/ ?>&bid=<?php /*echo $batchID;*/ ?>&sid=<?php /*echo $semesterID;*/ ?>&syear=<?php /*echo $studyYear;*/ ?>" frameborder="0" width="100%" height="600px">
-->

<!--<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>--->


<!-- End -->