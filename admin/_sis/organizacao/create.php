<?php
$RegId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($RegId):
    $Read->ExeRead(DB_USERS, "WHERE user_id = {$RegId} AND user_id_principal={$Admin["user_id"]}", "");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=organizacao/home');
        exit;
    endif;
else:
    $RegCreate = [
        'user_registration' => date('Y-m-d H:i:s'),
        'user_status' => 1,
        'user_id_principal' => $Admin['user_id'],
        "user_level" => 20
    ];
    $Create->ExeCreate(DB_USERS, $RegCreate);
    $user_id = $Create->getResult();
    if ($Create->getResult()):
        header("Location: dashboard.php?wc=organizacao/create&id={$user_id}");
        exit;
    endif;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-office">Minhas Empresas</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=organizacao/home">Empresas ou Equipes</a>
            <span class="crumb">/</span>
            <?= $user_name ? $user_name : "Nova Empresa ou Equipe" ?>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;" id="<?= $RegId; ?>">
        <span rel='dashboard_header_search' class='j_delete_action icon-warning btn btn_red' id='<?= $RegId; ?>'>Deletar</span>
        <span rel='dashboard_header_search' callback="Organizacao" callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='<?= $RegId; ?>'>EXCLUIR AGORA!</span>
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