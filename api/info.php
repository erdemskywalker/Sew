<?php

include_once '../config.php';







function getArticName($id) {
    global $db;
    try{
        $stmt = $db->prepare("SELECT id, name FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode([
                "status" => "success",
                "data" => [
                    "id" => $article['id'],
                    "name" => $article['name']
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Sanatçı bulunamadı."
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        return json_encode([
            "status" => "error",
            "message" => "Veritabanı hatası"
        ], JSON_UNESCAPED_UNICODE);
    }
}











function getMusicImages($id) {
    global $db;
    try{
        $path = "/var/www/admin/uploads/musicImage/". $id. ".png";
        if (file_exists($path)) {
            $imageData = base64_encode(file_get_contents($path));
            return json_encode([
                "status" => "success",
                "data" => $imageData
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Görsel bulunamadı."
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        return json_encode([
            "status" => "error",
            "message" => "İd geçersiz."
        ], JSON_UNESCAPED_UNICODE);
    }
}











function infoMusic($id) {
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM music WHERE id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode([
                "status" => "success",
                "data" => $result
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode([
                "status" => "error",
                "message" => "Müzik bulunamadı."
            ], JSON_UNESCAPED_UNICODE);
        }
        
    } catch (PDOException $e) {
        return json_encode([
            "status" => "error",
            "message" => "Veritabanı hatası"
        ], JSON_UNESCAPED_UNICODE);
    }
}






?>