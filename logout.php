<?php 

require_once('config.php');

session_unset();

session_destroy();

if (isset($_COOKIE['tnm_user_id'])) {
    unset($_COOKIE['tnm_user_id']); 
    setcookie('tnm_user_id', '', -1);
}

if (isset($_COOKIE['tnm_user_code'])) {
    unset($_COOKIE['tnm_user_code']); 
    setcookie('tnm_user_code', '', -1);
}

$mysqli->close();

header("Location: ".$base_url);
exit();