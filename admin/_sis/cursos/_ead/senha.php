<?php
if (!empty($_SESSION['recover_end'])):
    ?>
    <div class="wc_ead_alert" style="display: flex;">
        <div class="wc_ead_alert_box yellow">
            <div class="wc_ead_alert_icon icon-switch icon-notext"></div><div class="wc_ead_alert_text">
                <p class="wc_ead_alert_title">LINK EXPIRADO OU INVÁLIDO:</p>
                <p class="wc_ead_alert_content">O link de recuperação não é mais válido. Você pode gerar outro!</p>
            </div><div class="wc_ead_alert_close"><span class="icon-cross icon-notext"></span></div>
        </div>
    </div>
    <?php
    unset($_SESSION['recover_end']);
endif;
?>
<div class="login_container wc_ead_password">
    <div class="login_left">
        <div class="login_content">
         <?php
         if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.localhost'):
            echo "<img class='login_logo' src='https://ead.resiplace.com.br/_ead/images/logo.svg' alt='ResiH' />";
        else:
          echo "<img class='login_logo' src='https://ead.resiplace.com.br/_ead/images/logo.svg' alt='ResiH' />";
        endif;
        ?>

            <form class="login_form" name="wc_ead_password" action="" method="post">

                <div class="form_group">
                    <label class="form_label">
                        <span class="label_text">E-mail:</span>
                        <input type="email" name="user_email" placeholder="Digite seu e-mail" required />
                    </label>
                </div>

                <div class="form_submit">
                    <img class="form_load none" alt="Enviando..." src="_img/load.gif" />
                    <button class="btn_login" type="submit">Enviar instruções</button>
                </div>

                <div class="login_links">
                    <a href="<?= BASE; ?>/campus" class="link_secondary">Voltar ao login</a>
                </div>
            </form>
        </div>
    </div>

    <div class="login_right">
        <div class="login_visual">
            <div class="visual_content">
                <h3 class="visual_title">Esqueceu sua senha?</h3>
                <p class="visual_subtitle">Sem problemas! Informe seu e-mail e receba o link para criar uma nova senha.</p>
                <div class="features">
                    <div class="feature_item">
                        <div class="feature_icon">
                            <i class="fa fa-envelope-open-text"></i>
                        </div>
                        <span>Envio rápido e seguro</span>
                    </div>
                    <div class="feature_item">
                        <div class="feature_icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <span>Proteção dos seus dados</span>
                    </div>
                    <div class="feature_item">
                        <div class="feature_icon">
                            <i class="fa fa-user-check"></i>
                        </div>
                        <span>Acesso garantido após redefinir</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
