<?php 
session_start();
require "functions.php";

// $email = $_POST['email'];
// $password = $_POST['password'];


// $user = get_user_by_email($email);

// if(!empty($user)){
//     $message = set_flash_message("danger", "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
//     redirect_to("page_register.php");
//     exit;
// }

// add_user($email, $password);

create_new_user("page_register.php");

set_flash_message("success", "Регистрация успешна");
redirect_to("page_login.php");

?>



