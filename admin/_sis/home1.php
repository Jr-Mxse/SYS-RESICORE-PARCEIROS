
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-home">Dashboard</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; Parceiros Residere
            <span class="crumb">/</span>
            <a title="Parceiros Residere" href="dashboard.php?wc=home">Dashboard</a>
        </p>
    </div>
</header>

<div class="dashboard_content">
    <?php
    $texto = "O trabalho em equipe é a chave para alcançar metas ambiciosas e superar desafios.";
    echo Erro("<span class='icon-happy2 al_center'>Estamos apenas começando, em breve teremos novidades!<br>{$texto}</span>", E_USER_NOTICE);
    ?>
</div>

<script>
    //ICON REFRESH IN DASHBOARD
    $('#loopDashboard').click(function () {
        Dashboard();
    });

    //DASHBOARD REALTIME
    setInterval(function () {
        Dashboard();
    }, 10000);
</script>