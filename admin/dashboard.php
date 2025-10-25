<?php
ob_start();
session_start();
require '../_app/Config.inc.php';
require '../_cdn/cronjob.php';

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;

if (isset($_SESSION['userLoginParceiros']) && isset($_SESSION['userLoginParceiros']['user_level']) && $_SESSION['userLoginParceiros']['user_level'] >= 6) :
    $Admin = $_SESSION['userLoginParceiros'];
    $Admin['user_thumb'] = (!empty($Admin['user_thumb']) && file_exists("../uploads/{$Admin['user_thumb']}") && !is_dir("../uploads/{$Admin['user_thumb']}") ? "uploads/" . $Admin['user_thumb'] : '../admin/_img/no_avatar.jpg');
    $DashboardLogin = true;

    $PostData['user_document'] = str_replace(["(", ")", " ", "-", ".", "/"], "", $Admin['user_document']);
    $PostData['user_cell'] = str_replace(["(", ")", " ", "-"], "", $Admin['user_cell']);
    $Admin['user_cell'] = $PostData['user_cell'];
    $_SESSION['userLoginParceiros']['user_cell'] = $PostData['user_cell'];
    $Update->ExeUpdate(DB_USERS, $PostData, "WHERE user_id = :id", "id={$_SESSION['userLoginParceiros']['user_id']}");
else :
    unset($_SESSION['userLoginParceiros']);
    header('Location: ./index.php');
    exit;
endif;

MyAutoLoad("Datatable");

$AdminLogOff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
if ($AdminLogOff) :
    $_SESSION['trigger_login'] = Erro("<b>LOGOFF:</b> Olá {$Admin['user_name']}, você desconectou com sucesso do " . ADMIN_NAME . ", volte logo!");
    unset($_SESSION['userLoginParceiros']);
    header('Location: ./index.php');
    exit;
endif;

$getViewInput = filter_input(INPUT_GET, 'wc', FILTER_DEFAULT);
$getView = ($getViewInput == 'home' ? 'home' . ADMIN_MODE : $getViewInput);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Parceiros Residere</title>
    <meta name="description" content="<?= ADMIN_DESC; ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
    <meta name="robots" content="noindex, nofollow" />

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro:300,500' rel='stylesheet' type='text/css'>
    <link rel="base" href="<?= BASE; ?>/admin/">
    <link rel="shortcut icon" href="_img/favicon.png" />

    <link rel="stylesheet" href="../_cdn/datepicker/datepicker.min.css" />
    <link rel="stylesheet" href="_css/reset.css?v=<?= assetVersion(); ?>" />
    <link rel="stylesheet" href="_css/workcontrol.css?v=<?= assetVersion(); ?>" />
    <link rel="stylesheet" href="_css/workcontrol-860.css?v=<?= assetVersion(); ?>" media="screen and (max-width: 860px)" />
    <link rel="stylesheet" href="_css/workcontrol-480.css?v=<?= assetVersion(); ?>" media="screen and (max-width: 480px)" />
    <link rel="stylesheet" href="../_cdn/bootcss/fonticon.css" />

    <script src="../_cdn/jquery.js"></script>
    <script src="../_cdn/jquery.form.js"></script>
    <script src="_js/workcontrol.js?v=<?= assetVersion(); ?>"></script>

    <script src="_js/tinymce/tinymce.min.js"></script>
    <script src="_js/maskinput.js"></script>
    <script src="_js/jquery.mask.js"></script>
    <script src="_js/workplugins.js?v=<?= assetVersion(); ?>"></script>
    <script src="_js/ead.js?v=<?= assetVersion(); ?>"></script>

    <script src="../_cdn/highcharts.js"></script>
    <script src="../_cdn/datepicker/datepicker.min.js"></script>
    <script src="../_cdn/datepicker/datepicker.pt-BR.js"></script>

    <link href="_css/select2.min.css" rel="stylesheet" />
    <script src="_js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../_app/Library/DataTables/datatables.min.css" />
    <script type="text/javascript" src="../_app/Library/DataTables/datatables.min.js"></script>

    <link href="_tpl/elements/_dist/custom.css" rel="stylesheet" />
    <script src="_tpl/elements/_dist/custom.min.js"></script>

    <link href="_sis/transferencias/files/styles/main.min.css" rel="stylesheet" />
    <script src="_sis/transferencias/files/scripts/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
    <style>
        .select2-container--default .select2-selection--single {
            /* mesma altura do select nativo */
            padding: 25px 15px 23px 25px;
            margin: -12px 0px 0px 0px;
            font-size: 1em;
            color: #393939;
            background-color: #fcfcfc;
            border-radius: 5px;
            border: 1px solid #e1e1e1;
            display: flex;
            align-items: center;
            box-sizing: border-box;
        }

        /* Texto dentro do select */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #393939;
            line-height: normal;
            font-size: 1em;
        }

        /* Ícone da setinha */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 8px;
        }

        /* Input de pesquisa dentro do dropdown */
        .select2-container .select2-search--dropdown .select2-search__field {
            width: 100% !important;
            padding: 6px 10px;
            font-size: 1em;
            border: 1px solid #e1e1e1;
            border-radius: 3px;
            box-sizing: border-box;
        }

        /* Dropdown (lista de opções) */
        .select2-container--default .select2-results>.select2-results__options {
            max-height: 200px;
            overflow-y: auto;
            font-size: 1em;
        }

        .select2-container .select2-selection--single .select2-selection__clear {
            display: none;
            ;
        }
    </style>
</head>

<body class="dashboard_main">
    <div class="workcontrol_upload workcontrol_loadmodal">
        <div class="workcontrol_upload_bar">
            <img class="m_botton" width="50" src="_img/load_w.gif" alt="Processando requisição!" title="Processando requisição!" />
            <p><span class="workcontrol_upload_progrees">0%</span> - Processando requisição!</p>
        </div>
    </div>

    <div class="dashboard_fix">
        <?php
        if (isset($_SESSION['trigger_controll'])) :
            echo "<div class='trigger_modal' style='display: block'>";
            Erro("<span class='icon-warning'>{$_SESSION['trigger_controll']}</span>", E_USER_ERROR);
            echo "</div>";
            unset($_SESSION['trigger_controll']);
        endif;
        ?>

        <nav class="dashboard_nav" id="dashboardNav">
            <?php /*
            <span class="mobile_menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-testid="IconThumbsUp" class="sc-dYOqWG fxtfcv">
                    <path d="M12 8L8 12M8 12L12 16M8 12H16M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
            */ ?>
            <div style="width: 80%;text-align: center;padding: 20px 0 10px 0;margin: 0 auto;">
                <img style="max-width: 80%;" class="login_logo" src="_img/logo.png" alt="Parceiros Residere" />
                <br><br>
            </div>
            <ul class="dashboard_nav_menu">
                <?php
                require __DIR__ . "/_sis/wc_menu.php";
                ?>
            </ul>
            <div class="dashboard_nav_normalize"></div>
        </nav>

        <div class="dashboard">
            <?php
            if (file_exists('../DATABASE.sql')) :
                echo "<div>";
                echo Erro("<span class='al_center'><b class='icon-warning'>IMPORTANTE:</b> Para sua segurança delete o arquivo DATABASE.sql da pasta do projeto! <a class='btn btn_yellow' href='dashboard.php?wc=home&database=true' title=''>Deletar Agora!</a></span>", E_USER_ERROR);
                echo "</div>";

                $DeleteDatabase = filter_input(INPUT_GET, 'database', FILTER_VALIDATE_BOOLEAN);
                if ($DeleteDatabase) :
                    unlink('../DATABASE.sql');
                    header('Location: dashboard.php?wc=home');
                    exit;
                endif;
            endif;

            if (ADMIN_MAINTENANCE) :
                echo "<div>";
                echo Erro("<span class='al_center'><b class='icon-warning'>IMPORTANTE:</b> O modo de manutenção está ativo. Somente usuários administradores podem ver o site assim!</span>", E_USER_ERROR);
                echo "</div>";
            endif;

            //DB TEST
            $Read->FullRead("SELECT VERSION() as mysql_version");
            if ($Read->getResult()) :
                $MysqlVersion = $Read->getResult()[0]['mysql_version'];
                if (!stripos($MysqlVersion, "MariaDB")) :
                    echo "<div>";
                    echo Erro('<span class="al_center"><b class="icon-warning">ATENÇÃO:</b> O Parceiros Residere® foi projetado com <b>banco de dados MariaDB superior a 10.1</b>, você está usando ' . $MysqlVersion . '!</span>', E_USER_ERROR);
                    echo "</div>";
                endif;
            endif;

            //PHP TEST
            $PHPVersion = phpversion();
            if ($PHPVersion < '5.6') :
                echo "<div>";
                echo Erro('<span class="al_center"><b class="icon-warning">ATENÇÃO:</b> O Parceiros Residere® foi projetado com <b>PHP 5.6 ou superior</b>, a versão do seu PHP é ' . $PHPVersion . '!</span>', E_USER_ERROR);
                echo "</div>";
            endif;
            ?>
            <div class="dashboard_sidebar">
                <?php if ($getViewInput == 'home'): ?>
                    <span class="mobile_menu_mobile btn_pulse">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <div class="mobile_menu_mobile_box">
                            <div>Abrir Menu</div>
                        </div>
                    </span>
                <?php else: ?>
                    <span class="mobile_menu_mobile btn_pulse">
                        <div class="mobile_menu_mobile_box">
                            <div>Abrir Menu</div>
                        </div>
                    </span>
                <?php endif; ?>
                <div class="dashboard_sidebar_right">
                    <!-- <div class="dashboard_sidebar_left">
                        <span class="dashboard_sidebar_welcome">Bem-vindo(a) a ResiPlace, Hoje <?= date('d/m/Y'); ?></span>
                    </div> -->

                    <!--    <div class="dashboard_sidebar_notification">
                        <button class="notification-btn icon-bell" title="Notificações" id="open-modal">
                            <span class="notification-badge">0</span>
                        </button>
                    </div> -->

                    <div id="notification-modal" class="notification-modal">
                        <div class="notification-modal-content">
                            <div class="notification-header">
                                <div class="notification-header-content">
                                    <h4 class="notification-title">Notificações</h4>
                                    <span class="notification-count">0 novas</span>
                                </div>
                                <button class="close-modal" id="close-modal">&times;</button>
                            </div>

                            <div class="notification-tabs">
                                <button class="tab-btn active" data-tab="alerts">Alertas</button>
                                <button class="tab-btn" data-tab="updates">Atualizações</button>
                                <button class="tab-btn" data-tab="logs">Logs</button>
                            </div>

                            <div class="notification-tabs-content">
                                <div class="tab-content" id="tab-alerts">
                                    <div class="notification-list">

                                        <!-- <div class="notification-item">
                                            <span class="icon-info icon-notext notification-icon info"></span>
                                            <div class="notification-content">
                                                <p>Novo imóvel adicionado à rede</p>
                                                <span class="notification-time">5 min atrás</span>
                                            </div>
                                        </div> -->
                                        <div class="tab-content-empty">Nenhuma atualização encontrada</div>
                                    </div>
                                </div>

                                <div class="tab-content ds_none" id="tab-updates">
                                    <div class="tab-content-empty">Nenhuma atualização encontrada</div>
                                </div>

                                <div class="tab-content ds_none" id="tab-logs">
                                    <div class="tab-content-empty">Nenhum log recente</div>
                                </div>
                            </div>

                            <div class="notification-footer">
                                <a href="#" class="view-all-notifications">Ver todas as notificações</a>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard_nav_admin" onclick="toggleDropdown()">
                        <img class="dashboard_nav_admin_thumb rounded" src="<?= $Admin['user_thumb']; ?>" alt="Avatar">
                        <p class="admin-name">
                            <a href="dashboard.php?wc=users/create&id=<?= $Admin['user_id']; ?>" title="Meu Perfil"><?= $Admin['user_name']; ?></a>
                        </p>

                        <!-- Menu Dropdown -->
                        <div class="admin-dropdown" id="adminDropdown">
                            <div class="dropdown-item">
                                <a href="dashboard.php?wc=users/create&id=<?= $Admin['user_id']; ?>"><span>Meu Perfil</span></a>
                            </div>
                            <!-- <div class="dropdown-item">
                                <span>Meus Imóveis</span>
                                <span class="badge">3</span>
                            </div> -->
                            <!-- <div class="dropdown-divider"></div>
                            <!-- <div class="dropdown-item">
                                <span>Modo</span>
                                <i class="fa-solid fa-toggle-on"></i>
                            </div>
                            <!-- <div class="dropdown-item">
                                <span>Idioma</span>
                                <span class="language-info">Português 🇧🇷</span>
                            </div> -->
                            <!-- <div class="dropdown-item">
                                <span>Configurações da Conta</span>
                            </div> -->
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-item logout">
                                <a class="logout-btn" title="Desconectar da ResiPlace!" href="dashboard.php?wc=home&logoff=true">
                                    <span>Sair</span>
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- Botão de Sair -->
                    <div class="dashboard_sidebar_logout">
                        <a class="logout-btn" title="Desconectar da ResiPlace!" href="dashboard.php?wc=home&logoff=true">
                            <span>Sair</span>
                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>entrance_line</title>
                                <g troke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="System" transform="translate(-672.000000, -96.000000)" fill-rule="nonzero">
                                        <g id="entrance_line" transform="translate(672.000000, 96.000000)">
                                            <path d="M24,0 L24,24 L0,24 L0,0 L24,0 Z M12.5934901,23.257841 L12.5819402,23.2595131 L12.5108777,23.2950439 L12.4918791,23.2987469 L12.4918791,23.2987469 L12.4767152,23.2950439 L12.4056548,23.2595131 C12.3958229,23.2563662 12.3870493,23.2590235 12.3821421,23.2649074 L12.3780323,23.275831 L12.360941,23.7031097 L12.3658947,23.7234994 L12.3769048,23.7357139 L12.4804777,23.8096931 L12.4953491,23.8136134 L12.4953491,23.8136134 L12.5071152,23.8096931 L12.6106902,23.7357139 L12.6232938,23.7196733 L12.6232938,23.7196733 L12.6266527,23.7031097 L12.609561,23.275831 C12.6075724,23.2657013 12.6010112,23.2592993 12.5934901,23.257841 L12.5934901,23.257841 Z M12.8583906,23.1452862 L12.8445485,23.1473072 L12.6598443,23.2396597 L12.6498822,23.2499052 L12.6498822,23.2499052 L12.6471943,23.2611114 L12.6650943,23.6906389 L12.6699349,23.7034178 L12.6699349,23.7034178 L12.678386,23.7104931 L12.8793402,23.8032389 C12.8914285,23.8068999 12.9022333,23.8029875 12.9078286,23.7952264 L12.9118235,23.7811639 L12.8776777,23.1665331 C12.8752882,23.1545897 12.8674102,23.1470016 12.8583906,23.1452862 L12.8583906,23.1452862 Z M12.1430473,23.1473072 C12.1332178,23.1423925 12.1221763,23.1452606 12.1156365,23.1525954 L12.1099173,23.1665331 L12.0757714,23.7811639 C12.0751323,23.7926639 12.0828099,23.8018602 12.0926481,23.8045676 L12.108256,23.8032389 L12.3092106,23.7104931 L12.3186497,23.7024347 L12.3186497,23.7024347 L12.3225043,23.6906389 L12.340401,23.2611114 L12.337245,23.2485176 L12.337245,23.2485176 L12.3277531,23.2396597 L12.1430473,23.1473072 Z" id="MingCute" fill-rule="nonzero">

                                            </path>
                                            <path d="M12,3 C12.5523,3 13,3.44772 13,4 C13,4.51283143 12.613973,4.93550653 12.1166239,4.9932722 L12,5 L7,5 C6.48716857,5 6.06449347,5.38604429 6.0067278,5.88337975 L6,6 L6,18 C6,18.51285 6.38604429,18.9355092 6.88337975,18.9932725 L7,19 L11.5,19 C12.0523,19 12.5,19.4477 12.5,20 C12.5,20.51285 12.113973,20.9355092 11.6166239,20.9932725 L11.5,21 L7,21 C5.40232321,21 4.09633941,19.7511226 4.00509271,18.1762773 L4,18 L4,6 C4,4.40232321 5.24892392,3.09633941 6.82372764,3.00509271 L7,3 L12,3 Z M17.707,8.46447 L20.5355,11.2929 C20.926,11.6834 20.926,12.3166 20.5355,12.7071 L17.707,15.5355 C17.3165,15.9261 16.6834,15.9261 16.2928,15.5355 C15.9023,15.145 15.9023,14.5118 16.2928,14.1213 L17.4142,13 L12,13 C11.4477,13 11,12.5523 11,12 C11,11.4477 11.4477,11 12,11 L17.4142,11 L16.2928,9.87868 C15.9023,9.48816 15.9023,8.85499 16.2928,8.46447 C16.6834,8.07394 17.3165,8.07394 17.707,8.46447 Z" id="形状" fill="#09244B">

                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <?php
            //QUERY STRING
            if (!empty($getView)) :
                $includepatch = __DIR__ . '/_sis/' . strip_tags(trim($getView)) . '.php';
            else :
                $includepatch = __DIR__ . '/_sis/' . 'dashboard.php';
            endif;

            if (file_exists(__DIR__ . "/_sis/" . strip_tags(trim($getView)) . '.php')) :
                require_once __DIR__ . "/_sis/" . strip_tags(trim($getView)) . '.php';
            elseif (file_exists($includepatch)) :
                require_once($includepatch);
            else :
                $_SESSION['trigger_controll'] = "<b>OPPSSS:</b> <span class='fontred'>_sis/{$getView}.php</span> ainda está em contrução!";
                header('Location: dashboard.php?wc=home');
                exit;
            endif;
            ?>
        </div>
        <script>
            $(document).ready(function() {
                $('.js-select2').select2({
                    allowClear: true,
                    width: 'resolve' // ou '100%'
                });
            });
        </script>
    </div>
</body>

</html>
<?php
ob_end_flush();
