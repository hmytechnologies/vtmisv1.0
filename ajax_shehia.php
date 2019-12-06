<?php
include("DB.php");
$db=new DBHelper();
$districtID=$_POST['districtID'];
if($districtID)
{
    $district = $db->getRows('ddx_shehia',array('where'=>array('districtCode'=>$districtID),'order_by'=>'shehiaName ASC'));
    if(!empty($district)){
        echo"<option value=''>Please Select Here</option>";
        foreach($district as $dist)
        {
            $shehiaID=$dist['shehiaCode'];
            $shehiaName=$dist['shehiaName'];
            echo "<option value='$shehiaID'>$shehiaName</option>";

        }

    }
}
?>