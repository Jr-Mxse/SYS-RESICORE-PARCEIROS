<article class="box box100 wc_tab_target" id="contratos" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <span>
            <a href="dashboard.php?wc=contratos/create&user=<?= $clientes_id; ?>" class="btn btn_green icon-plus a icon-notext"></a>
        </span>
        <h2>Serviços Contratados</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <?php
        $apiTable = "table07";
        Datatable($apiTable, "", "", "[30,60,120,240]", ["print", "excel"], "");
        ?>
        <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
            <thead>
                <tr role="row" style="background: #CCC">
                    <th style="text-align: left;">Data</th>
                    <th style="text-align: left;">Código</th>
                    <th style="text-align: left;">Contratante</th>
                    <th style="text-align: left;">Serviço</th>
                    <th style="text-align: left;">Valor</th>
                    <th style="text-align: left;"></th>
                </tr>
            </thead>

            <body>
                <?php
                $Read->ExeRead(DB_CLIENTES_CONTRATOS, "WHERE clientes_id = :user ORDER BY cont_data ASC", "user={$clientes_id}");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Reg) :
                        extract($Reg);
                        $servicos_name = "";
                        $cnpj_name = "";

                        if ($servicos_id):
                            $Read->ExeRead(DB_SERVICOS, "WHERE servicos_id = :id", "id={$servicos_id}");
                            if ($Read->getResult()):
                                $servicos_name = $Read->getResult()[0]['servicos_title'];
                            endif;
                        endif;

                        if ($cnpj_id):
                            $Read->ExeRead(DB_CLIENTES_CNPJ, "WHERE cnpj_id = :id", "id={$cnpj_id}");
                            if ($Read->getResult()):
                                $cnpj_name = $Read->getResult()[0]['cnpj_name'];
                            endif;
                        else:
                            $Read->ExeRead(DB_CLIENTES, "WHERE clientes_id = :id", "id={$clientes_id}");
                            if ($Read->getResult()):
                                $cnpj_name = $Read->getResult()[0]['clientes_name'] . " " . $Read->getResult()[0]['clientes_lastname'];
                            endif;
                        endif;
                ?>
                        <tr role="row">
                            <td style="text-align: left" data-sort="<?= strtotime($cont_data) ?>"><?= date("d/m/Y", strtotime($cont_data)) ?></td>
                            <td style="text-align: left;"><?= $cont_code ?></td>
                            <td style="text-align: left;"><?= $cnpj_name ?></td>
                            <td style="text-align: left;"><?= $servicos_name ?></td>
                            <td style="text-align: left;"><?= "R$ " . number_format($cont_valor, '2', ',', '.') ?></td>
                            <td>
                                <div class='fl_right'>
                                    <a title="Editar" href="dashboard.php?wc=contratos/create&id=<?= $cont_id ?>" class="post_single_center icon-notext icon-pencil btn btn_green"></a>
                                    <span rel='dashboard_header_search' callback="Contratos" callback_action='cont_delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $cont_id; ?>'></span>
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