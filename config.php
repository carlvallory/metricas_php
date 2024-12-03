<?php

// Cargar Composer Autoload
require_once __DIR__ . '/vendor/autoload.php';

// Usar vlucas/phpdotenv para cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$timeout = 30 * 24 * 60 * 60; //30 días
ini_set( "session.gc_maxlifetime", $timeout );
ini_set( "session.cookie_lifetime", $timeout );
session_set_cookie_params($timeout);

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

session_start();

date_default_timezone_set('America/Asuncion');

$base_url = 'https://apps.nacionmedia.com/nm-metrica/talentos';
//$base_url = 'http://localhost:8089/grupo-nacion/talentos-analytics';

//DATABASE
define('DB_SERVER', 	$_ENV['DB_SERVER']); // Servidor de la base de datos.
define('DB_USERNAME', 	$_ENV['DB_USERNAME']); // Usuario de la base de datos.
define('DB_PASSWORD', 	$_ENV['DB_PASSWORD']); // Contraseña del usuario de la base de datos.
define('DB_NAME', 		$_ENV['DB_NAME']); // Nombre de la base de datos.

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

function isLogin() {
	global $mysqli;

	$user_id = $user_code = null;
	if (isset($_SESSION['tnm_user_id']) && isset($_SESSION['tnm_user_code'])) {
		$user_id = $mysqli->real_escape_string($_SESSION['tnm_user_id']);
		$user_code = $mysqli->real_escape_string($_SESSION['tnm_user_code']);
	} else if (isset($_COOKIE['tnm_user_id']) && !empty($_COOKIE['tnm_user_id']) && isset($_COOKIE['tnm_user_code']) && !empty($_COOKIE['tnm_user_code'])) {
		$user_id = $mysqli->real_escape_string($_COOKIE['tnm_user_id']);
		$user_code = $mysqli->real_escape_string($_COOKIE['tnm_user_code']);

		$_SESSION['tnm_user_id'] = $_COOKIE['tnm_user_id'];
        $_SESSION['tnm_user_code'] = $_COOKIE['tnm_user_code'];
	}

	if ($user_id !== null && $user_code !== null) {

		$result = $mysqli->query("
	        SELECT * 
	        FROM  analytics_talentos_sessions 
	        WHERE user_id = '$user_id' AND code = '$user_code';
	    ");

	    if ($result->num_rows > 0) {
			return true;
		}
	}
	return false;
}

function getCode($n = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    } 
    return $randomString;
}

function userLogin($email, $password) {
	global $mysqli;

	$email = $mysqli->real_escape_string($email);
	$password = md5($password);
	$password = $mysqli->real_escape_string($password);

	$result = $mysqli->query("
        SELECT * 
        FROM  analytics_redes_users 
        WHERE email = '$email' AND password = '$password';
    ");

    $return_data = null;

    if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();

		$user_id = $user['id'];
		$code = md5($user['id'].getCode().time());

		$sql = "INSERT INTO analytics_talentos_sessions (user_id, code)
        VALUES ('$user_id', '$code')";
        if($mysqli->query($sql)) {
        	$return_data = [
        		"user_id" => $user_id,
        		"user_code" => $code
        	];
        }
	}

	return $return_data;
}

function getPrograms($alldata = false) {
	global $mysqli;

	$where_enabled = !$alldata ? "WHERE enabled = 1" : "";

	$result = $mysqli->query("
        SELECT * 
        FROM  analytics_talentos_programs 
        $where_enabled
        ORDER BY id ASC;
    ");

    $data = [];

    if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	}

	return $data;
}

function getProgram($program_id) {
	global $mysqli;

	$program_id = $mysqli->real_escape_string($program_id);

	$result = $mysqli->query("
        SELECT * 
        FROM  analytics_talentos_programs 
        WHERE id = '$program_id';
    ");

    $post = null;

    if ($result->num_rows > 0) {
		$post = $result->fetch_assoc();
	}

	return $post;
}

function setProgramChangeLog($user_id, $program_id, $old_hashtag, $new_hashtag, $old_enabled, $new_enabled) {
	global $mysqli;

	$sql = "INSERT INTO analytics_talentos_programs_changelog (user_id, program_id, old_hashtag, new_hashtag, old_enabled, new_enabled)
    VALUES ('$user_id', '$program_id', '$old_hashtag', '$new_hashtag', '$old_enabled', '$new_enabled')";
    if($mysqli->query($sql)) {
    	return true;
    }
	return false;
}

function addProgram($title, $type, $hashtag, $color, $enabled) {
	global $mysqli;

	if(isset($_SESSION['tnm_user_id'])) {
		$title = $mysqli->real_escape_string($title);
		$type = $mysqli->real_escape_string($type);
		$hashtag = $mysqli->real_escape_string($hashtag);
		$color = $mysqli->real_escape_string($color);
		$enabled = $mysqli->real_escape_string($enabled);

		$sql = "INSERT INTO analytics_talentos_programs (title, type, hashtag, color, enabled)
	    VALUES ('$title', '$type', '$hashtag', '$color', '$enabled')";
	    if($mysqli->query($sql)) {
	    	return true;
	    }
	}
	return false;
}

function setProgram($program_id, $program_hashtag, $program_enabled) {
	global $mysqli;

	if(isset($_SESSION['tnm_user_id'])) {
		$program = getProgram($program_id);
		if($program){
			$user_id = $mysqli->real_escape_string($_SESSION['tnm_user_id']);
			$program_id = $mysqli->real_escape_string($program_id);
			$program_hashtag = $mysqli->real_escape_string($program_hashtag);
			$program_enabled = $mysqli->real_escape_string($program_enabled);

			$sql = "UPDATE analytics_talentos_programs SET hashtag = '$program_hashtag', enabled = '$program_enabled', updated_at = NOW() WHERE id = '$program_id';";
	        if($mysqli->query($sql)) {
	        	setProgramChangeLog($user_id, $program_id, $program['hashtag'], $program_hashtag, $program['enabled'], $program_enabled);
	        	return true;
	        }
		}
	}
	return false;
}

function getTalents() {
	global $mysqli;

	$result = $mysqli->query("
        SELECT * 
        FROM  excel 
        ORDER BY impressions DESC;
    ");

    $data = [];

    if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	}

	return $data;
}

//$talests = getTalents();
//print_r($talests);
//echo json_encode($talests); die;

function getMedios() {
	$medios = [
			'gen' => [
				'name' => 'GEN',
				'blogId' => '658349'

			],
			'lanacion' => [
				'name' => 'La Nación',
				'blogId' => '658341'

			],
			'hoy' => [
				'name' => 'HOY',
				'blogId' => '658439'

			],
			'popular' => [
				'name' => 'Popular',
				'blogId' => '659691'

			],
			'fmpopular' => [
				'name' => 'FM Popular',
				'blogId' => '662229'

			],
			'montecarlofm' => [
				'name' => 'Montecarlo FM',
				'blogId' => '658503'

			],
			'corazonfm' => [
				'name' => 'Corazón FM',
				'blogId' => '662141'

			],
			'versus' => [
				'name' => 'Versus',
				'blogId' => '658633' //658375
			]
		];

	return $medios;
}

function getReporteFields() {
	$fields = [
		'instagram' => [
			'impressions' => 0,
			'likes' => 0,
			'comments' => 0,
			'interactions' => 0,
			'reach' => 0,
			'saved' => 0,
			'videoViews' => 0,
			'posts' => 0
		],
		'facebook' => [
			'impressions' => 0,
			'impressionsUnique' => 0, //alcance
			'comments' => 0,
			'reactions' => 0,
			'shares' => 0,
			'clicks' => 0,
			'videoViews' => 0,
			'posts' => 0
		],
		/* 'twitter' => [
			'total_impressions' => 0,
			'total_retweets' => 0,
			'total_likes' => 0,
			'total_replies' => 0, //comments
			'total_videoviews' => 0,
			'posts' => 0
		], */
		'tiktok' => [
			'viewCount' => 0,
			'likeCount' => 0,
			'commentCount' => 0,
			'shareCount' => 0,
			'posts' => 0
		],
		/* 'youtube' => [
			'views' => 0,
			'likes' => 0,
			'dislikes' => 0,
			'comments' => 0,
			'shares' => 0,
			'posts' => 0
		] */
	];
	return $fields;
}