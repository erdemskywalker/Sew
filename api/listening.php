<?php

include '../config.php';

function listeningMusic($id){
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM music WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $music = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$music) {
            return json_encode([
                "status" => "error",
                "message" => "Müzik bulunamadı"
            ], JSON_UNESCAPED_UNICODE);
        }

        $file_path = '/var/www/admin/uploads/musics/' . $music['id'] . ".mp3";

        if (!file_exists($file_path)) {
            return json_encode([
                "status" => "error",
                "message" => "Dosya bulunamadı"
            ], JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            "status" => "success",
            "data" => [
                "path" => $file_path
            ]
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        return json_encode([
            "status" => "error",
            "message" => "Dosya hatası"
        ], JSON_UNESCAPED_UNICODE);
    }
}











?>