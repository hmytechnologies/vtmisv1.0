<?php
$db=new DBHelper();
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
        $("#userdata").DataTable({
            "paging":false
        });
    });
</script>

<div class="row">
    <div class="col-md-9">
        <h2 class="text-info">View/Assign/Remove Roles for
            <?php
                $userID= $db->my_simple_crypt($_REQUEST['id'],'d');
                $firstRoleID=$db->my_simple_crypt($_REQUEST['roleID'],'d');
                $name=$db->getRows("users",array('where'=>array('userID'=>$userID),'order by userID ASC'));
                if(!empty($name))
                {
                    foreach($name as $n)
                    {
                        $fname=$n['firstName'];
                        $lname=$n['lastName'];
                    }
                }
                echo "$fname $lname";
            ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form name="" method="post" action="action_user.php">
        <table  id="" class="table table-bordered table-responsive-xl table-hover display">
            <thead>
            <tr>
                <th style="width: 2%">No</th>
                <th style="width: 2%"></th>
                <th width="20%">Role</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $role = $db->getRows('roles', array('order by' => 'roleID ASC'));
            if (!empty($role)) {
            $count = 0;
            foreach ($role as $rl) {
            $count++;
            $roleID = $rl['roleID'];
            $rName = $rl['roleName'];

            $userRole=$db->getRows("userroles",array('where'=>array('roleID'=>$roleID,'userID'=>$db->my_simple_crypt($_REQUEST['id'],'d'))));
            if(!empty($userRole))
            {
                foreach($userRole as $urole)
                {
                    $userRoleID=$urole['roleID'];
                }
            }
            else {
                $userRoleID = "";
            }
            if($roleID!=2 && $roleID!=3 && $roleID!=4 && $roleID!=7 && $roleID!=9 && $roleID!=10){
            echo "<tr><td>$count</td>";?>
            <td>
                <?php
                if(!empty($userRole)) {
                    ?><!--<input type='checkbox' name='roleID<?php /*echo $count;*/?>' value='<?php /*echo $roleID;*/?>' checked>-->
                    <input type='checkbox' name='roleID[]' value='<?php echo $roleID;?>' checked>
                    <?php
                }else
                {
                    ?>
                    <!--<input type='checkbox' name='roleID<?php /*echo $count;*/?>' value='<?php /*echo $roleID;*/?>'>-->
                    <input type='checkbox' name='roleID[]' value='<?php echo $roleID;?>'>
                    <?php
                }
                    ?>
            </td>
            <?php

                echo"<td>$rName</td><td>";
            ?>
                    <?php
                }
            echo"</td></tr>";
                    }
                    }
                    ?>
            </tbody>

        </table>
        <tfoot>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <input type="reset" class="btn btn-danger form-control" value="Cancel">
            </div>
            <div class="col-lg-4">
                <input type="hidden" name="userID" value="<?php echo $_REQUEST['id'];?>">
                <input type="hidden" name="number_roles" value="<?php echo $count;?>">
                <input type="hidden" name="action_type" value="assign_roles"/>
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <a href="index3.php?sp=users" class="btn btn-success form-control">Go Back</a>
            </div>
        </div>
        </tfoot>
    </div>
</div>





