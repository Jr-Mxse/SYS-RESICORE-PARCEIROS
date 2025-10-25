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
<script>
    document.querySelectorAll(".dashboard_nav_menu_toggle").forEach(toggle => {
        toggle.addEventListener("click", function() {
            let parent = this.parentElement;
            parent.classList.toggle("open");
        });
    });
</script>