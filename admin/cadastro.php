<?php
ob_start();
session_start();
require '../_app/Config.inc.php';

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
                        <label class="form_label">
                            <h3 style="color: #fff">Ficha cadastral de Parceiro Comercial</h3>
                        </label>
                    </div>

                    <div class="form_group">
                        <label class="form_label">
                            <span class="label_text">Atendimento realizado por qual especialista?</span>
                            <select name="especialista_id" required>
                                <option selected disabled value="">Selecione o especialista:</option>
                                <?php
                                $Read->ExeRead("users", "WHERE user_comercial=1", "");
                                foreach ($Read->getResult() as $Reg) :
                                    echo "<option value='{$Reg["user_id"]}'>{$Reg["user_name"]} {$Reg["user_lastname"]}</option>";
                                endforeach;
                                ?>
                                <option value="44">Outro</option>
                            </select>
                        </label>
                    </div>

                    <div class="form_group">
                        <div class="label_50">
                            <div class="form_group">
                                <label class="form_label">
                                    <span class="label_text">Nome Completo</span>
                                    <input name="user_name" value="" placeholder="Nome Completo" required />
                                </label>
                            </div>
                            <div class="form_group">
                                <label class="form_label">
                                    <span class="label_text">CPF</span>
                                    <input name="user_document" value="" placeholder="Seu CPF"  required />
                                </label>
                            </div>
                        </div>
                        <div class="label_100"></div>
                    </div>


                    <div class="form_group">
                        <label class="form_label">
                            <span class="label_text">E-mail</span>
                            <input name="user_mail" value="" required placeholder="Seu E-mail"  />
                        </label>
                    </div>
                    <div class="label_100"></div>

                     <div class="form_group">
                    <label class="form_label">
                        <span class="label_text">Senha</span>

                        <div class="password_wrapper">
                            <input id="login_password" type="password" name="user_password" placeholder="Digite sua senha" required />
                            <button type="button" class="toggle_password" aria-label="Mostrar senha" aria-pressed="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M14.5582 13.5577C13.9186 14.6361 12.6764 15.2036 11.4426 14.9811C10.2087 14.7585 9.24301 13.7928 9.02048 12.559C8.79795 11.3251 9.36544 10.0829 10.4438 9.44336" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17.9966 16.9963C16.2719 18.3046 14.1649 19.0097 12.0001 19.0031C8.41297 19.0669 5.09862 17.0955 3.44251 13.9129C2.84761 12.7071 2.84761 11.2932 3.44251 10.0873C4.27076 8.43797 5.59106 7.08671 7.2208 6.22046" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M20.4275 14.1345C20.4677 14.0585 20.5199 13.9903 20.5578 13.9128C21.1527 12.707 21.1527 11.293 20.5578 10.0872C18.9017 6.90465 15.5873 4.93323 12.0002 4.99711C11.7753 4.99711 11.5567 5.02712 11.3347 5.04175" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M21.0039 20.0034L3.99683 2.99634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                    </label>
                </div>

                    <div class="form_submit">
                        <img class="form_load none" alt="Enviando..." src="_img/load.gif" />
                        <button class="btn_login" type="submit">Entrar</button>
                    </div>
                </form>

                <div class="login_links">
                    <a href="recover.php" class="link_secondary">Esqueceu sua senha?</a>
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
    <script src="_js/login.js"></script>
</body>

</html>
<?php ob_end_flush(); ?>