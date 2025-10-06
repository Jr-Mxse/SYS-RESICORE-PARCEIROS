<article class="box box100 wc_tab_target" id="address" style="padding: 0; margin: 0; display: none;">
    <?php
    $Read->ExeRead(DB_CLIENTES_ADDR, "WHERE clientes_id = :id", "id={$clientes_id}");
    if ($Read->getRowCount() < 2):
        if ($Read->getRowCount() == 0):
            $Create->ExeCreate(DB_CLIENTES_ADDR, ["clientes_id" => $Create->getResult(), "addr_name" => "Endereço residencial atual"]);
        endif;
        if ($Read->getRowCount() == 0 || $Read->getRowCount() == 1):
            $Create->ExeCreate(DB_CLIENTES_ADDR, ["clientes_id" => $Create->getResult(), "addr_name" => "Endereço serviço construção"]);
        endif;
    endif;
    ?>
    <div class="panel_header default">
        <span>
            <a href="dashboard.php?wc=leads/address&user=<?= $clientes_id; ?>" class="btn btn_green icon-plus a icon-notext"></a>
        </span>
        <h2>Endereços </h2>
    </div>
    <?php
    $Read->ExeRead(DB_CLIENTES_ADDR, "WHERE clientes_id = :id LIMIT 0,1", "id={$clientes_id}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData); ?>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <form class="auto_save" name="clientes_add_address" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="addr_manage" />
                <input type="hidden" name="addr_id" value="<?= $addr_id; ?>" />
                <input type="hidden" name="clientes_id" value="<?= $clientes_id; ?>" />
                <input type="hidden" name="especial" value="1" />

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend"><b>Nome do Endereço:</b></span>
                        <input name="addr_name" value="<?= $addr_name; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">CEP:</span>
                        <input name="addr_zipcode" value="<?= $addr_zipcode; ?>" class="formCep wc_getCep" placeholder="Informe o CEP:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Rua:</span>
                        <input class="wc_logradouro" name="addr_street" value="<?= $addr_street; ?>" placeholder="Nome da Rua:" required />
                    </label>
                    <label class="label">
                        <span class="legend">Número:</span>
                        <input name="addr_number" value="<?= $addr_number; ?>" placeholder="Número:" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label labelx_67">
                        <span class="legend">Complemento:</span>
                        <input class="wc_complemento" name="addr_complement" value="<?= $addr_complement; ?>" placeholder="Ex: Casa, Apto, Etc:" />
                    </label>
                    <label class="label">
                        <span class="legend">Bairro:</span>
                        <input class="wc_bairro" name="addr_district" value="<?= $addr_district; ?>" required />
                    </label>
                </div>

                <div class="label_33">
                    <label class="label">
                        <span class="legend">Cidade:</span>
                        <input class="wc_localidade" name="addr_city" value="<?= $addr_city; ?>" required />
                    </label>
                    <label class="label">
                        <span class="legend">Estado (UF):</span>
                        <input class="wc_uf" name="addr_state" value="<?= $addr_state; ?>" maxlength="2" required />
                    </label>

                    <label class="label">
                        <span class="legend">País:</span>
                        <input name="addr_country" value="<?= ($addr_country ? $addr_country : 'Brasil'); ?>" required />
                    </label>
                </div>

                <p>&nbsp;</p>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
                <div class="clear"></div>
            </form>
        </div>
    <?php endif; ?>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <?php
        $apiTable = "table01";
        Datatable($apiTable, "", "", "[30,60,120,240]", ["print", "excel"], "");
        ?>
        <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
            <thead>
                <tr role="row" style="background: #CCC">
                    <th style="text-align: left;">Atualização</th>
                    <th style="text-align: left;">CEP</th>
                    <th style="text-align: left;">Endereço</th>
                    <th style="text-align: left;"></th>
                </tr>
            </thead>

            <body>
                <?php
                $Read->ExeRead(DB_CLIENTES_ADDR, "WHERE clientes_id = :user ORDER BY addr_key DESC, addr_name ASC", "user={$clientes_id}");
                if ($Read->getResult()):
                    foreach ($Read->getResult() as $Reg) :
                        extract($Reg);

                ?>
                        <tr role="row">
                            <td style="text-align: left" data-sort="<?= strtotime($addr_update) ?>"><?= date("d/m/Y", strtotime($addr_update)) ?></td>
                            <td style="text-align: left; width: 15% !important;"><?= $Reg['addr_zipcode'] ?></td>
                            <td style="text-align: left; width: 50% !important;"><?= "{$Reg['addr_name']}" ?></td>
                            <td>
                                <div class='fl_right'>
                                    <a title="Editar" href="dashboard.php?wc=leads/address&id=<?= $addr_id ?>" class="post_single_center icon-notext icon-pencil btn btn_green"></a>
                                    <span rel='dashboard_header_search' callback="Leads" callback_action='addr_delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $addr_id; ?>'></span>
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