<article class="wc_tab_target wc_active" id="profile">
    <div class="panel_header default">
        <h2>Convidar mais Participantes</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Organizacao" />
            <input type="hidden" name="callback_action" value="convidar" />
            <input type="hidden" name="user_id" value="<?= $RegId; ?>" />

            <label class="label">
                <span class="legend" id="txt_name">Nome Integrante / Participante da Equipe::</span>
                <input value="" type="text" name="user_name" required />
            </label>

            <div class="label_50">
                <label class="label labelx_67">
                    <span class="legend">E-mail:</span>
                    <input value="" type="email" name="user_email" />
                </label>
            </div>

            <div class="label_50">
                <label class="label">
                    <span class="legend">Telefone:</span>
                    <input value="" class="formPhone" type="text" name="user_cell" />
                </label>
            </div>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
</article>