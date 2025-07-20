<?php

include_once '../config.php';
include_once '../logs.php';



function new_token($length = 32, $user_id = null) {
    global $db;
    $add_token=$db->prepare("INSERT INTO tokens (token,user_id) VALUES (:token,:user_id)");
    $token = generate_random_string($length);
    $add_token->bindParam(':token', $token);
    $add_token->bindParam(':user_id', $user_id);
    $add_token->execute();
    if($add_token->rowCount() > 0){
        return $token;
    } else {
        return "Hata oluştu, lütfen tekrar deneyiniz.";
    }
}

function generate_random_string($length = 32) {
    return bin2hex(random_bytes($length / 2));
}



function verify_token($token, $user_id = null) {
    global $db;
    $query = "SELECT * FROM tokens WHERE token = :token";
    if ($user_id !== null) {
        $query .= " AND user_id = :user_id";
    }
    $stmt = $db->prepare($query);
    $stmt->bindParam(':token', $token);
    if ($user_id !== null) {
        $stmt->bindParam(':user_id', $user_id);
    }
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        log_action($token, $user_id, "Token geçersiz");
        $ip = $_SERVER['REMOTE_ADDR'];
        $Controller = $db->prepare("SELECT COUNT(*) as total FROM logs WHERE ip = :ip AND action = 'Token geçersiz' AND created_at > NOW() - INTERVAL 10 MINUTE");
        $Controller->bindParam(':ip', $ip);
        $Controller->execute();
        $data = $Controller->fetch(PDO::FETCH_ASSOC);
        
        if ($data['total'] >= 10) {
            $add_darklist = $db->prepare("INSERT IGNORE INTO darklist (ip) VALUES (:ip)");
            $add_darklist->bindParam(':ip', $ip);
            $add_darklist->execute();
        }
    }
    return [
        "status" => $stmt->rowCount() > 0,
        "message" => $stmt->rowCount() > 0 ? "Token geçerli." : "Token geçersiz."
    ];
}

function logout($token) {
    global $db;
    $stmt = $db->prepare("DELETE FROM tokens WHERE token = :token"); 
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        return [
            "status" => "success",
            "message" => "Çıkış başarılı."
        ];
    } else {
        return [
            "status" => "error",
            "message" => "Çıkış işlemi başarısız."
        ];
    }
}   


function registry($post_name="", $post_email="", $post_password="", $post_passwordr=""){
    global $db;
    $name = htmlspecialchars(trim($post_name));
    $email = htmlspecialchars(trim($post_email));
    $password = trim($post_password);
    $passwordr = trim($post_passwordr);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return [
            "status" => "error",
            "message" => "Geçersiz e-posta adresi."
        ];
    }

    $Controller=$db->prepare("SELECT * FROM users WHERE email = :email");
    $Controller->bindParam(':email', $email);
    $Controller->execute();
    if($Controller->rowCount() > 0){
        return [
            "status" => "error",
            "message" => "Bu e-posta zaten kayıtlı."
        ];
    } else {
        if($name == "" || $email == "" || $password == "" || $passwordr == ""){
            return [
                "status" => "error",
                "message" => "Boş Yer Bırakmayınız."
            ];
        } else if($password != $passwordr){
            return [
                "status" => "error",
                "message" => "Şifreler eşleşmiyor."
            ];
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            if($stmt->execute()){
                return [
                    "status" => "success",
                    "message" => "Kayıt başarılı."
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Kayıt işlemi başarısız."
                ];
            }
        }
    }
}


function login($post_email="", $post_password=""){
    global $db;
    $email = htmlspecialchars(trim($post_email));
        $password = trim($post_password);

        if($email == "" || $password == ""){
            return [
                "status" => "error",
                "message" => "Boş Yer Bırakmayınız."
            ];
        } else {
            $Controller = $db->prepare("SELECT * FROM users WHERE email =:email");
            $Controller->bindParam(':email', $email);
            $Controller->execute();
            $user = $Controller->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($password, $user['password'])){
                return [
                    "status" => "success",
                    "message" => "Giriş başarılı.",
                    "user_id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "token" => new_token(32, $user['id'])
                ];
            } else {
                log_action(null, null, "Başarısız giriş denemesi");
                return [
                    "status" => "error",
                    "message" => "E-posta veya şifre yanlış."
                ];
            }
        }
}