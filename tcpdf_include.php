<?php
require_once('config/tcpdf_config_alt.php');
$tcpdf_include_dirs = array(
	realpath('../tcpdf.php'),
	'/var/www/html/tcpdf/tcpdf.php'
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}
?>