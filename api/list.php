<?php

include '../config.php';

function justMusicSearch($search='') {
    global $db;
    try{
        $searchTerm = "%".$search."%";

        $stmt = $db->prepare("
            SELECT music.*, artics.name AS artist_name
            FROM music
            INNER JOIN artics ON music.artist_id = artics.id
            WHERE music.title LIKE :search
        	OR artics.name LIKE :search
        	OR artics.tags LIKE :search
        	OR music.tags LIKE :search
            ORDER BY music.id DESC
        ");

        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($results as $row) {
            $data[] = $row;
        }

        return json_encode([
            "status" => "success",
            "data" => $data
        ], JSON_UNESCAPED_UNICODE);

    }catch (Exception $e) {
        return json_encode([
            "status" => "error",
            "message" => "Veritabanı hatası"
        ], JSON_UNESCAPED_UNICODE);
    }
}




?>