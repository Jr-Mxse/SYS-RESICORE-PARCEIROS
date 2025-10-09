<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;

$Token = explode("-|-", base64_decode(filter_input(INPUT_GET, 'tk', FILTER_DEFAULT)));
