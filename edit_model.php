<?php 
session_start();
require "functions.php";


edit_general_information($_GET['id']);

set_flash_message("success", "Профиль успешно обновлен");

redirect_to("page_profile.php"."?id=".$_GET['id']);

?>



