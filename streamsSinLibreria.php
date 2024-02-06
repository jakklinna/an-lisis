<?php

// Reemplaza con tu Client ID y Access Token
$clientID = '8sjfuizn8p9ee61m0rpd5rxg1kopfg';
$accessToken = '39gk15k5nv35gnt46tzwiy974atx73';

header('Content-Type: application/json; charset=utf-8');

// Construye la URL para obtener streams en vivo
$liveStreamsUrl = 'https://api.twitch.tv/helix/streams?';

// Configura las opciones para la solicitud HTTP
$options = [
    'http' => [
        'header' => "Client-ID: $clientID\r\n" .
                    "Authorization: Bearer $accessToken\r\n",
        'method' => 'GET',
    ],
];

// Crea el contexto para la solicitud HTTP
$context = stream_context_create($options);

try {
    // Realiza la solicitud HTTP para obtener streams en vivo
    $liveStreamsResponse = file_get_contents($liveStreamsUrl, false, $context);

    if ($liveStreamsResponse === false) {
        echo json_encode(['error' => 'Failed to retrieve live streams from Twitch API']);
    } else {
        // Decodifica el contenido JSON de la respuesta de streams en vivo
        $liveStreamsData = json_decode($liveStreamsResponse, true);

        // Extrae solo el título y el nombre de usuario de cada stream en vivo
        $formattedData = [];
        foreach ($liveStreamsData['data'] as $stream) {
            $formattedData[] = [
                'title' => $stream['title'],
                'user_name' => $stream['user_name'],
            ];
        }

        // Si hay streams en vivo, devuelve la información
        if (!empty($formattedData)) {
            echo json_encode($formattedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['message' => 'No live streams found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}