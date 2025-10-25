<li class="dashboard_nav_menu_li <?= $getViewInput == 'home' ? 'active' : ''; ?>">
    <a title="Dashboard" href="dashboard.php?wc=home" data-tooltip="Dashboard">
        <i class="icon-home"></i>
        <span class="menu-text">Página Inicial</span>
    </a>
</li>
<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'leads/home') ? 'active' : ''; ?>">
    <a title="Leads" href="dashboard.php?wc=leads/home" data-tooltip="Leads">
        <i class="icon-user-plus"></i>
        <span class="menu-text">Meus Clientes / Leads</span>
    </a>
</li>
<?php if (in_array($Admin["user_id"], [1, 230])): ?>
<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'organizacao/home') ? 'active' : ''; ?>">
    <a title="Leads" href="dashboard.php?wc=organizacao/home" data-tooltip="Leads">
        <i class="icon-office"></i>
        <span class="menu-text">Minhas Empresas</span>
    </a>
</li>
<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'organizacao/home') ? 'active' : ''; ?>">
    <a title="Minha Equipe" href="dashboard.php?wc=organizacao/equioe" data-tooltip="Minha Equipe">
        <i class="icon-users"></i>
        <span class="menu-text">Minha Equipe</span>
    </a>
</li>
<?php endif;?>
<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'especialista/create') ? 'active' : ''; ?>">
    <a title="Especialista Associado" href="dashboard.php?wc=especialista/create" data-tooltip="Especialista">
        <span style="display: flex;gap: 10px;">
            <svg fill="#1abc9c" width="20px" height="20px" viewBox="0 0 1.5 1.5" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.954 0.826a0.438 0.438 0 0 1 0.096 0.044l-0.002 -0.001c0.033 0.015 0.062 0.032 0.088 0.052l-0.001 -0.001q0.002 0.006 0.002 0.013l0 0.002v0c-0.001 0.027 -0.007 0.052 -0.017 0.075l0.001 -0.001c-0.014 0.029 -0.038 0.051 -0.068 0.063l-0.001 0a0.225 0.225 0 0 1 -0.099 0.026h0a0.519 0.519 0 0 1 -0.187 -0.061l0.003 0.001a0.556 0.556 0 0 1 -0.165 -0.114l0 0a1.281 1.281 0 0 1 -0.141 -0.175l-0.003 -0.005a0.338 0.338 0 0 1 -0.069 -0.187l0 -0.001v-0.008a0.214 0.214 0 0 1 0.071 -0.153l0 0a0.073 0.073 0 0 1 0.05 -0.021h0q0.009 0 0.018 0.002l-0.001 0c0.005 0.001 0.012 0.001 0.018 0.001h0l0.004 0c0.008 0 0.016 0.002 0.022 0.007l0 0c0.007 0.007 0.012 0.016 0.015 0.026l0 0.001q0.008 0.019 0.032 0.085c0.008 0.02 0.017 0.044 0.023 0.069l0.001 0.004a0.098 0.098 0 0 1 -0.033 0.056l0 0q-0.033 0.035 -0.033 0.045a0.028 0.028 0 0 0 0.005 0.015l0 0a0.438 0.438 0 0 0 0.099 0.132l0 0a0.619 0.619 0 0 0 0.143 0.096l0.004 0.002a0.044 0.044 0 0 0 0.021 0.007h0q0.015 0 0.052 -0.047t0.05 -0.047zm-0.197 0.513h0.001a0.588 0.588 0 0 0 0.238 -0.05l-0.004 0.002c0.147 -0.062 0.262 -0.177 0.323 -0.32l0.002 -0.004c0.031 -0.07 0.048 -0.151 0.048 -0.236s-0.018 -0.166 -0.05 -0.24l0.002 0.004c-0.062 -0.147 -0.177 -0.262 -0.32 -0.323l-0.004 -0.002c-0.07 -0.031 -0.151 -0.048 -0.236 -0.048s-0.166 0.018 -0.24 0.05l0.004 -0.002c-0.147 0.062 -0.262 0.177 -0.323 0.32l-0.002 0.004a0.594 0.594 0 0 0 -0.048 0.237 0.6 0.6 0 0 0 0.117 0.357l-0.001 -0.002 -0.077 0.226 0.234 -0.075a0.591 0.591 0 0 0 0.332 0.101h0.003zm0 -1.339h0.002c0.102 0 0.199 0.021 0.286 0.06L1.041 0.058c0.177 0.075 0.314 0.212 0.387 0.384l0.002 0.005c0.037 0.084 0.058 0.181 0.058 0.283s-0.021 0.2 -0.06 0.288l0.002 -0.005c-0.075 0.177 -0.212 0.314 -0.384 0.387l-0.005 0.002c-0.083 0.037 -0.18 0.058 -0.281 0.058h-0.002c-0.129 0 -0.249 -0.034 -0.354 -0.093l0.004 0.002L0 1.5l0.132 -0.392a0.719 0.719 0 0 1 -0.105 -0.376c0 -0.103 0.021 -0.201 0.06 -0.289l-0.002 0.005C0.16 0.271 0.298 0.133 0.47 0.06L0.474 0.058A0.7 0.7 0 0 1 0.756 0zh0z" />
            </svg>
            <span class="menu-text">Falar com a Residere</span>
        </span>
    </a>
</li>
<?php if (in_array($Admin["user_id"], [1, 230])): ?>
<li class="dashboard_nav_menu_li">
    <a target="_New" title="ResiH" href="https://ead.resiplace.com.br" data-tooltip="Academia">
        <i class="icon-books"></i>
        <span class="menu-text">Aprender e Crescer</span>
    </a>
</li>
<?php endif; ?>
<li class="dashboard_nav_menu_li">
    <a target="_New" title="ResiH" href="https://resiplace.com.br" data-tooltip="ResiPlace">
        <i class="icon-star-full"></i>
        <span class="menu-text">ResiPlace</span>
    </a>
</li>
<span class="mobile_menu_mobile_fechar">
    <div class="mobile_menu_mobile_box_fechar">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" data-testid="IconThumbsUp" class="sc-dYOqWG fxtfcv">
            <path d="M12 8L8 12M8 12L12 16M8 12H16M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" stroke="#ccc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <div>Fechar Menu</div>
    </div>
</span>
<script>
    document.querySelectorAll(".dashboard_nav_menu_toggle").forEach(toggle => {
        toggle.addEventListener("click", function() {
            let parent = this.parentElement;
            parent.classList.toggle("open");
        });
    });
</script>