<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$file = "logs/userlog.log";
$cont = 0;
if (file_exists($file))
foreach (array_reverse(file($file)) as $data) {
$datas = explode("; ", $data);
$stst = "<tr>
    <td>".++$cont."</td>";
    $ct = 0;
    foreach($datas as $dt) {
    if( $ct == 0) {
    $w['userID'] = $dt;
    $cond['where'] = $w;
    $cond['return_type'] = "single";
    $dt = $db->getRows("users", $cond)['username'];
    }
    $stst .= "<td> $dt </td>";
    $ct++;
    }
    echo "$stst
</tr>";
}
?>