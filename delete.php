<?php 
session_start();
require "functions.php";

$user = check_rights_to_edit('users'); 

if($user){delete($_GET['id']);};
set_flash_message("success", "Пользователь удален");

if($_SESSION['user']['id'] != $_GET['id']){
	redirect_to("users.php");
} else {
	unset($_SESSION['user']);
	redirect_to("page_register.php");
};





?>



