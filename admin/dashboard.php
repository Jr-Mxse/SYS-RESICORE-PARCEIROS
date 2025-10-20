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
    $Read = new Read;
    $Read->FullRead("SELECT user_level FROM " . DB_USERS . " WHERE user_id = :user", "user={$_SESSION['userLoginParceiros']['user_id']}");

    $Admin = $_SESSION['userLoginParceiros'];
    $Admin['user_thumb'] = (!empty($Admin['user_thumb']) && file_exists("../uploads/{$Admin['user_thumb']}") && !is_dir("../uploads/{$Admin['user_thumb']}") ? "uploads/" . $Admin['user_thumb'] : '../admin/_img/no_avatar.jpg');
    $DashboardLogin = true;

    $PostData['user_document'] = str_replace(["(", ")", " ", "-", ".", "/"], "", $Admin['user_document']);
    $PostData['user_cell'] = str_replace(["(", ")", " ", "-"], "", $Admin['user_cell']);
    $Admin['user_cell'] = $PostData['user_cell'];
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
    <link href="assets/vendors/keenicons/styles.bundle.css" rel="stylesheet"/>

    <script src="../_cdn/jquery.js"></script>
    <script src="../_cdn/jquery.form.js"></script>
    <script src="_js/workcontrol.js?v=<?= assetVersion(); ?>"></script>

    <script src="_js/tinymce/tinymce.min.js"></script>
    <script src="_js/maskinput.js"></script>
    <script src="_js/jquery.mask.js"></script>
    <script src="_js/workplugins.js?v=<?= assetVersion(); ?>"></script>

    <script src="../_cdn/highcharts.js"></script>
    <script src="../_cdn/datepicker/datepicker.min.js"></script>
    <script src="../_cdn/datepicker/datepicker.pt-BR.js"></script>

    <link href="_css/select2.min.css" rel="stylesheet" />
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

  <div class="dashboard_layout">
        <?php
        if (isset($_SESSION['trigger_controll'])):
            echo "<div class='trigger_modal' style='display: block'>";
            Erro("<span class='icon-warning'>{$_SESSION['trigger_controll']}</span>", E_USER_ERROR);
            echo "</div>";
            unset($_SESSION['trigger_controll']);
        endif;

        require("_sis/wc_menu.php");
        ?>

       <!-- CONTEÚDO PRINCIPAL -->
        <main class="dashboard_main_content" id="dashboardMainContent">
            <?php
            if (ADMIN_MAINTENANCE):
                echo "<div>";
                 Alert("ki-duotone ki-question","<b>IMPORTANTE:</b> O modo de manutenção está ativo. Somente usuários administradores podem ver o site assim!", "warning");
                echo "</div>";
            endif;
            ?>
            <header class="dashboard_header">
                <!-- Linha 1: Topo (título + ações) -->
                <div class="dashboard_header_top">
                    <div class="dashboard_header_left">
                        <button class="mobile_toggle_btn" id="mobileToggleBtn" aria-label="Open menu">
                           <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                        <!-- img depois -->
                    </div>

                    <div class="dashboard_header_right">

                   <!-- Botão de Troca de Tema Melhorado -->
<div class="dashboard_theme_switcher">
    <button class="dashboard_btn_theme" id="dashboardThemeToggle" aria-label="Alternar tema">
        <!-- Ícone Atual -->
        <span class="theme-icon-wrapper">
            <i class="ki-duotone ki-sun theme-icon active" data-theme="light">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                <span class="path4"></span><span class="path5"></span><span class="path6"></span>
            </i>
            
            <i class="ki-duotone ki-moon theme-icon" data-theme="dark">
                <span class="path1"></span><span class="path2"></span>
            </i>
            
            <i class="ki-duotone ki-screen theme-icon" data-theme="auto">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        
        <!-- Texto do Tema Atual -->
        <span class="theme-label" id="themeLabel">Claro</span>
        
        <!-- Seta de Dropdown -->
        <i class="ki-duotone ki-down dropdown-arrow">
            <span class="path1"></span>
        </i>
    </button>

    <!-- Dropdown do Tema -->
    <div class="dashboard_theme_dropdown" id="dashboardThemeDropdown">
        <ul>
            <li data-theme="light" class="theme-option active">
                <i class="ki-duotone ki-sun">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </i>
                <span class="option-text">
                    <strong>Claro</strong>
                    <small>Fundo claro, mais luminoso</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
            
            <li data-theme="dark" class="theme-option">
                <i class="ki-duotone ki-moon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="option-text">
                    <strong>Escuro</strong>
                    <small>Fundo escuro, reduz cansaço visual</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
            
            <li data-theme="auto" class="theme-option">
                <i class="ki-duotone ki-screen">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span>
                </i>
                <span class="option-text">
                    <strong>Sistema</strong>
                    <small>Segue preferência do dispositivo</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
        </ul>
    </div>
</div>
                         <!-- Dropdown do Tema -->
    <div class="dashboard_theme_dropdown" id="dashboardThemeDropdown">
        <ul>
            <li data-theme="light" class="theme-option active">
                <i class="ki-duotone ki-sun">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </i>
                <span class="option-text">
                    <strong>Claro</strong>
                    <small>Fundo claro, mais luminoso</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
            
            <li data-theme="dark" class="theme-option">
                <i class="ki-duotone ki-moon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="option-text">
                    <strong>Escuro</strong>
                    <small>Fundo escuro, reduz cansaço visual</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
            
            <li data-theme="auto" class="theme-option">
                <i class="ki-duotone ki-screen">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span>
                </i>
                <span class="option-text">
                    <strong>Sistema</strong>
                    <small>Segue preferência do dispositivo</small>
                </span>
                <i class="ki-duotone ki-check check-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </li>
        </ul>
    </div>
</div>
                        
                    </div>
                </div>

            </header>

            <?php
            //QUERY STRING
            if (!empty($getView)):
                $includepatch = __DIR__ . '/_sis/' . strip_tags(trim($getView)) . '.php';
            else:
                $includepatch = __DIR__ . '/_sis/' . 'dashboard.php';
            endif;

            if (file_exists(__DIR__ . "/_sis/" . strip_tags(trim($getView)) . '.php')):
                require_once __DIR__ . "/_sis/" . strip_tags(trim($getView)) . '.php';
            elseif (file_exists($includepatch)):
                require_once($includepatch);
            else:
                $_SESSION['trigger_controll'] = "<b>OPPSSS:</b> <span class='fontred'>_sis/{$getView}.php</span> ainda está em contrução!";
                header('Location: dashboard.php?wc=home');
                exit;
            endif;
            ?>
            
        </main>
        </div>
        <script>
            $(document).ready(function() {
                $('.js-select2').select2({
                    allowClear: true,
                    width: 'resolve' // ou '100%'
                });
            });
        </script>
</body>

</html>
<?php
ob_end_flush();
