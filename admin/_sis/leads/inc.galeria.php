<article class="box box100 wc_tab_target" id="galeria" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <h2>Galeria de Imagens</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <div class="panel_header default">
            <form class="" name="manage_vendor" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="callback" value="Documentos" />
                <input type="hidden" name="callback_action" value="add" />
                <input type="hidden" name="type" value="1" />
                <input type="hidden" name="clientes_id" value="<?= $clientes_id; ?>" />
                <label class="label">
                    <span class="legend">Fotos:</span>
                    <input type="file" name="file[]" multiple />
                </label>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <span class="btn btn_yellow icon-spinner9 wc_drag_active" title="Click para Organizar Tarefas" style='margin: 5px; top: 0px'>Ordenar</span>
                <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin: 5px;">Upload</button>
                <div class="clear"></div>
            </form>
        </div>
        <div class="panel" style="border-radius: 0 0 5px 5px;">
            <?php
            $Read->ExeRead(DB_CLIENTES_DOC, "WHERE type=1 AND clientes_id = :id ORDER BY file_order ASC, id ASC", "id={$clientes_id}");
            if (!$Read->getResult()) :
                echo "<div class='trigger trigger_info trigger_none al_center'>Ainda não existem registros <span class='icon-confused'></span></span></div><div class='clear'></div>";
            else :
                foreach ($Read->getResult() as $file) :
                    echo "<div class='single_user_addr box box25 wc_draganddrop' callback='Documentos' callback_action='ordem' id='{$file['id']}'>";
                    $fileUrl = ($file['file'] && file_exists("../uploads/{$file['file']}") && !is_dir("../uploads/{$file['file']}") ? BASE2 . "/uploads/{$file['file']}" : '_img/no_file.jpg');
                    echo "<img rel='vendors' id='{$file['id']}' alt='filem' title='filem' src='{$fileUrl}'/>";
                    echo "<div class='single_user_addr_actions'>
                                <span rel='single_user_addr' class='j_delete_action icon-notext icon-cancel-circle btn btn_red' id='{$file['id']}'></span>
                                <span rel='single_user_addr' callback='Documentos' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$file['id']}'>Deletar Imagem?</span>
                            </div>
                        </div>";
                endforeach;
            endif;
            ?>
            <div class="clear"></div>
        </div>
    </div>
</article>