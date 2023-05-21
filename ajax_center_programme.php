<?php
include("DB.php");
$db=new DBHelper();
$programmeLevelID=$_POST['programmeLevelID'];
$centerID=$_POST['centerID'];

if($centerID)
{
    $programme= $db->getRows('center_programme',array('where'=>array('centerRegistrationID'=>$centerID),'order_by'=>'programmeID ASC'));
    if(!empty($programme))
    {
        echo"<option value=''>Select Programmes</option>";
        foreach($programme as $gd)
        {
            $programmeID=$gd['programmeID'];
            $prog=$db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID)));
            if(!empty($prog)) {
                foreach($prog as $p) {
                    $programmeName = $p['programmeName'];
                    echo "<option value='$programmeID'>$programmeName</option>";
                }
            }
        }

    }
}
?>