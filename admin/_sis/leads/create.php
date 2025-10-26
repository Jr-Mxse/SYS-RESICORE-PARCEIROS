<?php
$RegId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($RegId):
    $Read->ExeRead(DB_LEADS, "WHERE leads_id = :id AND parceiros_id = :parceiros_id", "id={$RegId}&parceiros_id={$Admin['user_id']}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['leads_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=leads/home');
        exit;
    endif;
else:
    $RegCreate = [
        'leads_registration' => date('Y-m-d H:i:s'),
        'leads_status' => 1,
        'parceiros_id' => $Admin['user_id']
    ];
    $Create->ExeCreate(DB_LEADS, $RegCreate);
    $leads_id = $Create->getResult();
    if ($Create->getResult()):
        header("Location: dashboard.php?wc=leads/create&id={$leads_id}");
        exit;
    endif;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user-plus">Clientes / Leads</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/home">Clientes / Leads</a>
            <span class="crumb">/</span>
            <?= $leads_name ? $leads_name : "Novo Cliente / Lead" ?>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;" id="<?= $RegId; ?>">
        <span rel='dashboard_header_search' class='j_delete_action icon-warning btn btn_red btn_xlarge' id='<?= $RegId; ?>'>Deletar</span>
        <span rel='dashboard_header_search' callback="Leads" callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow btn_xlarge' style='display: none' id='<?= $RegId; ?>'>EXCLUIR AGORA!</span>
    </div>
</header>

<div class="dashboard_content">
    <div class="box box70">
        <?php
        require("inc.profile.php");
        ?>
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