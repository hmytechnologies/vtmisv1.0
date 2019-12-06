<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="js/script.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#onlydata').dataTable(
            {
                paging: false,
                dom: 'Blfrtip'
            });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#exam_date").datepicker({
            dateFormat:"yy-mm-dd",
            changeMonth:true,
            changeYear:true,
        });
    });
</script>
<div class="container">
<h3>Approve Graduands</h3>
<hr>
    <form name="" method="post" action="" onsubmit="return viewGranduands();">
<div class="row">
        <div class="col-lg-3">
            <label for="MiddleName">Trade Name</label>
            <select name="programmeID" class="form-control" id="programmeID" required>
                <?php
                $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                if(!empty($programmes)){
                    echo"<option value=''>Please Select Here</option>";
                    $count = 0; foreach($programmes as $prog){ $count++;
                        $programme_name=$prog['programmeName'];
                        $programme_id=$prog['programmeID'];
                        ?>
                        <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                    <?php }
                }
                ?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="MiddleName">Academic Year</label>
            <select name="admissionYearID" id="admissionYearID" class="form-control" required>
                <?php
                $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                if(!empty($adYear)){
                    echo"<option value=''>Please Select Here</option>";
                    $count = 0; foreach($adYear as $year){ $count++;
                        $academic_year=$year['academicYear'];
                        $academic_year_id=$year['academicYearID'];
                        ?>
                        <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                    <?php }
                }
                ?>
            </select>
        </div>

        <div class="col-lg-3">
            <label for="MiddleName">Graduation Date</label>
            <input type="text" name="graduationDate" class="form-control" id="exam_date" required>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8"></div>
        <div class="col-lg-4">
            <label for=""></label>
            <input type="submit" name="Search" value="Search" class="btn btn-primary form-control" /></div>
    </div>
    </form>


<div class="row">
    <div class="col-md-12">
        <div id="result">
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


