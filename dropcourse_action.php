<?php
session_start();
include 'DB.php';

$db = new DBHelper();
$tblName = 'final_result';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['finalResultIDs'])) {
        // Split the comma-separated list of finalResultIDs into an array
        $finalResultIDs = explode(',', $_POST['finalResultIDs']);

        try {
            foreach ($finalResultIDs as $finalResultID) {
                // Define the condition for deletion (use your column name)
                $condition = array('finalResultID' => $finalResultID);

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
