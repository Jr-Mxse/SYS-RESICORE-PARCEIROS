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
        <a href="dashboard.php?wc=leads/create" class="btn btn_green btn_xlarge" title="Novo Registro"><i class="icon-plus icon-notext"></i> Adicionar Lead</a>
    </div>
</header>
<div class="dashboard_content">
    <?php
    $vday = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime(date("Y-m-d H:i:s"))));
    $Delete->ExeDelete(DB_LEADS, "WHERE (leads_name IS NULL OR leads_name='') AND leads_registration<='{$vday}'", "");

    $apiTable = "table01";
    Datatable($apiTable, "", "[1, 'desc'],[2, 'asc']", "[30,60,120,240]", ["print", "excel"], "");
    ?>
    <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
        <thead>
            <tr role="row" style="background: #CCC">
                <th style="text-align: left;">Cadastro</th>
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
            ?>
                    <tr role="row">
                        <td style="text-align: left" data-sort="<?= strtotime($leads_registration) ?>"><?= date("d/m/Y H:i", strtotime($leads_registration)) ?></td>
                        <td style="text-align: left"><?= $leads_name ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_terreno, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta2, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta3, '2', ',', '.') ?></td>
                        <td style="text-align: left" data-sort="<?= $leads_proposta ?>"><?= "R$ " . number_format($leads_proposta4, '2', ',', '.') ?></td>
                        <td>
                            <div class='fl_right'>
                                <a title="Editar" href="dashboard.php?wc=leads/create&id=<?= $leads_id ?>" class="post_single_center icon-notext icon-pencil btn  btn_green"></a>
                                <span rel='dashboard_header_search' callback="Leads" callback_action='delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $leads_id; ?>'></span>
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