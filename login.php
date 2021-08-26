<?php 
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

$login = login($email, $password);

if($login==true) {
	redirect_to("users.php");
} else {
	set_flash_message("warning","Неверный логин или пароль");
	redirect_to("page_login.php");
	unset($_SESSION['user']);
}


?>



