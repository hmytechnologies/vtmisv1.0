<?php 
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$users = $db->getRows('users',array('order_by'=>'userID ASC'));
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
    $userID=$user['userID'];
    $fname=$user['firstName'];
    $mname=$user['middleName'];
    $lname=$user['lastName'];
    $username=$user['username'];
    $email = $user['email'];
    $status = $user['status'];
    $departmentID=$user['departmentID'];
    $phoneNumber=$user['phoneNumber'];
    $name = "$fname $lname";

     $userRole = $db->getRows("userroles", array('where' => array('userID' => $userID)));
     $roleNames = array();
     if (!empty($userRole)) {
         foreach ($userRole as $role) {
             $roleID = $role['roleID'];
             $roleName = $db->getData("roles", "roleName", "roleID", $roleID);
             $roleNames[] = $roleName;
         }
     } else {
         $roleNames[] = "None";
     }

     if($roleID==4)
         $officeCode=$db->getData("center_registration","centerName","centerRegistrationID",$departmentID);
     else if($roleID==2)
         $officeCode="Student";
     else if($roleID==3)
         $officeCode=$db->getData("departments","departmentCode","departmentID",$departmentID);
     else
         $officeCode="Academics";

     $resetButton = '
	<div class="btn-group">
	     <a href="action_user.php?action_type=reset&id='.$db->my_simple_crypt($user['userID'],'e').'" class="btn btn-success fa fa-trash" title="Reset User Password" onclick="return confirm("Are you sure you want to Reset Password of this User?");"></a>
	</div>';

     if ($status == 1) {
         $blockButton = '<a href="action_user.php?action_type=deactivate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-unlock"  title="Deactivate User" onclick="return confirm("Are you sure,you want to Deactivate this User?");"></a>';
         $statusOutput = "<span style='color: green'>Active</span>";
     } else {
         $blockButton = '<a href="action_user.php?action_type=activate&id=' . $db->my_simple_crypt($user['userID'], 'e') . '" class="btn btn-success fa fa-lock" title="Activate User" onclick="return confirm("Are you sure, you want to Activate this User?");"></a>';
         $statusOutput = "<span style='color: red'>Blocked</span>";
     }

     $editButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=edit_user&id='.$db->my_simple_crypt($user['userID'],'e').'" title="Edit User Info" class="btn btn-success fa fa-edit"></a>
	</div>';
                    if($roleID==2){
                        $assignroles = '
	                    <div class="btn-group">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>';

                    }
                    else{

                        $assignroles = '
	                    <div class="btn-group">
	                        <a href="index3.php?sp=assign_roles&id='.$db->my_simple_crypt($user['userID'], 'e').'&roleID='.$db->my_simple_crypt($roleID,'e').'" title="Assign more roles to Users" class="btn btn-success fa fa-plus"></a>
                        </div>';
                        }

                        $button="$resetButton $blockButton $editButton $assignroles";

	$output['data'][] = array(
	    $count,
		$name,
	    $username,
	    $phoneNumber,
	    $email,
	    $officeCode,
	    implode(",",$roleNames),
	    $statusOutput,
	    $button
	);

	//$x++;
}
}

// database connection close


echo json_encode($output);
//$db->close();