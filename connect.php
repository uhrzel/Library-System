<?php
$conn = new mysqli('localhost', 'root', 'arzelzolina10', 'db_ls') or die(mysqli_error());
if (!$conn) {
	die("Fatal Error: Connection Failed!");
}
