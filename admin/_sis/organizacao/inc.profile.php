<article class="wc_tab_target wc_active" id="profile">
    <div class="panel_header default">
        <h2>Dados Básicos</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Organizacao" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="user_id" value="<?= $RegId; ?>" />

            <label class="label">
                <span class="legend" id="txt_name"><b>Nome Empresarial (Nome Fantasia): *</b></span>
                <input value="<?= $user_name; ?>" type="text" name="user_name" required />
            </label>

            <div class="label_33">
                <label class="label labelx_67">
                    <span class="legend">E-mail:</span>
                    <input value="<?= $user_email; ?>" type="email" name="user_email" />
                </label>
                <label class="label">
                    <span class="legend">Telefone:</span>
                    <input value="<?= $user_cell; ?>" class="formPhone" type="text" name="user_cell" />
                </label>
            </div>

            <div class="label_33">
                <label class="label" id="div_cpf">
                    <span class="legend">CNPJ:</span>
                    <input value="<?= $user_document; ?>" type="text" name="user_document" class="formCnpj" />
                </label>
                <label class="label"></label>
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
                <label class="label">
                    <span class="legend">Complemento:</span>
                    <input class="wc_complemento" name="addr_complement" value="<?= $addr_complement; ?>" placeholder="Ex: Casa, Apto, Etc:" />
                </label>
                <label class="label">
                    <span class="legend">Bairro:</span>
                    <input class="wc_bairro" name="addr_district" value="<?= $addr_district; ?>" required />
                </label>

                <label class="label">
                    <span class="legend">Cidade:</span>
                    <input class="wc_localidade" name="addr_city" value="<?= $addr_city; ?>" required />
                </label>
            </div>

            <div class="label_33">
                <label class="label">
                    <span class="legend">Estado (UF):</span>
                    <input class="wc_uf" name="addr_state" value="<?= $addr_state; ?>" maxlength="2" required />
                </label>
            </div>

            <label class="label">
                <span class="legend">Observações:</span>
                <textarea name="user_content" class="work_mce" rows="10"><?= $user_content; ?></textarea>
            </label>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
</article>