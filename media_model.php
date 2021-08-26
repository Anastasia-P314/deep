<?php 
session_start();
require "functions.php";


upload_avatar($_GET['id']);

set_flash_message("success", "Профиль успешно обновлен");

redirect_to("page_profile.php"."?id=".$_GET['id']);

?>


