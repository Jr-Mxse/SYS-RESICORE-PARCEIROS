<article class="wc_tab_target wc_active" id="profile">
    <div class="panel_header default">
        <h2>Dados Básicos</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="j_tab_home tab_create" name="leads_manager" action="" method="post" enctype="multipart/form-data">
            <?php if ($RegTp == "editar"): ?>
                <input type="hidden" name="callback" value="Leads" />
                <input type="hidden" name="callback_action" value="manager" />
                <input type="hidden" name="leads_id" value="<?= $RegId; ?>" />
            <?php endif; ?>

            <label class="label">
                <span class="legend" id="txt_name">Nome:</span>
                <input value="<?= $leads_name; ?>" type="text" name="leads_name" <?= $RegTp != "editar" ? "disabled" : "required" ?> />
            </label>

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend">E-mail:</span>
                    <input value="<?= $leads_email; ?>" type="email" name="leads_email" <?= $RegTp != "editar" ? "disabled" : "" ?> />
                </label>
                <label class="label" id="div_genre">
                    <span class="legend">Gênero do Usuário:</span>
                    <select name="leads_genre" <?= $RegTp != "editar" ? "disabled" : "" ?>>
                        <option value="1" <?= ($leads_genre == 1 ? 'selected="selected"' : ''); ?>>Masculino</option>
                        <option value="2" <?= ($leads_genre == 2 ? 'selected="selected"' : ''); ?>>Feminino</option>
                    </select>
                </label>
            </div>

            <div class="label_33">
                <label class="label" id="div_cpf">
                    <span class="legend">CPF:</span>
                    <input value="<?= $leads_document; ?>" type="text" name="leads_document" class="formCpf" <?= $RegTp != "editar" ? "disabled" : "" ?> />
                </label>
                <label class="label">
                    <span class="legend" id="txt_niver">Data de Nascimento:</span>
                    <input value="<?= (!empty($leads_datebirth) ? date("d/m/Y", strtotime($leads_datebirth)) : null); ?>" type="text" name="leads_datebirth" class="jwc_datepicker formDate" placeholder="Data de Nascimento:" <?= $RegTp != "editar" ? "disabled" : "" ?> />
                </label>
                <label class="label">
                    <span class="legend">Celular:</span>
                    <input value="<?= $leads_cell; ?>" class="formPhone" type="text" name="leads_cell" <?= $RegTp != "editar" ? "disabled" : "" ?> />
                </label>
            </div>

            <label class="label">
                <span class="legend">Observações:</span>
                <textarea name="leads_content" class="work_mce" rows="10" <?= $RegTp != "editar" ? "disabled" : "" ?>><?= $leads_content; ?></textarea>
            </label>
            <?php if ($RegTp == "editar"): ?>
                <div class="clear"></div>
                <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                <button name="public" value="1" class="btn btn_green fl_right icon-share btn_xlarge btn_pulse" style="margin-left: 5px;">Atualizar</button>
            <?php endif; ?>
            <div class="clear"></div>
        </form>
    </div>
</article> 