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
        <a href="dashboard.php?wc=leads/create" class="btn btn_blue btn_xlarge btn_pulse" title="Novo Registro"><i class="icon-plus icon-notext"></i> Adicionar Clientes / Leads</a>
    </div>
</header>
<div class="dashboard_content">
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
            $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = :id", "id={$Admin['user_id']}");
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
                                <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $leads_id ?>" class="post_single_center icon-notext icon-eye btn btn_blue"></a>
                                <?php if ($leads_status == 1): ?>
                                    <a class="btn btn_red icon-notext icon-bin btn-rounded j_swal_action wc_tooltip" callback="Leads" callback_action="delete" data-confirm-text="Cliente Perdido" data-confirm-message="Ao confirmar essa ação o seu Cliente / Lead será considerado Perdido! Quer mesmo continuar? " id="<?= $leads_id ?>"><span>Cliente Perdido</span></a>
                                <?php endif; ?>
                                <?php if ($leads_status == 0): ?>
                                    <a class="btn btn_green icon-notext icon-checkmark btn-rounded j_swal_action wc_tooltip" callback="Leads" callback_action="reativar" data-confirm-text="Cliente Ativado" data-confirm-message="Ao confirmar essa ação o seu Cliente / Lead será considerado reativado e Aberto! Quer mesmo continuar? " id="<?= $leads_id ?>"><span>Cliente Aberto</span></a>
                                <?php endif; ?>
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