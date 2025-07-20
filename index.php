<?php

header('Content-Type: application/json');

foreach (glob("api/*.php") as $filename) {
    require_once $filename;
}
/**/

// POST verisini oku
$data = json_decode(file_get_contents('php://input'), true);

// GET ile dinleme desteği (React Native GET ile çağırırsa)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type']) && $_GET['type'] === 'listening' && isset($_GET['token'], $_GET['user_id'], $_GET['id'])) {
    if (verify_token($_GET['token'], $_GET['user_id'])['status'] == "success" && darklist($_SERVER['REMOTE_ADDR'])['status'] == "success") {
        try {
            $result = json_decode(listeningMusic($_GET['id']), true);
            if ($result['status'] == "success") {
                header('Content-Type: audio/mpeg');
                header('Content-Length: ' . filesize($result['data']['path']));
                readfile($result['data']['path']);
                exit;
            }
        } catch (Exception $e) {
            exit;
        }
    }
}

// POST ile işlemler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && darklist($_SERVER['REMOTE_ADDR'])['status'] == "success") {
    $type = $data['type'] ?? null;

    if ($type == "registry") {
        $result = registry($data["name"], $data["email"], $data["password"], $data["password_repeat"]);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    } else if ($type == "login") {
        $result = login($data["email"], $data["password"]);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (isset($data['token'], $data['user_id']) && verify_token($data['token'], $data['user_id'])['status'] == "success") {

        if ($type == "logout") {
            $result = logout($data['token']);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($type == "infoMusic") {
            $result = json_decode(infoMusic($data['id']), true);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($type == "getMusicImages") {
            $result = json_decode(getMusicImages($data['id']), true);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($type == "getArticName") {
            $result = json_decode(getArticName($data['id']), true);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($type == "justMusicSearch") {
            echo justMusicSearch($data['search'] ?? '');
            exit;
        }

        if ($type == "listeningMusic") {
            $result = json_decode(listeningMusic($data['id']), true);
            if ($result['status'] == "success") {
                header('Content-Type: audio/mpeg');
                header('Content-Length: ' . filesize($result['data']['path']));
                readfile($result['data']['path']);
            }
            exit;
        }
    } else {
        log_action($data['token'] ?? null, $data['user_id'] ?? null, "İzinsiz istek");
    }
}