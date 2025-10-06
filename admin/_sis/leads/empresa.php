<?php
$AdminLevel = LEVEL_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

$CnpjId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$RegId = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
if ($CnpjId):
    $Read->ExeRead(DB_CLIENTES_CNPJ, "WHERE cnpj_id = :id", "id={$CnpjId}");
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
    $NewReg = ['clientes_id' => $RegId, 'cnpj_name' => 'Nome da Empresa'];
    $Create->ExeCreate(DB_CLIENTES_CNPJ, $NewReg);
    header('Location: dashboard.php?wc=leads/empresa&id=' . $Create->getResult());
    exit;
else:
    $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['clientes_name']}</b>, você tentou editar um registro que não existe ou que foi removido recentemente!", E_USER_NOTICE);
    header('Location: dashboard.php?wc=leads/home');
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Empresa de <?= "{$clientes_name} {$clientes_lastname}"; ?></h1>
        <p class="dashboard_header_breadcrumbs">

            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/home">Leads</a>
            <span class="crumb">/</span>
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/create&id=<?= $clientes_id; ?>"><?= "{$clientes_name} {$clientes_lastname}"; ?></a>
            <span class="crumb">/</span>
            <?= $cnpj_name; ?>
        </p>
    </div>

    <div class="dashboard_header_search" style="font-size: 0.875em; margin-top: 16px;">
        <a class="btn btn_blue icon-undo2" title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=leads/create&id=<?= $clientes_id; ?>#empresas">Conta de <?= $clientes_name; ?></a>
    </div>

</header>

<div class="dashboard_content">
    <div class="box box70">
        <div class="panel_header default">
            <h2>Dados da Empresa</h2>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <form class="auto_save" name="clientes_add_CNPJess" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="cnpj_manage" />
                <input type="hidden" name="cnpj_id" value="<?= $CnpjId; ?>" />

                <div class="label_33">
                    <label class="label"></label>
                    <label class="label"></label>
                    <label class="label">
                        <span class="legend">CNPJ:</span>
                        <input name="cnpj_documento" value="<?= $cnpj_documento; ?>" required class="formCnpj" />
                    </label>
                </div>

                <label class="label">
                    <span class="legend">Razão Social:</span>
                    <input name="cnpj_name" value="<?= $cnpj_name; ?>" required />
                </label>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">E-mail:</span>
                        <input value="<?= $cnpj_email; ?>" type="email" name="cnpj_email" />
                    </label>

                    <label class="label">
                        <span class="legend">Telefone:</span>
                        <input value="<?= $cnpj_telefone; ?>" class="formPhone" type="text" name="cnpj_telefone" />
                    </label>
                </div>

                <label class="label">
                    <span class="legend">Observações:</span>
                    <textarea class='work_mce_basic' name='cnpj_content' rows='8'></textarea>
                </label>

                <p>&nbsp;</p>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
                <div class="clear"></div>
            </form>
        </div>
        <br>
        <div class="panel_header default">
            <h2>Responsável Financeiro</h2>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <form class="auto_save" name="clientes_add_CNPJess" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="cnpj_manage" />
                <input type="hidden" name="cnpj_id" value="<?= $CnpjId; ?>" />

                <div class="label_33">
                    <label class="label"></label>
                    <label class="label"></label>
                    <label class="label">
                        <span class="legend">CPF:</span>
                        <input name="cnpj_responsavel_documento" value="<?= $cnpj_responsavel_documento; ?>" required class="formCpf" />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Nome do Responsável:</span>
                        <input name="cnpj_responsavel_name" value="<?= $cnpj_responsavel_name; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">Estado Civil:</span>
                        <select name="cnpj_responsavel_civil">
                            <option selected disabled value="">Selecione o Estado Civil:</option>
                            <option value="1" <?= ($cnpj_responsavel_civil == 1 ? 'selected="selected"' : ''); ?>>Solteiro</option>
                            <option value="2" <?= ($cnpj_responsavel_civil == 2 ? 'selected="selected"' : ''); ?>>Casado</option>
                            <option value="3" <?= ($cnpj_responsavel_civil == 3 ? 'selected="selected"' : ''); ?>>Viúvo</option>
                            <option value="4" <?= ($cnpj_responsavel_civil == 4 ? 'selected="selected"' : ''); ?>>Divorciado</option>
                            <option value="5" <?= ($cnpj_responsavel_civil == 5 ? 'selected="selected"' : ''); ?>>Separado</option>
                        </select>
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">E-mail:</span>
                        <input value="<?= $cnpj_responsavel_email; ?>" type="email" name="cnpj_responsavel_email" />
                    </label>
                    <label class="label">
                        <span class="legend">Celular:</span>
                        <input value="<?= $cnpj_responsavel_telefone; ?>" class="formPhone" type="text" name="cnpj_responsavel_telefone" />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Profissao:</span>
                        <input name="cnpj_responsavel_profissao" value="<?= $cnpj_responsavel_profissao; ?>" />
                    </label>
                </div>

                <p>&nbsp;</p>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
                <div class="clear"></div>
            </form>
        </div>
        <br>
        <div class="panel_header default">
            <h2>Endereço</h2>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <form class="auto_save" name="clientes_add_CNPJess" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="cnpj_manage" />
                <input type="hidden" name="cnpj_id" value="<?= $CnpjId; ?>" />

                <div class="label_33">
                    <label class="label"></label>
                    <label class="label"></label>
                    <label class="label">
                        <span class="legend">CEP:</span>
                        <input name="cnpj_zipcode" value="<?= $cnpj_zipcode; ?>" class="formCep wc_getCep" placeholder="Informe o CEP:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Rua:</span>
                        <input class="wc_logradouro" name="cnpj_street" value="<?= $cnpj_street; ?>" placeholder="Nome da Rua:" required />
                    </label>
                    <label class="label">
                        <span class="legend">Número:</span>
                        <input name="cnpj_number" value="<?= $cnpj_number; ?>" placeholder="Número:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Complemento:</span>
                        <input class="wc_complemento" name="cnpj_complement" value="<?= $cnpj_complement; ?>" placeholder="Ex: Casa, Apto, Etc:" />
                    </label>
                    <label class="label">
                        <span class="legend">Bairro:</span>
                        <input class="wc_bairro" name="cnpj_district" value="<?= $cnpj_district; ?>" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label">
                        <span class="legend">Cidade:</span>
                        <input class="wc_localidade" name="cnpj_city" value="<?= $cnpj_city; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">Estado (UF):</span>
                        <input class="wc_uf" name="cnpj_state" value="<?= $cnpj_state; ?>" maxlength="2" required />
                    </label>
                    <label class="label">
                        <span class="legend">País:</span>
                        <input name="cnpj_country" value="<?= ($cnpj_country ? $cnpj_country : 'Brasil'); ?>" required />
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