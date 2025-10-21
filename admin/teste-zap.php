<?php

ob_start();
session_start();
require '../_app/Config.inc.php';
echo "<pre>";

$numeros = [
    ["Wellington", "5521979158558"],
    ["Wellington", "5521986395580"],
    ["Herbert", "5518996653770"],
    ["Caio", "5511947958589"],
    ["Paulo", "5544998155220"],
    ["Arielton", "5521998616387"],
];

$n = 0;
while ($n < 5):

    $destino["numero"] = $numeros[$n][1];
    $destino["mensagem"] = "Olá {$numeros[$n][0]}, isso é apenas uma mensagem de teste de API (Evolution) da plataforma ResiParceiros timestamp (" . time() . ").\n Favor desconsiderar.";

    $envio = envioZapParceiro($destino);

    var_dump($envio);

    $n++;
    echo "<hr>";
endwhile;