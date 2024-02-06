<?php

// Reemplaza con tu Client ID y Access Token
$clientID = '8sjfuizn8p9ee61m0rpd5rxg1kopfg';
$accessToken = '39gk15k5nv35gnt46tzwiy974atx73';

header('Content-Type: application/json');

// Verifica si el ID del usuario ha sido proporcionado
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$userID = htmlspecialchars($_GET['id']);

// Construye la URL con el ID del usuario
$requestUrl = 'https://api.twitch.tv/helix/users?id=' . $userID;

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
    // Realiza la solicitud HTTP y obtiene el contenido de la respuesta
    $response = file_get_contents($requestUrl, false, $context);

    if ($response === false) {
        echo json_encode(['error' => 'Failed to retrieve data from Twitch API']);
    } else {
        // Decodifica el contenido JSON de la respuesta
        $data = json_decode($response, true);

        // Si hay datos, devuelve el primer usuario encontrado; de lo contrario, error
        if (!empty($data['data'])) {
            echo json_encode($data['data'][0], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}