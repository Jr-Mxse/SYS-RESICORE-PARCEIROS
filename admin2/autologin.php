<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$Read = new Read;

$Mail = filter_input(INPUT_GET, 'ml', FILTER_VALIDATE_EMAIL);
$Pass = filter_input(INPUT_GET, 'tk', FILTER_DEFAULT);
$End = base64_decode(filter_input(INPUT_GET, 'end', FILTER_DEFAULT));

$Read->ExeRead(DB_USERS, "WHERE user_email = :email AND user_password = :pass", "email={$Mail}&pass={$Pass}");
if (!$Read->getResult()):
    header('Location: ./index.php');
    exit;
else:
    $_SESSION['userLoginParceiros'] = $Read->getResult()[0];
    $_SESSION['company'] = $Read->getResult()[0]['company_id'];
    if ($End):
        header("Location: dashboard.php?wc={$End}");
    else:
        header('Location: dashboard.php?wc=imobi/avaliacao');
    endif;
    exit;
endif;
