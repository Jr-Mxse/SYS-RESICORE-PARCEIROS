<?php
$UserId = $Admin['especialista_id'];
$Read->ExeRead("users", "WHERE user_id = :id", "id={$UserId}");
if ($Read->getResult()):
    $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
    extract($FormData);
else:
    header('Location: dashboard.php?wc=home');
    exit;
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user">Especialista Associado</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Especialista Residere
        </p>
    </div>
</header>

<div class="dashboard_content dashboard_users">
    <div class="box box70">
        <article class="wc_tab_target wc_active" id="profile">

            <div class="panel_header default">
                <h2 class="icon-user">Dados do Especialista associado à minha conta</h2>
            </div>

            <div class="panel" style="border-radius: 0 0 5px 5px;">
                <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Perfil:</span>
                            <input value="<?= $user_socio ? "Sócio Especialista" : "Especialista"?>" disabled />
                        </label>
                    </div>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Nome:</span>
                            <input value="<?= $user_name; ?> <?= $user_lastname; ?>" disabled />
                        </label>
                    </div>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">E-mail:</span>
                            <input value="<?= $user_email; ?>" disabled />
                        </label>
                    </div>

                    <div class="label_33">
                        <label class="label">
                            <span class="legend">Celular:</span>
                            <input value="<?= $user_cell; ?>" disabled />
                        </label>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </article>
    </div>

    <div class="box box30" style="padding-left: 4%; padding-right: 4%">
       <?php
        $Image = ajusteFotoPerfil($user_thumb);
        ?>
        <img class="user_thumb" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=400&h=400" alt="" title="" />
    </div>
</div>