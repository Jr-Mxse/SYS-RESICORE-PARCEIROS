<header class="dashboard_header" style="padding: 0px"></header>
<div class="dashboard_content">
    <section class="dashboard_cards_container">

        <div class="dashboard_card">
            <div class="dashboard_card_header">
                <div class="dashboard_card_icon card_icon_leads">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4zm0 9h3l3 3h4l3-3h3v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4z" />
                    </svg>
                </div>
                <div class="dashboard_card_content">
                    <h3 class="dashboard_card_title">Clientes / Leads</h3>
                    <?php
                    $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = {$Admin['user_id']} ", "");
                    $totalLeads = $Read->getRowCount();

                    $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = {$Admin['user_id']} AND leads_registration >= (CURDATE() - INTERVAL 7 DAY)", "");
                    $totalLeadsSemana = $Read->getRowCount();
                    ?>
                    <p class="dashboard_card_value"><?= $totalLeads ?></p>
                    <p class="dashboard_card_subtitle"><span class="card_value_highlight">+<?= $totalLeadsSemana ?></span> últimos<br>07 dias</p>
                    <a class="dashboard_card_btn btn_gradient btn_gradient_success btn_pulse" href="dashboard.php?wc=leads/create">Adicionar Cliente / Lead</a>

                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="dashboard_card">
            <div class="dashboard_card_header">
                <div class="dashboard_card_icon card_icon_proposals">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 12h8v2H8v-2zm0 4h8v2H8v-2zm0-8h5v2H8V8z" />
                    </svg>
                </div>
                <div class="dashboard_card_content">
                    <h3 class="dashboard_card_title">Propostas</h3>
                    <?php
                    $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = {$Admin['user_id']} AND leads_proposta>0", "");
                    $totalPropostas = $Read->getRowCount();

                    $taxaFechamento = $totalLeads > 0 ? ($totalPropostas / $totalLeads) * 100 : 0;
                    $taxaFechamentoFmt = number_format($taxaFechamento, 1, ',', '.');
                    ?>
                    <p class="dashboard_card_value"><?= $totalPropostas ?></p>
                    <p class="dashboard_card_subtitle card_subtitle_rate">Taxa: <?= $taxaFechamentoFmt ?>%</p>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="dashboard_card">
            <div class="dashboard_card_header">
                <div class="dashboard_card_icon card_icon_sales">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z" />
                    </svg>
                </div>
                <div class="dashboard_card_content">
                    <h3 class="dashboard_card_title">Vendas / Obras</h3>
                    <?php
                    $Read->ExeRead(DB_LEADS, "WHERE parceiros_id = {$Admin['user_id']} AND leads_status=2", "");
                    $totalVendas = $Read->getRowCount();

                    $taxaFechamento = $totalLeads > 0 ? ($totalVendas / $totalLeads) * 100 : 0;
                    $taxaFechamentoFmt = number_format($taxaFechamento, 1, ',', '.');
                    ?>
                    <p class="dashboard_card_value"><?= $totalVendas ?></p>
                    <p class="dashboard_card_subtitle">Taxa: <?= $taxaFechamentoFmt ?>%</p>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="dashboard_card">
            <div class="dashboard_card_header">
                <div class="dashboard_card_icon card_icon_commission">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 18v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v1h-9a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                    </svg>
                </div>
                <div class="dashboard_card_content">
                    <h3 class="dashboard_card_title">Comissão Potencial</h3>
                    <?php
                    $Read->FullRead("SELECT SUM(leads_proposta) AS total FROM " . DB_LEADS . " WHERE parceiros_id = :id AND leads_status=1", "id={$Admin['user_id']}");
                    $total = $Read->getResult()[0]["total"];
                    $leads_terreno = (20 * $total / 100);
                    $leads_previsto = (6 * ($total - $leads_terreno) / 100);
                    ?>
                    <p class="dashboard_card_value"><?= "R$ " . number_format($leads_previsto, '2', ',', '.') ?></p>
                    <p class="dashboard_card_subtitle">Indicação e Fechamento</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FULL WIDTH CARD ===== -->
    <div class="dashboard_full_card">
        <h3 class="full_card_title">Pontuação de engajamento (em breve)</h3>
        <div class="engagement_progress_container">
            <div class="engagement_progress_bar" style="width: 0%;"></div>
        </div>
        <p class="engagement_label">0/100 (Gamificação)</p>
    </div>

    <section class="dashboard_section7_container">
        <!-- Card 1: Alertas automáticos -->
        <div class="dashboard_alerts_card">
            <h3 class="alerts_title">Alertas automáticos</h3>
            <div class="alerts_list">

                <div class="alert_item_action">
                    <div class="alert_content">
                        <div class="alert_icon alert_icon_success">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                            </svg>
                        </div>
                        <div class="alert_text_content">
                            <?php
                            $EspecialistaId = $Admin['especialista_id'];
                            $Read->ExeRead("users", "WHERE user_id = :id", "id={$EspecialistaId}");
                            $Especialista = $Read->getResult()[0];
                            $Especialista["user_name"] = explode(" ", $Especialista["user_name"])[0];
                            ?>
                            <p class="alert_main_text">Precisa de alguma ajuda com seu Especialista</p>
                            <p class="alert_sub_text">Entre em contato com <?= $Especialista["user_name"] ?></p>
                        </div>
                    </div>
                    <a href="https://wa.me/55<?= preg_replace('/\D/', '', $Especialista["user_cell"]); ?>?text=Olá!%20Gostaria%20de%20fazer%20um%20agendamento%20online."
                        target="_blank">
                        <button class="btn btn_pulse btn_green">Whatsapp</button>
                    </a>
                </div>

                <?php /*
                <!-- Alerta 1: Lead sem retorno -->
                <div class="alert_item_action">
                    <div class="alert_content">
                        <div class="alert_icon alert_icon_warning">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                            </svg>
                        </div>
                        <div class="alert_text_content">
                            <p class="alert_main_text">Lead sem retorno há 48h</p>
                            <p class="alert_sub_text">João Silva</p>
                        </div>
                    </div>
                    <button class="alert_action_button">abrir chat</button>
                </div>

                <!-- Alerta 2: Proposta pendente -->
                <div class="alert_item_action">
                    <div class="alert_content">
                        <div class="alert_icon alert_icon_info">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 12h8v2H8v-2zm0 4h8v2H8v-2zm0-8h5v2H8V8z" />
                            </svg>
                        </div>
                        <div class="alert_text_content">
                            <p class="alert_main_text">Proposta pendente</p>
                            <p class="alert_sub_text">Terreno Jardim Aurora</p>
                        </div>
                    </div>
                    <span class="alert_date">25/10</span>
                </div>

                <!-- Alerta 3: Comissão liberada -->
                <div class="alert_item_action">
                    <div class="alert_content">
                        <div class="alert_icon alert_icon_success">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 6H3v2h18V6zm-2 4H5c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1zm-1 8H6v-6h12v6zm-8-3c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5 1.5zM2 18h2v2H2v-2zm18 0h2v2h-2v-2z" />
                            </svg>
                        </div>
                        <div class="alert_text_content">
                            <p class="alert_main_text">Comissão liberada</p>
                            <p class="alert_sub_text">Casa Vila Real</p>
                        </div>
                    </div>
                    <span class="alert_value">R$ 3.200</span>
                </div>
                */ ?>
            </div>
        </div>

        <!-- Card 2: Agenda -->
        <div class="dashboard_alerts_card">
            <h3 class="alerts_title">Agenda</h3>
            <table class="agenda_table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Evento</th>
                        <th>Detalhe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>28/10</td>
                        <td class="agenda_event">Residere Conecta</td>
                        <td class="agenda_detail">Onde conexões viram negócios</td>
                    </tr>
                    <tr>
                        <td>30/10</td>
                        <td class="agenda_event">Residere Conecta</td>
                        <td class="agenda_detail">Onde conexões viram negócios</td>
                    </tr>
                    <tr>
                        <td>01/11</td>
                        <td class="agenda_event">Campanha</td>
                        <td class="agenda_detail">Q&A com especialistas</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ===== SECTION 2: Ranking e Meta ===== -->
    <section class="dashboard_section2_container">
        <!-- Meta -->
        <div class="dashboard_widget_card">
            <h3 class="widget_card_title">Meta do mês (em breve)</h3>

            <div class="alert_item_action  btn_pulse">
                <div class="alert_content">
                    <div class="alert_icon alert_icon_success">
                        <span class="icon-rocket" style="color: #4CAF50;"></span>
                    </div>
                    <div class="alert_text_content">
                        <p class="alert_main_text">Mais Organização para o seu dia à dia</p>
                        <p class="alert_sub_text">Vamos montar seu plano de crescimento</p>
                    </div>
                </div>
            </div>

            <?php /*
            <p class="goal_info">Meta: <strong>R$ 80.000</strong> • Realizado: <strong>R$ 56.400 (71%)</strong></p>
            <div class="progress_bar_container">
                <div class="progress_bar_fill" style="width: 71%;"></div>
            </div>
            <div class="goal_badges">
                <span class="goal_badge warning">Faltam R$ 23.600</span>
                <span class="goal_badge success">Projeção: 92%</span>
                <span class="goal_badge info">Valor da Meta: 100%</span>
            </div>
            */ ?>
        </div>

        <!-- Funil -->
        <div class="dashboard_widget_card">
            <h3 class="widget_card_title">Status por etapa do funil (em breve)</h3>
            <div class="alert_item_action  btn_pulse">
                <div class="alert_content">
                    <div class="alert_icon alert_icon_success">
                        <span class="icon-rocket" style="color: #4CAF50;"></span>
                    </div>
                    <div class="alert_text_content">
                        <p class="alert_main_text">Mais Organização para o seu dia à dia</p>
                        <p class="alert_sub_text">Vamos montar seu plano de crescimento</p>
                    </div>
                </div>
            </div>
            <?php /*
            <table class="funnel_table">
                <thead>
                    <tr>
                        <th>Etapa</th>
                        <th>Qtde</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Contato / Qualificação</td>
                        <td>14</td>
                        <td>em atendimento</td>
                    </tr>
                    <tr>
                        <td>Análise de crédito</td>
                        <td>10</td>
                        <td>aguardando docs</td>
                    </tr>
                    <tr>
                        <td>Projeto</td>
                        <td>3</td>
                        <td>apresentação</td>
                    </tr>
                    <tr>
                        <td>Contrato</td>
                        <td>2</td>
                        <td>em assinatura</td>
                    </tr>
                </tbody>
            </table>
            */ ?>
        </div>

        <!-- Ranking -->
        <div class="dashboard_widget_card">
            <h3 class="widget_card_title">Ranking Atual (em breve)</h3>
            <div class="alert_item_action  btn_pulse">
                <div class="alert_content">
                    <div class="alert_icon alert_icon_success">
                        <span class="icon-rocket" style="color: #4CAF50;"></span>
                    </div>
                    <div class="alert_text_content">
                        <p class="alert_main_text">Mais Organização para o seu dia à dia</p>
                        <p class="alert_sub_text">Vamos montar seu plano de crescimento</p>
                    </div>
                </div>
            </div>
            <?php /*
            <ul class="ranking_list">
                <li class="ranking_item"><span class="ranking_position">1º</span><span class="ranking_name">Ana Souza</span><span class="ranking_points">1.540 pts</span></li>
                <li class="ranking_item"><span class="ranking_position">2º</span><span class="ranking_name">Carlos Lima</span><span class="ranking_points">1.420 pts</span></li>
                <li class="ranking_item highlight"><span class="ranking_position">3º</span><span class="ranking_name">Você</span><span class="ranking_points">1.200 pts</span></li>
                <li class="ranking_item"><span class="ranking_position">4º</span><span class="ranking_name">Fernanda Alves</span><span class="ranking_points">1.050 pts</span></li>
            </ul>*/ ?>
        </div>

    </section>

    <?php /*
    <section class="dashboard_section3_container">
        <!-- Comissões -->
        <div class="dashboard_large_card">
            <h3 class="large_card_title">Comissões pagas e previstas</h3>
            <table class="commission_table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente/Obra</th>
                        <th>Status</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>03/09</td>
                        <td>Casa — Jardim Aurora</td>
                        <td><span class="commission_status status_paga">Paga</span></td>
                        <td>R$ 3.200</td>
                    </tr>
                    <tr>
                        <td>18/09</td>
                        <td>Casa — Parque Verde</td>
                        <td><span class="commission_status status_paga">Paga</span></td>
                        <td>R$ 2.800</td>
                    </tr>
                    <tr>
                        <td>05/10</td>
                        <td>Casa — Vila Real</td>
                        <td><span class="commission_status status_prevista">Prevista</span></td>
                        <td>R$ 1.900</td>
                    </tr>
                    <tr>
                        <td>22/10</td>
                        <td>Casa — Marapendi</td>
                        <td><span class="commission_status status_prevista">Prevista</span></td>
                        <td>R$ 5.000</td>
                    </tr>
                </tbody>
            </table>
            <div class="card_actions">
                <button class="action_button">Ranking de Comissões: 2ª</button>
                <button class="action_button">Extrato detalhado</button>
            </div>
        </div>
        <div class="cases_card">
            <h3 class="table_card_title">Cases e campanhas</h3>
            <div class="cases_badges">
                <div class="case_badge"><i class="fas fa-bell badge_icon_fire"></i><span>Bonificação ativa</span></div>
                <div class="case_badge"><i class="fas fa-trophy badge_icon_trophy"></i><span>Campanha "Casa na Planta"</span></div>
                <div class="case_badge"><i class="fas fa-star badge_icon_star"></i><span>Case: Cheque R$ 114.900</span></div>
            </div>
            <div class="case_quote">
                <p class="case_quote_text">"Fechei minha 3ª casa e bati minha meta do mês!"</p>
                <p class="case_quote_author">— Carla M.</p>
            </div>
        </div>
    </section>
    */ ?>

</div>
<?php /* ?>
    <style>
        @media (max-width: 800px) {
            .home_header {
                height: 80px;
            }
        }
    </style>
    <header class="home_header">

    </header>
    <div style="position: relative; width: 100%; height: 100vh;">
        <iframe
            src="https://parceiro-residere-interno-pag-painel.lovable.app/"
            style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
            allowfullscreen
            loading="lazy">
        </iframe>
    </div>

<?php */ ?>