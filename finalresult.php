<?php
session_start();
include 'DB.php';

$db = new DBHelper();
$tblName = 'final_result';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['finalResultIDs'])) {
        // Split the comma-separated list of finalResultIDs into an array
        $number_student = $_POST['number_student'];
        $academicYearID = $_POST['academicYearID'];
        $courseID = $_POST['courseID'];
        $examDate = $_POST['examDate'];
        $finalResultIDs = explode(',', $_POST['finalResultIDs']);
        

        try {
            foreach ($finalResultIDs as $finalResultID) {
                // Define the condition for deletion (use your column name)
                $finalData = array(
                    'courseID' => $courseID,
                    'examNumber' => $finalResultID,
                    'academicYearID' => $academicYearID,
                    'examCategoryID' => $examCategoryID,
                    'examDate' => $examDate,
                    'examSitting' => 1,
                    'examScore' => $db->encrypt($examScore),
                    'status' => 0,
                    'checked' => 0,
                    'present' => $status,
                    'comments' => 0
                );

                // Perform the deletion
               echo  $delete = $db->delete($tblName, $condition);
               $statusMsg = true;
           

                if ($delete) {
                    // Handle success (e.g., display a success message)
                    // echo "Deleted finalResultID: $finalResultID<br>";

                    header("Location:index3.php?sp=dropcourse&msg=deleted&action=getRecords&finalResultID=$finalResultID");
                } else {
                    // Handle failure (e.g., display an error message)
                    echo "Failed to delete finalResultID: $finalResultID<br>";
                }
            }
        } catch (Exception $e) {
           
            echo "Error: " . $e->getMessage();
        }

       
    }
}
?>



<?php
// insert_selected_exam_numbers.php

session_start();
include("DB.php");
$db = new DBHelper();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize the incoming data (add more validation if needed)
    $examNumbers = isset($_POST['examNumbers']) ? $_POST['examNumbers'] : [];

    // Perform the database operations to insert the selected exam numbers
    foreach ($examNumbers as $examNumber) {
        $examNumber = htmlspecialchars($examNumber); // Sanitize input

        // You may need to adjust the following query based on your database structure
        $insertQuery = "INSERT INTO final_result (examNumber, otherColumns) VALUES ('$examNumber', 'otherValues')";

        $db->executeQuery($insertQuery); // Assuming you have a method to execute SQL queries in your DBHelper class
    }

    // You can send a success message back to the client if needed
    $response = ['success' => true, 'message' => 'Records inserted successfully'];
    echo json_encode($response);
} else {
    // Handle other types of requests or unauthorized access
    http_response_code(403); // Forbidden
    echo "Forbidden Access";
}
?>
