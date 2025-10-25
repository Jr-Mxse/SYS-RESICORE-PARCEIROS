<?php
if ($Admin['user_name'] == $Admin['user_lastname']):
    $Admin['user_lastname'] = "";
endif;
?>

<nav class="dashboard_nav" id="dashboardSidebar">
    <!-- Menu de Ícones -->
    <ul class="dashboard_nav_menu">

        <!-- Logo/Header -->
        <div class="dashboard_nav_header">
            <div class="dashboard_nav_logo">
                <img class="dashboard_nav_admin_thumb rounded" src="_img/grupo.svg" alt="Avatar">
            </div>
        </div>

        <!-- Dashboard/Início -->
        <li class="dashboard_nav_menu_li <?= $getViewInput == 'home' ? 'dashboard_nav_menu_active' : ''; ?> dashboard_tooltip_container" data-tooltip="Início">
            <a href="dashboard.php?wc=home">
                <i class="ki-duotone ki-element-11 fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </a>
        </li>

        <!-- Especialista -->
        <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'especialista/') ? 'dashboard_nav_menu_active' : ''; ?> dashboard_tooltip_container" data-tooltip="Especialista">
            <a href="dashboard.php?wc=especialista/create">
                <i class="ki-duotone ki-user fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </a>
        </li>

        <!-- Leads -->
        <li class="dashboard_nav_menu_li dashboard_tooltip_container" data-menu="leads" data-tooltip="Leads">
            <a href="#">
                <i class="ki-duotone ki-users fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </a>
        </li>

        <!-- ResiPlace -->
        <li class="dashboard_nav_menu_li dashboard_tooltip_container" data-tooltip="ResiPlace">
            <a href="https://resiplace.com.br" target="_blank">
                <i class="ki-duotone ki-star fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </a>
        </li>

    </ul>

    <!-- User info no rodapé -->
    <div class="dashboard_nav_footer">
        <ul class="dashboard_nav_menu">

            <!-- Ver Site -->
            <li class="dashboard_nav_menu_li dashboard_tooltip_container" data-tooltip="Ver Site">
                <a href="<?= BASE; ?>" target="_blank">
                    <i class="ki-duotone ki-mouse-circle fs-2x">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </a>
            </li>

        </ul>

        <div class="dashboard_user_info">

            <img class="dashboard_user_avatar dashboard_tooltip_container" data-tooltip="Abrir menu do usuário" src="../tim.php?src=<?= $Admin['user_thumb']; ?>&w=40&h=40" alt="Avatar">

            <button class="dashboard_expand_btn dashboard_tooltip_container" id="dashboardPanelToggle" data-tooltip="Expandir painel">
                <i class="ki-duotone ki-arrow-left fs-2 rotate-180">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </button>

            <div class="dashboard_user_menu" id="userMenu">
                <div class="dashboard_user_header">
                    <img src="../tim.php?src=<?= $Admin['user_thumb']; ?>&w=400&h=400" alt="Avatar">
                    <div>
                        <strong><?= $Admin['user_name']; ?></strong>
                        <span class="dashboard_user_menu_email"><?= $Admin['user_email']; ?></span>
                    </div>
                </div>
                <ul>
                    <li><a href="dashboard.php?wc=users/create&id=<?= $Admin['user_id']; ?>">Meu Perfil</a></li>
                </ul>
                <hr>
                <ul>
                    <li><a href="dashboard.php?logoff=true">Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="dashboard_overlay" id="dashboardOverlay"></div>

<!-- PAINEL LATERAL EXPANSÍVEL -->
<div class="dashboard_sidebar_panel" id="dashboardSidebarPanel">
    <div class="dashboard_panel_content">

        <!-- ************ SESSÃO INÍCIO *********** -->
        <div class="dashboard_panel_section dashboard_section_active" data-section="home">
            <div class="dashboard_expandable_item">
                <i class="ki-duotone ki-element-11 fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span>Dashboard</span>
                <i class="ki-duotone ki-right fs-2x indicator">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>

            <ul class="dashboard_submenu">
                <li>
                    <a href="dashboard.php?wc=home">
                        <span class="dashboard_submenu_indicator"></span> Visão Geral
                    </a>
                </li>
            </ul>
        </div>
        <!-- ************ SESSÃO INÍCIO *********** -->

        <!-- ************ SESSÃO ESPECIALISTA *********** -->
        <div class="dashboard_panel_section" data-section="especialista">
            <div class="dashboard_expandable_item">
                <i class="ki-duotone ki-user fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span>Especialista</span>
                <i class="ki-duotone ki-right fs-2x indicator">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>

            <ul class="dashboard_submenu">
                <li>
                    <a href="dashboard.php?wc=especialista/create">
                        <span class="dashboard_submenu_indicator"></span> Gerenciar Especialista
                    </a>
                </li>
            </ul>
        </div>
        <!-- ************ SESSÃO ESPECIALISTA *********** -->

        <!-- ************ SESSÃO LeadS *********** -->
        <div class="dashboard_panel_section" data-section="leads">
            <div class="dashboard_expandable_item">
                <i class="ki-duotone ki-users fs-2x">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span>Leads</span>
                <i class="ki-duotone ki-right fs-2x indicator">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>

            <ul class="dashboard_submenu">
                <li>
                    <a href="dashboard.php?wc=leads/home">
                        <span class="dashboard_submenu_indicator"></span> Ver Leads
                    </a>
                </li>
                <li>
                    <a href="dashboard.php?wc=leads/create">
                        <span class="dashboard_submenu_indicator"></span> Novo Lead
                    </a>
                </li>
            </ul>
        </div>
        <!-- ************ SESSÃO LeadS *********** -->

    </div>
</div>

