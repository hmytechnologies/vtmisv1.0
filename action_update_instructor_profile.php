<?php
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
include('DB.php');
$db = new DBHelper();

echo $_POST['edit'];
//$userID=$_SESSION['user_session'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    $edit = $_REQUEST['action_type'];
if ( $edit === 'edit') {
   echo  $instructorID = $_POST['edit'];
    echo $officeNumber = $_POST['officeNumber'];$officeNumber = $_POST['officeNumber'];
   echo $fname = trim($_POST['fname']);
   echo $lname = trim($_POST["lname"]);
   echo  $name = "$fname $lname";
    $userData = array(
    'salutation' => $_POST['salutation'],
    'titleID' => $_POST['titleID'],
    'gender' => $_POST['gender'],
    'phoneNumber' => $_POST['phone'],
    'employmentStatusID' => $_POST['employmentStatus'],
    'officeNumber' => $officeNumber,
    'departmentID'=>$_POST['departmentID'],
    'instructorStatus' => 1,
    'status'=>1,
    'createdBy'=>$instructorID
    );
    $userID = $db->getData("instructor", "userID", "instructorID", $instructorID);
$condition = array('instructorID' => $instructorID);
$update = $db->update("instructor", $userData, $condition);
//upload image
echo $imgFile = $_FILES['photo']['name'];
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
 'instructorImage' => $userpic
);
$condition = array('instructorID' => $_POST['edit']);
echo $update = $db->update("instructor", $pictureData, $condition);

$userImage = array(
'userImage' => $userpic
);
$condition_user = array('userID' => $userID);
$update = $db->update("users", $userImage, $condition_user);
header("Location:index3.php");

} else {
$errMSG = "Sorry, your image file is too large.";
$boolStaus = false;
}
} else {
$errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
$boolStaus = false;
}
}

}
header("Location:index3.php");
}
?>