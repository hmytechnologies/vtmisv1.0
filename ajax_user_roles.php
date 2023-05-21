<?php
include("DB.php");
$db=new DBHelper();
$office=$_POST['office'];

if($office)
{
    if($office=='center')
        $centerID=2;
    else
        $centerID=1;
    $roles = $db->getRows('roles',array('where'=>array('officeID'=>$centerID),'order_by'=>'roleID ASC'));
    if(!empty($roles)){
        ?>
        <option value="">Select Here</option>
        <?php
        $count = 0; foreach($roles as $role){ $count++;
            $roleName=$role['roleName'];
            $roleID=$role['roleID'];
            if($roleID != 2){
                ?>
                <option value="<?php echo $roleID;?>"><?php echo $roleName;?></option>
            <?php }
        }
    }
}
?>

<?php

?>
