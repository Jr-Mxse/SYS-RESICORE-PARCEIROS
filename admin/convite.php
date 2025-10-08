<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;


$Token = explode("-|-", base64_decode(filter_input(INPUT_GET, 'tk', FILTER_DEFAULT)));

var_dump($Token);

$Read->ExeRead(DB_USERS, "WHERE user_email = '{$Token[1]}' OR user_cell = '{$Token[2]}'", "");
if (!$Read->getResult()):
    $pass =
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
    header("Location: dashboard.php?wc=home");

//mandar msg

else:
    unset($_SESSION['userLoginParceiros']);
    header("Location: https://painel.residere.com.br");
    exit;
endif;
