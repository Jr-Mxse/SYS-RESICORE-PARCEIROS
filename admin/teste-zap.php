<?php

ob_start();
session_start();
require '../_app/Config.inc.php';
echo "<pre>";

$numeros = [
    ["Wellington", "5521979158558"],
    ["Herbert", "5518996653770"],
    ["Caio", "5511947958589"],
    ["Paulo", "5544998155220"],
    ["Arielton", "5521998616387"],
];

$n = 0;
while ($n < 5):

    $url = "https://evolution.zapidere.com.br/message/sendText/Parceiros";

    $headers = [
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];

    $payload = [
        "number" => "{$numeros[$n][1]}@s.whatsapp.net",
        "text"   => "Olá {$numeros[$n][0]}, isso é apenas uma mensagem de teste de API (Evolution) da plataforma ResiParceiros timestamp (" . time() . ").\n Favor desconsiderar."
    ];

    var_dump($payload);
    echo "<br>";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = (array) json_decode(curl_exec($ch));
    if ($response["status"] != "PENDING") {
        echo "<b>Erro cURL: {$response["error"]}</b>";
    } else {
        echo "Envio realizado: " . $payload["number"];
    }
    echo "<br>";
    var_dump($response);

    curl_close($ch);
    $n++;
    echo "<hr>";
endwhile;
