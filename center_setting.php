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

<div class="container">
    <div class="content">
        <h1>Center Setting</h1>
        <hr>
        <ul class="nav nav-tabs" role="tablist" id="myTab">
            <li class="nav-item active">
                <a class="nav-link" href="#registration" role="tab" data-toggle="tab"><strong>Type of Registration</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#accredition" role="tab" data-toggle="tab"><strong>Type of Accredition</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#ownership" role="tab" data-toggle="tab"><strong>Type of Ownership</strong></a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tab" class="tab-pane fade in active" id="registration">
                <div class="row">
                    <div class="col-md-9">
                        <h3>List of Registration Setting</h3>
                    </div>
                    <div class="col-md-3">
                        <button data-toggle="modal" data-target="#add_new_region" class="btn btn-success form-control">Add New Type</button>
                    </div>
                </div>
                <br/>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $("#regiontab").DataTable({
                            paging:true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf'
                            ]
                        });
                    });
                </script>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $district = $db->getRows('center_registration_type',array('order_by'=>'typeCode DESC'));
                        ?>
                        <table  id="regiontab" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Type Code</th>
                                <th>Type Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($district)){ $countr = 0; foreach($district as $inst){ $countr++;
                                ?>
                                <tr>
                                    <td><?php echo $countr; ?></td>
                                    <td><?php echo $inst['typeCode']; ?></td>
                                    <td><?php echo $inst['typeName'] ;?></td>
                                    <td><button class="btn btn-success fa fa-pencil" data-toggle="modal" data-target="#<?php echo $inst['centerTypeID']?>"></button></td>
                                </tr>
                                <!-- Modal form for editting -->
                                <div class="modal fade" id="<?php echo $inst['centerTypeID']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                            </div>
                                            <form name="" method="post" action="action_center_setting.php">
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Code: </label>
                                                                <input type="text" id="typeCode" name="code" value="<?php echo $inst['typeCode']?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Name: </label>
                                                                <input type="text" id="typeName" name="name" value="<?php echo $inst['typeName']?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <br />
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                                        <input type="hidden" name="action_type" value="editRegistrationType"/>
                                                        <input type="hidden" name="id" value="<?php echo $inst['centerTypeID']?>"/>
                                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal form -->
                            <?php } } ?>
                            </tbody>
                        </table>
                    </div></div>

                <!-- Modal form for region -->
                <div class="modal fade" id="add_new_region" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            </div>
                            <form name="" method="post" action="action_center_setting.php">
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Code: </label>
                                                <input type="text" id="typeCode" name="code" placeholder="Eg.RC001" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Name: </label>
                                                <input type="text" id="typeName" name="name" placeholder="Eg. Full Registration" class="form-control" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <br />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                        <input type="hidden" name="action_type" value="addRegistrationType"/>
                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal form -->
            <div role="tabpanel" class="tab-pane fade" id="accredition">
                <div class="row">
                    <div class="col-md-9">
                        <h3>List of Accreditation</h3>
                    </div>
                    <div class="col-md-3">
                        <button data-toggle="modal" data-target="#add_new_acc" class="btn btn-success form-control">Add New Accreditation</button>
                    </div>
                </div>
                <br/>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $("#accreditation").DataTable({
                            paging:true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf'
                            ]
                        });
                    });
                </script>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $acc = $db->getRows('center_accreditation_type',array('order_by'=>'typeCode DESC'));
                        ?>
                        <table  id="accreditation" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Type Code</th>
                                <th>Type Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($acc)){ $counta = 0; foreach($acc as $ac){ $counta++;
                            ?>
                                <tr>
                                    <td><?php echo $counta; ?></td>
                                    <td><?php echo $ac['typeCode']; ?></td>
                                    <td><?php echo $ac['typeName'] ;?></td>
                                    <td><button class="btn btn-success fa fa-pencil" data-toggle="modal" data-target="#<?php echo $ac['typeCode'];?>"></button></td>
                                </tr>
                            <!-- Modal form for editting -->
                                <div class="modal fade" id="<?php echo $ac['typeCode'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                            </div>
                                            <form name="" method="post" action="action_center_setting.php">
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Code: </label>
                                                                <input type="text" id="typeCode" name="code" value="<?php echo $ac['typeCode']?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Name: </label>
                                                                <input type="text" id="typeName" name="name" value="<?php echo $ac['typeName']?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <br />
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                                        <input type="hidden" name="action_type" value="editAccType"/>
                                                        <input type="hidden" name="id" value="<?php echo $ac['ID']?>"/>
                                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal form -->
                            <?php } }?>
                            </tbody>
                        </table>
                    </div></div>

                <!-- Modal form for region -->
                <div class="modal fade" id="add_new_acc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            </div>
                            <form name="" method="post" action="action_center_setting.php">
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Code: </label>
                                                <input type="text" id="typeCode" name="code" placeholder="Eg.RC001" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Name: </label>
                                                <input type="text" id="typeName" name="name" placeholder="Eg. Accre" class="form-control" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <br />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                        <input type="hidden" name="action_type" value="addAccType"/>
                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal form -->
            <div role="tabpanel" class="tab-pane fade" id="ownership">
                <div class="row">
                    <div class="col-md-9">
                        <h3>List of Center Ownership</h3>
                    </div>
                    <div class="col-md-3">
                        <button data-toggle="modal" data-target="#add_new_ownership" class="btn btn-success form-control">Add New Ownership</button>
                    </div>
                </div>
                <br/>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $("#ownertab").DataTable({
                            paging:true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf'
                            ]
                        });
                    });
                </script>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $onwership = $db->getRows('center_owner_type',array('order_by'=>'typeCode DESC'));
                        ?>
                        <table  id="ownertab" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Type Code</th>
                                <th>Type Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($onwership)){ $counto = 0; foreach($onwership as $own){ $counto++;
                                $ownerID=$own['ID'];
                            ?>
                                <tr>
                                    <td><?php echo $counto; ?></td>
                                    <td><?php echo $own['typeCode']; ?></td>
                                    <td><?php echo $own['typeName'] ;?></td>
                                    <td><button class="btn btn-success fa fa-pencil" data-toggle="modal" data-target="#<?php echo $own['typeCode'];?>"></button></td>
                                </tr>
                            <!-- Modal form for editting -->
                                <div class="modal fade" id="<?php echo $own['typeCode'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <form name="" method="post" action="action_center_setting.php">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Code: </label>
                                                                <input type="text" id="typeCode" name="code" value="<?php echo $own['typeCode'];?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="email">Type Name: </label>
                                                                <input type="text" id="typeName" name="name" value="<?php echo $own['typeName'];?>" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <br />
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                                        <input type="hidden" name="action_type" value="editOwnershipType"/>
                                                        <input type="hidden" name="id" value="<?php echo $own['ID']?>"/>
                                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal form -->
                            <?php } } ?>
                            </tbody>
                        </table>
                    </div></div>

                <!-- Modal form for region -->
                <div class="modal fade" id="add_new_ownership" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            </div>
                            <form name="" method="post" action="action_center_setting.php">
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Code: </label>
                                                <input type="text" id="typeCode" name="code" placeholder="Eg.OWN001" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="email">Type Name: </label>
                                                <input type="text" id="typeName" name="name" placeholder="Eg. Government(VTA)" class="form-control" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <br />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                                        <input type="hidden" name="action_type" value="addOwnershipType"/>
                                        <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal form -->
            </div>


        </div>
    </div>
</div>