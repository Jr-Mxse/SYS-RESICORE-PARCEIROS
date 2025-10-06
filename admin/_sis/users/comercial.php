<?php
$AdminLevel = LEVEL_USERS;
if (!APP_USERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

//AUTO DELETE USER TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_USERS, "WHERE user_name IS NULL AND user_email IS NULL and user_password IS NULL and user_level = :st", "st=1");
endif;



$S = filter_input(INPUT_GET, "s", FILTER_DEFAULT);
$O = filter_input(INPUT_GET, "opt", FILTER_DEFAULT);

$WhereString = (!empty($S) ? " AND user_name LIKE '%{$S}%' OR user_lastname LIKE '%{$S}%' OR concat(user_name, ' ', user_lastname) LIKE '%{$S}%' OR user_email LIKE '%{$S}%' OR user_id LIKE '%{$S}%' OR user_document LIKE '%{$S}%' " : "");
$WhereOpt = ((!empty($O) && $O == 'customers') ? " AND user_level <= 5" : ((!empty($O) && $O == 'team') ? " AND user_level >= 6 " : ""));

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $S = urlencode($Search['s']);
    $O = urlencode($Search['opt']);
    header("Location: dashboard.php?wc=users/home&opt={$O}&s={$S}");
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-users">Usuários</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; Parceiros Residere
            <span class="crumb">/</span>
            <a title="Parceiros Residere" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Comercial
        </p>
    </div>
</header>

<div class="dashboard_content">
    <article class="wc_tab_target wc_active" id="profile">
        <div class="box box30">
            <div class="panel_header default">
                <h2 class="icon-user-plus">Listagem de atendimento Fila Comercial</h2>
            </div>
            <div class="panel" style="border-radius: 0 0 5px 5px;">
                <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Users" />
                    <input type="hidden" name="callback_action" value="comercial" />
                    <?php
                    $whereSQL = "user_id = 3 OR user_id = 19 OR user_id = 23 OR user_id = 21 OR user_id = 31 OR user_id = 4 OR user_id = 7 OR user_id = 29 OR user_id = 44 OR user_id = 12 OR user_id = 9 OR user_id = 30";
                    $Read->ExeRead(DB_USERS, "WHERE {$whereSQL} ORDER BY user_name ASC ", "");
                    if ($Read->getResult()):
                        foreach ($Read->getResult() as $Reg):
                            extract($Reg);
                    ?>
                    <label class="user_check">
                        <div class="user_check_box">
                            <input value="1" type="checkbox" name="user_comercial[<?= $user_id ?>]" <?= ($user_comercial ? 'checked' : ''); ?> />
                            <span><?= $user_name ?> <?= $user_lastname ?></span>
                        </div>
                    </label>

                    <?php
                        endforeach;
                    endif;
                    ?>
                    <div class="clear"></div>

                    <img class="form_load none fl_right" style="margin-left: 10px; margin-top: 2px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                    <button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">Atualizar</button>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </article>

</div>

<style>
    /* container geral */
.user_check {
  display: block;
  margin-bottom: 10px;
  cursor: pointer;
  font-family: Arial, sans-serif;
}

/* bloco interno */
.user_check_box {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border: 1px solid #e1e5ec;
  border-radius: 8px;
  background: #fff;
  transition: all 0.2s ease;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* efeito ao passar o mouse */
.user_check_box:hover {
  background: #f7faff;
  border-color: #007bff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

/* input checkbox */
.user_check input[type="checkbox"] {
  accent-color: #007bff;
  margin:  0 !important;
  width: 18px;
  height: 18px;
  cursor: pointer;
}

/* nome do usuário */
.user_check span {
  font-size: 15px;
  color: #333;
  font-weight: 500;
}

</style>