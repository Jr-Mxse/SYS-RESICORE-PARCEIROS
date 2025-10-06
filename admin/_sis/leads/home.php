<?php
$AdminLevel = LEVEL_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) :
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Leads</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Listagem
        </p>
    </div>
    <div class="dashboard_header_search">
        <a href="dashboard.php?wc=leads/create" class="btn btn_green icon-plus a icon-notext" title="Novo Registro"></a>
    </div>
</header>
<div class="dashboard_content">
    <?php
    $apiTable = "table01";
    Datatable($apiTable, "", "[1, 'desc'],[2, 'asc']", "[30,60,120,240]", ["print", "excel"], "");
    ?>
    <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
        <thead>
            <tr role="row" style="background: #CCC">
                <th style="text-align: left;">Cadastro</th>
                <th style="text-align: left;">Código</th>
                <th style="text-align: left;">Responsável</th>
                <th style="text-align: left;">Empresa(s)</th>
                <th style="text-align: left;">E-mail</th>
                <th style="text-align: left;">Telefone</th>
                <th style="text-align: left;"></th>
            </tr>
        </thead>

        <body>
            <?php
            $Read->ExeRead(DB_CLIENTES);
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Reg) :
                    extract($Reg);

                    if ($clientes_cell):
                        $clientes_cell = Check::Phone($clientes_cell);
                    elseif ($clientes_telephone):
                        $clientes_cell = Check::Phone($clientes_telephone);
                    else:
                        $clientes_cell = "";
                    endif;
                    if ($clientes_lastname):
                        $clientes_name .=  " " . $clientes_lastname;
                    endif;

                    $empresa_name = "";
                    $Read->ExeRead(DB_CLIENTES_CNPJ, "WHERE clientes_id = :id", "id={$clientes_id}");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $Reg2) :
                            extract($Reg2);
                            $empresa_name .= $cnpj_name . "<br>";
                        endforeach;
                    endif;

                    $status = ($clientes_status ? 'btn_green' : 'btn_gray');
            ?>
                    <tr role="row">
                        <td style="text-align: left" data-sort="<?= strtotime($clientes_registration) ?>"><?= date("d/m/Y", strtotime($clientes_registration)) ?></td>
                        <td style="text-align: left"><?= $clientes_code ?></td>
                        <td style="text-align: left"><?= $clientes_name ?></td>
                        <td style="text-align: left"><?= $empresa_name ?></td>
                        <td style="text-align: left"><?= $clientes_email ?></td>
                        <td style="text-align: left"><?= $clientes_cell ?></td>
                        <td>
                            <div class='fl_right'>
                                <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $clientes_id ?>" class="post_single_center icon-notext icon-pencil btn  <?= $status ?>"></a>
                                <span rel='dashboard_header_search' callback="Leads" callback_action='delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $clientes_id; ?>'></span>
                            </div>
                        </td>
                    </tr>
            <?php
                endforeach;
            endif;
            ?>
        </body>
    </table>
</div>