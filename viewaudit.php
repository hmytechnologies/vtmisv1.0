<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$file = "./logs/userlog.log";

// Check if the file exists and is readable
if (is_file($file) && is_readable($file)) {
    $cont = 0;

    // Use file_get_contents to read the file
    $fileContents = file_get_contents($file);

    if ($fileContents !== false) {
        // Split the contents into lines
        $lines = array_reverse(explode(PHP_EOL, $fileContents));

        foreach ($lines as $data) {
            $datas = explode("; ", $data);
            $stst = "<tr>
                <td>".++$cont."</td>";

            $ct = 0;

            foreach ($datas as $dt) {
                if ($ct == 0) {
                    $w['userID'] = $dt;
                    $cond['where'] = $w;
                    $cond['return_type'] = "single";

                    // Check if the result is an array before accessing 'username'
                    $userRow = $db->getRows("users", $cond);

                    if (is_array($userRow) && isset($userRow['username'])) {
                        $dt = $userRow['username'];
                    } else {
                        $dt = 'N/A'; // Set a default value or handle the case when data is not found
                    }
                }

                $stst .= "<td> $dt </td>";
                $ct++;
            }

            echo "$stst
    </tr>";
        }
    } else {
        echo "Error reading the file.";
    }
} else {
    echo "The specified path is not a valid file or is not readable.";
}
?>
