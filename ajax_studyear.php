<?php
include("DB.php");
$db=new DBHelper();
if($_POST['id'])
{
$id=$_POST['id'];

 $programmeDuration = $db->getRows('programmes',array('where'=>array('programmeID'=>$id),'order_by'=>'programmeName DESC'));
 if(!empty($programmeDuration)){
   echo"<option value=''>Please Select Here</option>";
   $count = 0; foreach($programmeDuration as $pDuration){ $count++;
   $programmeDuration=$pDuration['programmeDuration'];
   for($x=1;$x<=$programmeDuration;$x++)
   {
	    ?>
	   <option value="<?php echo $x;?>"><?php echo $x;?></option>
	   <?php
    }
     ?>
                                 
   <?php }}
}
?>