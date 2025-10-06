<article class="box box100 wc_tab_target" id="documentos" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <h2>Documentos</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <div class="panel_header default">
            <form class="" name="manage_vendor" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Documentos" />
                <input type="hidden" name="callback_action" value="doc_add" />
                <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />
                <label class="label">
                    <span class="legend">Título do Documento</span>
                    <input type="text" name="titulo" required />
                </label>
                <div class="label_50">
                    <label class="label">
                        <span class="legend">Tipo:</span>
                        <select name="type">
                            <option value="2">PDF</option>
                            <option value="3">Word</option>
                            <option value="4">Excel</option>
                        </select>
                    </label>
                    <label class="label">
                        <span class="legend">Arquivo:</span>
                        <input type="file" name="file" />
                    </label>
                </div>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin: 5px;">Upload</button>
                <div class="clear"></div>
            </form>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <?php
            $apiTable = "table04";
            Datatable($apiTable, "", "", "[30,60,120,240]", ["print", "excel"], "");
            ?>
            <table id="<?= $apiTable ?>" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr role="row" style="background: #CCC">
                        <th style="text-align: left;">Atualização</th>
                        <th style="text-align: left;">Tipo de Documento</th>
                        <th style="text-align: left;">Documento + Link</th>
                        <th style="text-align: left;"></th>
                    </tr>
                </thead>

                <body>
                    <?php
                    $Read->ExeRead(DB_CLIENTES_DOC, "WHERE clientes_id = :user ORDER BY titulo ASC", "user={$clientes_id}");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $Reg) :
                            extract($Reg);
                            switch ($type) {
                                case 2:
                                    $type = "PDF";
                                    break;
                                case 3:
                                    $type = "Word";
                                    break;
                                case 4:
                                    $type = "Excel";
                                    break;
                                default:
                                    $type = "Outro";
                            }
                    ?>
                            <tr role="row">
                                <td style="text-align: left" data-sort="<?= strtotime($cadastro) ?>"><?= date("d/m/Y", strtotime($cadastro)) ?></td>
                                <td style="text-align: left; width: 15% !important;"><?= $type ?></td>
                                <td style="text-align: left; width: 65% !important;">
                                    <a target="_New" title="Visualizar" href="<?= BASE2 ?>/uploads/<?= $file ?>" class="post_single_center icon-notext icon-eye btn btn_blue"></a>
                                    <?= $titulo ?>
                                </td>
                                <td>
                                    <div class='fl_right'>
                                        <?php /*<a title="Editar" href="dashboard.php?wc=leads/doc&id=<?= $id ?>" class="post_single_center icon-notext icon-pencil btn btn_green"></a>*/ ?>
                                        <span rel='dashboard_header_search' callback="Documentos" callback_action='doc_delete' class='j_delete_action_confirm icon-cancel-circle btn btn_red  icon-notext' id='<?= $id; ?>'></span>
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
    </div>
</article>