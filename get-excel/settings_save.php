<?php
require_once('../config.php');

if(!isLogin() || $_SESSION['tnm_user_id'] != 1) {
  header("Location: ".$base_url);
  exit();
}

http_response_code(200);
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$result = [
	'status' => 'error'
];

if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['hashtag'])) {

	$program_id = $_POST['id'];
	$program_hashtag = $_POST['hashtag'];
	$program_enabled = $_POST['enabled'] == 1 ? 1 : 0;
	$updated_data = setProgram($program_id, $program_hashtag, $program_enabled);

	if($updated_data) {
		$result = [
			'status' => 'success'
		];
	}
}

echo json_encode($result);

$mysqli->close();