<?php
require_once 'connect.php';
$email = $_POST['email'];
$password = md5($_POST['password']);
$q_admin = $conn->query("SELECT * FROM `admin` WHERE `email` = '$email' && `password` = '$password'") or die(mysqli_error());
$f_admin = $q_admin->fetch_array();
$v_admin = $q_admin->num_rows;
if ($v_admin > 0) {
	echo 'Success';
	session_start();
	$_SESSION['admin_id'] = $f_admin['admin_id'];
} else {
	echo 'Error';
}
