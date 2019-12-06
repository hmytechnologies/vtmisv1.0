<?php
include 'DB.php';
$db=new DBHelper();
$regNumber=$_GET['regNumber'];
?>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="id">Upload Image for <?php echo $regNumber ;?></h4>
</div>
<div class="modal-body">
    <form id="form1" method="post" action="action_upload_image.php" enctype="multipart/form-data" class="form-horizontal">
        <label class="control-label">Student Image</label>
        <img id="image" src="student_images/<?php echo $userData['userImage'];?>" height="150px" width="150px;" />
        <input type='file' name="photo" accept=".jpg" onchange="readURL(this);" />
        <!--<img id="image" height="150px" width="150px;" />
        <input type='file' name="photo" accept=".jpg" onchange="readURL(this);" />
       <input class="input-group" type="file" name="student_image" accept="image/*" /><br>-->
        <input type="hidden" name="action_type" value="add"/>
       <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
        <input  type="submit" class="btn btn-success" name="btnSave" value="Save Records"/>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>