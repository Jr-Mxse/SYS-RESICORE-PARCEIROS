<?php

ob_start();
session_start();
require '../_app/Config.inc.php';

$url = "https://evolution.zapidere.com.br/message/sendText/RESIDERE";

$headers = [
    "Content-Type: application/json",
    "apikey: 429683C4C977415CAAFCCE10F7D57E11"
];

$payload = [
    "number" => "5521979158558@s.whatsapp.net",
    "text"   => "Mensagem de Teste ".time()
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
if ($response === false) {
    echo "Erro cURL: " . curl_error($ch);
} else {
    echo "Resposta: " . $response;
}
curl_close($ch);
