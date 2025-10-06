<article class="wc_tab_target wc_active" id="profile">
    <div class="panel_header default">
        <h2>Dados Básicos</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="auto_save" class="j_tab_home tab_create" name="clientes_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />
            <div class="label_33">
                <label class="label">
                    <span class="legend">Código</span>
                    <input value="<?= $clientes_code; ?>" type="text" name="clientes_code" required />
                </label>
                <label class="label"></label>
                <label class="label">
                    <span class="legend">Status:</span>
                    <select name="clientes_status" required>
                        <option value="0" <?= ($clientes_status == 0 ? 'selected="selected"' : ''); ?>>Desativado</option>
                        <option value="1" <?= ($clientes_status == 1 ? 'selected="selected"' : ''); ?>>Ativo</option>
                    </select>
                </label>
            </div>

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend" id="txt_name">Primeiro nome:</span>
                    <input value="<?= $clientes_name; ?>" type="text" name="clientes_name" required />
                </label>
                <label class="label">
                    <span class="legend" id="txt_name2">Sobrenome:</span>
                    <input value="<?= $clientes_lastname; ?>" type="text" name="clientes_lastname" />
                </label>
            </div>

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend">E-mail:</span>
                    <input value="<?= $clientes_email; ?>" type="email" name="clientes_email" />
                </label>
            </div>


            <label class="custom_file_upload">
                <span class="legend">Foto (<?= AVATAR_W; ?>x<?= AVATAR_H; ?>px, JPG ou PNG):</span>
                <div class="file_upload_area">
                    <div class="upload_icon">
                        <i class="icon-image"></i>
                    </div>
                    <div class="upload_text">
                        <span class="main_text">Clique para subir uma imagem</span>
                        <span class="sub_text">ou arraste e solte aqui</span>
                    </div>
                </div>
                <input type="file" name="clientes_thumb" class="wc_loadimage file_input_hidden" accept="image/*" />
            </label>

            <div class="label_33">
                <label class="label" id="div_cpf">
                    <span class="legend">CPF:</span>
                    <input value="<?= $clientes_document; ?>" type="text" name="clientes_document" class="formCpf" />
                </label>
                <label class="label" id="div_genre">
                    <span class="legend">Gênero do Usuário:</span>
                    <select name="clientes_genre" required>
                        <option value="1" <?= ($clientes_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
                        <option value="2" <?= ($clientes_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
                    </select>
                </label>
            </div>

            <div class="label_33">
                <label class="label">
                    <span class="legend" id="txt_niver">Data de Nascimento:</span>
                    <input value="<?= (!empty($clientes_datebirth) ? date("d/m/Y", strtotime($clientes_datebirth)) : null); ?>" type="text" name="clientes_datebirth" class="jwc_datepicker formDate" placeholder="Data de Nascimento:" />
                </label>
                <label class="label">
                    <span class="legend">Telefone:</span>
                    <input value="<?= $clientes_telephone; ?>" class="formPhone" type="text" name="clientes_telephone" />
                </label>
                <label class="label">
                    <span class="legend">Celular:</span>
                    <input value="<?= $clientes_cell; ?>" class="formPhone" type="text" name="clientes_cell" />
                </label>
            </div>

            <label class="label">
                <span class="legend">Observações:</span>
                <textarea name="clientes_content" class="work_mce" rows="10"><?= $clientes_content; ?></textarea>
            </label>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
    <br>
    <div class="panel_header default">
        <h2>Dados Complementares</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="auto_save" class="j_tab_home tab_create" name="clientes_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />
            <label class="label">
                <span class="legend">Indicado Por Lead:</span>
                <select name="clientes_id_indica" class="js-select2" style="width: 100%;">
                    <option value="" selected>Nenhum Usuário</option>
                    <?php
                    $Read->FullRead("SELECT clientes_id, clientes_name, clientes_lastname, clientes_level FROM " . DB_CLIENTES . " ORDER BY clientes_name ASC, clientes_lastname ASC");
                    if ($Read->getResult()) :
                        foreach ($Read->getResult() as $Reg) :
                            echo "<option value='{$Reg["clientes_id"]}' " . ($clientes_id_indica == $Reg["clientes_id"] ? 'selected="selected"' : '') . ">{$Reg["clientes_name"]} {$Reg["clientes_lastname"]}</option>";
                        endforeach;
                    endif;
                    ?>
                </select>
            </label>

            <div class="label_50">
                <label class="label">
                    <span class="legend">Profissão:</span>
                    <?php
                    $Read->FullRead("SELECT clientes_profissao FROM " . DB_CLIENTES . " GROUP BY clientes_profissao ORDER BY clientes_profissao ASC");
                    if ($Read->getResult()) :
                        echo '<datalist id="clientes_profissao">';
                        foreach ($Read->getResult() as $Reg) :
                            echo "<option value='{$Reg['clientes_profissao']}'></option>";
                        endforeach;
                        echo '</datalist>';
                    endif;
                    ?>
                    <input value="<?= $clientes_profissao; ?>" list="clientes_profissao" type="text" name="clientes_profissao" />
                </label>

                <label class="label">
                    <span class="legend">Formação:</span>
                    <?php
                    $Read->FullRead("SELECT clientes_formacao FROM " . DB_CLIENTES . " GROUP BY clientes_formacao ORDER BY clientes_formacao ASC");
                    if ($Read->getResult()) :
                        echo '<datalist id="clientes_formacao">';
                        foreach ($Read->getResult() as $Reg) :
                            echo "<option value='{$Reg['clientes_formacao']}'></option>";
                        endforeach;
                        echo '</datalist>';
                    endif;
                    ?>
                    <input value="<?= $clientes_formacao; ?>" list="clientes_formacao" type="text" name="clientes_formacao" />
                </label>
            </div>

            <div class="label_33">
                <label class="label">
                    <span class="legend">Renda Mensal Estimada:</span>
                    <input value="<?= $clientes_renda ? number_format($clientes_renda, '2', ',', '.') : "0,00"; ?>" type="text" name="clientes_renda" onkeypress="return(moeda(this,'.',',',event))" />
                </label>

                <label class="label">
                    <span class="legend">Patrimônio Estimado:</span>
                    <input value="<?= $clientes_patrimonio ? number_format($clientes_patrimonio, '2', ',', '.') : "0,00"; ?>" type="text" name="clientes_patrimonio" onkeypress="return(moeda(this,'.',',',event))" />
                </label>
            </div>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
</article>