<?php
$AdminLevel = LEVEL_CLIENTES;
if (!APP_CLIENTES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa serviço!</div>');
endif;

$RegId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($RegId):
    $Read->ExeRead(DB_CLIENTES, "WHERE clientes_id = :id", "id={$RegId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['clientes_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=leads/home');
        exit;
    endif;
else:
    $RegCreate = ['clientes_registration' => date('Y-m-d H:i:s'), 'clientes_status' => 0];
    $Create->ExeCreate(DB_CLIENTES, $RegCreate);
    $clientes_id = $Create->getResult();
    if ($Create->getResult()):
        $Create->ExeCreate(DB_CLIENTES_ADDR, ["clientes_id" =>  $clientes_id, "addr_name" => "Endereço residencial atual"]);
        $Create->ExeCreate(DB_CLIENTES_ADDR, ["clientes_id" =>  $clientes_id, "addr_name" => "Endereço serviço construção"]);
        header("Location: dashboard.php?wc=leads/create&id={$clientes_id}");
        exit;
    endif;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Leads</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/home">Leads</a>
            <span class="crumb">/</span>
            <?= $clientes_name ? $clientes_name : "Novo Lead" ?>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;" id="<?= $RegId; ?>">
        <span rel='dashboard_header_search' class='j_delete_action icon-warning btn btn_red' id='<?= $RegId; ?>'>Deletar</span>
        <span rel='dashboard_header_search' callback="Leads" callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='<?= $RegId; ?>'>EXCLUIR AGORA!</span>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
        <?php
        require("inc.profile.php");
        require("inc.profile2.php");
        require("inc.address.php");
        require("inc.empresas.php");
        require("inc.documentos.php");
        require("inc.galeria.php");
        require("inc.contratos.php");
        require("inc.links.php");
        ?>
    </div>

    <div class="box box30">
        <div class="avatar_union">
            <?php
            $ImageLead = (file_exists("../uploads/{$clientes_thumb}") && !is_dir("../uploads/{$clientes_thumb}") ? "uploads/{$clientes_thumb}" : 'admin/_img/no_avatar.jpg');
            $ImageConjuge = (file_exists("../uploads/{$conjuge_thumb}") && !is_dir("../uploads/{$conjuge_thumb}") ? "uploads/{$conjuge_thumb}" : 'admin/_img/no_avatar.jpg');
            ?>

            <div class="avatar">
                <img class="clientes_thumb" src="../tim.php?src=<?= $ImageLead; ?>&w=400&h=400" alt="Lead" title="Lead">
            </div>

            <div class="avatar overlap">
                <img class="conjuge_thumb" src="../tim.php?src=<?= $ImageConjuge; ?>&w=400&h=400" alt="Cônjuge" title="Cônjuge">
            </div>
        </div>

        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <div class="box_conf_menu modern_light_menu">
                <a class='conf_menu wc_tab wc_active' href='#profile'>
                    <i class="icon-user"></i>
                    Dados Pessoais
                </a>
                <a class='conf_menu wc_tab' href='#profile2'>
                    <i class="icon-heart"></i>
                    Dados Cônjuge
                </a>
                <a class='conf_menu wc_tab' href='#links'>
                    <i class="icon-link"></i>
                    Links Úteis
                </a>
                <a class='conf_menu wc_tab' href='#address'>
                    <i class="icon-location"></i>
                    Endereços
                </a>
                <a class='conf_menu wc_tab' href='#empresas'>
                    <i class="icon-office"></i>
                    Empresas
                </a>
                <a class='conf_menu wc_tab' href='#contratos'>
                    <i class="icon-fire"></i>
                    Serviços Contratados
                </a>
                <a class='conf_menu wc_tab' href='#documentos'>
                    <i class="icon-file-text"></i>
                    Documentos
                </a>
                <a class='conf_menu wc_tab' href='#galeria'>
                    <i class="icon-images"></i>
                    Galeria
                </a>
            </div>

        </div>
    </div>
</div>

<style>
    .avatar_union {
        display: flex;
        align-items: center;
        position: relative;
        gap: 0;
        background: #eee;
        padding: 20px;
    }

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar.overlap {
        margin-left: -20px;
        z-index: 2;
    }
</style>