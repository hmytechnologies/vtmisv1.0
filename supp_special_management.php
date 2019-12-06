<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>

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

<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Supp/Special Results Management</h1>
        <hr>
        <h3>Manage results by course or by individual student</h3>
                <h3>Choose Semester</h3>
                <div class="row">
                    <form name="" method="post" action="" onsubmit="return view_supp_special_list();">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="MiddleName">Academic Year</label>
                                    <select name="academicYearID" class="form-control" required>
                                        <?php
                                        $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear DESC'));
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
                                    <label for=""><br></label>
                                    <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="result">

                        </div>
                    </div>
                </div>
    </div></div>

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