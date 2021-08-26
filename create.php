<?php 
session_start();
require "functions.php";


$created_user_id = create_new_user("create_user.php"); 

edit_general_information($created_user_id);

set_status($created_user_id);

upload_avatar($created_user_id); //var_dump($_FILES["avatar"]);die; СДЕЛАТЬ УНИКАЛЬНЫМ НАЗВАНИЕ ФАЙЛА, чтобы если удалить по названию у одного пользователя, для другого этот же файл не удалялся

add_social_media($created_user_id); 

$_SESSION['flash_message_name'] = "success";
set_flash_message($_SESSION['flash_message_name'], "Пользователь добавлен");
//unset($_SESSION['flash_message_name']);

redirect_to("users.php");

?>



