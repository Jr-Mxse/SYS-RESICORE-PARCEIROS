<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

$Cookie = filter_input(INPUT_COOKIE, 'resiparceiros', FILTER_VALIDATE_EMAIL);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Parceiros Residere - Recuperar Senha!</title>
    <meta name="description" content="<?= ADMIN_DESC; ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <link rel="shortcut icon" href="_img/favicon.png" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:300,500' rel='stylesheet' type='text/css'>
    <link rel="base" href="<?= BASE2; ?>/admin/">

    <link rel="stylesheet" href="_css/reset.css?v=<?= assetVersion(); ?>" />
    <link rel="stylesheet" href="_css/login.css?v=<?= assetVersion(); ?>" />
</head>

<body>
    <div class="container login_container">
        <div class="login_left">
            <div class="login_content">
                <img class="login_logo" src="_img/logo.png" alt="ResiPlace" />
                <form class="login_form" name="work_login" action="" method="post" enctype="multipart/form-data">
                    <div class="trigger trigger_info m_botton">Informeu seu Email ou Whatsapp abaixo para encontrarmos o seu cadastro. Você receberá uma link para recuperar sua senha!</div>
                    <div class="callback_return m_botton"></div>
                    <input type="hidden" name="callback" value="Login">
                    <input type="hidden" name="callback_action" value="admin_recover">

                    <div class="callback_return m_botton">
                        <?php
                        if (!empty($_SESSION['trigger_login'])):
                            echo $_SESSION['trigger_login'];
                            unset($_SESSION['trigger_login']);
                        endif;
                        ?>
                    </div>

                    <div class="form_group">
                        <label class="form_label">
                            <span class="label_text">E-mail</span>
                            <input name="user_email" value="" placeholder="Seu E-mail" />
                        </label>
                    </div>

                    <div class="label_50">
                        <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">Telefone</span>
                                <input class="formPhone" name="user_cell" value="" placeholder="Seu telefone" />
                            </label>
                        </div>
                    </div>

                    <div class="form_submit">
                        <img class="form_load none" alt="Enviando..." src="_img/load.gif" />
                        <button class="btn_login" type="submit">Solicitar Nova Senha</button>
                    </div>
                </form>

                <div class="login_links">
                    <a class="link_secondary" href="./">&larrhk; Logar-se!</a>
                </div>
                <img style="max-width: 350px;" class="login_logo" src="_img/marcas.png" alt="ResiPlace" />
            </div>
        </div>

        <div class="login_right">
            <div class="login_visual">
                <div class="visual_content">
                    <h3 class="visual_title">Você faz a diferença!</h3>
                    <p class="visual_subtitle motivational_text">
                        "Cada atendimento é uma oportunidade de transformar um sonho em realidade."
                    </p>

                    <div class="features">
                        <div class="feature_item">
                            <div class="feature_icon"><i class="fa fa-bolt"></i></div>
                            <span>Energia e atitude positiva</span>
                        </div>
                        <div class="feature_item">
                            <div class="feature_icon"><i class="fa fa-handshake"></i></div>
                            <span>Confiança gera vendas</span>
                        </div>
                        <div class="feature_item">
                            <div class="feature_icon"><i class="fa fa-star"></i></div>
                            <span>Excelência em cada contato</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../_cdn/jquery.js"></script>
    <script src="../_cdn/jquery.form.js"></script>
    <script src="_js/jquery.mask.js"></script>
    <script src="_js/login.js"></script>
</body>

</html>
<?php ob_end_flush(); ?>