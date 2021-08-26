<?php 
session_start();
require "functions.php";


update_security($_GET['id'], $_POST['email'], $_POST['password']);

$_SESSION['flash_message_name'] = "success";
set_flash_message($_SESSION['flash_message_name'], "Профиль успешно обновлен");

redirect_to("page_profile.php"."?id=".$_GET['id']);

?>



