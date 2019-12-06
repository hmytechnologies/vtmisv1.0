<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $sectionID = $_POST['schoolID'];
            if (empty($sectionID)) {
                $sectionID = 0;
            } else {
                $sectionID = $sectionID;
            }

            $randomnumber = rand(1111, 9999);
            if ($db->isFieldExist('randomnumber', 'randomNumbers', $randomnumber)) {
                $randomnumber = rand(1111, 9999);
            } else {
                $randomnumber = $randomnumber;
            }
            $username = strtolower(trim($_POST['fname'])) . $randomnumber;
            $email = $_POST['email'];
            $password = $db->PwdHash(strtoupper($_POST['lname']));
            $userData = array(
                'firstName' => trim($_POST['fname']),
                'middleName' => trim($_POST['mname']),
                'lastName' => trim($_POST['lname']),
                'phoneNumber' => $_POST['phone'],
                'email' => $email,
                'departmentID' => $sectionID,
                'userName' => $username,
                'password' => $password,
                'login' => 0,
                'status' => 1
            );
            if ($db->isFieldExist('users', 'userName', $username)) {
                $boolStatus = false;
            } else {
                $insert = $db->insert($tblName, $userData);
                $userID = $insert;
                $roleData = array(
                    'userID' => $userID,
                    'roleID' => $_POST['roleID']
                );
                $insertRole = $db->insert("userroles", $roleData);
                //add random number
                $random = array(
                    'randomNumbers' => $randomnumber
                );
                $insertRandom = $db->insert('randomnumber', $random);

                $firstName = trim($_POST['fname']);
                $middleName = trim($_POST['mname']);
                $lastName = trim($_POST['lname']);

                $name="$firstName $middleName $lastName";
                //send mail
                $to = $email;
                $subject = 'Online Registration';
                $from = 'info@hmytechnologies.com';

// To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
                $headers .= 'From: '.$from."\r\n".
                    'Reply-To: '.$from."\r\n" .
                    'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
                $message = '<html><body>';
                $message = '<h1>Welcome to StAR,the extended Student Academic Register</h1>';
                $message .= '<h1 style="color:#080;">Hi<br>Dear '.$name.'</h1>';
                $message.='<p>Please find the following information as login credentials</p>';
                $message .= '<p style="color:#f40;font-size:18px;">UserName: '.$username.' and Password: '.strtoupper($lastName).'</p>';
                $message.='<p>You must change your password at your first login</p>';
                $message .= '</body></html>';

// Sending email
                mail($to, $subject, $message, $headers);

                //upload image
                $imgFile = $_FILES['photo']['name'];
                $tmp_dir = $_FILES['photo']['tmp_name'];
                $imgSize = $_FILES['photo']['size'];
                if (!empty($imgFile)) {
                    $upload_dir = 'student_images/'; // upload directory

                    $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                    // valid image extensions
                    $valid_extensions = array('png', 'jpg', 'jpeg'); // valid extensions

                    // rename uploading image
                    $userpic = rand(1000, 1000000) . "." . $imgExt;

                    // allow valid image file formats
                    if (in_array($imgExt, $valid_extensions)) {
                        // Check file size '5MB'
                        if ($imgSize < 5000000) {
                            move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                            $pictureData = array(
                                'userImage' => $userpic
                            );
                            $condition = array('userID' => $userID);
                            $update = $db->update($tblName, $pictureData, $condition);
                        } else {
                            $errMSG = "Sorry, your image file is too large.";
                            $boolStaus = false;
                        }
                    } else {
                        $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                        $boolStaus = false;
                    }
                }

                $boolStatus = true;
            }
            if ($boolStatus) {
                header("Location:index3.php?sp=user&msg=secc");
            } else {
                header("Location:index3.php?sp=user&msg=unsecc");
            }

        } elseif ($_REQUEST['action_type'] == 'edit') {
            if (!empty($_POST['id'])) {
                $sectionID = $_POST['schoolID'];
                if(empty($sectionID)) {
                    $sectionID = 0;
                } else {
                    $sectionID = $sectionID;
                }
                $userData = array(
                    'firstName' => trim($_POST['fname']),
                    'middleName' => trim($_POST['mname']),
                    'lastName' => trim($_POST['lname']),
                    'phoneNumber' => $_POST['phone'],
                    'email' => $_POST['email'],
                    'departmentID' => $sectionID
                );
                $condition = array('userID' => $_POST['id']);
                $update = $db->update($tblName, $userData, $condition);
                $roleData = array(
                    'userID' => $_POST['id'],
                    'roleID' => $_POST['roleID']
                );

                $insertRole = $db->update("userroles", $roleData, $condition);

                //upload image
                $imgFile = $_FILES['photo']['name'];
                $tmp_dir = $_FILES['photo']['tmp_name'];
                $imgSize = $_FILES['photo']['size'];
                if (!empty($imgFile)) {
                    $upload_dir = 'student_images/'; // upload directory

                    $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                    // valid image extensions
                    $valid_extensions = array('png', 'jpg', 'jpeg'); // valid extensions

                    // rename uploading image
                    $userpic = rand(1000, 1000000) . "." . $imgExt;

                    // allow valid image file formats
                    if (in_array($imgExt, $valid_extensions)) {
                        // Check file size '5MB'
                        if ($imgSize < 5000000) {
                            move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                            $pictureData = array(
                                'userImage' => $userpic
                            );
                            $condition = array('userID' => $_POST['id']);
                            $update = $db->update($tblName, $pictureData, $condition);
                        } else {
                            $errMSG = "Sorry, your image file is too large.";
                            $boolStaus = false;
                        }
                    } else {
                        $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                        $boolStaus = false;
                    }
                }

                $statusFlag = true;
                header("Location:index3.php?sp=user&msg=edited");
            }
        } elseif ($_REQUEST['action_type'] == 'block') {
            if (!empty($_GET['id'])) {
                $userData = array(
                    'status' => 0
                );
                $condition = array('userID' => $_GET['id']);
                $update = $db->update($tblName, $userData, $condition);
                $statusFlag = true;
                header("Location:index3.php?sp=user&msg=block");
            }
        } elseif ($_REQUEST['action_type'] == 'unblock') {
            if (!empty($_GET['id'])) {
                $userData = array(
                    'status' => 1
                );
                $condition = array('userID' => $_GET['id']);
                $update = $db->update($tblName, $userData, $condition);
                $statusFlag = true;
                header("Location:index3.php?sp=user&msg=unblock");
            }
        } elseif ($_REQUEST['action_type'] == 'reset') {
            if (!empty($_GET['id'])) {
                $users = $db->getRows('users', array('where' => array('userID' => $_GET['id']), 'order_by' => 'userID DESC'));
                if (!empty($users)) {
                    foreach ($users as $us) {
                        $lname = trim($us['lastName']);
                    }
                }
                $userData = array(
                    'password' => $db->PwdHash(strtoupper(trim($lname))),
                    'login' => 0
                );
                $condition = array('userID' => $_GET['id']);
                $update = $db->update($tblName, $userData, $condition);
                $statusFlag = true;
                header("Location:index3.php?sp=user&msg=reset");
            }
        }
    }
}catch (PDOException $ex)
{

}