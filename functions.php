<?php 

//задание 1,2 Регистрация

function get_user_by_email($email) {
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    $sql = "SELECT * from users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email'=>$email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
};


function add_user($email, $password){
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    $sql = "INSERT INTO users(email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'email'=> $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
};


function set_flash_message($name, $message){
    $_SESSION[$name] = $message;
};


function return_flash_message($name){
    if(isset($_SESSION[$name])){echo
        "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">
        {$_SESSION[$name]}                                   
        </div>";
    unset($_SESSION[$name]);     
    }
};


function redirect_to($path){
    header("location: ./$path");
};

function create_new_user($path){
    $email = $_POST['email'];
    $password = $_POST['password'];

    //$pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");

    $user = get_user_by_email($email);

    if(!empty($user)){
        $message = set_flash_message("danger", "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
        redirect_to($path);
        exit;   
    };

    add_user($email, $password);
    return get_user_by_email($email)['id'];
};


//задание 3 Авторизация

function login($email, $password) {
    $_SESSION['user'] = get_user_by_email($email);
    return password_verify($password, $_SESSION['user']['password']);
};

//задание 4 Список пользователей

function not_logged_in(){
    if(empty($_SESSION['user'])){
        return true;
    }
};

function is_admin(){
    if($_SESSION['user']['role'] =='admin'){
        return true;
    }
};

//function get_all_users(){};

function show_links(){if(is_admin()){}};

//задание 5 Добавить пользователя

function get_user_by_id($id, $table) {
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    $sql = "SELECT * from $table WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id'=>$id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
};


function edit_general_information($id){
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    if(empty(get_user_by_id($id, 'users_general_info'))){
        $sql = "INSERT INTO users_general_info(id, name, profession, phone, address) VALUES (:id, :name, :profession, :phone, :address)";
    } else {
        $sql = "UPDATE users_general_info SET name=:name, profession=:profession, phone=:phone, address=:address WHERE id=:id"; 
    };
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'name'=> $_POST['name'],
        'profession' => $_POST['profession'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address']
    ]);
    //echo get_user_by_id($id, 'users_general_info')['name'];die;
};

function set_status($id){
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    if(empty(get_user_by_id($id, 'statuses'))){
        $sql = "INSERT INTO statuses(id, status) VALUES (:id, :status)";
    } else {
        $sql = "UPDATE statuses SET status=:status WHERE id=:id"; 
    };
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'status'=> $_POST['status']
    ]);
};

function upload_avatar($id){
    $uploaded_file_name = $_FILES["avatar"]["name"]; 
    $filename = pathinfo($uploaded_file_name)['filename'];
    $ext = pathinfo($uploaded_file_name)['extension'];
    $new_filename = strtolower(md5(uniqid($filename)).'.'.$ext);
    //var_dump($new_filename);die;
    move_uploaded_file($_FILES["avatar"]["tmp_name"], "./img/demo/avatars/".$new_filename);
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    if(empty(get_user_by_id($id, 'avatars'))){
        $sql = "INSERT INTO avatars(id, avatar) VALUES (:id, :avatar)";
    } else {
        $old_avatar = get_user_by_id($id, 'avatars')['avatar']; //echo "./".$old_avatar;die;
        $sql = "UPDATE avatars SET avatar=:avatar WHERE id=:id"; 
    };
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'avatar'=> "img/demo/avatars/".$new_filename
    ]);
    unlink("./".$old_avatar);
};

function add_social_media($id){
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    if(empty(get_user_by_id($id, 'social_media'))){
        $sql = "INSERT INTO social_media(id, vk, telegram,ig) VALUES (:id, :vk, :telegram, :ig)";
    } else {
        $sql = "UPDATE social_media SET vk=:vk, telegram=:telegram,ig=:ig WHERE id=:id"; //echo "p";die;
    };
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'vk'=> $_POST['vk'],
        'telegram' => $_POST['telegram'],
        'ig' => $_POST['ig']
    ]);
};


//задание 6 Редактировать пользователя

function check_rights_to_edit($table){

    if(not_logged_in()){ 
        redirect_to("page_login.php");
    };

    if(!is_admin() && $_SESSION['user']['id'] != $_GET['id']){
        $_SESSION['flash_message_name'] = "danger";
        set_flash_message($_SESSION['flash_message_name'], "Можно редактировать только свой профиль");
        redirect_to("users.php");
    };

    $user_id = $_GET['id']; 
    $pdo = new PDO("mysql:host=localhost;dbname=deep","root","");
    $sql = "SELECT * FROM $table WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id'=>$user_id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;

};

//задание 8 Редактировать авторизационные данные

//add_user($email, $password);
function update_user($id, $email, $password){
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    $sql = "UPDATE users SET email=:email, password=:password WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
        'email'=> $email, 
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
};

//create_new_user 
function update_security($id, $email, $password){

    $user = get_user_by_email($email); 

    if(!empty($user) and $user['email']!=$email){
        $message = set_flash_message("danger", "<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.");
        redirect_to("security.php"."?id=".$id);  
        exit; 
    };

    if(empty($password) or password_verify($password, get_user_by_id($id, 'users')['password'])){
        $password = get_user_by_id($id, 'users')['password'];
    };

    update_user($id, $email, $password);
};

//задание 9 Установить статус

//задание 11 Удалить пользователя
function delete($id){
    $old_avatar = get_user_by_id($id, 'avatars')['avatar'];
    unlink("./".$old_avatar);
    $pdo = new PDO("mysql:host=localhost;dbname=deep;","root","");
    $sql = "DELETE users, users_general_info, statuses, social_media, avatars
    FROM users  
    LEFT JOIN users_general_info ON users.id = users_general_info.id 
    LEFT JOIN statuses ON users.id = statuses.id
    LEFT JOIN social_media ON users.id = social_media.id
    LEFT JOIN avatars ON users.id = avatars.id
    WHERE users.id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        'id' => $id,
    ]);

};

?>