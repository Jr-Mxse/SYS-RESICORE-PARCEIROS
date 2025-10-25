<?php
if (!empty($_SESSION['ead_logoff'])):
    ?>
    <div class="wc_ead_alert" style="display: flex;">
        <div class="wc_ead_alert_box blue">
            <div class="wc_ead_alert_icon icon-switch icon-exit"></div><div class="wc_ead_alert_text">
                <p class="wc_ead_alert_title">Volte logo <?= $_SESSION['ead_logoff']; ?>,</p>
                <p class="wc_ead_alert_content">Sua conta foi desconectada com sucesso!</p>
            </div><div class="wc_ead_alert_close"><span class="icon-cross icon-notext"></span></div>
        </div>
    </div>
    <?php
    unset($_SESSION['ead_logoff']);
endif;

if (!empty($URL[2]) && $URL[2] == 'restrito'):
    ?>
    <div class="wc_ead_alert" style="display: flex;">
        <div class="wc_ead_alert_box red">
            <div class="wc_ead_alert_icon icon-switch"></div><div class="wc_ead_alert_text">
                <p class="wc_ead_alert_title">ACESSO NEGADO</p>
                <p class="wc_ead_alert_content">Não foi possível identificar seu login :/</p>
            </div><div class="wc_ead_alert_close"><span class="icon-cross icon-notext"></span></div>
        </div>
    </div>
    <?php
endif;

if (!empty($URL[2]) && $URL[2] == 'multiple'):
    ?>
    <div class="wc_ead_alert" style="display: flex;">
        <div class="wc_ead_alert_box red">
            <div class="wc_ead_alert_icon icon-switch"></div><div class="wc_ead_alert_text">
                <p class="wc_ead_alert_title">Oppsss. Você foi desconectado!</p>
                <p class="wc_ead_alert_content">Sua conta foi conectada por outra pessoa ou dispositivo!</p>
            </div><div class="wc_ead_alert_close"><span class="icon-cross icon-notext"></span></div>
        </div>
    </div>
    <?php
endif;

if (!empty($URL[2]) && $URL[2] == 'register'):
    ?>
    <div class="wc_ead_alert" style="display: flex;">
        <div class="wc_ead_alert_box yellow">
            <div class="wc_ead_alert_icon icon-switch"></div><div class="wc_ead_alert_text">
                <p class="wc_ead_alert_title">ERRO AO ATIVAR CONTA:</p>
                <p class="wc_ead_alert_content">Desculpe, mas não foi possível validar o email de ativação! :/</p>
            </div><div class="wc_ead_alert_close"><span class="icon-cross icon-notext"></span></div>
        </div>
    </div>
    <?php
endif;
?>

<div class="wc_ead_enter">
    <?php require "inc/login.inc.php"; ?>
</div>