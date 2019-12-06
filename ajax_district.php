<?php
include("DB.php");
$db=new DBHelper();
$regionID=$_POST['regionID'];
if($regionID)
{
  $district = $db->getRows('ddx_district',array('where'=>array('regionCode'=>$regionID),'order_by'=>'districtName ASC'));
 if(!empty($district)){
   echo"<option value=''>Please Select Here</option>";
   foreach($district as $dist)
    {
       $districtID=$dist['districtCode'];
       $districtName=$dist['districtName'];
       echo "<option value='$districtID'>$districtName</option>";

    }

    }
}
?>