<?php

include_once '../config.php';

function darklist($ip) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM darklist WHERE ip = :ip");
    $stmt->bindParam(':ip', $ip);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return [
            "status" => "error",
            "message" => "IP adresi kara listede."
        ];
    }
    return [
        "status" => "success",
        "message" => "IP adresi kara listede değil."
    ];
}

function log_action($token = null, $user_id = null, $action) {
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $db->prepare("INSERT INTO logs (token, user_id, ip, action) VALUES (:token, :user_id, :ip, :action)");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':action', $action);
    $stmt->execute();

    $Controller = $db->prepare("SELECT COUNT(*) as total FROM logs WHERE ip = :ip AND action = 'Başarısız giriş denemesi' AND created_at > NOW() - INTERVAL 10 MINUTE");
    $Controller->bindParam(':ip', $ip);
    $Controller->execute();
    $data = $Controller->fetch(PDO::FETCH_ASSOC);
    
    if ($data['total'] >= 15) {
        $add_darklist = $db->prepare("INSERT IGNORE INTO darklist (ip) VALUES (:ip)");
        $add_darklist->bindParam(':ip', $ip);
        $add_darklist->execute();
    }
}