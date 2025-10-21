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

$Image = ajusteFotoPerfil($user_thumb);

?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1>Especialista Associado</h1>
        <p class="dashboard_header_breadcrumbs">
            <a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Especialista Residere
        </p>
    </div>
</header>

<div class="dashboard_content dashboard_users">
    <div class="box box100">
         <section class="wc_tab_target wc_active m_top_15" id="profile">

            <div class="panel_header default">
                <h2 class="font_18">Dados do Especialista associado à minha conta</h2>
            </div>

             <div class="panel padding_32 radius_bottom_left_10 radius_bottom_right_10">
                <form class="j_tab_home tab_create" name="user_manager" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="callback" value="Users"/>
                    <input type="hidden" name="callback_action" value="manager"/>
                    <input type="hidden" name="user_id" value="<?= $UserId; ?>"/>
                    <div class="dashboard_profile_photo box box100">
                        <div class="dashboard_profile_photo_container">
                            <img class="user_thumb radius_10" src="../tim.php?src=<?= $Image; ?>&w=400&h=400" alt="Foto do usuário">
                            <input type="file" name="user_thumb" id="uploadPhoto" class="file-input wc_loadimage" accept="image/png, image/jpg, image/jpeg">

                            <label for="uploadPhoto" class="btn-edit wc_tooltip tooltip-white tooltip-top">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <span class="wc_tooltip_text font_weight_600">Editar foto</span>
                            </label>

                            <button class="btn-delete wc_tooltip tooltip-white">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <span class="wc_tooltip_text font_weight_600">Remover foto</span>
                            </button>
                        </div>

                        <p class="dashboard_profile_photo_infor"><?= AVATAR_W; ?>x<?= AVATAR_H; ?>px,Tipos de arquivo permitidos: png, jpg, jpeg</p>

                    </div>

                     <div class="label_50">
                        <label class="label">
                            <span class="legend">Perfil:</span>
                            <input value="<?= $user_socio ? "Sócio Especialista" : "Especialista"?>" disabled />
                        </label>
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

                        <label class="label">
                            <span class="legend">Celular:</span>
                            <input value="<?= $user_cell; ?>" disabled />
                        </label>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </section>
    </div>

</div>