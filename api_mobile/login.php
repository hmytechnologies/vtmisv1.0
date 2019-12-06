<?php
require_once '../DB.php';
$db = new DBHelper();
$response = array("error" => FALSE);

if (isset($_GET['regNumber']) && isset($_GET['password'])) {

    $regNumber = $_GET['regNumber'];
    $password = $_GET['password'];

    $user = $db->doMobileLogin($regNumber, $password);

    if ($user != false) {
        $response["error"] = FALSE;
        $response["uid"] = $user["userID"];
        $response['regNumber']=$user['registrationNumber'];
        echo json_encode($response);
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters regNumber or password is missing!";
    echo json_encode($response);
}
?>