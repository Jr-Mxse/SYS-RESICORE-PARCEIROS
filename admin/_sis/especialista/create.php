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

                    <a href="https://wa.me/55<?= preg_replace('/\D/', '', $user_cell); ?>?text=Olá!%20Gostaria%20de%20fazer%20um%20agendamento%20online."
                     target="_blank" 
                     class="whatsapp_agendamento_btn">
                     <div class="whatsapp_agendamento_content">
                        <div class="whatsapp_agendamento_icon">
                            <svg fill="#fff" width="20px" height="20px" viewBox="0 0 1.5 1.5" xmlns="http://www.w3.org/2000/svg"><path d="M0.954 0.826a0.438 0.438 0 0 1 0.096 0.044l-0.002 -0.001c0.033 0.015 0.062 0.032 0.088 0.052l-0.001 -0.001q0.002 0.006 0.002 0.013l0 0.002v0c-0.001 0.027 -0.007 0.052 -0.017 0.075l0.001 -0.001c-0.014 0.029 -0.038 0.051 -0.068 0.063l-0.001 0a0.225 0.225 0 0 1 -0.099 0.026h0a0.519 0.519 0 0 1 -0.187 -0.061l0.003 0.001a0.556 0.556 0 0 1 -0.165 -0.114l0 0a1.281 1.281 0 0 1 -0.141 -0.175l-0.003 -0.005a0.338 0.338 0 0 1 -0.069 -0.187l0 -0.001v-0.008a0.214 0.214 0 0 1 0.071 -0.153l0 0a0.073 0.073 0 0 1 0.05 -0.021h0q0.009 0 0.018 0.002l-0.001 0c0.005 0.001 0.012 0.001 0.018 0.001h0l0.004 0c0.008 0 0.016 0.002 0.022 0.007l0 0c0.007 0.007 0.012 0.016 0.015 0.026l0 0.001q0.008 0.019 0.032 0.085c0.008 0.02 0.017 0.044 0.023 0.069l0.001 0.004a0.098 0.098 0 0 1 -0.033 0.056l0 0q-0.033 0.035 -0.033 0.045a0.028 0.028 0 0 0 0.005 0.015l0 0a0.438 0.438 0 0 0 0.099 0.132l0 0a0.619 0.619 0 0 0 0.143 0.096l0.004 0.002a0.044 0.044 0 0 0 0.021 0.007h0q0.015 0 0.052 -0.047t0.05 -0.047zm-0.197 0.513h0.001a0.588 0.588 0 0 0 0.238 -0.05l-0.004 0.002c0.147 -0.062 0.262 -0.177 0.323 -0.32l0.002 -0.004c0.031 -0.07 0.048 -0.151 0.048 -0.236s-0.018 -0.166 -0.05 -0.24l0.002 0.004c-0.062 -0.147 -0.177 -0.262 -0.32 -0.323l-0.004 -0.002c-0.07 -0.031 -0.151 -0.048 -0.236 -0.048s-0.166 0.018 -0.24 0.05l0.004 -0.002c-0.147 0.062 -0.262 0.177 -0.323 0.32l-0.002 0.004a0.594 0.594 0 0 0 -0.048 0.237 0.6 0.6 0 0 0 0.117 0.357l-0.001 -0.002 -0.077 0.226 0.234 -0.075a0.591 0.591 0 0 0 0.332 0.101h0.003zm0 -1.339h0.002c0.102 0 0.199 0.021 0.286 0.06L1.041 0.058c0.177 0.075 0.314 0.212 0.387 0.384l0.002 0.005c0.037 0.084 0.058 0.181 0.058 0.283s-0.021 0.2 -0.06 0.288l0.002 -0.005c-0.075 0.177 -0.212 0.314 -0.384 0.387l-0.005 0.002c-0.083 0.037 -0.18 0.058 -0.281 0.058h-0.002c-0.129 0 -0.249 -0.034 -0.354 -0.093l0.004 0.002L0 1.5l0.132 -0.392a0.719 0.719 0 0 1 -0.105 -0.376c0 -0.103 0.021 -0.201 0.06 -0.289l-0.002 0.005C0.16 0.271 0.298 0.133 0.47 0.06L0.474 0.058A0.7 0.7 0 0 1 0.756 0zh0z"/></svg>
                        </div>
                        <div class="whatsapp_agendamento_text">
                            <strong>Falar com o Especialista Agora</strong>
                        </div>
                    </div>
                </a>


                    <div class="clear"></div>
                </form>
            </div>
        </article>
    </div>

    <div class="box box30">
        <div class="panel">
       <?php $Image = ajusteFotoPerfil($user_thumb); ?>
        <img class="user_thumb" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=400&h=400" alt="" title="" />
        </div>
    </div>
</div>