<?php
include("../DB.php");
$db=new DBHelper();
$regionID=$_POST['regionID'];
if($regionID)
{
  $district = $db->getRows('district',array('where'=>array('regionID'=>$regionID),'order_by'=>'districtName ASC'));
 if(!empty($district)){ 
   echo"<option value=''>Please Select Here</option>";
   foreach($district as $dist)
    { 
       $districtID=$dist['districtID'];
       $districtName=$dist['districtName'];
       echo "<option value='$districtID'>$districtName</option>";
        
    }
      
    }
}
?>