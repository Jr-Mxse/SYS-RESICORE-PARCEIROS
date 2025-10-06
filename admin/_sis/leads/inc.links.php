<article class="box box100 wc_tab_target" id="links" style="padding: 0; margin: 0; display: none;">
    <div class="panel_header default">
        <h2>Links Úteis</h2>
    </div>
    <div class="panel" style="border-radius: 0 0 5px 5px">
        <form class="auto_save" class="j_tab_home tab_create" name="clientes_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="clientes_id" value="<?= $RegId; ?>" />

            <label class="label">
                <span class="legend">Orçamentos:</span>
                <input value="<?= $link_01; ?>" type="text" name="link_01" />
            </label>

            <label class="label">
                <span class="legend">Contrato:</span>
                <input value="<?= $link_02; ?>" type="text" name="link_02" />
            </label>

            <label class="label">
                <span class="legend">Projeto:</span>
                <input value="<?= $link_03; ?>" type="text" name="link_03" />
            </label>

            <label class="label">
                <span class="legend">Serviços:</span>
                <input value="<?= $link_04; ?>" type="text" name="link_04" />
            </label>

            <label class="label">
                <span class="legend">Jira:</span>
                <input value="<?= $link_05; ?>" type="text" name="link_05" />
            </label>

            <?php /*
            <label class="label">
                <span class="legend">ObraPrima:</span>
                <input value="<?= $link_06; ?>" type="text" name="link_06" />
            </label>*/?>

            <label class="label">
                <span class="legend">Extra 01:</span>
                <input value="<?= $link_07; ?>" type="text" name="link_07" />
            </label>

            <label class="label">
                <span class="legend">Extra 02:</span>
                <input value="<?= $link_08; ?>" type="text" name="link_08" />
            </label>

            <label class="label">
                <span class="legend">Extra 03:</span>
                <input value="<?= $link_09; ?>" type="text" name="link_09" />
            </label>

            <div class="clear"></div>
            <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
            <div class="clear"></div>
        </form>
    </div>
</article>