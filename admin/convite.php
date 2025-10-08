<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;


$Token = explode("-|-", base64_decode(filter_input(INPUT_GET, 'tk', FILTER_DEFAULT)));

$Read->ExeRead(DB_USERS, "WHERE user_email = '{$Token[1]}' OR user_cell = '{$Token[2]}'", "");
if (!$Read->getResult()):
    $pass = rand(1000, 9999999);
    $RegCreate = [
        'user_name' => $Token[0],
        'user_email' => $Token[1],
        'user_password' => hash('sha512', $pass),
        'user_cell' => $Token[2],
        'especialista_id' => 44,
    ];
    $Create->ExeCreate(DB_USERS, $RegCreate);
    $user_id = $Create->getResult();

    $Read->ExeRead(DB_USERS, "WHERE user_id = '{$user_id}'", "");
    $_SESSION['userLoginParceiros'] = $Read->getResult()[0];

    $nome = explode(" ", $Token[0])[0];
    $destino["numero"] = "55" . $Token[2];
    $destino["mensagem"] = "Parabéns {$nome}!\n 

Agradecemos pela sua confiança e seu cadastro já está ativo. Segue sua senha que pode ser alterada a qualqeur momento:\n
👉 {$pass}\n
Ficamos à disposição para o que precisar.\n
Um grande abraço,\n
Equipe Grupo Residere";

    $destino["numero"] = "5521979158558";

    $url = "https://evolution.zapidere.com.br/message/sendText/Parceiros";
    $headers = [
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];
    $payload = [
        "number" => "{$destino["numero"]}@s.whatsapp.net",
        "text"   => $destino["mensagem"]
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
        $msg = curl_error($ch);
    } else {
        $msg = $response;
    }
    curl_close($ch);

    $destino["numero"] = "5518996653770";

    $url = "https://evolution.zapidere.com.br/message/sendText/Parceiros";
    $headers = [
        "Content-Type: application/json",
        "apikey: 429683C4C977415CAAFCCE10F7D57E11"
    ];
    $payload = [
        "number" => "{$destino["numero"]}@s.whatsapp.net",
        "text"   => $destino["mensagem"]
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
        $msg = curl_error($ch);
    } else {
        $msg = $response;
    }
    curl_close($ch);

else:
    unset($_SESSION['userLoginParceiros']);
endif;
sleep(3);
header("Location: https://painel.residere.com.br/admin");
