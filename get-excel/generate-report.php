<?php
require_once('../config.php');

if(!isLogin()) {
  die('Error 401');
}

http_response_code(200);
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$result = [
	'status' => 'error'
];

if(isset($_POST['date_from']) && isset($_POST['date_to'])) {

	$programs_db = getPrograms();
	
	$mysqli->close();

	require_once __DIR__.'/inc/functions.php';
	
	$date_from = $_POST['date_from'];
	$date_to = $_POST['date_to'];
	$programs = get_reporte($programs_db, $date_from, $date_to);

	$result = [
		'status' => 'success'
	];
}

echo json_encode($result);