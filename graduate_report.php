<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
        
        var admissionYearID=$("#admissionYearID").val();
        console.log(programmeID);
        $('#selection_list').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'api/graduate_list.php',
                        data:{programmeID:programmeID,academicYearID:admissionYearID},
                        "serverSide" : true,
                        cache: false/*,
                        success: function(html) {
                            $('#myPleaseWait').modal('hide');
                        }*/
                    },
                "scrollX":true,
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,1,2,3,4,5]
                        }
                    },
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'print',
                        title: titleheader,
                        footer: false,
                        exportOptions: {
                            columns:[0,1,2,3,4,5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns:[0,1,2,3,4,5]
                        },
                        /*orientation: 'potrait',*/
                    }

                ]
            });
    });
</script>
<div class="container">
    <div class="content">
        <h3>Approve Graduands</h3>
        <hr>
        <form name="" method="post" action="">
            <div class="row">
                <div class="col-lg-3">
                    <label for="MiddleName">Programme Name</label>
                    <select name="programmeID" class="form-control" id="programmeID2" required>
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
                    <select name="admissionYearID" id="admissionYearID2" class="form-control" required>
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


                
            </div>
            <div class="row">
                <div class="col-lg-8"></div>
                <div class="col-lg-4">
                    <label for=""></label>
                    <input type="submit" name="doSearch" value="View Records" class="btn btn-primary form-control" /></div>
            </div>
        </form>


        <br><br>
        <div class="row">

            <?php
            if(isset($_POST['doSearch'])=="View Records") {
                $academicYearID=$_POST['admissionYearID'];
                $programmeID=$_POST['programmeID'];
              
                ?>
                <input type="hidden" id="admissionYearID" value="<?php echo $academicYearID; ?>">
                <!-- <input type="hidden" id="batchID" value="<?php echo $batchID; ?>"> -->
                <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">

                <div class="col-lg-12">
                    <h4><span class="text-danger" id="titleheader">
                    List of Graduated Student in <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?> for the year <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
                    <hr>
                </div>
                <table id="selection_list" class="display nowrap" cellspacing="0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Reg.Number</th>
                        <th>Gender</th>
                        <th>GPA</th>
                        <th>Class</th>
                        <th>Graduation Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php
            }
            ?>
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


