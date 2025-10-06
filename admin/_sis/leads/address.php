<?php
$AdminLevel = LEVEL_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

$AddrId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$RegId = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
if ($AddrId):
    $Read->ExeRead(DB_CLIENTES_ADDR, "WHERE addr_id = :id", "id={$AddrId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);

        $Read->ExeRead(DB_CLIENTES, "WHERE clientes_id = :user", "user={$clientes_id}");
        if ($Read->getResult()):
            extract($Read->getResult()[0]);
        else:
            $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['clientes_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
            header('Location: dashboard.php?wc=leads/home');
            exit;
        endif;
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['clientes_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
        header('Location: dashboard.php?wc=leads/home');
        exit;
    endif;
elseif ($RegId):
    $NewReg = ['clientes_id' => $RegId, 'addr_name' => 'Endereço Principal'];
    $Create->ExeCreate(DB_CLIENTES_ADDR, $NewReg);
    header('Location: dashboard.php?wc=leads/address&id=' . $Create->getResult());
    exit;
else:
    $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['clientes_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=leads/home');
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Endereço de <?= "{$clientes_name} {$clientes_lastname}"; ?></h1>
        <p class="dashboard_header_breadcrumbs">

            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/home">Leads</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/create&id=<?= $clientes_id; ?>"><?= "{$clientes_name} {$clientes_lastname}"; ?></a>
            <span class="crumb">/</span>
            <?= $addr_name; ?>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn btn_blue icon-undo2" title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/create&id=<?= $clientes_id; ?>#address">Conta de <?= $clientes_name; ?></a>
    </div>

</header>

<div class="dashboard_content">
    <div class="box box70">
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <form class="auto_save" name="clientes_add_address" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="addr_manage" />
                <input type="hidden" name="addr_id" value="<?= $AddrId; ?>" />

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend"><b>Nome do Endereço:</b></span>
                        <input name="addr_name" value="<?= $addr_name; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">CEP:</span>
                        <input name="addr_zipcode" value="<?= $addr_zipcode; ?>" class="formCep wc_getCep" placeholder="Informe o CEP:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Rua:</span>
                        <input class="wc_logradouro" name="addr_street" value="<?= $addr_street; ?>" placeholder="Nome da Rua:" required />
                    </label>
                    <label class="label">
                        <span class="legend">Número:</span>
                        <input name="addr_number" value="<?= $addr_number; ?>" placeholder="Número:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Complemento:</span>
                        <input class="wc_complemento" name="addr_complement" value="<?= $addr_complement; ?>" placeholder="Ex: Casa, Apto, Etc:" />
                    </label>
                    <label class="label">
                        <span class="legend">Bairro:</span>
                        <input class="wc_bairro" name="addr_district" value="<?= $addr_district; ?>" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label">
                        <span class="legend">Cidade:</span>
                        <input class="wc_localidade" name="addr_city" value="<?= $addr_city; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">Estado (UF):</span>
                        <input class="wc_uf" name="addr_state" value="<?= $addr_state; ?>" maxlength="2" required />
                    </label>

                    <label class="label">
                        <span class="legend">País:</span>
                        <input name="addr_country" value="<?= ($addr_country ? $addr_country : 'Brasil'); ?>" required />
                    </label>
                </div>

                <p>&nbsp;</p>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
    <div class="box box30">
        <?php
        $Image = (file_exists("../uploads/{$clientes_thumb}") && !is_dir("../uploads/{$clientes_thumb}") ? "uploads/{$clientes_thumb}" : 'admin/_img/no_avatar.jpg');
        ?>
        <img class="clientes_thumb" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=400&h=400" alt="" title="" />
    </div>
</div>