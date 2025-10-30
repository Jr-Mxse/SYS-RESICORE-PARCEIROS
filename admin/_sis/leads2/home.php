<?php
$Filtro = filter_input(INPUT_GET, 'fil', FILTER_DEFAULT) ? explode("-", filter_input(INPUT_GET, 'fil', FILTER_DEFAULT)) : "";
$sqlWhere = "";

if (!isset($Filtro[0])):
    $Filtro[0] = 0;
endif;
if (!isset($Filtro[1])):
    $Filtro[1] = 0;
endif;
if (!isset($Filtro[2])):
    $Filtro[2] = 0;
endif;

if ($Filtro[0]):
    $sqlWhere .= "leads_status='0'";
endif;

if ($Filtro[1]):
    $sqlWhere .= $sqlWhere ? " OR " : "";
    $sqlWhere .= "leads_status='1'";
endif;

if ($Filtro[2]):
    $sqlWhere .= $sqlWhere ? " OR " : "";
    $sqlWhere .= "leads_status='2'";
endif;

if (empty($sqlWhere)):
    $sqlWhere = "leads_status IN ('0','1','2')";
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
        <a href="" class="btn btn_blue btn_xlarge btn_pulse" class="jOpenWizard"
            data-wizard="open"
            data-wizard-target="#wizardModal" data-wizard-redirect="" title="Meu Perfil" data-tooltip="Meu Perfil" title="Novo Registro"><i class="icon-plus icon-notext"></i> Clientes / Leads</a>
    </div>
</header>
<div class="dashboard_content">
    <!-- Filtros Modernizados -->
    <div class="filter-container">
        <div class="filter-header">
            <div class="filter-title">
                <i class="icon-filter"></i>
                <h3>Filtros</h3>
            </div>
            <button type="button" class="btn-clear-filters" onclick="limparFiltros()" style="display: <?= ($Filtro[0] || $Filtro[1] || $Filtro[2]) ? 'inline-flex' : 'none' ?>;">
                <i class="icon-refresh"></i> Limpar
            </button>
        </div>

        <form class="filter-form j_tab_home" name="user_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="filtro" />

            <div class="filter-options">
                <label class="filter-checkbox <?= $Filtro[0] ? 'active' : '' ?>">
                    <input type="checkbox" name="perdido" value="1" <?= $Filtro[0] ? "checked" : "" ?>>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">
                        <i class="icon-close-circle"></i>
                        Perdido
                    </span>
                    <span class="filter-badge status-perdido"><?php
                        $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = :id AND leads_status='0'", "id={$Admin['user_id']}");
                        echo $Read->getRowCount();
                    ?></span>
                </label>

                <label class="filter-checkbox <?= $Filtro[1] ? 'active' : '' ?>">
                    <input type="checkbox" name="aberto" value="1" <?= $Filtro[1] ? "checked" : "" ?>>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">
                        <i class="icon-time"></i>
                        Aberto
                    </span>
                    <span class="filter-badge status-aberto"><?php
                        $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = :id AND leads_status='1'", "id={$Admin['user_id']}");
                        echo $Read->getRowCount();
                    ?></span>
                </label>

                <label class="filter-checkbox <?= $Filtro[2] ? 'active' : '' ?>">
                    <input type="checkbox" name="ganho" value="1" <?= $Filtro[2] ? "checked" : "" ?>>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">
                        <i class="icon-check-circle"></i>
                        Ganho
                    </span>
                    <span class="filter-badge status-ganho"><?php
                        $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = :id AND leads_status='2'", "id={$Admin['user_id']}");
                        echo $Read->getRowCount();
                    ?></span>
                </label>
            </div>

            <div class="filter-actions">
                <img class="form_load none" alt="Carregando..." title="Carregando..." src="_img/load.gif" />
                <button type="submit" name="public" value="1" class="btn btn-apply">
                    <i class="icon-check"></i>
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>
    <br><br>


    <?php
    $vday = date('Y-m-d H:i:s', strtotime('-10 minutes'));
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
                        $leads_status_txt = "<span class='font_blue'><b>Aberto</b></span>";
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
                                <a href="javascript:void(0)" class="jViewLead post_single_center icon-eye btn btn_gray" data-id="<?= $leads_id ?>"  style="margin: 2px"> Visualizar</a>
                                <a href="javascript:void(0)" class="jEditLead post_single_center icon-pencil btn btn_blue" data-id="<?= $leads_id ?>" style="margin: 2px"> Editar</a>
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

<script>
    function limparFiltros() {
        document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('.filter-checkbox').forEach(label => {
            label.classList.remove('active');
        });
        window.location.href = window.location.pathname + '?wc=leads/home';
    }

    document.querySelectorAll('.filter-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.filter-checkbox');
            if (this.checked) {
                label.classList.add('active');
            } else {
                label.classList.remove('active');
            }
            const anyChecked = document.querySelectorAll('.filter-checkbox input:checked').length > 0;
            document.querySelector('.btn-clear-filters').style.display = anyChecked ? 'inline-flex' : 'none';
        });
    });
</script>