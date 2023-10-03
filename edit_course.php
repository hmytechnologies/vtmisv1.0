<h1>Edit User Data</h1>
<?php
$db = new DBHelper();
$userData = $db->getRows('course',array('where'=>array('courseID'=>$_GET['id']),'return_type'=>'single'));
if(!empty($userData)){
?>
<form name="" method="post" action="action_course.php">
<div class="row">
<div class="col-md-6">
<div class="modal-body">

<div class="form-group">
<label for="email">Course Name</label>
<input type="text" id="name" name="name" value="<?php echo $userData['courseName'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Code</label>
<input type="text" id="code" name="code" value="<?php echo $userData['courseCode'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Type</label>
<select name="courseTypeID"  class="form-control">
 <option value='<?php echo $db->getData('course_type','courseTypeID','courseTypeID',$userData['courseTypeID']);?>'>
         <?php echo $db->getData('course_type','courseType','courseTypeID',$userData['courseTypeID']);?></option> 
           <?php
           $type = $db->getRows('course_type',array('order_by'=>'courseType DESC'));
           if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
            $courseType=$t['courseType'];
            $courseTypeID=$t['courseTypeID'];
           ?>
           <option value="<?php echo $courseTypeID;?>"><?php echo $courseType;?></option>
           <?php }}?>
</select></div>

<div class="form-group">
<label for="email">Course Units</label>
<input type="number" id="units" name="units" step="any" value="<?php echo $userData['units'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Number of Hours/Week</label>
<input type="number" id="nhrs" name="nhrs" value="<?php echo $userData['numberOfHours'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Capacity</label>
<input type="number" id="capacity" name="capacity" value="<?php echo $userData['capacity'];?>" class="form-control" />
</div>

<div class="form-group">
<label for="email">Course Prerequisite</label>
<select name="coursePrerequisite"  class="form-control">
<?php 
if($userData['coursePrerequiste']=="" || $userData['coursePrerequiste']==0)
{
    $coursePre="None";
    ?>
    <option value="0">None</option>
    <?php 
}
else 
{
?>
<option value='<?php echo $db->getData('course','courseID','courseID',$userData['coursePrerequiste']);?>'>
         <?php echo $db->getData('course','courseName','courseID',$userData['coursePrerequiste']);?></option> 
         <?php 
}
?>
           <?php
           $course = $db->getRows('course',array('order_by'=>'courseName DESC'));
           if(!empty($course)){ $count = 0; 
           ?>
           <option value="0" selected>None</option>
           <?php
            foreach($course as $c){ $count++;
            $courseName=$c['courseName'];
            $courseID=$c['courseID'];
           ?>
           <option value="<?php echo $courseID;?>"><?php echo $courseName;?></option>
           <?php }
           ?>
            
           <?php
           }?>
</select></div>

<div class="form-group">
<label for="email">Department Name</label>
<select name="department_id"  class="form-control">
    <option value='<?php echo $db->getData('departments','departmentID','departmentID',$userData['departmentID']);?>'>
         <?php echo $db->getData('departments','departmentName','departmentID',$userData['departmentID']);?></option>  
           <?php
           $department = $db->getRows('departments',array('order_by'=>'departmentName DESC'));
           if(!empty($department)){ $count = 0; foreach($department as $dept){ $count++;
            $department_name=$dept['departmentName'];
            $department_id=$dept['departmentID'];
           ?>
           <option value="<?php echo $department_id;?>"><?php echo $department_name;?></option>
           <?php }}?>
</select>

</div>

<div class="form-group">
<label for="email">Course Category</label>
<select name="course_category"  class="form-control">

<option 
value='<?php echo $db->getData('course_category','courseCategoryID','courseCategoryID',$userData['courseCategoryID']);?>'>
      <?php echo $db->getData('course_category','courseCategory','courseCategoryID',$userData['courseCategoryID']);?></option>
           <?php
           $type = $db->getRows('course_category',array('order_by'=>'courseCategoryID DESC'));
           if(!empty($type)){ $count = 0; foreach($type as $t){ $count++;
            $courseCategory=$t['courseCategory'];
            $courseCategoryID=$t['courseCategoryID'];
           ?>
           <option value="<?php echo $courseCategoryID;?>"><?php echo $courseCategory;?></option>
           <?php }}?>
</select></div>
    
<div class="form-group">
<label for="email">Course Status</label>
<?php if($userData['status']==1)
{?>
<input type="radio" name="status" value="1" checked>Active <input type="radio" name="status" value="0">Not Active
<?php }else {?>
<input type="radio" name="status" value="1">Active <input type="radio" name="status" value="0" checked>Not Active
<?php }?>
</div>

</div>
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-3">
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
<input type="submit" name="doSubmit" value="Update Records" class="btn btn-success" tabindex="8">
</div></div>
</form>
</div>
</div>
<?php }?>
