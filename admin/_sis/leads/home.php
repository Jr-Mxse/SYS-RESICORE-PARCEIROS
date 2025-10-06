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
                <th style="text-align: left;">ID</th>
                <th style="text-align: left;">Nome</th>
                <th style="text-align: left;">E-mail</th>
                <th style="text-align: left;">Telefone</th>
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
            ?>
                    <tr role="row">
                        <td style="text-align: left" data-sort="<?= strtotime($leads_registration) ?>"><?= date("d/m/Y", strtotime($leads_registration)) ?></td>
                        <td style="text-align: left"><?= $leads_id ?></td>
                        <td style="text-align: left"><?= $leads_name ?></td>
                        <td style="text-align: left"><?= $leads_email ?></td>
                        <td style="text-align: left"><?= $leads_cell ?></td>
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