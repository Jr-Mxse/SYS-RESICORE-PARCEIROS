<li class="dashboard_nav_menu_li">
    <a title="Dashboard" href="dashboard.php?wc=home" data-tooltip="Dashboard">
        <i class="icon-home"></i>
        <span class="menu-text">Página Inicial</span>
    </a>
</li>
<li class="dashboard_nav_menu_li">
    <a title="Leads" href="dashboard.php?wc=leads/home" data-tooltip="Leads">
        <i class="icon-users"></i>
        <span class="menu-text">Meus Clientes / LEADs</span>
    </a>
</li>
<li class="dashboard_nav_menu_li">
    <a title="Leads" href="dashboard.php?wc=organizacao/home" data-tooltip="Leads">
        <i class="icon-office"></i>
        <span class="menu-text">Minhas Empresas</span>
    </a>
</li>
<li class="dashboard_nav_menu_li">
    <a title="Especialista Associado" href="dashboard.php?wc=especialista/create" data-tooltip="Especialista">
        <i class="icon-user"></i>
        <span class="menu-text">Falar com a Residere</span>
    </a>
</li>
<?php /*. 
<li class="dashboard_nav_menu_li">
    <a target="_New" title="ResiH" href="https://ead.resiplace.com.br" data-tooltip="Academia">
        <i class="icon-books"></i>
        <span class="menu-text">Aprender e Crescer</span>
    </a>
</li> */?>
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