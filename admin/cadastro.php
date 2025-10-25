<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

unset($_SESSION['userLoginParceiros']);

if (isset($_SESSION['userLoginParceiros']) && isset($_SESSION['userLoginParceiros']['user_level']) && $_SESSION['userLoginParceiros']['user_level'] >= 6):
    header('Location: dashboard.php?wc=home');
    exit;
endif;

$redirect = filter_input(INPUT_GET, 'redirect', FILTER_DEFAULT);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="mit" content="2019-09-17T00:12:51-03:00+17631">
    <title>Parceiros Residere - Cadastro de Parceiros!</title>
    <meta name="description" content="<?= ADMIN_DESC; ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" />
    <meta name="robots" content="noindex, nofollow" />

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
                    <input type="hidden" name="callback" value="Login">
                    <input type="hidden" name="callback_action" value="admin_cadastro">
                    <?php if ($redirect): ?>
                        <input type="hidden" name="redirect" value="<?= $redirect; ?>">
                    <?php endif; ?>

                    <div class="callback_return m_botton">
                        <?php
                        if (!empty($_SESSION['trigger_login'])):
                            echo $_SESSION['trigger_login'];
                            unset($_SESSION['trigger_login']);
                        endif;
                        ?>
                    </div>

                    <div class="form_group">
                        <label class="form_label" style="text-align: center">
                            <h3 style="color: #fff">Ficha cadastral de Parceiro Comercial</h3>
                        </label>
                    </div>

                    <div class="form_group">
                        <label class="form_label">
                            <span class="label_text">Atendimento realizado por qual gestor?</span>
                            <select name="especialista_id" required>
                                <option selected disabled value="">Selecione o Gestor de Parceiros:</option>
                                <?php
                                $Read->ExeRead("users", "WHERE user_relacionamento=1", "");
                                foreach ($Read->getResult() as $Reg) :
                                    echo "<option value='{$Reg["user_id"]}'>{$Reg["user_name"]} {$Reg["user_lastname"]}</option>";
                                endforeach;
                                ?>
                                <option value="44">Outro</option>
                            </select>
                        </label>
                    </div>

                    <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">Nome Completo</span>
                                <input name="user_name" value="" placeholder="Nome Completo" required />
                            </label>
                        </div>

                    <div class="label_50">
                        <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">CPF</span>
                                <input class="formCpf" name="user_document" value="" placeholder="Seu CPF" required />
                            </label>
                        </div>
                        <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">Telefone</span>
                                <input class="formPhone" name="user_cell" value="" placeholder="Seu telefone" required />
                            </label>
                        </div>
                    </div>

                    <div class="label_50">
                        <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">E-mail</span>
                                <input name="user_email" value="" required placeholder="Seu E-mail" />
                            </label>
                        </div>
                        <div class="form_group">
                            <label class="form_label">
                                <span class="label_text">Senha</span>
                                <input name="user_password" value="" required placeholder="Sua Senha"/>
                            </label>
                        </div>
                    </div>

                    <div class="form_submit">
                        <img class="form_load none" alt="Enviando..." src="_img/load.gif" />
                        <button class="btn_login" type="submit">Concluir meu cadastro</button>
                    </div>
                </form>

                <div class="login_links">
                    <a href="index.php" class="link_secondary">Acesso / Login</a>
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