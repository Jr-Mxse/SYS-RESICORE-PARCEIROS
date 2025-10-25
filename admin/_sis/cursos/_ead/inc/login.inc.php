<div class="login_container wc_ead_login">
    <div class="login_left">
        <div class="login_content">
         <?php
         if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.localhost'):
            echo "<img class='login_logo' src='https://ead.resiplace.com.br/_ead/images/logo.svg' alt='ResiH' />";
        else:
            echo "<img class='login_logo' src='https://ead.resiplace.com.br/_ead/images/logo.svg' alt='ResiH' />";
        endif;
        ?>
        <form class="login_form" name="wc_ead_login" action="" method="post">

            <div class="form_group">
                <label class="form_label">
                    <span class="label_text">E-mail</span>
                    <input type="email" name="user_email" value="" placeholder="Digite seu e-mail" required />
                </label>
            </div>

            <div class="form_group">
                <label class="form_label">
                    <span class="label_text">Senha:</span>

                    <div class="password_wrapper">
                        <input type="password" name="user_password" placeholder="Digite sua senha" value="" required />
                        <button type="button" class="toggle_password" aria-label="Mostrar senha" aria-pressed="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M14.5582 13.5577C13.9186 14.6361 12.6764 15.2036 11.4426 14.9811C10.2087 14.7585 9.24301 13.7928 9.02048 12.559C8.79795 11.3251 9.36544 10.0829 10.4438 9.44336" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M17.9966 16.9963C16.2719 18.3046 14.1649 19.0097 12.0001 19.0031C8.41297 19.0669 5.09862 17.0955 3.44251 13.9129C2.84761 12.7071 2.84761 11.2932 3.44251 10.0873C4.27076 8.43797 5.59106 7.08671 7.2208 6.22046" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M20.4275 14.1345C20.4677 14.0585 20.5199 13.9903 20.5578 13.9128C21.1527 12.707 21.1527 11.293 20.5578 10.0872C18.9017 6.90465 15.5873 4.93323 12.0002 4.99711C11.7753 4.99711 11.5567 5.02712 11.3347 5.04175" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M21.0039 20.0034L3.99683 2.99634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </label>
            </div>

            <div class="form_submit">
                <img class="form_load none" alt="Enviando..." src="_img/load.gif" />
                <button class="btn_login" type="submit">Entrar</button>
            </div>
        </form>

        <div class="login_links">
            <a href="<?= BASE; ?>/campus/cadastro" class="link_secondary">Cadastrar</a>
            <a href="<?= BASE; ?>/campus/senha" class="link_secondary">Esqueceu sua senha?</a>
        </div>

    </div>
</div>

<div class="login_right">
    <div class="login_visual">
        <div class="visual_content">
            <h3 class="visual_title">Portal do Aluno RESIH</h3>
            <p class="visual_subtitle">Acesse sua conta para continuar seus estudos e aproveitar todos os recursos do curso.</p>
            <div class="features">
                <div class="feature_item">
                    <div class="feature_icon">
                        <i class="fa fa-play-circle"></i>
                    </div>
                    <span>Assista às suas aulas</span>
                </div>
                <div class="feature_item">
                    <div class="feature_icon">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <span>Acompanhe seu progresso</span>
                </div>
                <div class="feature_item">
                    <div class="feature_icon">
                        <i class="fa fa-file-certificate"></i>
                    </div>
                    <span>Emita seus certificados</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>