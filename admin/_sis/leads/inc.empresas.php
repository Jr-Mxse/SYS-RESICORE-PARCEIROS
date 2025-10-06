<article class="box box100 wc_tab_target" id="empresas" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <span>
            <a href="dashboard.php?wc=leads/empresa&user=<?= $clientes_id; ?>" class="btn btn_green icon-plus a icon-notext"></a>
        </span>
        <h2>Empresas </h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <?php
        $apiTable = "table02";
        Datatable($apiTable, "", "", "[30,60,120,240]", ["print", "excel"], "");
        ?>
        <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
            <thead>
                <tr role="row" style="background: #CCC">
                    <th style="text-align: left;">Atualização</th>
                    <th style="text-align: left;">CNPJ</th>
                    <th style="text-align: left;">Empresa</th>
                    <th style="text-align: left;">Responsável</th>
                    <th style="text-align: left;"></th>
                </tr>
            </thead>

            <body>
                <?php
                $Read->ExeRead(DB_CLIENTES_CNPJ, "WHERE clientes_id = :user ORDER BY cnpj_key DESC, cnpj_name ASC", "user={$clientes_id}");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Reg) :
                        extract($Reg);

                ?>
                        <tr role="row">
                            <td style="text-align: left" data-sort="<?= strtotime($cnpj_update) ?>"><?= date("d/m/Y", strtotime($cnpj_update)) ?></td>
                            <td style="text-align: left; width: 15% !important;"><?= $cnpj_documento ?></td>
                            <td style="text-align: left; width: 25% !important;"><?= $cnpj_name ?></td>
                            <td style="text-align: left; width: 25% !important;"><?= $cnpj_responsavel_name ?></td>
                            <td>
                                <div class='fl_right'>
                                    <a title="Editar" href="dashboard.php?wc=leads/empresa&id=<?= $cnpj_id ?>" class="post_single_center icon-notext icon-pencil btn btn_green"></a>
                                    <span rel='dashboard_header_search' callback="Leads" callback_action='cnpj_delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $cnpj_id; ?>'></span>
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
</article>