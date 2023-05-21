<script type="text/javascript" src="js/jquery.min.js"></script>
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
        <h1>Administrative Entities</h1>

        <div class="pull-right">
            <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Settings</a>
        </div>
        <br>
        <hr>
       <!-- <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#zone"><span style="font-size: 16px"><strong>Manage Zone</strong></span></a></li>
            <li><a data-toggle="tab" href="#region"><span style="font-size: 16px"><strong>Manage Region</strong></span></a></li>
            <li><a data-toggle="tab" href="#district"><span style="font-size: 16px"><strong>Manage District</strong></span></a></li>
            <li><a data-toggle="tab" href="#shehia"><span style="font-size: 16px"><strong>Manage Shehia</strong></span></a></li>
        </ul>-->
<ul class="nav nav-tabs" role="tablist" id="myTab">
   <li class="nav-item active">
        <a class="nav-link" href="#zone" role="tab" data-toggle="tab"><strong>Manage Zone</strong></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#region" role="tab" data-toggle="tab"><strong>Manage Region</strong></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#district" role="tab" data-toggle="tab"><strong>Manage District</strong></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#shehia" role="tab" data-toggle="tab"><strong>Manage Shehia</strong></a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">

    <div role="tab" class="tab-pane fade in active" id="zone">
        <script type="text/javascript">
            $(document).ready(function () {
                $("#zonetable").DataTable({
                    paging:true,
                    dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf'
                    ]
                });
            });
        </script>

        <div class="row">
        	<div class="col-md-9">
        		<h3>List of Zones</h3>
        	</div>
        </div>
        <br/>        
        <div class="row">
            <div class="col-md-12">
                <?php
                $db = new DBHelper();
                $zone = $db->getRows('ddx_zone',array('order_by'=>'zoneCode DESC'));
                ?>
                <table  id="zonetable" class="table table-bordered table-responsive-xl table-hover display">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Zone Code</th>
                        <th>Zone Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($zone)){ $count = 0; foreach($zone as $inst){ $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $inst['zoneCode']; ?></td>
                            <td><?php echo $inst['zoneName'] ?></td>
                        </tr>
                    <?php }
                    }else{ ?>
                    <tr><td colspan="4">No zone(s) found......</td>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div role="tab" class="tab-pane fade" id="region">
    <div class="row">
    	<div class="col-md-9">
    		 <h3>List of Region</h3>
    	</div>
    	<div class="col-md-3">
    	<button data-toggle="modal" data-target="#add_new_region" class="btn btn-success form-control">Add Region</button>
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
                $district = $db->getRows('ddx_region',array('order_by'=>'regionCode DESC'));
                ?>
                <table  id="regiontab" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Region Code</th>
                        <th>Region Name</th>
                        <th>Zone Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($district)){ $countr = 0; foreach($district as $inst){ $countr++;
                        ?>
                        <tr>
                            <td><?php echo $countr; ?></td>
                            <td><?php echo $inst['regionCode']; ?></td>
                            <td><?php echo $inst['regionName'] ;?></td>
                            <td><?php echo $db->getData("ddx_zone","zoneName","zoneCode",$inst['zoneCode']);?></td>
                            <td><button class="btn btn-success fa fa-pencil" data-toggle="modal" data-target="#<?php echo $inst['regionCode']?>"></button></td>
                        </tr>
 <!-- Modal form for editting -->
  <div class="modal fade" id="<?php echo $inst['regionCode']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Code: </label>
                                    <input type="text" id="regionCode" name="code" value="<?php echo $inst['regionCode']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Name: </label>
                                    <input type="text" id="regionName" name="name" value="<?php echo $inst['regionName']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Zone Name: </label>
                                    <select name=zoneCode  class="form-control">
                                    <option value='<?php echo $inst['zoneCode']?>'><?php echo $db->getData("ddx_zone", "zoneName", "zoneCode", $inst['zoneCode'])?></option>
                                        <?php
                                        $category = $db->getRows('ddx_zone',array('order_by'=>'zoneCode ASC'));
                                        if(!empty($category)){
                                            
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $zoneID=$dept['zoneCode'];
                                                $zoneName=$dept['zoneName'];
                                                ?>
                                                <option value="<?php echo $zoneID;?>"><?php echo $zoneName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="editregion"/>
                            <input type="hidden" name="regionCode" value="<?php echo $inst['regionCode']?>"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
 <!-- End of Modal form -->
                    <?php } }else{ ?>
                    <tr><td colspan="4">No Region(s) found......</td>
                        <?php } ?>
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
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Code: </label>
                                    <input type="text" id="regionCode" name="code" placeholder="Eg.RC001" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Name: </label>
                                    <input type="text" id="regionName" name="name" placeholder="Eg. Mjini" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Zone Name: </label>
                                    <select name=zoneCode  class="form-control">
                                        <?php
                                        $category = $db->getRows('ddx_zone',array('order_by'=>'zoneCode ASC'));
                                        if(!empty($category)){
                                            echo "<option value=''>Select Here</option>";
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $zoneID=$dept['zoneCode'];
                                                $zoneName=$dept['zoneName'];
                                                ?>
                                                <option value="<?php echo $zoneID;?>"><?php echo $zoneName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="addregion"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>
    <!-- End of Modal form -->
    <div role="tabpanel" class="tab-pane fade" id="district">
    <br/>
    <div class="row">
    	<div class="col-md-9">
    		 <h3>List of District</h3>
    	</div>
    	<div class="col-md-3">
    		<button class="btn btn-success form-control" data-toggle="modal"data-target="#add_new_district">Add District</button>
    	</div>
    </div>
      <br/> 
        <script type="text/javascript">
            $(document).ready(function () {
                $("#districttab").DataTable({
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
                $district = $db->getRows('ddx_district',array('order_by'=>'regionCode DESC'));
                ?>
                <table  id="districttab" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>District Code</th>
                        <th>District Name</th>
                        <th>Region Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($district)){ $countd = 0; foreach($district as $inst){ $countd++;
                        ?>
                        <tr>
                            <td><?php echo $countd; ?></td>
                            <td><?php echo $inst['districtCode']; ?></td>
                            <td><?php echo $inst['districtName'] ;?></td>
                            <td><?php echo $db->getData("ddx_region","regionName","regionCode",$inst['regionCode']);?></td>
                            <td><button data-toggle="modal" data-target="#<?php echo $inst['districtCode']?>" class="btn btn-success fa fa-pencil"></button></td>
                        </tr>
  <!-- Modal for editting -->
  <div class="modal fade" id="<?php echo $inst['districtCode']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Code: </label>
                                    <input type="text" id="regionCode" name="code" value="<?php echo $inst['districtCode']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Name: </label>
                                    <input type="text" id="regionName" name="name" value="<?php echo $inst['districtName']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Name: </label>
                                    <select name=regionCode  class="form-control">
                                  <option value='<?php echo $inst['regionCode']?>'><?php echo $db->getData("ddx_region", "regionName", "regionCode", $inst['regionCode'])?></option>
                                        <?php
                                        $category = $db->getRows('ddx_region',array('order_by'=>'regionCode ASC'));
                                        if(!empty($category)){
                                            
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $regionID=$dept['regionCode'];
                                                $regionName=$dept['regionName'];
                                                ?>
                                                <option value="<?php echo $regionID;?>"><?php echo $regionName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="editdistrict"/>
                            <input type="hidden" name="districtCode" value="<?php echo $inst['districtCode']?>"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
  <!-- End of Modal form -->
                    <?php } }else{ ?>
                    <tr><td colspan="4">No District(s) found......</td>
                        <?php } ?>
                    </tbody>
                </table>
            </div></div>
    </div>
    <!-- Modal form for adding districts -->
        <div class="modal fade" id="add_new_district" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Code: </label>
                                    <input type="text" id="regionCode" name="code" placeholder="Eg.RC001" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Name: </label>
                                    <input type="text" id="regionName" name="name" placeholder="Eg. Mjini" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Region Name: </label>
                                    <select name=regionCode  class="form-control">
                                        <?php
                                        $category = $db->getRows('ddx_region',array('order_by'=>'regionCode ASC'));
                                        if(!empty($category)){
                                            echo "<option value=''>Select Here</option>";
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $regionID=$dept['regionCode'];
                                                $regionName=$dept['regionName'];
                                                ?>
                                                <option value="<?php echo $regionID;?>"><?php echo $regionName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="adddistrict"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End of Modal form -->
    <div role="tabpanel" class="tab-pane fade" id="shehia">
        <h3>List of Shehia</h3>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#shehiatab").DataTable({
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
                <div class="pull-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_shehia_modal">Add New Shehia</button>
                </div>
            </div>
            <br><br>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $db = new DBHelper();
                $shehia = $db->getRows('ddx_shehia',array('order_by'=>'districtCode DESC'));
                ?>
                <table  id="shehiatab" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Shehia Code</th>
                        <th>Shehia Name</th>
                        <th>District Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($shehia)){ $counts = 0; foreach($shehia as $inst){ $counts++;

                        ?>
                        <tr>
                            <td><?php echo $counts; ?></td>
                            <td><?php echo $inst['shehiaCode'] ?></td>
                            <td><?php echo $inst['shehiaName']; ?></td>
                            <td><?php echo $db->getData("ddx_district","districtName","districtCode",$inst['districtCode']); ?></td>
                            <td><button data-toggle="modal" data-target="#<?php echo $inst['shehiaCode']?>" class="btn btn-success fa fa-pencil"></button></td>
                        </tr>
   <!-- Modal for editting -->
 <div class="modal fade" id="<?php echo $inst['shehiaCode']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Shehia Code: </label>
                                    <input type="text" id="shehiaCode" name="code" value="<?php echo $inst['shehiaCode']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Shehia Name: </label>
                                    <input type="text" id="lname" name="name" value="<?php echo $inst['shehiaName']?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Name: </label>
                                    <select name="districtCode"  class="form-control">
                                    <option value='<?php $inst['districtCode']?>'><?php echo $db->getData("ddx_district", 'districtName', 'districtCode', $inst['districtCode'])?></option>
                                        <?php
                                        $category = $db->getRows('ddx_district',array('order_by'=>'districtCode ASC'));
                                        if(!empty($category)){
                                            
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $districtID=$dept['districtCode'];
                                                $districtName=$dept['districtName'];
                                                ?>
                                                <option value="<?php echo $districtID;?>"><?php echo $districtName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="editshehia"/>
                            <input type="hidden" name="shehiaCode" value="<?php echo $inst['shehiaCode']?>"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
 <!-- end of modal form -->
                    <?php } }
                    else
                    {
                        ?>
                        <tr><td colspan="4">No Shehia(s) found......</td></tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

    <div class="modal fade" id="add_new_shehia_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>
                <form name="" method="post" action="action_administrative.php">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Shehia Code: </label>
                                    <input type="text" id="shehiaCode" name="code" placeholder="Eg.001" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Shehia Name: </label>
                                    <input type="text" id="lname" name="name" placeholder="Eg. Kwahani" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">District Name: </label>
                                    <select name="districtCode"  class="form-control">
                                        <?php
                                        $category = $db->getRows('ddx_district',array('order_by'=>'regionCode ASC'));
                                        if(!empty($category)){
                                            echo "<option value=''>Select Here</option>";
                                            $count = 0; foreach($category as $dept){ $count++;
                                                $shehiaID=$dept['districtCode'];
                                                $shehiaName=$dept['districtName'];
                                                ?>
                                                <option value="<?php echo $shehiaID;?>"><?php echo $shehiaName;?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="9">Cancel</button>
                            <input type="hidden" name="action_type" value="addshehia"/>
                            <input type="submit" name="doSubmit" value="Save Record" class="btn btn-primary" tabindex="8">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
    </div>
</div>