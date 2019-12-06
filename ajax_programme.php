<?php
include("DB.php");
$db=new DBHelper();
$programmeLevelID=$_POST['programmeLevelID'];
$centerID=$_POST['centerID'];

if($programmeLevelID)
{
    if($centerID=='all') {
        $programme = $db->getCenterDistinctProgramme($programmeLevelID);

        if (!empty($programme)) {
            echo "<option value=''>Select Programmes</option>";
            foreach ($programme as $gd) {
                $programmeID = $gd['programmeID'];
                $prog = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID)));
                if (!empty($prog)) {
                    foreach ($prog as $p) {
                        $programmeName = $p['programmeName'];
                        echo "<option value='$programmeID'>$programmeName</option>";
                    }
                }
            }

        }
    }
    else
    {
        $programme = $db->getRows('center_programme', array('where' => array('programmeLevelID' => $programmeLevelID, 'centerRegistrationID' => $centerID), 'order_by' => 'programmeID ASC'));
        if (!empty($programme)) {
            echo "<option value=''>Select Programmes</option>";
            foreach ($programme as $gd) {
                $programmeID = $gd['programmeID'];
                $prog = $db->getRows('programmes', array('where' => array('programmeID' => $programmeID)));
                if (!empty($prog)) {
                    foreach ($prog as $p) {
                        $programmeName = $p['programmeName'];
                        echo "<option value='$programmeID'>$programmeName</option>";
                    }
                }
            }

        }
    }
}
?>