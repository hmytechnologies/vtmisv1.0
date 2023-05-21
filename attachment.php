<div class="page-title">
    <div>
        <h1><i class="fa fa-graduation-cap"></i>Documents Upload</h1>
        <p>Please upload your <span style="color: red;">certifiated certificates in pdf format and  personal photo in jpg,png format</span></p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">

            <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Upload New Document</button>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <hr>
        </div>
    </div>
    <?php
    if(!empty($_REQUEST['msg']))
    {
        if($_REQUEST['msg']=="succ")
        {
            echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>You have successfully upload document</strong>.
</div>";
        }
        else if($_REQUEST['msg']=="deleted") {
            echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Document has been droped successfully</strong>.
</div>";
        }
    }
    ?>



<div class="row">
    <div class="col-lg-12">
        <?php
        $document = $db->getRows("attachment", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by applicantID ASC'));
        if (!empty($document)) {
            ?>

            <div class="col-lg-12">
                <h3>List of Attached Documents</h3>

                <table class="table table-striped" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Document Title</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($document as $doc) {
                        $attachmentID = $doc['attachmentID'];
                        $documentType = $doc['documentType'];
                        $fileUrl = $doc['fileUrl'];
                        echo "<tr><td>$documentType</td>";
                        ?>
                        <td><a href="upload_doc/<?php echo $fileUrl;?>" target="_blank">Download</a>
                        </td>
                        <td>
                            <a href="action_working_experience.php?action_type=drop&id=<?php echo $db->my_simple_crypt($attachmentID,'e'); ?>"
                               class="glyphicon glyphicon-trash"
                               onclick="return confirm('Are you sure you want to drop this information?');">Drop</a>
                        </td>
                        <?php
                        echo "</tr>";


                    }
                    ?>
                    </tbody>
                </table>


            </div>

            <?php
        } else {
            ?>
            <h3><span style="color: red;">No Document Found</span> </h3>
        <?php } ?>
        </fieldset>
    </div>
    <!-- End of Equivant Results-->

    <!--<form name="" method="post" action="">
        <div class="col-lg-3">
            <input type="submit" name="doProceed" value="Proceed to Application"
                   class="btn btn-success form-control"/>
        </div>
    </form>
-->
</div>
</div>
</form>
</div>
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" method="post" action="action_upload_document.php" enctype="multipart/form-data">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Document Type</label>
                           <select name="documentType" id="documentType" class="form-control">
                               <option value="">Select Document</option>
                               <option value="Personal Photo">Personal Photo</option>
                               <option value="Form Four Certificate">Form Four Certificate</option>
                               <option value="Form Six Certificate">Form Six Certificate</option>
                               <option value="Transcript">Transcript</option>
                               <option value="Bachelor Degree Certificate">Bachelor Degree Certificate</option>
                               <option value="Master Degree Certificate">Master Degree Certificate</option>
                               <option value="Bank Receipt">Bank Receipt</option>
                           </select>
                        </div>

                        <div class="form-group">
                            <label for="email">File Upload</label>
                            <input type="file" id="file" name="user_image"  class="form-control" />
                        </div>





                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                        <!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>