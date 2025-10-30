<div class="wizard_backdrop" id="wizardBackdrop" style="display: none;"></div>

<div class="wizard_modal" id="wizardModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="wizardTitle">
  <div class="wizard_modal_container">
        <!-- Botão Fechar (X) -->
    <button type="button" class="wizard_modal_close jCloseWizard" aria-label="Fechar formulário">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div class="wizard_container" id="wizardContainer">
        <!-- Progress Bar -->
        <div class="wizard_progress_bar">
            <div class="wizard_progress_fill" id="progressFill"></div>
        </div>

        <h2 class="wizard_title" id="wizardTitle">Preencha as informações do cliente em 3 etapas</h2>

        <!-- Step Indicators -->
        <div class="wizard_steps_indicator">
            <div class="wizard_step active" data-step="1">
                <div class="wizard_step_number">1</div>
                <div class="wizard_step_label">Dados do Cliente</div>
            </div>
            <div class="wizard_step_line active"></div>
            <div class="wizard_step" data-step="2">
                <div class="wizard_step_number">2</div>
                <div class="wizard_step_label">Perfil do Interesse</div>
            </div>
            <div class="wizard_step_line"></div>
            <div class="wizard_step" data-step="3">
                <div class="wizard_step_number">3</div>
                <div class="wizard_step_label">Qualificação</div>
            </div>
        </div>

        <form id="wizardForm" name="user_manager" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Leads" />
            <input type="hidden" name="callback_action" value="manager" />
            <input type="hidden" name="parceiros_id" value="<?= $Admin['user_id']; ?>" />

            <!-- ETAPA 1: Dados do Cliente -->
            <div class="wizard_step_content active" data-step="1">
                <div class="wizard_form_group">
                    <label class="wizard_label">Nome completo <span class="wizard_required">*</span></label>
                    <input type="text" name="leads_name" class="wizard_input" placeholder="Nome completo" required>
                </div>

                <div class="wizard_form_row">
                    <div class="wizard_form_group wizard_half">
                        <label class="wizard_label">Telefone / WhatsApp <span class="wizard_required">*</span></label>
                        <input type="tel" name="leads_cell" class="formPhone wizard_input" placeholder="Telefone / WhatsApp" required>
                    </div>
                    <div class="wizard_form_group wizard_half">
                        <label class="wizard_label">E-mail</label>
                        <input type="email" name="leads_email" class="wizard_input" placeholder="E-mail">
                    </div>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Cidade de interesse <span class="wizard_required">*</span></label>
                    <select name="leads_cidade_interesse" class="wizard_select" required>
                        <option value="">Selecione a cidade</option>
                        <option value="Sarandi">Sarandi</option>
                        <option value="Maringá">Maringá</option>
                        <option value="Paiçandu">Paiçandu</option>
                        <option value="Marialva">Marialva</option>
                    </select>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Possui terreno? <span class="wizard_required">*</span></label>
                    <div class="wizard_button_group">
                        <button type="button" class="wizard_option_btn active" data-value="sim_proprio">Sim próprio</button>
                        <button type="button" class="wizard_option_btn" data-value="sim_familiar">Sim familiar</button>
                        <button type="button" class="wizard_option_btn" data-value="em_negociacao">Em negociação</button>
                        <button type="button" class="wizard_option_btn" data-value="nao">Não</button>
                    </div>
                    <input type="hidden" name="leads_terreno" value="sim_proprio">
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Endereço do terreno</label>
                    <textarea name="leads_endereco_terreno" class="wizard_textarea" placeholder="Rua, número, bairro..." rows="4"></textarea>
                </div>
            </div>

            <!-- ETAPA 2: Perfil do Interesse -->
            <div class="wizard_step_content" data-step="2">
                <div class="wizard_form_group">
                    <label class="wizard_label">Tipo de construção desejada <span class="wizard_required">*</span></label>
                    <div class="wizard_button_group">
                        <button type="button" class="wizard_option_btn" data-value="casa_terrea">Casa térrea</button>
                        <button type="button" class="wizard_option_btn" data-value="sobrado">Sobrado</button>
                        <button type="button" class="wizard_option_btn" data-value="geminada">Geminada</button>
                        <button type="button" class="wizard_option_btn" data-value="alto_padrao">Alto padrão</button>
                    </div>
                    <input type="hidden" name="leads_tipo_construcao" value="">
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Faixa de investimento prevista <span class="wizard_required">*</span></label>
                    <select name="leads_faixa_investimento" class="wizard_select" required>
                        <option value="">Selecione a faixa</option>
                        <option value="ate_150k">Até R$ 150.000</option>
                        <option value="150k_250k">R$ 150.000 - R$ 250.000</option>
                        <option value="250k_400k">R$ 250.000 - R$ 400.000</option>
                        <option value="400k_mais">Acima de R$ 400.000</option>
                    </select>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Parcela que cabe no bolso <span class="wizard_required">*</span></label>
                    <select name="leads_parcela_bolso" class="wizard_select" required>
                        <option value="">Selecione o valor</option>
                        <option value="ate_1k">Até R$ 1.000</option>
                        <option value="1k_2k">R$ 1.000 - R$ 2.000</option>
                        <option value="2k_3k">R$ 2.000 - R$ 3.000</option>
                        <option value="3k_5k">R$ 3.000 - R$ 5.000</option>
                        <option value="5k_mais">Acima de R$ 5.000</option>
                    </select>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Forma de pagamento / entrada (múltiplas opções)</label>
                    <div class="wizard_checkbox_group">
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="fgts">
                            <span>FGTS</span>
                        </label>
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="consorcio">
                            <span>Consórcio</span>
                        </label>
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="carro">
                            <span>Carro</span>
                        </label>
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="outro_imovel">
                            <span>Outro imóvel</span>
                        </label>
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="a_vista">
                            <span>À vista</span>
                        </label>
                        <label class="wizard_checkbox_label">
                            <input type="checkbox" name="leads_forma_pagamento[]" value="financiamento">
                            <span>Financiamento bancário</span>
                        </label>
                    </div>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Expectativa para iniciar o projeto <span class="wizard_required">*</span></label>
                    <select name="leads_expectativa_inicio" class="wizard_select" required>
                        <option value="">Selecione o prazo</option>
                        <option value="imediato">Imediato</option>
                        <option value="1_3_meses">1 a 3 meses</option>
                        <option value="3_6_meses">3 a 6 meses</option>
                        <option value="6_12_meses">6 a 12 meses</option>
                        <option value="acima_12_meses">Acima de 12 meses</option>
                    </select>
                </div>

                <div class="wizard_form_row">
                    <div class="wizard_form_group wizard_half">
                        <label class="wizard_label">Já conhece a Residere? <span class="wizard_required">*</span></label>
                        <div class="wizard_button_group">
                            <button type="button" class="wizard_option_btn" data-value="sim">Sim</button>
                            <button type="button" class="wizard_option_btn" data-value="nao">Não</button>
                        </div>
                        <input type="hidden" name="leads_conhece_residere" value="">
                    </div>
                    <div class="wizard_form_group wizard_half" id="comoConheceuGroup" style="display: none;">
                        <label class="wizard_label">Como conheceu</label>
                        <select name="leads_como_conheceu" class="wizard_select">
                            <option value="">Selecione</option>
                            <option value="indicacao">Indicação</option>
                            <option value="redes_sociais">Redes sociais</option>
                            <option value="google">Google</option>
                            <option value="outdoor">Outdoor</option>
                            <option value="evento">Evento</option>
                        </select>
                    </div>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Comentários do parceiro</label>
                    <textarea name="leads_comentarios_parceiro" class="wizard_textarea" placeholder="Observações importantes sobre este lead..." rows="4"></textarea>
                </div>
            </div>

            <!-- ETAPA 3: Qualificação -->
            <div class="wizard_step_content" data-step="3">
                <div class="wizard_form_group">
                    <label class="wizard_label">Já visitou alguma casa Residere? <span class="wizard_required">*</span></label>
                    <div class="wizard_button_group">
                        <button type="button" class="wizard_option_btn" data-value="sim">Sim</button>
                        <button type="button" class="wizard_option_btn" data-value="nao">Não</button>
                    </div>
                    <input type="hidden" name="leads_visitou_casa" value="">
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Finalidade do imóvel <span class="wizard_required">*</span></label>
                    <div class="wizard_button_group">
                        <button type="button" class="wizard_option_btn" data-value="morar">Morar</button>
                        <button type="button" class="wizard_option_btn" data-value="investir">Investir</button>
                        <button type="button" class="wizard_option_btn" data-value="a_decidir">A decidir</button>
                    </div>
                    <input type="hidden" name="leads_finalidade_imovel" value="">
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Prazo preferido para contato <span class="wizard_required">*</span></label>
                    <select name="leads_prazo_contato" class="wizard_select" required>
                        <option value="">Selecione o prazo</option>
                        <option value="imediato">Imediato</option>
                        <option value="24h">Até 24 horas</option>
                        <option value="48h">Até 48 horas</option>
                        <option value="proxima_semana">Próxima semana</option>
                    </select>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Cliente com crédito pré-aprovado? <span class="wizard_required">*</span></label>
                    <div class="wizard_button_group">
                        <button type="button" class="wizard_option_btn" data-value="sim">Sim</button>
                        <button type="button" class="wizard_option_btn" data-value="nao">Não</button>
                    </div>
                    <input type="hidden" name="leads_credito_aprovado" value="">
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Casas ou links de interesse</label>
                    <textarea name="leads_casas_interesse" class="wizard_textarea" placeholder="Cole aqui links de projetos ou casas que o cliente demonstrou interesse..." rows="4"></textarea>
                </div>

                <div class="wizard_form_group">
                    <label class="wizard_label">Upload de anexos (fotos do terreno, simulações, etc.)</label>
                    <div class="wizard_upload_area">
                        <div class="wizard_upload_icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <p class="wizard_upload_text">Clique para fazer upload ou arraste arquivos aqui</p>
                        <p class="wizard_upload_hint">Imagens, PDFs ou documentos</p>
                        <input type="file" id="fileUpload" name="file[]" multiple accept="image/*,.pdf,.doc,.docx" style="display: none;">
                    </div>
                    <div id="fileList" class="wizard_file_list"></div>
                </div>
            </div>

            <!-- ETAPA 4: Resumo -->
            <div class="wizard_step_content" data-step="4">
                <h3 class="wizard_review_title">Confira os dados antes de enviar</h3>

                <div class="wizard_review_section">
                    <h4 class="wizard_review_section_title">Dados do Cliente</h4>
                    <div class="wizard_review_grid">
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Nome</span>
                            <span class="wizard_review_value" id="review_nome_completo">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Telefone</span>
                            <span class="wizard_review_value" id="review_telefone">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">E-mail</span>
                            <span class="wizard_review_value" id="review_email">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Cidade</span>
                            <span class="wizard_review_value" id="review_cidade_interesse">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Possui terreno</span>
                            <span class="wizard_review_value" id="review_possui_terreno">-</span>
                        </div>
                    </div>
                </div>

                <div class="wizard_review_section">
                    <h4 class="wizard_review_section_title">Perfil do Interesse</h4>
                    <div class="wizard_review_grid">
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Tipo de construção</span>
                            <span class="wizard_review_value" id="review_tipo_construcao">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Faixa de investimento</span>
                            <span class="wizard_review_value" id="review_faixa_investimento">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Parcela no bolso</span>
                            <span class="wizard_review_value" id="review_parcela_bolso">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Formas de pagamento</span>
                            <span class="wizard_review_value" id="review_forma_pagamento">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Expectativa de início</span>
                            <span class="wizard_review_value" id="review_expectativa_inicio">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Conhece Residere</span>
                            <span class="wizard_review_value" id="review_conhece_residere">-</span>
                        </div>
                    </div>
                </div>

                <div class="wizard_review_section">
                    <h4 class="wizard_review_section_title">Qualificação</h4>
                    <div class="wizard_review_grid">
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Visitou casa Residere</span>
                            <span class="wizard_review_value" id="review_visitou_casa">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Finalidade</span>
                            <span class="wizard_review_value" id="review_finalidade_imovel">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Prazo de contato</span>
                            <span class="wizard_review_value" id="review_prazo_contato">-</span>
                        </div>
                        <div class="wizard_review_item">
                            <span class="wizard_review_label">Crédito pré-aprovado</span>
                            <span class="wizard_review_value" id="review_credito_aprovado">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ETAPA 5: Sucesso -->
            <div class="wizard_step_content" data-step="5">
                <div class="wizard_success_container">
                    <div class="wizard_success_icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="wizard_success_progress">
                        <div class="wizard_success_progress_fill"></div>
                    </div>
                    <p class="wizard_success_message">Nossa equipe entrará em contato com o cliente em até 24h.</p>
                    <button type="button" class="wizard_btn wizard_btn_success" id="btnNovoLead">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Enviar outro lead
                    </button>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="wizard_navigation">
                <button type="button" class="wizard_btn wizard_btn_secondary" id="btnVoltar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </button>
                <button type="button" class="wizard_btn wizard_btn_primary" id="btnProximo">
                    Próximo
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
  </div>
</div>
