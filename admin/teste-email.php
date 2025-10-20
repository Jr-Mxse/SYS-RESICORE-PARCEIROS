<?php

ob_start();
session_start();
require '../_app/Config.inc.php';
require '_tpl/Mail.email.php';

echo '<h1>Teste de Envio e Recebimento de Email</h1>';
echo '<br>MAIL_HOST ' . MAIL_HOST;
echo '<br>MAIL_PORT ' . MAIL_PORT;
echo '<br>MAIL_USER ' . MAIL_USER;
echo '<br>MAIL_SMTP ' . MAIL_SMTP;
echo '<br>MAIL_PASS ' . MAIL_PASS;
echo '<br>MAIL_SENDER ' . MAIL_SENDER;
echo '<br>MAIL_MODE ' . MAIL_MODE;
echo '<br><br><b>Se esta tela aparecer, o e-mail foi enviado. Por favor, não responder.</b>';
echo "<hr>";

$MailBody = "
<p>
Olá, <b>Administrador da Plataforma!</b><br>
Este é apenas um e-mail de teste<br><br>

Com você nessa jornada,<br>
Equipe de TI da ResiCode, uma empresa do Grupo Residere.<br><br>";

$MailContent = str_replace("#mail_body#", $MailBody, $MailContent);
$Email = new Email;
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!" . ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Wellington Junior", "junior@mxse.com.br");

$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!" . ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Wellington Junior", "junior.mxsolution@hotmail.com");
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!" . ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Wellington Junior", "junior.mxsolution@gmail.com");
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!" . ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Wellington Junior", "wellington.junior@residere.com.br");
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!".ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Arielton Pires", "arielton.pires@residere.com.br");
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!".ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Paulo", "paulo@residere.com.br");
$Email->EnviarMontando("Chegou a ResiParceiros:  A sua nova plataforma de gestão empresarial no Grupo Residere!".ADMIN_NAME, $MailContent, MAIL_SENDER, MAIL_USER, "Herbert", "herbert.lossavaro@residere.com.br");
