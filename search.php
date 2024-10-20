<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $query = strtolower($input['word']);

    $jsonFilePath = 'words.json';
    $data = file_get_contents($jsonFilePath);
    $words = json_decode($data, true);

    $found = false;
    foreach ($words as &$wordObj) {
        if (strtolower($wordObj['word']) === $query) {
            $wordObj['searchCount'] += 1;
            $found = true;
            $response = [
                'word' => $wordObj['word'],
                'article' => $wordObj['article'],
                'searchCount' => $wordObj['searchCount']
            ];
            break;
        }
    }

    if ($found) {
        file_put_contents($jsonFilePath, json_encode($words, JSON_PRETTY_PRINT));
        echo json_encode($response);
    } else {
        echo json_encode(['message' => 'Kelime bulunamadı!']);
    }
} else {
    echo json_encode(['message' => 'Geçersiz istek!']);
}
?>
