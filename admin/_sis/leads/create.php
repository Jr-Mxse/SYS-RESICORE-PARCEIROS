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

$RegTp = filter_input(INPUT_GET, 'tp', FILTER_DEFAULT);
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
        <?php if ($RegTp != "editar"): ?>
            <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $leads_id ?>&tp=editar" class="post_single_center icon-pencil btn btn_blue" style="margin: 2px"> Editar</a>
        <?php endif; ?>
        <?php if ($leads_status == 1): ?>
            <a class="btn btn_red icon-notext icon-bin btn-rounded j_swal_action" callback="Leads" callback_action="delete" data-confirm-text="Cliente Perdido" data-confirm-message="Ao confirmar essa ação o seu Cliente / Lead será considerado Perdido! Quer mesmo continuar? " id="<?= $leads_id ?>"> Cliente Perdido</a>
        <?php endif; ?>
        <?php if ($leads_status == 0): ?>
            <a class="btn btn_green icon-notext icon-checkmark btn-rounded j_swal_action" callback="Leads" callback_action="reativar" data-confirm-text="Cliente Ativado" data-confirm-message="Ao confirmar essa ação o seu Cliente / Lead será considerado reativado e Aberto! Quer mesmo continuar? " id="<?= $leads_id ?>"> Cliente Aberto</a>
        <?php endif; ?>
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