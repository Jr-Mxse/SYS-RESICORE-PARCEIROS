<li class="dashboard_nav_menu_li <?= $getViewInput == 'home' ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-home" title="Dashboard" href="dashboard.php?wc=home">Dashboard</a></li>
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
    <a title="ResiH" href="https://resiplace.com.br" data-tooltip="ResiPlace">
        <i class="icon-star-full"></i>
        <span class="menu-text">ResiPlace</span>
    </a>
</li>
<li class="dashboard_nav_menu_li">
    <a title="ResiH" href="https://ead.resiplace.com.br" data-tooltip="Academia ResiH">
        <i class="icon-books"></i>
        <span class="menu-text">Academia ResiH</span>
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