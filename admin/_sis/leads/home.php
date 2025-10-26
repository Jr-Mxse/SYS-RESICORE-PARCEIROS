<?php
$Filtro = filter_input(INPUT_GET, 'fil', FILTER_DEFAULT) ? explode("-", filter_input(INPUT_GET, 'fil', FILTER_DEFAULT)) : "";
$sqlWhere = "";

if (!isset($Filtro[0])):
    $Filtro[0] = 1;
endif;
if (!isset($Filtro[1])):
    $Filtro[1] = 1;
endif;
if (!isset($Filtro[2])):
    $Filtro[2] = 1;
    $Filtro[20] = 1;
endif;

if ($Filtro[0]):
    $sqlWhere .= " leads_status='0'";
endif;

if ($Filtro[1]):
    $sqlWhere .= $sqlWhere ? " OR " : "";
    $sqlWhere .= "leads_status='1'";
endif;

if ($Filtro[2]):
    $sqlWhere .= $sqlWhere ? " OR " : "";
    $sqlWhere .= "leads_status='2'";
endif;
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user-plus">Clientes / Leads</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Listagem de Clientes / Leads
        </p>
    </div>
    <div class="dashboard_header_search">
        <a href="dashboard.php?wc=leads/create" class="btn btn_blue btn_xlarge btn_pulse" title="Novo Registro"><i class="icon-plus icon-notext"></i> Clientes / Leads</a>
    </div>
</header>
<div class="dashboard_content">
    <div class="dashboard_header_search">
        <div class="panel_header default">
            <h2>Filtros para Seleção</h2>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px">
            <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="filtro" />

                <div class="label_33" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;">
                    <label class="label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="perdido" style="margin:0;" value="1" <?= $Filtro[0] ? "checked" : "" ?>>
                        <span class="legend" style="line-height:1.2;">Perdido</span>
                    </label>

                    <label class="label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="aberto" style="margin:0;" value="1" <?= $Filtro[1] ? "checked" : "" ?>>
                        <span class="legend" style="line-height:1.2;">Aberto</span>
                    </label>

                    <label class="label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="ganho" style="margin:0;" value="1" <?= $Filtro[2] ? "checked" : "" ?>>
                        <span class="legend" style="line-height:1.2;">Ganho</span>
                    </label>
                </div>

                <div class="clear"></div>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right" style="margin-left: 5px;">Filtrar</button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
    <br><br>


    <?php
    $vday = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime(date("Y-m-d H:i:s"))));
    $Delete->ExeDelete(DB_LEADS, "WHERE (leads_name IS NULL OR leads_name='') AND leads_registration<='{$vday}'", "");

    $apiTable = "table01";
    Datatable($apiTable, "", "[1, 'desc'],[2, 'asc']");
    ?>
    <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
        <thead>
            <tr role="row" style="background: #CCC">
                <th style="text-align: left;">Status</th>
                <th style="text-align: left;">Nome</th>
                <th style="text-align: left;">Previsão<br>Negócio</th>
                <th style="text-align: left;">Previsão<br>Terreno</th>
                <th style="text-align: left;">Indicação</th>
                <th style="text-align: left;">Ind. + Acomp.</th>
                <th style="text-align: left;">Ind. e Fechamento</th>
                <th style="text-align: left;"></th>
            </tr>
        </thead>

        <body>
            <?php
            $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = :id AND ({$sqlWhere})", "id={$Admin['user_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Reg) :
                    extract($Reg);

                    if ($leads_cell):
                        $leads_cell = Check::Phone($leads_cell);
                    elseif ($leads_telephone):
                        $leads_cell = Check::Phone($leads_telephone);
                    else:
                        $leads_cell = "";
                    endif;
                    if ($leads_lastname):
                        $leads_name .=  " " . $leads_lastname;
                    endif;

                    $leads_terreno = (20 * $leads_proposta / 100);

                    $leads_proposta2 = (0.48 * ($leads_proposta - $leads_terreno) / 100);
                    $leads_proposta3 = (1.5 * ($leads_proposta - $leads_terreno) / 100);
                    $leads_proposta4 = (6 * ($leads_proposta - $leads_terreno) / 100);

                    switch ($leads_status):
                        case 0:
                            $leads_status_txt = "<span style='color: red'><b>Perdido</b></span>";
                            break;
                        case 1:
                            $leads_status_txt = "Aberto";
                            break;
                        case 2:
                            $leads_status_txt = "<span style='color: green'><b>Ganho</b></span>";
                            break;
                    endswitch;
            ?>
                    <tr role="row">
                        <td style="text-align: left"><?= $leads_status_txt ?></td>
                        <td style="text-align: left"><?= $leads_name ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_terreno, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta2, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta3, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta4, '2', ',', '.') ?></td>
                        <td>
                            <div class='fl_left'>
                                <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $leads_id ?>" class="post_single_center icon-eye btn btn_gray" style="margin: 2px"> Visualizar</a>
                                <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $leads_id ?>&tp=editar" class="post_single_center icon-pencil btn btn_blue" style="margin: 2px"> Editar</a>
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