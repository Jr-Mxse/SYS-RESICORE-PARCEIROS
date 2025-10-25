<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Minha Equipe</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Listagem de Integrantes da Equipe
        </p>
    </div>
    <div class="dashboard_header_search">
        <?php
        $Read->ExeRead(DB_USERS, "WHERE user_id_principal={$Admin["user_id"]}", "");
        if ($Read->getRowCount() == 0):
            echo "<a class='btn btn_green icon-plus a btn_xlarge btn_pulse' href='dashboard.php?wc=organizacao/home'><b>Criar uma Empresa primeiro</b></a>";
        elseif ($Read->getRowCount() == 1):
            echo "<a class='btn btn_green icon-plus a btn_xlarge btn_pulse j_ajaxModal' callback='Membro' callback_action='convite' callback_id='{$Read->getResult()[0]["user_id"]}'><b>Convidar Integrante</b></a>";
        else:
            echo "<a class='btn btn_green icon-plus a btn_xlarge btn_pulse' href='dashboard.php?wc=organizacao/home'>><b>Escolher Empresa</b></a>";
        endif;
        ?>
    </div>
</header>
<div class="dashboard_content">
    <?php
    $Delete->ExeDelete(DB_USERS, "WHERE user_id_principal={$Admin["user_id"]} AND user_name IS NULL", "");

    $vday = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime(date("Y-m-d H:i:s"))));
    $Delete->ExeDelete(DB_USERS, "WHERE (user_name IS NULL OR user_name='') AND user_registration<='{$vday}'", "");

    $apiTable = "table01";
    Datatable($apiTable, "", "[1, 'desc'],[2, 'asc']");
    ?>
    <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
        <thead>
            <tr role="row" style="background: #CCC">
                <th style="text-align: left;">Cadastro</th>
                <th style="text-align: left;">Empresa / Equipe</th>
                <th style="text-align: left;">Nome do Integrante</th>
                <th style="text-align: left;">E-mail</th>
                <th style="text-align: left;">Whatsapp</th>
                <th style="text-align: left;"></th>
            </tr>
        </thead>

        <body>
            <?php
            $Read->ExeRead(DB_USERS, "WHERE user_id_principal={$Admin["user_id"]}", "");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $Reg) :

                    $Read->ExeRead(DB_USERS, "WHERE user_associado={$Reg["user_id"]}", "");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $Reg2) :
                            extract($Reg2);

                            if ($user_cell):
                                $user_cell = str_replace(["(", ")", " ", "-"], "", $user_cell);
                                $user_cell = Check::Phone($user_cell);
                            elseif ($leads_telephone):
                                $user_cell = str_replace(["(", ")", " ", "-"], "", $leads_telephone);
                                $user_cell = Check::Phone($user_cell);
                            else:
                                $user_cell = "";
                            endif;
            ?>
                            <tr role="row">
                                <td style="text-align: left" data-sort="<?= strtotime($user_registration) ?>"><?= date("d/m/Y H:i", strtotime($user_registration)) ?></td>
                                <td style="text-align: left"><?= $Reg["user_name"] ?></td>
                                <td style="text-align: left"><?= $user_name ?></td>
                                <td style="text-align: left"><?= $user_email ?></td>
                                <td style="text-align: left"><?= $user_cell ?></td>
                                <td></td>
                            </tr>
            <?php
                        endforeach;
                    endif;
                endforeach;
            endif;
            ?>
        </body>
    </table>
</div>