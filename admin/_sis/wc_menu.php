<li class="dashboard_nav_menu_li <?= $getViewInput == 'home' ? 'dashboard_nav_menu_active' : ''; ?>">
    <a class="icon-home" title="Dashboard" href="dashboard.php?wc=home">Dashboard</a>
</li>
<li class="dashboard_nav_menu_li">
    <a title="Especialista" href="dashboard.php?wc=especialista/create" data-tooltip="Especialista">
        <i class="icon-user"></i>
        <span class="menu-text">Especialista</span>
    </a>
</li>
<li class="dashboard_nav_menu_li has-submenu">
    <a title="Leads" href="dashboard.php?wc=leads/home" data-tooltip="Leads">
        <i class="icon-users"></i>
        <span class="menu-text">Leads</span>
        <div class="submenu-arrow">
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.5303 9.46967C18.8232 9.76256 18.8232 10.2374 18.5303 10.5303L12.5303 16.5303C12.2374 16.8232 11.7626 16.8232 11.4697 16.5303L5.46967 10.5303C5.17678 10.2374 5.17678 9.76256 5.46967 9.46967C5.76256 9.17678 6.23744 9.17678 6.53033 9.46967L12 14.9393L17.4697 9.46967C17.7626 9.17678 18.2374 9.17678 18.5303 9.46967Z" fill="#030D45" />
            </svg>
        </div>
    </a>
    <ul class="dashboard_nav_menu_sub">
        <li><a title="Ver Leads" href="dashboard.php?wc=leads/home">&raquo; Ver Leads</a></li>
        <li><a title="Novo Lead" href="dashboard.php?wc=leads/create">&raquo; Novo Lead</a></li>
    </ul>
</li>
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

<?php /*
<li class="dashboard_nav_menu_li">
    <a target="_New" title="ResiH" href="https://ead.resiplace.com.br" data-tooltip="Academia">
        <i class="icon-books"></i>
        <span class="menu-text">Treinamentos</span>
    </a>
</li> */?>

<script>
    document.querySelectorAll(".dashboard_nav_menu_toggle").forEach(toggle => {
        toggle.addEventListener("click", function() {
            let parent = this.parentElement;
            parent.classList.toggle("open");
        });
    });
</script>