<article class="box box100 wc_tab_target" id="profile2" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <h2>Dados Cônjuge</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="auto_save" class="j_tab_home tab_create" name="conjuge_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend" id="txt_name">Primeiro nome</span>
                    <input value="<?= $conjuge_name; ?>" type="text" name="conjuge_name" required />
                </label>

                <label class="label">
                    <span class="legend" id="txt_name2">Sobrenome</span>
                    <input value="<?= $conjuge_lastname; ?>" type="text" name="conjuge_lastname" />
                </label>
            </div>

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend">E-mail:</span>
                    <input value="<?= $conjuge_email; ?>" type="email" name="conjuge_email" />
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
                <input type="file" name="conjuge_thumb" class="wc_loadimage file_input_hidden" accept="image/*" />
            </label>

            <div class="label_33">
                <label class="label" id="div_cpf">
                    <span class="legend">CPF:</span>
                    <input value="<?= $conjuge_document; ?>" type="text" name="conjuge_document" class="formCpf" />
                </label>

                <label class="label" id="div_genre">
                    <span class="legend">Gênero do Usuário:</span>
                    <select name="conjuge_genre" required>
                        <option value="1" <?= ($conjuge_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
                        <option value="2" <?= ($conjuge_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
                    </select>
                </label>
            </div>
            <div class="label_33">
                <label class="label">
                    <span class="legend" id="txt_niver">Data de Nascimento</span>
                    <input value="<?= (!empty($conjuge_datebirth) ? date("d/m/Y", strtotime($conjuge_datebirth)) : null); ?>" type="text" name="conjuge_datebirth" class="jwc_datepicker formDate" placeholder="Data de Nascimento:" />
                </label>

                <label class="label">
                    <span class="legend">Telefone:</span>
                    <input value="<?= $conjuge_telephone; ?>" class="formPhone" type="text" name="conjuge_telephone" />
                </label>

                <label class="label">
                    <span class="legend">Celular:</span>
                    <input value="<?= $conjuge_cell; ?>" class="formPhone" type="text" name="conjuge_cell" />
                </label>
            </div>
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
        <form class="auto_save" class="j_tab_home tab_create" name="conjuge_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />
            <div class="label_50">
                <label class="label">
                    <span class="legend">Profissão:</span>
                    <?php
                    $Read->FullRead("SELECT conjuge_profissao FROM " . DB_CLIENTES . " GROUP BY conjuge_profissao ORDER BY conjuge_profissao ASC");
                    if ($Read->getResult()) :
                        echo '<datalist id="conjuge_profissao">';
                        foreach ($Read->getResult() as $Reg) :
                            echo "<option value='{$Reg['conjuge_profissao']}'></option>";
                        endforeach;
                        echo '</datalist>';
                    endif;
                    ?>
                    <input value="<?= $conjuge_profissao; ?>" list="conjuge_profissao" type="text" name="conjuge_profissao" />
                </label>

                <label class="label">
                    <span class="legend">Formação:</span>
                    <?php
                    $Read->FullRead("SELECT conjuge_formacao FROM " . DB_CLIENTES . " GROUP BY conjuge_formacao ORDER BY conjuge_formacao ASC");
                    if ($Read->getResult()) :
                        echo '<datalist id="conjuge_formacao">';
                        foreach ($Read->getResult() as $Reg) :
                            echo "<option value='{$Reg['conjuge_formacao']}'></option>";
                        endforeach;
                        echo '</datalist>';
                    endif;
                    ?>
                    <input value="<?= $conjuge_formacao; ?>" list="conjuge_formacao" type="text" name="conjuge_formacao" />
                </label>
            </div>

            <div class="label_33">
                <label class="label">
                    <span class="legend">Renda Mensal Estimada:</span>
                    <input value="<?= $conjuge_renda ? number_format($conjuge_renda, '2', ',', '.') : "0,00"; ?>" type="text" name="conjuge_renda" onkeypress="return(moeda(this,'.',',',event))" />
                </label>

                <label class="label">
                    <span class="legend">Patrimônio Estimado:</span>
                    <input value="<?= $conjuge_patrimonio ? number_format($conjuge_patrimonio, '2', ',', '.') : "0,00"; ?>" type="text" name="conjuge_patrimonio" onkeypress="return(moeda(this,'.',',',event))" />
                </label>
            </div>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
</article>