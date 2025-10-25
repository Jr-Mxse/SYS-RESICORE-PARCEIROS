<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-office">Minhas Empresas</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Listagem de Empresas ou Equipe
        </p>
    </div>
    <div class="dashboard_header_search">
        <a href="dashboard.php?wc=organizacao/create" class="btn btn_green icon-plus a  btn_xlarge btn_pulse" title="Nova Empresa"> Nova Empresa ou Equipe</a>
    </div>
</header>
<div class="dashboard_content">
    <?php
    $vday = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime(date("Y-m-d H:i:s"))));
    $Delete->ExeDelete(DB_USERS, "WHERE (user_name IS NULL OR user_name='') AND user_registration<='{$vday}'", "");

    $apiTable = "table01";
    Datatable($apiTable, "", "[1, 'desc'],[2, 'asc']");
    ?>
    <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
        <thead>
            <tr role="row" style="background: #CCC">
                <th style="text-align: left;">Cadastro</th>
                <th style="text-align: left;">Nome da Empresa</th>
                <th style="text-align: left;">Quantidade de Membros</th>
                <th style="text-align: left;">Quantidade de Leads</th>
                <th style="text-align: left;"></th>
            </tr>
        </thead>

        <body>
            <?php
            $Read->ExeRead(DB_USERS, "WHERE user_id_principal={$Admin["user_id"]}", "");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Reg) :
                    extract($Reg);
            ?>
                    <tr role="row">
                        <td style="text-align: left" data-sort="<?= strtotime($user_registration) ?>"><?= date("d/m/Y H:i", strtotime($user_registration)) ?></td>
                        <td style="text-align: left"><?= $user_name ?></td>
                        <td style="text-align: left"></td>
                        <td style="text-align: left"></td>
                        <td>
                            <div class='fl_right'>
                                <a title="Editar" href="dashboard.php?wc=organizacao/create&id=<?= $user_id ?>" class="post_single_center icon-notext icon-eye btn  btn_green"></a>
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